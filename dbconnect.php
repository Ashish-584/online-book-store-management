
<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'sourcecodester_bookdb');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

$con = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME,
    3307
);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
