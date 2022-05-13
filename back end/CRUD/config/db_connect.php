<?php
header("Access-Control-Allow-Origin: *"); // add this CORS header to enable any domain to send HTTP requests to there endpoints;
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$serverName = "localhost:3307";
$userName = "root";
$password = "";
$dbName = "db_blog";
$id = '';
$connection = mysqli_connect($serverName,$userName,$password,$dbName);
$method = $_SERVER['REQUEST_METHOD'];
if(!$connection) {
    die("Connection Failed ".mysqli_connect_error());
}