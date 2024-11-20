<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Universidad de Cundinamarca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 25rem;">
        <div class="text-center mb-4">
        <img src="logo_ucundinamarca.png" alt="Logo UdeC" class="img-fluid" style="max-width: 100px;">
        <h4 class="mt-3">Inicio de Sesión</h4>
        </div>
        <form action="/control/control_login.php" method="POST">
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ingresa tu usuario" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            <div class="text-center mt-3">
        <a href="registro.php" class="text-decoration-none">¿No tienes una cuenta? Regístrate</a>
        </div>
    </form>
    </div>
    </div>
</body>
</html>
