<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// MySQL database credentials
$db_host = 'rpmhealthtech.ctigs0a46ow7.ap-south-1.rds.amazonaws.com';
$db_user = 'root';
$db_password = 'Rpmhealth2129';
$db_name = 'rpm_health_tech';

// Connect to MySQL database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Database:	mdcloudx_fitbit_data Rpmhealth@2129
// Host:	192.168.0.100
// Username:	mdcloudx_fitbit_data
// Password:	fitbit_data

//root
//Rpmhealth2129
?>