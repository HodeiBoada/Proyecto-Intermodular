<?php
include './utilidades/seguridad.php';
include './utilidades/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol_sesion = $_SESSION['rol'];
$mensaje_toastr = ""; 

// --- 1. CONSULTA INICIAL (Para tener los datos antes de procesar el POST) ---
$sql_datos = "SELECT u.*, e.nombre AS nom_ent, e.apellido1 AS ape_ent, e.correo AS mail_ent 
        FROM usuarios u 
        LEFT JOIN usuarios e ON u.id_entrenador = e.id_usuario 
        WHERE u.id_usuario = ?";
$stmt_datos = mysqli_prepare($conexion, $sql_datos);
mysqli_stmt_bind_param($stmt_datos, "i", $id_usuario);
mysqli_stmt_execute($stmt_datos);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_datos));

// --- 2. LÓGICA DE ACTUALIZACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $nombre    = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $telefono  = $_POST['telefono'];
    $nueva_clave = $_POST['nueva_clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

    // BLOQUEO DE CORREO: Solo el usuario estándar puede cambiarlo
    $correo = ($rol_sesion === 'usuario') ? $_POST['correo'] : $usuario['correo'];

    if (!empty($nueva_clave) && $nueva_clave !== $confirmar_clave) {
        $mensaje_toastr = "mismatch";
    } else {
        $sql_upd = "UPDATE usuarios SET nombre=?, apellido1=?, apellido2=?, correo=?, telefono=? ";
        $params = [$nombre, $apellido1, $apellido2, $correo, $telefono];
        $types = "sssss";

        if (!empty($nueva_clave)) {
            $pass_hash = password_hash($nueva_clave, PASSWORD_DEFAULT);
            $sql_upd .= ", password_hash=? "; 
            $params[] = $pass_hash;
            $types .= "s";
        }

        $sql_upd .= " WHERE id_usuario = ?";
        $params[] = $id_usuario;
        $types .= "i";

        $stmt_upd = mysqli_prepare($conexion, $sql_upd);
        mysqli_stmt_bind_param($stmt_upd, $types, ...$params);

        try {
            if (mysqli_stmt_execute($stmt_upd)) {
                $mensaje_toastr = "success";
                // Refrescamos los datos en la variable $usuario para que se vean en el form tras guardar
                $usuario['nombre'] = $nombre;
                $usuario['apellido1'] = $apellido1;
                $usuario['apellido2'] = $apellido2;
                $usuario['correo'] = $correo;
                $usuario['telefono'] = $telefono;
            }
        } catch (mysqli_sql_exception $e) {
            $mensaje_toastr = "error";
        }
    }
}

// --- 3. CONSULTAS PARA TABLAS LATERALES ---
// Alumnos (si es entrenador)
$alumnos = null;
if ($rol_sesion === 'entrenador') {
    $sql_alumnos = "SELECT nombre, correo, suscrito FROM usuarios WHERE id_entrenador = ? AND activo = 1";
    $stmt_a = mysqli_prepare($conexion, $sql_alumnos);
    mysqli_stmt_bind_param($stmt_a, "i", $id_usuario);
    mysqli_stmt_execute($stmt_a);
    $alumnos = mysqli_stmt_get_result($stmt_a);
}

// Entrenadores (si es admin o si el usuario quiere ver el staff)
$entrenadores = null;
if ($rol_sesion === 'administrador' || $rol_sesion === 'usuario') {
    $sql_entrenadores = "SELECT nombre, correo, activo FROM usuarios WHERE rol = 'entrenador'";
    $stmt_e = mysqli_prepare($conexion, $sql_entrenadores);
    mysqli_stmt_execute($stmt_e);
    $entrenadores = mysqli_stmt_get_result($stmt_e);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | FitnessGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
    <style>
        .card { border-radius: 20px; border: none; }
        .room-input { border-radius: 10px; border: 1px solid #ddd; padding: 10px; width: 100%; }
        .pass-wrapper { position: relative; display: flex; align-items: center; }
        .room-input-pass { padding-right: 45px !important; }
        .btn-eye-adjust { position: absolute; right: 15px; background: none; border: none; color: #764ba2; cursor: pointer; z-index: 5; }
        .btn-gym { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; border-radius: 50px; }
        .readonly-custom { background-color: #f8f9fa !important; color: #6c757d !important; cursor: not-allowed; }
    </style>
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card p-4 shadow-sm">
                    <?php 
                        $pagina_inicio = "menu_" . ($_SESSION['rol'] ?? 'usuario') . ".php"; 
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold mb-0">Mis Datos Personales</h3>
                        <a href="<?= $pagina_inicio ?>" class="btn btn-sm btn-outline-secondary">
                            Volver al Menú
                        </a>
                    </div>
                    <hr>
                    <form action="" method="POST" id="formPerfil">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Nombre</label>
                                <input type="text" name="nombre" class="room-input" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Teléfono</label>
                                <input type="text" name="telefono" class="room-input" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">1º Apellido</label>
                                <input type="text" name="apellido1" class="room-input" value="<?= htmlspecialchars($usuario['apellido1']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">2º Apellido</label>
                                <input type="text" name="apellido2" class="room-input" value="<?= htmlspecialchars($usuario['apellido2'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Correo Electrónico</label>
                            <input type="email" name="correo" 
                                class="room-input <?= ($rol_sesion !== 'usuario') ? 'readonly-custom' : '' ?>" 
                                value="<?= htmlspecialchars($usuario['correo']) ?>" 
                                required 
                                <?= ($rol_sesion !== 'usuario') ? 'readonly' : '' ?>>
                                <?php if ($rol_sesion !== 'usuario'): ?>
                                    <div class="form-text text-muted small">Al ser <?= $rol_sesion ?>, tu correo está protegido.</div>
                                <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-danger">Nueva Contraseña</label>
                                <div class="pass-wrapper">
                                    <input type="password" name="nueva_clave" id="p1" class="room-input room-input-pass" placeholder="Opcional">
                                    <button type="button" class="btn-eye-adjust" onclick="verPassword('p1', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-danger">Repetir Contraseña</label>
                                <div class="pass-wrapper">
                                    <input type="password" name="confirmar_clave" id="p2" class="room-input room-input-pass" placeholder="Opcional">
                                    <button type="button" class="btn-eye-adjust" onclick="verPassword('p2', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="actualizar" class="btn btn-danger w-100 mt-3 py-2">
                            Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card p-4 shadow-sm h-100">
                    <?php if ($rol_sesion === 'usuario'): ?>
                        <h4 class="fw-bold"><i class="fas fa-chalkboard-teacher me-2"></i>Mi Instructor</h4>
                        <hr>
                        <?php if ($usuario['nom_ent']): ?>
                            <div class="p-3 border rounded shadow-sm bg-white mb-4">
                                <h5 class="mb-1 fw-bold"><?= htmlspecialchars($usuario['nom_ent'] . " " . $usuario['ape_ent']) ?></h5>
                                <p class="text-muted small mb-0"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($usuario['mail_ent']) ?></p>
                            </div>
                        <?php else: ?>
                            <p class="alert alert-light text-muted small">Sin instructor asignado todavía.</p>
                        <?php endif; ?>
                        
                        <div class="mt-auto">
                            <button onclick="confirmarBaja()" class="btn btn-outline-danger w-100 btn-sm">Solicitar Baja del Sistema</button>
                        </div>
                    <?php elseif ($rol_sesion === 'entrenador'): ?>
                        <h4 class="fw-bold"><i class="fas fa-users me-2"></i>Alumnos Asignados</h4>
                        <hr>
                        <table id="tablaMisAlumnos" class="table align-middle small table-hover">
                            <thead><tr><th>Alumno</th><th class="text-center">Suscrito</th></tr></thead>
                            <tbody>
                                <?php while ($al = mysqli_fetch_assoc($alumnos)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($al['nombre']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($al['correo']) ?></small></td>
                                    <td class="text-center"><?= $al['suscrito'] ? '✅' : '❌' ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                    <?php elseif ($rol_sesion === 'administrador'): ?>
                        <h4 class="fw-bold"><i class="fas fa-user-tie me-2"></i>Staff de Entrenadores</h4>
                        <hr>
                        <table id="tablaEntrenadoresAdmin" class="table align-middle small table-hover">
                            <thead class="text-muted small"><tr><th>Entrenador</th><th>Correo</th></tr></thead>
                            <tbody>
                                <?php while ($en = mysqli_fetch_assoc($entrenadores)): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($en['nombre']) ?></td>
                                    <td><?= htmlspecialchars($en['correo']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function verPassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        input.type = (input.type === "password") ? "text" : "password";
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    $(document).ready(function() {
        // Notificaciones Toastr
        <?php if ($mensaje_toastr === "success"): ?>
            toastr.success("¡Perfil actualizado con éxito!");
        <?php elseif ($mensaje_toastr === "error"): ?>
            toastr.error("Error al actualizar. Posible correo duplicado.");
        <?php elseif ($mensaje_toastr === "mismatch"): ?>
            toastr.warning("Las nuevas contraseñas no coinciden.");
        <?php endif; ?>

        // DataTables
        $('.table').DataTable({
            "pageLength": 5,
            "dom": 'tp',
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
        });
    });

    function confirmarBaja() {
        Swal.fire({
            title: '¿Deseas darte de baja?',
            text: "Perderás el acceso a tus rutinas y dietas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, darme de baja',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => { if (result.isConfirmed) window.location.href='./utilidades/borrado_logico.php'; });
    }
    </script>
</body>
</html>