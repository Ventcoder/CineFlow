<?php

$host = 'localhost';
$port = '5432';
$dbname = 'CineFlow';
$user = 'postgres';
$password = '1234';

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$db = pg_connect($conn_string);

if (!$db) {
    die("Error: Unable to open database connection. Please check your credentials.");
}
?>