<?php
/**
 * Database Auto-Migrator & Schema Fixer
 */
require_once('initialize.php');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_init();
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 15);

$is_cloud_db = ((int)DB_PORT !== 3306) || 
               (strpos(DB_SERVER, 'aivencloud.com') !== false) || 
               (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1');

if ($is_cloud_db) {
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    $flags = MYSQLI_CLIENT_SSL;
} else {
    $flags = 0;
}

$conn->real_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, (int)DB_PORT, NULL, $flags);

header('Content-Type: text/plain');

echo "=== Fixing student_list & academic_history Schema ===\n";

// Disable foreign keys temporarily
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

// Check if student_list has wrong columns
$res = $conn->query("SHOW COLUMNS FROM student_list LIKE 'lastname'");
if (!$res || $res->num_rows == 0) {
    echo "Old conflicting student_list detected! Dropping and recreating...\n";
    $conn->query("DROP TABLE IF EXISTS `student_list`");
    
    $create_student = "CREATE TABLE `student_list` (
      `id` int(30) NOT NULL AUTO_INCREMENT,
      `roll` varchar(100) NOT NULL,
      `firstname` text NOT NULL,
      `middlename` text DEFAULT NULL,
      `lastname` text NOT NULL,
      `gender` varchar(100) NOT NULL,
      `contact` text NOT NULL,
      `present_address` text NOT NULL,
      `permanent_address` text NOT NULL,
      `dob` date NOT NULL,
      `status` tinyint(1) NOT NULL DEFAULT 1,
      `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
      `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($create_student)) {
        echo "Successfully created correct student_list table!\n";
        
        // Insert sample student
        $insert_student = "INSERT INTO `student_list` (`id`, `roll`, `firstname`, `middlename`, `lastname`, `gender`, `contact`, `present_address`, `permanent_address`, `dob`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
        (1, '231415061007', 'Mark', 'D', 'Cooper', 'Male', '09123456789', 'Sample present address.', 'Sample permanent address.', '2007-06-23', 1, 0, '2022-01-27 11:14:07', '2022-01-28 08:50:13')";
        $conn->query($insert_student);
        echo "Inserted sample student record.\n";
    } else {
        echo "Error creating student_list: " . $conn->error . "\n";
    }
} else {
    echo "student_list table already has 'lastname' column.\n";
}

// Check academic_history columns
$res_ac = $conn->query("SHOW COLUMNS FROM academic_history LIKE 'semester'");
if (!$res_ac || $res_ac->num_rows == 0) {
    echo "Old conflicting academic_history detected! Recreating...\n";
    $conn->query("DROP TABLE IF EXISTS `academic_history`");
    
    $create_ac = "CREATE TABLE `academic_history` (
      `id` int(30) NOT NULL AUTO_INCREMENT,
      `student_id` int(30) NOT NULL,
      `course_id` int(30) NOT NULL,
      `semester` varchar(200) NOT NULL,
      `year` varchar(200) NOT NULL,
      `school_year` text NOT NULL,
      `status` int(10) NOT NULL DEFAULT 1,
      `end_status` tinyint(3) NOT NULL DEFAULT 0,
      `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `student_id` (`student_id`),
      KEY `course_id` (`course_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($create_ac)) {
        echo "Successfully created correct academic_history table!\n";
        $insert_ac = "INSERT INTO `academic_history` (`id`, `student_id`, `course_id`, `semester`, `year`, `school_year`, `status`, `end_status`, `date_created`, `date_updated`) VALUES
        (1, 1, 11, 'First Semester', '1st Year', '2018-2019', 1, 1, '2022-01-27 13:02:36', '2022-01-27 13:22:31')";
        $conn->query($insert_ac);
        echo "Inserted sample academic history.\n";
    } else {
        echo "Error creating academic_history: " . $conn->error . "\n";
    }
} else {
    echo "academic_history table has correct columns.\n";
}

$conn->query("SET FOREIGN_KEY_CHECKS = 1;");

echo "\n=== Verifying student_list query ===\n";
$test = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from `student_list` order by concat(lastname,', ',firstname,' ',middlename) asc");
if ($test) {
    echo "SUCCESS: Found " . $test->num_rows . " student(s)!\n";
    while ($row = $test->fetch_assoc()) {
        echo "- Student: " . $row['fullname'] . " (Roll: " . $row['roll'] . ")\n";
    }
} else {
    echo "FAILED: " . $conn->error . "\n";
}
