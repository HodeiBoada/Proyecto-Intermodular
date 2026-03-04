<?php
include './utilidades/seguridad.php';
include './utilidades/conexion.php';
verificarRoles(rolesPermitidos: ['administrador', 'entrenador']);

$id_dieta = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta basada en tus capturas de pantalla:
// Tabla 'comidas': id_comida, nombre, calorias
// Tabla 'dieta_comida': id_dieta, id_comida, dia_semana, momento, orden_momento
$sql = "SELECT 
            dc.dia_semana, 
            dc.momento,
            c.nombre as comida, 
            c.calorias
        FROM dieta_comida dc
        JOIN comidas c ON dc.id_comida = c.id_comida
        WHERE dc.id_dieta = ?
        ORDER BY 
            FIELD(dc.dia_semana, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'),
            dc.orden_momento ASC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_dieta);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$total_comidas = mysqli_num_rows($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Dieta | FITNESSGYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body style="background-color: #f8f9fa;">
    <?php include './utilidades/navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow border-0 p-4" style="border-radius: 25px;">
            <div class="card-body">
                <h2 class="mb-4">Plan nutricional</h2>
                <hr>

                <?php if ($total_comidas > 0): ?>
                    <table id="tablaDietasDetalle" class="table table-hover align-middle mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>DÍA</th>
                                <th>MOMENTO</th>
                                <th>COMIDA</th>
                                <th>CALORÍAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><strong><?= ucfirst($row['dia_semana']) ?></strong></td>
                                <td>
                                    <span class="badge bg-info text-dark" style="border-radius: 10px; padding: 5px 12px;">
                                        <?= htmlspecialchars($row['momento']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['comida']) ?></td>
                                <td><strong><?= $row['calorias'] ?></strong> <small>kcal</small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-apple-whole fa-3x mb-3 text-muted"></i>
                        <h4 class="text-muted">No hay comidas en esta dieta</h4>
                        <p>Esta plantilla aún no tiene alimentos asignados.</p>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary px-4" style="border-radius: 20px;">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#tablaDietasDetalle').length) {
                $('#tablaDietasDetalle').DataTable({
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
                    "dom": 'ftip',
                    "ordering": false,
                    "pageLength": 10
                });
            }
        });
    </script>
</body>
</html>