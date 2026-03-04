<?php
include './utilidades/seguridad.php';
verificarRoles(['entrenador', 'administrador']);
include './utilidades/conexion.php';

$rol_sesion = $_SESSION['rol'];
$resultado = mysqli_query($conexion, "SELECT * FROM comidas ORDER BY tipo, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Comidas</title>
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
            <h2>Biblioteca de Comidas</h2>
            <?php if ($rol_sesion === 'administrador'): ?>
                <a href="nueva_comida.php" class="btn btn-success shadow-sm">
                    Añadir Comida
                </a>
            <?php endif; ?>
        </div>

        <table id="tablaComidas" class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Calorías</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= ucfirst($fila['tipo']) ?></span></td>
                        <td><?= $fila['calorias'] ?> kcal</td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
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
        $('#tablaComidas').DataTable({
            "language": {
                "sSearch": "Filtrar comidas:",
                "sLengthMenu": "Ver _MENU_",
                "sZeroRecords": "No hay comidas en tu plan",
                "sInfo": "Total: _TOTAL_ comidas",
                "sInfoEmpty": "Sin datos",
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            },
            "pageLength": 5,
            "lengthChange": false,
            "responsive": true
        });
    });
</script>
</body>
</html>