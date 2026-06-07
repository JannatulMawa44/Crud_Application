<?php
$dbHost = "localhost";
$dbPassword = '';
$dbName = 'crud';
$dbUserName = 'root';

$conn = mysqli_connect($dbHost, $dbUserName, $dbPassword, $dbName);

if ($conn->connect_error) {
    die('Connection failed');
} else {
    // echo "Connected successfully";
}
