<?php
// --- BLOQUE 1: LÓGICA PHP ---
include 'conexion.php';
$mensaje_error = "";
$registro_exitoso = false; // Variable bandera para el JavaScript

// Variables para persistencia de datos (mantener lo escrito)
$nombre = $_POST['nombre'] ?? '';
$apellido1 = $_POST['apellido1'] ?? '';
$apellido2 = $_POST['apellido2'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = $_POST['clave'];
    $clave_confirmar = $_POST['clave_confirmar'];

    if ($clave !== $clave_confirmar) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } else {
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $rol = 'usuario'; 
        $suscrito = isset($_POST['suscrito']) ? 1 : 0;
        $activo = 1; 

        $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, telefono, correo, password_hash, rol, suscrito, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssii", $nombre, $apellido1, $apellido2, $telefono, $correo, $clave_hash, $rol, $suscrito, $activo);

        try {
            if (mysqli_stmt_execute($stmt)) {
                $registro_exitoso = true;
                // Limpiamos variables para que el formulario se vea vacío al terminar
                $nombre = $apellido1 = $apellido2 = $telefono = $correo = "";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $mensaje_error = "Ese correo ya está registrado.";
            } else {
                $mensaje_error = "Error en el registro: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>

    <div class="container mt-5">
        <div class="card p-4 shadow" style="max-width: 450px; margin: auto;">
            <h2 class="text-center">Crear Cuenta</h2>
            <form method="post" id="formRegistro">
                
                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" class="room-input" placeholder="Nombre *" required>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <input type="text" name="apellido1" value="<?= htmlspecialchars($apellido1) ?>" class="room-input" placeholder="1º Apellido *" required>
                    </div>
                    <div style="flex: 1;">
                        <input type="text" name="apellido2" value="<?= htmlspecialchars($apellido2) ?>" class="room-input" placeholder="2º Apellido">
                    </div>
                </div>

                <input type="email" name="correo" value="<?= htmlspecialchars($correo) ?>" class="room-input" placeholder="Email *" required>
                
                <input type="phone" name="telefono" value="<?= htmlspecialchars($telefono) ?>" class="room-input" placeholder="Teléfono *" required>

                <div class="pass-group">
                    <input type="password" name="clave" id="pass1" class="room-input" placeholder="Contraseña *" required>
                    <button type="button" class="btn-eye" onclick="verPassword('pass1', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="pass-group">
                    <input type="password" name="clave_confirmar" id="pass2" class="room-input" placeholder="Repite Contraseña *" required>
                    <button type="button" class="btn-eye" onclick="verPassword('pass2', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="mb-3">
                    <label class="small" style="cursor:pointer;">
                        <input type="checkbox" name="suscrito"> Solicitar suscripción Premium
                    </label>
                </div>

                <button class="btn btn-submit" type="submit">Registrarse</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php" class="text-muted small">¿Ya tienes cuenta? Entra aquí</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function verPassword(idInput, btn) {
            const input = document.getElementById(idInput);
            const icono = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icono.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icono.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        $(document).ready(function() {
            // 1. Éxito: Registro completado
            <?php if ($registro_exitoso): ?>
                toastr.success("¡Registro completado con éxito! Redirigiendo...");
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 2500);
            <?php endif; ?>

            // 2. Error de PHP (ej: correo duplicado o claves distintas en server)
            <?php if ($mensaje_error): ?>
                toastr.error("<?= $mensaje_error ?>");
            <?php endif; ?>

            // 3. Validación de claves en el cliente (evita recarga)
            $('#formRegistro').on('submit', function(e) {
                const p1 = $('#pass1').val();
                const p2 = $('#pass2').val();
                
                if (p1 !== p2) {
                    e.preventDefault(); 
                    toastr.warning("Las contraseñas no coinciden en el formulario.");
                }
            });
        });
    </script>
</body>
</html>