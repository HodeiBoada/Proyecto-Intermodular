<?php
include 'seguridad.php';
include 'conexion.php';

$id_rutina = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para sacar los ejercicios
$sql = "SELECT 
            re.dia, 
            e.nombre as ejercicio, 
            e.categoria, 
            re.series, 
            re.repeticiones, 
            re.tiempo_descanso 
        FROM rutina_ejercicio re
        JOIN ejercicios e ON re.id_ejercicio = e.id_ejercicio
        WHERE re.id_rutina = ?
        ORDER BY re.dia ASC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_rutina);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Contamos cuántos ejercicios han venido
$total_ejercicios = mysqli_num_rows($resultado);

function nombreDia($num) {
    $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
    return $dias[$num] ?? "Día $num";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Rutina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="estilo_global.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow border-0 p-4" style="border-radius: 25px;">
            <div class="card-body">
                <h2 class="mb-4">Ejercicios de la Plantilla</h2>
                <hr>

                <?php if ($total_ejercicios > 0): ?>
                    <table id="tablaDetalles" class="table table-hover align-middle mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>DÍA</th>
                                <th>EJERCICIO</th>
                                <th>CATEGORÍA</th>
                                <th>SERIES</th>
                                <th>REPES</th>
                                <th>DESCANSO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><strong><?= nombreDia($row['dia']) ?></strong></td>
                                <td><?= htmlspecialchars($row['ejercicio']) ?></td>
                                <td>
                                    <span class="badge" style="background-color: #00d2ff; color: #fff; border-radius: 15px; padding: 5px 15px;">
                                        <?= htmlspecialchars($row['categoria']) ?>
                                    </span>
                                </td>
                                <td><?= $row['series'] ?></td>
                                <td><?= $row['repeticiones'] ?></td>
                                <td><?= $row['tiempo_descanso'] ?> seg</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-dumbbell fa-3x mb-3 text-muted"></i>
                        <h4>Rutina vacía</h4>
                        <p class="text-muted">Aún no se han añadido ejercicios a esta plantilla de entrenamiento.</p>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary px-4" style="border-radius: 20px;">
                        <i class="fa-solid fa-arrow-left"></i> Volver atrás
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
            if ($('#tablaDetalles').length) {
                $('#tablaDetalles').DataTable({
                    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
                    "dom": 'ftip',
                    "ordering": false 
                });
            }
        });
    </script>
</body>
</html>