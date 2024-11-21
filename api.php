<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
require 'conexion.php';

// Obtener la conexión
$pdo = getConnection();

// Procesar solicitud
$request_method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($_GET['action'] ?? '') {
    case 'login':
        login($pdo, $input);
        break;
    case 'create':
        createUser($pdo, $input);
        break;
    case 'read':
        readUsers($pdo);
        break;
    case 'update':
        updateUser($pdo, $input);
        break;
    case 'delete':
        deleteUser($pdo, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

// Función de Login
function login($pdo, $input) {
    $nom_usuario = $input['nom_usuario'] ?? '';
    $password = $input['password'] ?? '';

    // Validar campos obligatorios
    if (!$nom_usuario || !$password) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son obligatorios']);
        return;
    }

    try {
        // Consulta SQL para verificar el usuario y la contraseña
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nom_usuario = :nom_usuario");
        $stmt->execute(['nom_usuario' => $nom_usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                echo json_encode(['success' => true, 'message' => 'Login exitoso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}

function createUser($pdo, $input) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO usuario (nom_usuario, pr_nombre, sg_nombre, pr_apellido, sg_apellido, password, email, telefono, id_restaurante, fecha_registro, estado)
            VALUES (:nom_usuario, :pr_nombre, :sg_nombre, :pr_apellido, :sg_apellido, :password, :email, :telefono, :id_restaurante, :fecha_registro, :estado)
        ");
        $stmt->execute([
            'nom_usuario' => $input['nom_usuario'] ?? '',
            'pr_nombre' => $input['pr_nombre'] ?? '',
            'sg_nombre' => $input['sg_nombre'] ?? '',
            'pr_apellido' => $input['pr_apellido'] ?? '',
            'sg_apellido' => $input['sg_apellido'] ?? '',
            'password' => password_hash($input['password'] ?? '', PASSWORD_BCRYPT),
            'email' => $input['email'] ?? '',
            'telefono' => $input['telefono'] ?? '',
            'id_restaurante' => $input['id_restaurante'] ?? null,
            'fecha_registro' => date('Y-m-d'),
            'estado' => 1,
        ]);
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $e->getMessage()]);
    }
}

function registerAttendance($pdo, $input) {
    // Obtener el código del estudiante
    $codigoEstudiante = $input['codigo_estudiante'] ?? '';
    if (!$codigoEstudiante) {
        echo json_encode(['success' => false, 'message' => 'Código del estudiante es obligatorio']);
        return;
    }

    // Verificar si el estudiante existe en la base de datos
    try {
        $stmt = $pdo->prepare("SELECT id_estudiante FROM estudiantes WHERE codigo_estudiante = :codigo_estudiante");
        $stmt->execute(['codigo_estudiante' => $codigoEstudiante]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$estudiante) {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
            return;
        }

        // Obtener la fecha y hora actual
        $fechaAsistencia = date('Y-m-d');
        $horaAsistencia = date('H:i:s');

        // Insertar el registro de asistencia
        $stmt = $pdo->prepare("
            INSERT INTO registro_asistencia (id_estudiante, id_beneficio, id_restaurante, fecha_asistencia, hora_asistencia, estado_beneficio)
            VALUES (:id_estudiante, :id_beneficio, :id_restaurante, :fecha_asistencia, :hora_asistencia, :estado_beneficio)
        ");

        // Valores predeterminados para id_beneficio, id_restaurante y estado_beneficio (ajusta según tu lógica)
        $idBeneficio = 1;  // Ajusta según corresponda
        $idRestaurante = 1; // Ajusta según corresponda
        $estadoBeneficio = 1; // Ajusta según corresponda

        $stmt->execute([
            'id_estudiante' => $estudiante['id_estudiante'],
            'id_beneficio' => $idBeneficio,
            'id_restaurante' => $idRestaurante,
            'fecha_asistencia' => $fechaAsistencia,
            'hora_asistencia' => $horaAsistencia,
            'estado_beneficio' => $estadoBeneficio
        ]);

        // Retornar mensaje de éxito
        echo json_encode(['success' => true, 'message' => 'Asistencia registrada con éxito']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar asistencia: ' . $e->getMessage()]);
    }
}

