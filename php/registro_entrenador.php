<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';

$mensaje_toastr = "";
$correo_creado = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido1 = mysqli_real_escape_string($conexion, $_POST['apellido1']);
    $apellido2 = mysqli_real_escape_string($conexion, $_POST['apellido2']);
    $telefono  = mysqli_real_escape_string($conexion, $_POST['telefono']);

    // 1. Generar correo base: nombre@fitness.com
    $nombre_limpio = strtolower(str_replace(' ', '', $nombre));
    $correo = $nombre_limpio . "@fitness.com";

    // 2. Verificar si ya existe ese correo en la BD
    $check_email = mysqli_prepare($conexion, "SELECT id_usuario FROM usuarios WHERE correo = ?");
    mysqli_stmt_bind_param($check_email, "s", $correo);
    mysqli_stmt_execute($check_email);
    mysqli_stmt_store_result($check_email);

    // 3. Si existe, le concatenamos un número aleatorio entre 10 y 99
    if (mysqli_stmt_num_rows($check_email) > 0) {
        $correo = $nombre_limpio . rand(10, 99) . "@fitness.com";
    }
    mysqli_stmt_close($check_email);

    // 4. Contraseña provisional
    $password_provisional = "entrena"; 
    $pass_hash = password_hash($password_provisional, PASSWORD_DEFAULT);

    // 5. Insertar en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, telefono, correo, password_hash, rol, suscrito, activo) 
            VALUES (?, ?, ?, ?, ?, ?, 'entrenador', 1, 1)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $apellido1, $apellido2, $telefono, $correo, $pass_hash);

    try {
        if (mysqli_stmt_execute($stmt)) {
            $mensaje_toastr = "success";
            $correo_creado = $correo; 
        }
    } catch (mysqli_sql_exception $e) {
        $mensaje_toastr = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Entrenador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4 border-0">
                    <div class="text-center mb-4">
                        <h2>Registrar Entrenador</h2>
                    </div>
                    <hr>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="room-input" placeholder="Ej: Juan" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">1º Apellido</label>
                                <input type="text" name="apellido1" class="room-input" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">2º Apellido</label>
                                <input type="text" name="apellido2" class="room-input">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" name="telefono" class="room-input" placeholder="600 000 000">
                        </div>

                        <div class="alert alert-warning border-0 py-3">
                            <strong>Información adicional:</strong><br>
                            La clave inicial es "entrena". 
                            El correo se genera automáticamente.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn">
                                Registrar entrenador
                            </button>
                            <a href="menu_administrador.php" class="btn">Volver al Menú</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Configuración de Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "10000"
            };

            <?php if ($mensaje_toastr === "success"): ?>
                toastr.success("Cuenta creada.<br>Correo: <strong><?= $correo_creado ?></strong>", "¡Éxito!");
            <?php elseif ($mensaje_toastr === "error"): ?>
                toastr.error("No se pudo completar el registro. Inténtelo de nuevo.", "Error");
            <?php endif; ?>
        });
    </script>
</body>
</html>