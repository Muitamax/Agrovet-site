<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'amboni_agrovet');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Site Configuration
define('SITE_NAME', 'Amboni Agrovet');
define('SITE_EMAIL', 'amboniagrovet@yahoo.com');
define('SITE_PHONE', '+254721476350');
define('SITE_PHONE2', '+254720567641');
define('SITE_LOCATION', 'Mweiga town next to Siel Supermarket on Taifa Sacco building');

// Session settings
ini_set('session.gc_maxlifetime', 86400);
session_start();
?>
