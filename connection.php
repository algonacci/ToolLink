<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "db_tool";

$koneksi = new mysqli($host, $user, $password, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
