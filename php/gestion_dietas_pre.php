<?php
include './utilidades/seguridad.php';
verificarRoles(['administrador', 'entrenador']);
include './utilidades/conexion.php';

// Consulta adaptada a tu imagen: usamos 'descripcion' en lugar de 'objetivo'
$sql = "SELECT d.id_dieta, d.nombre, d.descripcion, u.nombre AS creador_nombre, u.apellido1 AS creador_apellido
        FROM dietas d
        LEFT JOIN usuarios u ON d.creada_por = u.id_usuario
        WHERE d.es_predefinida = 1
        ORDER BY d.nombre ASC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Plantillas de Dietas</h2>
                
                <?php if ($_SESSION['rol'] === 'administrador'): ?>
                    <a href="nueva_dieta_pre.php" class="btn btn-danger">
                        <i class="fa-solid fa-plus"></i> Crear Nueva Dieta
                    </a>
                <?php endif; ?>
            </div>

            <table id="tablaDietas" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre de la Dieta</th>
                        <th>Descripción</th> 
                        <th>Creada por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td>
                            <small class="text-muted italic">
                                <?= htmlspecialchars($row['descripcion']) ?>
                            </small>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['creador_nombre'] . " " . $row['creador_apellido']) ?>
                        </td>
                        <td>
                            <a href="detalle_dieta.php?id=<?= $row['id_dieta'] ?>" class="btn btn-info btn-sm text-white">
                               <i class="fa-solid fa-eye"></i> Ver Comidas
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="mt-4">
                <?php 
                    $url_volver = ($_SESSION['rol'] === 'administrador') ? 'menu_administrador.php' : 'menu_entrenador.php';
                ?>
                <a href="<?= $url_volver ?>" class="btn btn-secondary shadow-sm">
                    Volver al Menú
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablaDietas').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
                "lengthChange": false,
                "pageLength": 8,
                "info": false
            });
        });
    </script>
</body>
</html>