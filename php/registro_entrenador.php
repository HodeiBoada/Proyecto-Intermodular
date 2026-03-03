<?php
include 'seguridad.php';
verificarRol('administrador'); // Asegúrate de que tu sistema use 'administrador' o 'admin'
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Entrenadores</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel de Gestión de Entrenadores</h2>
            <a href="menu_administrador.php" class="btn btn-secondary">Volver al Menú</a>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="tablaEntrenadores" class="table table-hover w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Primer Apellido</th>
                            <th>Correo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para traer entrenadores activos
                        $sql = "SELECT id_usuario, nombre, apellido, correo FROM usuarios WHERE rol = 'entrenador' AND activo = 1";
                        $res = mysqli_query($conexion, $sql);

                        if ($res) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $nombreCompleto = htmlspecialchars($row['nombre'] . " " . $row['apellido']);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                echo "<td>
                                        <a href='borrado_logico.php?id={$row['id_usuario']}' 
                                           class='btn btn-danger btn-sm' 
                                           onclick='return confirm(\"¿Estás seguro de dar de baja a $nombreCompleto? Sus alumnos quedarán libres.\")'>
                                           Dar de Baja
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Inicializar DataTable
            $('#tablaEntrenadores').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true
            });

            // 2. Configurar Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            // 3. Detectar si venimos de un borrado exitoso
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('status') === 'success_baja') {
                toastr.success('El entrenador ha sido dado de baja y sus alumnos han sido liberados correctamente.', '¡Hecho!');
                
                // Limpiar la URL para que el mensaje no salga al refrescar
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>