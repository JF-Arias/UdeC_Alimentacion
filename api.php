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
    case 'create_error_report':
        createErrorReport($pdo, $input);
        break;
    case 'analisis_datos':
        readUsers($pdo, $input);
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
    
    // Validar que el código de estudiante no esté vacío
    if (empty($codigoEstudiante)) {
        echo json_encode(['success' => false, 'message' => 'Código del estudiante es obligatorio']);
        return;
    }

    try {
        // Verificar si el estudiante existe y si ya tiene un registro de asistencia para hoy
        $fechaAsistencia = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT e.id_estudiante, e.nombre_estudiante, ra.id_estudiante AS asistencia_existente
                            FROM estudiantes e
                            LEFT JOIN registro_asistencia ra ON e.id_estudiante = ra.id_estudiante AND ra.fecha_asistencia = :fecha_asistencia
                            WHERE e.codigo_estudiante = :codigo_estudiante");
        $stmt->execute([
            'codigo_estudiante' => $codigoEstudiante,
            'fecha_asistencia' => $fechaAsistencia
        ]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el estudiante existe
        if (!$estudiante) {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
            return;
        }

        // Verificar si ya existe un registro de asistencia para hoy
        if ($estudiante['asistencia_existente']) {
            echo json_encode(['success' => false, 'message' => 'El estudiante ya tiene un registro de asistencia para hoy']);
            return;
        }

        // Obtener la hora actual para el registro de la asistencia
        $horaAsistencia = date('H:i:s');

        // Asignar valores para los otros campos necesarios
        $idBeneficio = 1;  // Ajusta según tu lógica
        $idRestaurante = 1; // Igualmente, cambia si es necesario
        $estadoBeneficio = 1; // Esto se puede ajustar según el estado que deseas registrar

        // Insertar el registro de asistencia
        $stmt = $pdo->prepare("INSERT INTO registro_asistencia (id_estudiante, id_beneficio, id_restaurante, fecha_asistencia, hora_asistencia, estado_beneficio)
                            VALUES (:id_estudiante, :id_beneficio, :id_restaurante, :fecha_asistencia, :hora_asistencia, :estado_beneficio)");

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
        // Manejo de errores si la consulta falla
        echo json_encode(['success' => false, 'message' => 'Error al registrar asistencia: ' . $e->getMessage()]);
    }
}

function createErrorReport($pdo, $input) {
    $id_usuario = $input['id_usuario'] ?? '';
    $documento_estudiante = $input['documento_estudiante'] ?? '';
    $observacion = $input['observacion'] ?? '';

    // Validar que los campos no estén vacíos
    if (empty($id_usuario) || empty($documento_estudiante) || empty($observacion)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        return;
    }

    try {
        // Buscar el ID del estudiante con el documento proporcionado
        $stmt = $pdo->prepare("SELECT id_estudiante FROM estudiantes WHERE documento_estudiante = :documento_estudiante");
        $stmt->execute(['documento_estudiante' => $documento_estudiante]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$estudiante) {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
            return;
        }

        // Insertar el reporte de error en la base de datos
        $stmt = $pdo->prepare("INSERT INTO reporte_errores (id_usuario, id_estudiante, observacion, fecha_registro)
                            VALUES (:id_usuario, :id_estudiante, :observacion, NOW())");
        $stmt->execute([
            'id_usuario' => $id_usuario,
            'id_estudiante' => $estudiante['id_estudiante'],
            'observacion' => $observacion
        ]);

        echo json_encode(['success' => true, 'message' => 'Reporte de error registrado con éxito']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el reporte: ' . $e->getMessage()]);
    }
}


// Función para obtener la asistencia
function readUsers($pdo) {
    try {
        // Consulta para obtener los registros de asistencia
        $stmt = $pdo->prepare("SELECT e.nombre_estudiante, e.codigo_estudiante, ra.hora_asistencia, ra.estado_beneficio
                            FROM estudiantes e
                            JOIN registro_asistencia ra ON e.id_estudiante = ra.id_estudiante
                            WHERE ra.fecha_asistencia = CURDATE()");
        $stmt->execute();
        $asistencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los datos en formato JSON
        echo json_encode([
            'success' => true,
            'asistencia' => $asistencia
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los datos: ' . $e->getMessage()]);
    }
}




?>

