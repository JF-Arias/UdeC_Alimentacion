<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_1 = $_POST['nombre_1'];
    $nombre_2 = $_POST['nombre_2'] ?? null; // Segundo nombre es opcional
    $apellido_1 = $_POST['apellido_1'];
    $apellido_2 = $_POST['apellido_2'] ?? null; // Segundo apellido es opcional
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Cifrar la contraseña

    // Verificar si el usuario ya existe
    $sql_check = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $usuario, $email);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();

    if ($resultado->num_rows > 0) {
        echo "El usuario o correo ya está registrado.";
    } else {
        // Insertar nuevo usuario
        $sql_insert = "INSERT INTO usuarios (usuario, password, email, telefono, nombre_1, nombre_2, apellido_1, apellido_2, estado) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'activo')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssssss", $usuario, $password, $email, $telefono, $nombre_1, $nombre_2, $apellido_1, $apellido_2);

        if ($stmt_insert->execute()) {
            echo "Usuario registrado exitosamente. <a href='index.php'>Iniciar sesión</a>";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    }
}
?>
