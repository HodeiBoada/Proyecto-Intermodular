<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Panel de Control Administrativo</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Gestión de Staff</h5>
                        <div class="d-grid gap-2">
                            <a href="registro_entrenador.php" class="btn btn-dark w-100">Alta Entrenador</a>
                            <a href="baja_entrenador.php" class="btn btn-dark w-100">Baja Entrenador</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Asignación</h5>
                        <a href="asignar_clientes.php" class="btn btn-dark w-100">Asignar Clientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Ejercicios y Comidas</h5>
                        <div class="d-grid gap-2">
                            <a href="biblioteca_ejercicios.php" class="btn btn-dark w-100">Ejercicios</a>
                            <a href="biblioteca_comidas.php" class="btn btn-dark w-100">Comidas</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Rutinas Predefinidas</h5>
                        <a href="gestion_rutinas_pre.php" class="btn btn-dark w-100">Gestionar Rutinas</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Dietas Predefinidas</h5>
                        <a href="gestion_dietas_pre.php" class="btn btn-dark w-100">Gestionar Dietas</a>
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Cerrar Sesión</h5>
                        <a href="logout.php" class="btn btn-dark w-100">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>