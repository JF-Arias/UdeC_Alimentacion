<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Universidad de Cundinamarca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 30rem;">
        <div class="text-center mb-4">
        <img src="logo_ucundinamarca.png" alt="Logo UdeC" class="img-fluid" style="max-width: 100px;">
        <h4 class="mt-3">Registro de Usuario</h4>
        </div>
        <form action="/control/control_registro.php method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
            <label for="nombre_1" class="form-label">Primer Nombre</label>
            <input type="text" id="nombre_1" name="nombre_1" class="form-control" placeholder="Primer Nombre" required>
            </div>
            <div class="col-md-6 mb-3">
            <label for="nombre_2" class="form-label">Segundo Nombre</label>
            <input type="text" id="nombre_2" name="nombre_2" class="form-control" placeholder="Segundo Nombre">
            </div>
            <div class="col-md-6 mb-3">
            <label for="apellido_1" class="form-label">Primer Apellido</label>
            <input type="text" id="apellido_1" name="apellido_1" class="form-control" placeholder="Primer Apellido" required>
            </div>
            <div class="col-md-6 mb-3">
            <label for="apellido_2" class="form-label">Segundo Apellido</label>
            <input type="text" id="apellido_2" name="apellido_2" class="form-control" placeholder="Segundo Apellido">
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@ude.edu.co" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Número de Teléfono" required>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Crea tu usuario" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Crea una contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">¿Ya tienes una cuenta? Inicia Sesión</a>
        </div>
        </form>
    </div>
    </div>
</body>
</html>
