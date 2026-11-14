<?php
/**
 * Database Auto-Migrator & Inspector
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

echo "=== Tables in " . DB_NAME . " ===\n";
$res = $conn->query("SHOW TABLES");
while ($r = $res->fetch_array()) {
    echo $r[0] . "\n";
}

echo "\n=== Columns in student_list ===\n";
$res2 = $conn->query("SHOW COLUMNS FROM student_list");
if ($res2) {
    while ($r = $res2->fetch_assoc()) {
        echo $r['Field'] . " (" . $r['Type'] . ")\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

echo "\n=== Test Query: SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from `student_list` ===\n";
$test = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from `student_list` order by concat(lastname,', ',firstname,' ',middlename) asc");
if ($test) {
    echo "SUCCESS: Found " . $test->num_rows . " rows\n";
} else {
    echo "QUERY FAILED! Error: " . $conn->error . "\n";
}
