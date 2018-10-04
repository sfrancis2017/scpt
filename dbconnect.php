<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_host = "localhost";
$db_name = "scpt";
$db_user = "sfrancis";
$db_pass = "Fairfield123";
$charset = 'utf8mb4';

$bcryptOption = [
    'cost' => 12,
];

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

// $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$conn = new PDO($dsn, $db_user, $db_pass, $opt);


// NECESSARY FOR WEB SERVER TO WORK
//$webRoot = "http://localhost:8888/scpt/NetBeansProjects/scpt/";
$webRoot = "https://scpt.gwiddle.co.uk/";


// Check connection
//if ($conn->connect_error) {
  //  die("Connection failed: " . $conn->connect_error);
//}
