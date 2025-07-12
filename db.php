<?php
$host = 'localhost';
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda
$port = 3307;
$dbname = 'music_db';

$conn = new mysqli($host, $user, $pass, $dbname , $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>