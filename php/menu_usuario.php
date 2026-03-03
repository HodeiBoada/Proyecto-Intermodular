<?php
include './utilidades/seguridad.php';
verificarRol('usuario');
include './utilidades/conexion.php';

// --- LÓGICA DE VERIFICACIÓN DE SUSCRIPCIÓN ---
$id_user = $_SESSION['id_usuario'];
$es_premium = false;

// Consultamos los datos del usuario logueado
$query_status = mysqli_query($conexion, "SELECT suscrito, fecha_fin_suscripcion FROM usuarios WHERE id_usuario = $id_user");
$user_status = mysqli_fetch_assoc($query_status);

if ($user_status) {
    $hoy = date('Y-m-d');
    // Verificamos si está marcado como suscrito Y si la fecha no ha caducado
    if ($user_status['suscrito'] == 1 && $user_status['fecha_fin_suscripcion'] >= $hoy) {
        $es_premium = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel | GymFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Bienvenido a tu Panel Personal</h2>

        <div class="row mb-4">
            <div class="col-12">
                <?php if (!$es_premium): ?>
                    <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm" style="border-radius: 15px; border: none; background: linear-gradient(90deg, #fceabb 0%, #f8b500 100%); color: #333;">
                        <div>
                            <i class="fa-solid fa-crown me-2"></i> 
                            <strong>¡Pásate a Premium!</strong> Desbloquea videollamadas y asesoría directa.
                        </div>
                        <a href="suscripcion.php" class="btn btn-dark btn-sm shadow-sm" style="border-radius: 10px;">Ver Planes</a>
                    </div>
                <?php else: ?>
                    <div class="text-muted small mb-3">
                        <i class="fa-solid fa-calendar-check me-1"></i> 
                        Tu suscripción vence el: <strong><?php echo date('d/m/Y', strtotime($user_status['fecha_fin_suscripcion'])); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Mi Entrenamiento</h5>
                        <a href="ver_rutina_usuario.php" class="btn btn-dark w-100">Ver mi Rutina</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Mi Plan Nutricional</h5>
                        <a href="ver_dieta_usuario.php" class="btn btn-dark w-100">Ver mi Dieta</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Mi Perfil</h5>
                        <a href="perfil.php" class="btn btn-dark w-100">Editar Perfil</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Asesoría Online</h5>
                        <?php if ($es_premium): ?>
                            <a href="index.php" class="btn btn-primary w-100">Ir a Sala Virtual</a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>🔒 Solo Premium</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Cerrar Sesión</h5>
                        <a href="logout.php" class="btn btn-danger w-100">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>