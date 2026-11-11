<?php
/**
 * Database Auto-Migrator / Setup Tool
 * Helps easily import sis_db.sql into Aiven, Cloud, or local MySQL database.
 */
require_once('initialize.php');

$page_title = "Student Information System - Database Setup (Aiven & Cloud Ready)";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/adminlte.min.css">
    <style>
        body { background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .setup-card { width: 100%; max-width: 650px; margin: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.12); border-radius: 8px; border: none; }
        .setup-card .card-header { background: #007bff; color: white; border-top-left-radius: 8px; border-top-right-radius: 8px; }
    </style>
</head>
<body>
<div class="card setup-card">
    <div class="card-header">
        <h4 class="card-title mb-0"><i class="fas fa-database mr-2"></i> Database Setup & Health Check</h4>
    </div>
    <div class="card-body">
        <?php
        $db_host = DB_SERVER;
        $db_user = DB_USERNAME;
        $db_pass = DB_PASSWORD;
        $db_name = DB_NAME;
        $db_port = defined('DB_PORT') ? DB_PORT : 3306;

        echo "<div class='alert alert-info'><strong>Connected Configuration:</strong><br><ul class='mb-0' style='padding-left: 20px;'>";
        echo "<li><strong>Host:</strong> <code>" . htmlspecialchars($db_host) . "</code></li>";
        echo "<li><strong>Port:</strong> <code>" . htmlspecialchars($db_port) . "</code></li>";
        echo "<li><strong>Database:</strong> <code>" . htmlspecialchars($db_name) . "</code></li>";
        echo "<li><strong>User:</strong> <code>" . htmlspecialchars($db_user) . "</code></li>";
        echo "</ul></div>";

        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = mysqli_init();
        $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 15);

        // Aiven SSL handling
        $is_cloud_db = ((int)$db_port !== 3306) || 
                       (strpos($db_host, 'aivencloud.com') !== false) || 
                       (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1');

        if ($is_cloud_db) {
            $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
            $flags = MYSQLI_CLIENT_SSL;
        } else {
            $flags = 0;
        }

        $connected = @$conn->real_connect($db_host, $db_user, $db_pass, $db_name, (int)$db_port, NULL, $flags);

        if (!$connected && $flags !== 0) {
            $conn = mysqli_init();
            $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 15);
            $connected = @$conn->real_connect($db_host, $db_user, $db_pass, $db_name, (int)$db_port);
        }

        if (!$connected || $conn->connect_error) {
            echo "<div class='alert alert-danger'>";
            echo "<h5><i class='icon fas fa-ban'></i> Cannot connect to MySQL Server!</h5>";
            echo "<p>" . htmlspecialchars($conn->connect_error ?: mysqli_connect_error()) . "</p>";
            echo "<hr><p class='mb-0'>Please check your <strong>Aiven Credentials</strong> in Render Environment Variables (DB_SERVER, DB_PORT, DB_USERNAME, DB_PASSWORD, DB_NAME).</p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-success'><i class='fas fa-check-circle mr-2'></i> Successfully connected to Aiven / MySQL server!</div>";

            // If user clicked import
            if (isset($_POST['run_import'])) {
                $sql_file = __DIR__ . '/database/sis_db.sql';
                if (!file_exists($sql_file)) {
                    echo "<div class='alert alert-danger'>File sis_db.sql not found at {$sql_file}!</div>";
                } else {
                    $raw_sql = file_get_contents($sql_file);

                    // Remove SQL comments
                    $clean_sql = preg_replace('/--.*$/m', '', $raw_sql);

                    // Split into statements
                    $statements = array_filter(array_map('trim', explode(';', $clean_sql)));
                    $success_count = 0;
                    $error_msgs = array();

                    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
                    foreach ($statements as $stmt) {
                        if (!empty($stmt)) {
                            if ($conn->query($stmt)) {
                                $success_count++;
                            } else {
                                $error_msgs[] = $conn->error;
                            }
                        }
                    }
                    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");

                    echo "<div class='alert alert-success'>";
                    echo "<h5><i class='icon fas fa-check'></i> Import Completed!</h5>";
                    echo "Executed {$success_count} SQL statements into Aiven database.";
                    if (!empty($error_msgs)) {
                        echo "<br><small class='text-muted'>Ignored " . count($error_msgs) . " non-fatal warnings.</small>";
                    }
                    echo "</div>";
                }
            }

            // Check existing tables
            $tables_res = $conn->query("SHOW TABLES");
            $tables = array();
            if ($tables_res) {
                while ($r = $tables_res->fetch_array()) {
                    $tables[] = $r[0];
                }
            }

            if (in_array('system_info', $tables) && in_array('users', $tables)) {
                echo "<div class='alert alert-success'>";
                echo "<h5><i class='icon fas fa-check'></i> Database tables are installed and verified!</h5>";
                echo "Tables found: <code>" . implode(', ', $tables) . "</code>";
                echo "</div>";

                echo "<div class='text-center mt-4'>";
                echo "<a href='" . base_url . "admin/login.php' class='btn btn-success btn-lg'><i class='fas fa-sign-in-alt mr-2'></i> Open Admin Login</a>";
                echo "<br><br><small class='text-muted'>Default credentials: Username: <b>admin</b> &nbsp;|&nbsp; Password: <b>admin123</b></small>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>";
                echo "<h5><i class='icon fas fa-exclamation-triangle'></i> Tables Not Found in Aiven Database</h5>";
                echo "Found " . count($tables) . " tables. Click below to automatically import all required tables and default admin credentials from <code>sis_db.sql</code>.";
                echo "</div>";

                echo "<form method='post' class='text-center mt-3'>";
                echo "<button type='submit' name='run_import' value='1' class='btn btn-primary btn-lg'><i class='fas fa-upload mr-2'></i> Import Database to Aiven Now</button>";
                echo "</form>";
            }

            $conn->close();
        }
        ?>
    </div>
</div>
</body>
</html>
