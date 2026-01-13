<?php
$host = "localhost";
$user = "your_username";
$pass = "your_password";
$db   = "your_db_name";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
