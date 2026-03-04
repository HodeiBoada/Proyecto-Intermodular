<?php
include './utilidades/seguridad.php';
verificarRoles(['entrenador', 'administrador']);
include './utilidades/conexion.php';

$rol_sesion = $_SESSION['rol'];
$resultado = mysqli_query($conexion, "SELECT * FROM ejercicios ORDER BY categoria, dificultad");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Ejercicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
<?php include './utilidades/navbar.php'; ?> 

<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Biblioteca de Ejercicios</h2>
            <?php if ($rol_sesion === 'administrador'): ?>
                <a href="nuevo_ejercicio.php" class="btn btn-success shadow-sm">
                Añadir Ejercicio
                </a>
            <?php endif; ?>
        </div>

        <table id="tablaEjercicios" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Dificultad</th>
                    <th>Descripción</th>
                    <th>Instrucciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td>
                            <span class="badge <?= $fila['dificultad'] == 'Principiante' ? 'bg-success' : ($fila['dificultad'] == 'Intermedio' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= $fila['dificultad'] ?>
                            </span>
                        </td>
                        <td><small><?= htmlspecialchars($fila['descripcion']) ?></small></td>
                        <td><small><?= htmlspecialchars($fila['instrucciones']) ?></small></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <a href="menu_<?= $rol_sesion ?>.php" class="btn btn-secondary shadow-sm">
                Volver al menú
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaEjercicios').DataTable({
            "language": {
                "sSearch": "Filtrar ejercicios:",
                "sLengthMenu": "Ver _MENU_",
                "sZeroRecords": "No hay ejercicios en tu plan",
                "sInfo": "Total: _TOTAL_ ejercicios",
                "sInfoEmpty": "Sin datos",
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            },
            "lengthChange": false,
            "pageLength": 5,
            "order": [[0, 'asc']], // Ordenamos por Nombre
            "responsive": true
        });
    });
</script>
</body>
</html>