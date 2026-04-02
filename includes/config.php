<?php
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'shopping');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
// Check connection
if (mysqli_connect_errno())
{
 error_log("Failed to connect to MySQL: " . mysqli_connect_error());
 die("Database connection failed. Please contact administrator.");
}
?>