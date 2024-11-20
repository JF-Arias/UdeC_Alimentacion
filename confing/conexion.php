<?php
$servername = "localhost";
$username = "u983503200_20242_cd703g2";
$password = "Udec2024*";
$database = "u983503200_20242_cd703g2";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
