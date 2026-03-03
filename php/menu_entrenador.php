<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Entrenador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Panel de Control de Entrenador</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Mis Clientes</h5>
                        <a href="clientes.php" class="btn btn-dark w-100">Ver Clientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Entrenamiento</h5>
                        <div class="d-grid gap-2">
                            <a href="crear_rutina.php" class="btn btn-dark w-100">Crear Rutina</a>
                            <a href="asignar_rutina.php" class="btn btn-dark w-100">Asignar Rutina</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Nutrición</h5>
                        <div class="d-grid gap-2">
                            <a href="crear_dieta.php" class="btn btn-dark w-100">Crear Dieta</a>
                            <a href="asignar_dieta.php" class="btn btn-dark w-100">Asignar Dieta</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 20px;">
                    <div class="card-body text-center p-4">
                        <h5 class="mb-4">Catálogo de Recursos</h5>
                        <div class="row g-3"> 
                            <div class="col-6">
                                <a href="biblioteca_ejercicios.php" class="btn btn-dark w-100 py-3 shadow-sm" style="border-radius: 15px;">Ejercicios</a>
                            </div>
                            <div class="col-6">
                                <a href="biblioteca_comidas.php" class="btn btn-dark w-100 py-3 shadow-sm" style="border-radius: 15px;">Comidas</a>
                            </div>
                            <div class="col-6">
                                <a href="gestion_dietas_pre.php" class="btn btn-dark w-100 py-3 shadow-sm" style="border-radius: 15px;">Plantillas Dietas</a>
                            </div>
                            <div class="col-6">
                                <a href="gestion_rutinas_pre.php" class="btn btn-dark w-100 py-3 shadow-sm" style="border-radius: 15px;">Plantillas Rutinas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
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