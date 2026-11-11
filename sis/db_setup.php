<?php
/**
 * Database Auto-Migrator / Setup Tool
 * Helps beginners easily import sis_db.sql into remote or local MySQL database.
 */
require_once('initialize.php');

$page_title = "Student Information System - Database Setup";
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
        body { background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .setup-card { width: 100%; max-width: 600px; margin: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; }
    </style>
</head>
<body>
<div class="card setup-card">
    <div class="card-header bg-primary text-white">
        <h4 class="card-title mb-0"><i class="fas fa-database mr-2"></i> Database Setup & Health Check</h4>
    </div>
    <div class="card-body">
        <?php
        $db_host = DB_SERVER;
        $db_user = DB_USERNAME;
        $db_pass = DB_PASSWORD;
        $db_name = DB_NAME;
        $db_port = defined('DB_PORT') ? DB_PORT : 3306;

        echo "<div class='alert alert-info'><strong>Current Connection Config:</strong><br>";
        echo "Host: <code>" . htmlspecialchars($db_host) . ":" . htmlspecialchars($db_port) . "</code><br>";
        echo "Database: <code>" . htmlspecialchars($db_name) . "</code><br>";
        echo "User: <code>" . htmlspecialchars($db_user) . "</code></div>";

        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, (int)$db_port);

        if ($conn->connect_error) {
            echo "<div class='alert alert-danger'>";
            echo "<h5><i class='icon fas fa-ban'></i> Cannot connect to MySQL!</h5>";
            echo htmlspecialchars($conn->connect_error);
            echo "<hr><p class='mb-0'>Please verify your Environment Variables (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT) in Render dashboard or initialize.php.</p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-success'><i class='fas fa-check-circle mr-2'></i> Connected to MySQL server successfully!</div>";

            // Check if tables already exist
            $check_tbl = $conn->query("SHOW TABLES LIKE 'system_info'");
            $tables_exist = ($check_tbl && $check_tbl->num_rows > 0);

            if ($tables_exist) {
                echo "<div class='alert alert-success'>";
                echo "<h5><i class='icon fas fa-check'></i> Database is already set up!</h5>";
                echo "The database tables are in place and ready to use.";
                echo "</div>";

                echo "<div class='text-center mt-4'>";
                echo "<a href='" . base_url . "admin/login.php' class='btn btn-success btn-lg'><i class='fas fa-sign-in-alt mr-2'></i> Open Admin Login</a>";
                echo "<br><br><small class='text-muted'>Default credentials: <b>admin</b> / <b>admin123</b></small>";
                echo "</div>";
            } else {
                if (isset($_POST['run_import'])) {
                    $sql_file = __DIR__ . '/database/sis_db.sql';
                    if (!file_exists($sql_file)) {
                        echo "<div class='alert alert-danger'>File sis_db.sql not found at {$sql_file}!</div>";
                    } else {
                        $sql = file_get_contents($sql_file);
                        
                        // Execute multi query
                        if ($conn->multi_query($sql)) {
                            do {
                                if ($res = $conn->store_result()) {
                                    $res->free();
                                }
                            } while ($conn->more_results() && $conn->next_result());

                            echo "<div class='alert alert-success'>";
                            echo "<h5><i class='icon fas fa-check'></i> Success!</h5>";
                            echo "Database <code>sis_db.sql</code> has been imported successfully!";
                            echo "</div>";

                            echo "<div class='text-center mt-3'>";
                            echo "<a href='" . base_url . "admin/login.php' class='btn btn-primary btn-lg'><i class='fas fa-sign-in-alt mr-2'></i> Proceed to Admin Login</a>";
                            echo "<br><br><small class='text-muted'>Default credentials: <b>admin</b> / <b>admin123</b></small>";
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error importing SQL: " . htmlspecialchars($conn->error) . "</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-warning'>";
                    echo "<h5><i class='icon fas fa-exclamation-triangle'></i> Tables Not Found</h5>";
                    echo "The database is empty. Click the button below to import <code>sis_db.sql</code> automatically.";
                    echo "</div>";

                    echo "<form method='post' class='text-center mt-3'>";
                    echo "<button type='submit' name='run_import' value='1' class='btn btn-primary btn-lg'><i class='fas fa-upload mr-2'></i> Import Database Now</button>";
                    echo "</form>";
                }
            }

            $conn->close();
        }
        ?>
    </div>
</div>
</body>
</html>
