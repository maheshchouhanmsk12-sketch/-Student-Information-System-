<?php
if(!defined('DB_SERVER')){
    require_once("../initialize.php");
}
class DBConnection{

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $port = defined('DB_PORT') ? DB_PORT : 3306;
    
    public $conn;
    
    public function __construct(){

        if (!isset($this->conn)) {
            // Turn off automatic fatal exception throwing on mysqli connection error in PHP 8.1+
            mysqli_report(MYSQLI_REPORT_OFF);

            $this->conn = mysqli_init();
            if (!$this->conn) {
                echo "mysqli_init failed";
                exit;
            }

            // Connection timeout
            $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 15);

            // Aiven MySQL and cloud providers require SSL/TLS encryption
            $is_cloud_db = ((int)$this->port !== 3306) || 
                           (strpos($this->host, 'aivencloud.com') !== false) || 
                           (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1');

            if ($is_cloud_db) {
                // Initialize SSL without enforcing local CA cert checks
                $this->conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
                $flags = MYSQLI_CLIENT_SSL;
            } else {
                $flags = 0;
            }

            // Attempt connection with SSL flags first
            $connected = @$this->conn->real_connect(
                $this->host,
                $this->username,
                $this->password,
                $this->database,
                (int)$this->port,
                NULL,
                $flags
            );

            // Fallback to standard connection if SSL flag wasn't needed
            if (!$connected && $flags !== 0) {
                $this->conn = mysqli_init();
                $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 15);
                $connected = @$this->conn->real_connect(
                    $this->host,
                    $this->username,
                    $this->password,
                    $this->database,
                    (int)$this->port
                );
            }

            if (!$connected || $this->conn->connect_error) {
                echo "<div style='font-family: Arial, sans-serif; padding: 25px; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 8px; margin: 20px; max-width: 700px; line-height: 1.6;'>";
                echo "<h3 style='color: #c53030; margin-top: 0;'>⚠️ Database Connection Error</h3>";
                echo "<p>Could not connect to the MySQL database server. Please check your Aiven credentials or Render Environment Variables.</p>";
                echo "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
                echo "<tr><td style='padding: 6px; font-weight: bold;'>Host:</td><td style='padding: 6px;'><code>" . htmlspecialchars($this->host) . "</code></td></tr>";
                echo "<tr><td style='padding: 6px; font-weight: bold;'>Port:</td><td style='padding: 6px;'><code>" . htmlspecialchars($this->port) . "</code></td></tr>";
                echo "<tr><td style='padding: 6px; font-weight: bold;'>Database:</td><td style='padding: 6px;'><code>" . htmlspecialchars($this->database) . "</code></td></tr>";
                echo "<tr><td style='padding: 6px; font-weight: bold;'>User:</td><td style='padding: 6px;'><code>" . htmlspecialchars($this->username) . "</code></td></tr>";
                echo "<tr><td style='padding: 6px; font-weight: bold; color: #c53030;'>Error Details:</td><td style='padding: 6px; color: #c53030;'><code>" . htmlspecialchars($this->conn->connect_error ?: mysqli_connect_error()) . "</code></td></tr>";
                echo "</table>";
                echo "<p style='margin-top: 15px;'><a href='" . base_url . "db_setup.php' style='display: inline-block; padding: 10px 18px; background: #3182ce; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;'>Open Database Setup & Importer</a></p>";
                echo "</div>";
                exit;
            }

            $this->conn->set_charset("utf8mb4");
        }    
        
    }
    public function __destruct(){
        if ($this->conn && !($this->conn->connect_error)) {
            @$this->conn->close();
        }
    }
}
?>