<?php
include 'seguridad.php';
verificarRol('usuario');
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Consulta SQL con orden lógico por día de la semana y momento
$sql = "SELECT d.nombre AS nombre_dieta, d.descripcion, c.nombre AS nombre_comida, c.descripcion AS descripcion_comida, 
               dc.dia_semana, dc.momento, dc.orden_momento
        FROM usuarios u
        JOIN dietas d ON u.id_dieta_activa = d.id_dieta
        JOIN dieta_comida dc ON d.id_dieta = dc.id_dieta
        JOIN comidas c ON dc.id_comida = c.id_comida
        WHERE u.id_usuario = ?
        ORDER BY 
            FIELD(dc.dia_semana, 'lunes','martes','miércoles','jueves','viernes','sábado','domingo'),
            FIELD(dc.momento, 'mañana','mediodía','tarde','noche'),
            dc.orden_momento";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Extraer datos generales de la dieta para el encabezado
$datos_dieta = mysqli_fetch_assoc($resultado);
// Resetear el puntero para que el bucle while recorra todas las filas
if ($datos_dieta) {
    mysqli_data_seek($resultado, 0);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Dieta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <?php if ($datos_dieta): ?>
            <h1><i class="fas fa-utensils"></i> Mi Plan Nutricional</h1>
            <table id="tablaDieta" class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Momento</th>
                        <th>Comida</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td>
                            <span class="active-room" style="margin:0; font-size: 0.8rem; display: inline-block;">
                                <?php echo ucfirst($fila['dia_semana']); ?>
                            </span>
                        </td>
                        
                        <td>
                            <?php 
                                $badge_class = 'bg-secondary';
                                if($fila['momento'] == 'mañana') $badge_class = 'bg-info text-dark';
                                if($fila['momento'] == 'mediodía') $badge_class = 'bg-success text-white';
                                if($fila['momento'] == 'tarde') $badge_class = 'bg-warning text-dark';
                                if($fila['momento'] == 'noche') $badge_class = 'bg-dark text-white';
                            ?>
                            <span class="badge <?php echo $badge_class; ?> rounded-pill px-3">
                                <?php echo ucfirst($fila['momento']); ?>
                            </span>
                        </td>

                        <td><strong><?php echo htmlspecialchars($fila['nombre_comida']); ?></strong></td>
                        
                        <td class="text-muted" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($fila['descripcion_comida']); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="card p-5 text-center" style="background: rgba(255,255,255,0.8);">
                <h3 class="text-muted">No tienes una dieta activa</h3>
                <p>Tu entrenador aún no ha asignado un plan nutricional a tu perfil.</p>
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
        $('#tablaDieta').DataTable({
            "language": {
                "sSearch": "Filtrar por día o comida:",
                "sLengthMenu": "Ver _MENU_",
                "sZeroRecords": "No hay coincidencias en tu dieta",
                "sInfo": "Total: _TOTAL_ comidas",
                "sInfoEmpty": "Sin datos",
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            },
            "pageLength": 10,
            "lengthChange": false, 
            "responsive": true
        });
    });
    </script>
</body>
</html>