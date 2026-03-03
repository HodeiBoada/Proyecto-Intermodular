<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';

$mensaje_toastr = "";

// --- LÓGICA DE ASIGNACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_entrenador = $_POST['id_entrenador'];

    if (!empty($id_entrenador)) {
        $sql_upd = "UPDATE usuarios SET id_entrenador = ? WHERE id_usuario = ?";
        $stmt_upd = mysqli_prepare($conexion, $sql_upd);
        mysqli_stmt_bind_param($stmt_upd, "ii", $id_entrenador, $id_cliente);

        if (mysqli_stmt_execute($stmt_upd)) {
            $mensaje_toastr = "success";
        } else {
            $mensaje_toastr = "error";
        }
    }
}

// --- CONSULTA: Clientes sin entrenador ---
$sql_libres = "SELECT id_usuario, nombre, apellido1, correo FROM usuarios 
               WHERE rol = 'usuario' AND activo = 1 AND id_entrenador IS NULL";
$res_libres = mysqli_query($conexion, $sql_libres);
$total_libres = mysqli_num_rows($res_libres); // Guardamos el total para la condición

// --- CONSULTA: Entrenadores ---
$sql_entrenadores = "SELECT id_usuario, nombre, apellido1 FROM usuarios 
                     WHERE rol = 'entrenador' AND activo = 1";
$res_entrenadores = mysqli_query($conexion, $sql_entrenadores);
$entrenadores = mysqli_fetch_all($res_entrenadores, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Clientes | GymFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary">Asignar Clientes</h2>
                <a href="menu_administrador.php" class="btn btn-outline-secondary btn-sm">Volver al Menú</a>
            </div>
            <hr>
            <?php if ($total_libres > 0): ?>
                <table id="tablaAsignar" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Asignar a Entrenador</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cli = mysqli_fetch_assoc($res_libres)): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($cli['nombre'] . " " . $cli['apellido1']) ?></strong></td>
                            <td><?= htmlspecialchars($cli['correo']) ?></td>
                            <td>
                                <form action="" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="id_cliente" value="<?= $cli['id_usuario'] ?>">
                                    <select name="id_entrenador" class="form-select form-select-sm" required>
                                        <option value="">Seleccionar Instructor...</option>
                                        <?php foreach ($entrenadores as $ent): ?>
                                            <option value="<?= $ent['id_usuario'] ?>">
                                                <?= htmlspecialchars($ent['nombre'] . " " . $ent['apellido1']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </td>
                            <td class="text-center">
                                    <button type="submit" name="asignar" class="btn btn-success btn-sm px-3 shadow-sm">
                                        Asignar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <h4>¡Todo al día!</h4>
                    <p>No hay clientes libres esperando asignación de entrenador.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Solo inicializamos DataTable si la tabla existe en el DOM
            if ($('#tablaAsignar').length) {
                $('#tablaAsignar').DataTable({
                    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
                    "pageLength": 10,
                    "lengthChange": false,
                    "info": false,
                    "ordering": false // Opcional: quitamos ordenación para que no se muevan los selects
                });
            }

            <?php if ($mensaje_toastr === "success"): ?>
                toastr.success("Cliente asignado con éxito.");
            <?php elseif ($mensaje_toastr === "error"): ?>
                toastr.error("Hubo un error en la base de datos.");
            <?php endif; ?>
        });
    </script>
</body>
</html>