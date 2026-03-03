<?php
include 'seguridad.php';
verificarRol('administrador'); 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
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
                <table id="tablaEntrenadores" class="table table-hover">
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
                        $sql = "SELECT id_usuario, nombre, apellido1, correo FROM usuarios WHERE rol = 'entrenador' AND activo = 1";
                        $res = mysqli_query($conexion, $sql);

                        if ($res) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apellido1']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                echo "<td>
                                        <button type='button' 
                                           class='btn btn-danger btn-sm btn-baja' 
                                           data-id='{$row['id_usuario']}' 
                                           data-nombre='{$row['nombre']} {$row['apellido1']}'>
                                           Dar de Baja
                                        </button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#tablaEntrenadores').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
            });

            // Lógica de SweetAlert2 para el botón de baja
            $(document).on('click', '.btn-baja', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Vas a dar de baja al entrenador ${nombre}. Sus alumnos quedarán sin instructor asignado.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, dar de baja',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigimos al script de borrado si confirma
                        window.location.href = `borrado_logico.php?id=${id}`;
                    }
                });
            });

            // Toastr para el éxito (después de la redirección)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('status') === 'success_baja') {
                toastr.success('Entrenador dado de baja y alumnos liberados.', 'Operación Exitosa');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>