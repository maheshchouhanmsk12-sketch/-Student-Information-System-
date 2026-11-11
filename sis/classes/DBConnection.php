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

            $this->conn = @new mysqli($this->host, $this->username, $this->password, $this->database, (int)$this->port);
            
            if ($this->conn->connect_error) {
                echo "<div style='font-family: Arial, sans-serif; padding: 20px; background: #fff3f3; border: 1px solid #ffcccc; border-radius: 6px; margin: 20px;'>";
                echo "<h3 style='color: #cc0000; margin-top: 0;'>Database Connection Error</h3>";
                echo "<p>Could not connect to database server. Please check your database settings or environment variables.</p>";
                echo "<p><strong>Host:</strong> " . htmlspecialchars($this->host) . ":" . htmlspecialchars($this->port) . "<br>";
                echo "<strong>Database:</strong> " . htmlspecialchars($this->database) . "<br>";
                echo "<strong>Error Details:</strong> " . htmlspecialchars($this->conn->connect_error) . "</p>";
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