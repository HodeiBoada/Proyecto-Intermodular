<?php
include 'seguridad.php';
verificarRol('usuario');
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// 1. Buscamos la asignación más reciente para este usuario
$sql_id = "SELECT id_rutina FROM rutina_usuario 
           WHERE id_usuario = ? 
           ORDER BY fecha_asignacion DESC LIMIT 1";
$stmt_id = mysqli_prepare($conexion, $sql_id);
mysqli_stmt_bind_param($stmt_id, "i", $id_usuario);
mysqli_stmt_execute($stmt_id);
$res_id = mysqli_stmt_get_result($stmt_id);
$asignacion = mysqli_fetch_assoc($res_id);

$resultado = null;
$datos_rutina = null;

if ($asignacion) {
    $id_rutina_actual = $asignacion['id_rutina'];

    // 2. Traemos los detalles de la rutina y ejercicios
    $sql = "SELECT r.nombre AS nombre_rutina, r.objetivo, 
                   e.nombre AS nombre_ejercicio, re.dia, 
                   re.series, re.repeticiones, re.tiempo_descanso, e.categoria
            FROM rutinas r
            JOIN rutina_ejercicio re ON r.id_rutina = re.id_rutina
            JOIN ejercicios e ON re.id_ejercicio = e.id_ejercicio
            WHERE r.id_rutina = ?
            ORDER BY re.dia ASC, re.orden ASC";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_rutina_actual);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    // Extraer datos generales para el encabezado (misma lógica que dieta)
    $datos_rutina = mysqli_fetch_assoc($resultado);
    if ($datos_rutina) {
        mysqli_data_seek($resultado, 0);
    }
}

$dias_texto = [
    1 => "lunes", 2 => "martes", 3 => "miércoles", 
    4 => "jueves", 5 => "viernes", 6 => "sábado", 7 => "domingo"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Rutina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="estilo_global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <?php if ($datos_rutina): ?>
            <h1>Mi Entrenamiento</h1>
            <table id="tablaRutina" class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Ejercicio</th>
                        <th>Categoría</th>
                        <th>Series</th>
                        <th>Repes</th>
                        <th>Descanso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td>
                            <span class="active-room" style="margin:0; font-size: 0.8rem; display: inline-block;">
                                <?php echo ucfirst($dias_texto[$fila['dia']] ?? 'N/A'); ?>
                            </span>
                        </td>
                        
                        <td><strong><?php echo htmlspecialchars($fila['nombre_ejercicio']); ?></strong></td>

                        <td>
                            <span class="badge bg-info text-dark rounded-pill px-3">
                                <?php echo htmlspecialchars($fila['categoria']); ?>
                            </span>
                        </td>

                        <td><?php echo $fila['series']; ?></td>
                        <td><?php echo $fila['repeticiones']; ?></td>
                        
                        <td class="text-muted" style="font-size: 0.9rem;">
                            <?php echo $fila['tiempo_descanso']; ?> seg
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="card p-5 text-center" style="background: rgba(255,255,255,0.8);">
                <h3 class="text-muted">No tienes una rutina activa</h3>
                <p>Tu entrenador aún no ha configurado tu plan de entrenamiento más reciente.</p>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="menu_usuario.php" class="btn btn-secondary">Volver al menú</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#tablaRutina').DataTable({
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
            "pageLength": 5,
            "lengthChange": false, 
            "responsive": true
        });
    });
    </script>
</body>
</html>