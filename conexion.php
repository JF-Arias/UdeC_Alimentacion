<?php
function getConnection() {
    $host = 'localhost';
    $dbname = 'u983503200_20242_cd703g2';
    $username = 'u983503200_20242_cd703g2';
    $password = 'Udec2024*';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die(json_encode(['error' => 'Error al conectar a la base de datos', 'message' => $e->getMessage()]));
    }
}
?>
