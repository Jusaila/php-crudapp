<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = ""; // Use the correct password for the 'root' user
$dbName = "registration";
$portNo = "3307";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $portNo);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
