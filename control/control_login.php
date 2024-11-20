<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta a la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario_db = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $usuario_db['password'])) {
            $_SESSION['usuario'] = $usuario_db['usuario'];
            header("Location: menu.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
