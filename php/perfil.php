<?php
include 'seguridad.php';
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol_sesion = $_SESSION['rol'];
$mensaje_toastr = ""; 

// --- 1. LÓGICA DE ACTUALIZACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $nombre    = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $correo    = $_POST['correo'];
    $telefono  = $_POST['telefono'];
    $nueva_clave = $_POST['nueva_clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

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
            }
        } catch (mysqli_sql_exception $e) {
            $mensaje_toastr = "error";
        }
    }
}

// --- 2. CONSULTA DE DATOS PARA EL FORMULARIO ---
$sql = "SELECT u.*, e.nombre AS nom_ent, e.apellido1 AS ape_ent, e.correo AS mail_ent 
        FROM usuarios u 
        LEFT JOIN usuarios e ON u.id_entrenador = e.id_usuario 
        WHERE u.id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Alumnos (si es entrenador)
$alumnos = null;
if ($rol_sesion === 'entrenador') {
    $sql_alumnos = "SELECT nombre, apellido1, correo, suscrito FROM usuarios WHERE id_entrenador = ? AND activo = 1";
    $stmt_a = mysqli_prepare($conexion, $sql_alumnos);
    mysqli_stmt_bind_param($stmt_a, "i", $id_usuario);
    mysqli_stmt_execute($stmt_a);
    $alumnos = mysqli_stmt_get_result($stmt_a);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | GymFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="estilo_global.css">
    
    <style>
        /* Ajuste fino para los ojos de los inputs */
        .pass-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .room-input-pass {
            padding-right: 45px !important;
            width: 100%;
        }
        .btn-eye-adjust {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #764ba2;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            z-index: 5;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-<?= ($rol_sesion === 'administrador') ? '12' : '7' ?> mb-4">
                <div class="card p-4 shadow-sm">
                    <h3>Mis Datos Personales</h3>
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
                            <input type="email" name="correo" class="room-input" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
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

                        <button type="submit" name="actualizar" class="btn btn-primary w-100 mt-2">
                            Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

            <?php if ($rol_sesion !== 'administrador'): ?>
            <div class="col-lg-5">
                <div class="card p-4 shadow-sm h-100">
                    <?php if ($rol_sesion === 'usuario'): ?>
                        <h4> Mi Instructor</h4>
                        <hr>
                        <?php if ($usuario['nom_ent']): ?>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($usuario['nom_ent'] . " " . $usuario['ape_ent']) ?></h5>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($usuario['mail_ent']) ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="alert alert-light text-muted small">Sin instructor asignado.</p>
                        <?php endif; ?>
                        
                        <div class="mt-auto pt-4">
                            <button onclick="confirmarBaja()" class="btn btn-outline-danger w-100 btn-sm">Solicitar Baja</button>
                        </div>

                    <?php elseif ($rol_sesion === 'entrenador'): ?>
                        <h4><i class="fas fa-users"></i> Alumnos Asignados</h4>
                        <hr>
                        <table id="tablaMisAlumnos" class="table align-middle small">
                            <thead><tr><th>Alumno</th><th class="text-center">Prem.</th></tr></thead>
                            <tbody>
                                <?php while ($al = mysqli_fetch_assoc($alumnos)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($al['nombre']) ?></strong><br><?= htmlspecialchars($al['correo']) ?></td>
                                    <td class="text-center"><?= $al['suscrito'] ? '✅' : '❌' ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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
        <?php if ($mensaje_toastr === "success"): ?>
            toastr.success("¡Perfil actualizado con éxito!");
        <?php elseif ($mensaje_toastr === "error"): ?>
            toastr.error("Error: El correo ya está registrado.");
        <?php elseif ($mensaje_toastr === "mismatch"): ?>
            toastr.warning("Las nuevas contraseñas no coinciden.");
        <?php endif; ?>

        $('#formPerfil').on('submit', function(e) {
            const p1 = $('#p1').val();
            const p2 = $('#p2').val();
            if (p1 !== "" && p1 !== p2) {
                e.preventDefault();
                toastr.warning("Las contraseñas no coinciden.");
            }
        });

        if ($('#tablaMisAlumnos').length) {
            $('#tablaMisAlumnos').DataTable({
                "pageLength": 5, "dom": 'tp',
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });
        }
    });

    function confirmarBaja() {
        Swal.fire({
            title: '¿Deseas darte de baja?',
            text: "Se desactivará tu acceso al sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmar Baja',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => { if (result.isConfirmed) window.location.href='borrado_logico.php'; });
    }
    </script>
</body>
</html>