<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessGym | Tu App de Entrenamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body class="landing-body">

      <?php include 'navbar.php'; ?> 


    <div class="container main-content-wrapper">
        <div class="text-center py-5">
            <h1 class="display-4 fw-bold mb-3">TU CUERPO, TU MOMENTO</h1>
            <p class="text-muted lead">La aplicación definitiva para entrenar donde quieras.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 p-3">
                    <div class="card-body text-center">
                        <i class="fas fa-mobile-alt fa-3x mb-3" style="color: #764ba2;"></i>
                        <h4 class="fw-bold">Entrenamiento Digital</h4>
                        <p class="text-muted">No somos un gimnasio físico. Accede a rutinas personalizadas y videos explicativos desde cualquier dispositivo, adaptados a tu nivel y material disponible.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 p-3">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x mb-3" style="color: #764ba2;"></i>
                        <h4 class="fw-bold">Seguimiento Real</h4>
                        <p class="text-muted">Registra tus marcas, controla tu peso y visualiza tu evolución con gráficas interactivas. Todo lo que necesitas para mantener la motivación al máximo.</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-center mb-5"><i class="fas fa-star me-2"></i>Nuestros Planes</h2>
<div class="row g-4 mb-5 justify-content-center">
   
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 p-4 custom-price-card">
            <div class="card-body d-flex flex-column">
                <div class="price-header text-center">
                    <h5 class="text-uppercase fw-bold text-muted">Mensual</h5>
                </div>
               
                <div class="price-body text-center d-flex flex-column justify-content-center">
                    <h2 class="display-3 fw-bold">9.99€</h2>
                    <p class="text-muted small">Acceso total por 30 días a todos los recursos.</p>
                </div>
               
                <div class="price-footer mt-auto">
                    <div class="badge-placeholder mb-2"></div>
                    <a href="login.php" class="btn btn-dark w-100">Suscribirme</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-strong border-0 p-4 custom-price-card featured-card">
            <div class="card-body d-flex flex-column">
                <div class="price-header text-center">
                    <h5 class="text-uppercase fw-bold text-muted">Trimestral</h5>
                </div>
               
                <div class="price-body text-center d-flex flex-column justify-content-center">
                    <h2 class="display-3 fw-bold">24.99€</h2>
                    <p class="text-muted small">Ahorra un 15% con el pago cada tres meses.</p>
                </div>
               
                <div class="price-footer mt-auto text-center">
                    <div class="badge-popular-yellow mb-2">POPULAR</div>
                    <a href="login.php" class="btn btn-dark w-100">Suscribirme</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 p-4 custom-price-card">
            <div class="card-body d-flex flex-column">
                <div class="price-header text-center">
                    <h5 class="text-uppercase fw-bold text-muted">Anual</h5>
                </div>
               
                <div class="price-body text-center d-flex flex-column justify-content-center">
                    <h2 class="display-3 fw-bold">89.99€</h2>
                    <p class="text-muted small">La mejor oferta para usuarios comprometidos.</p>
                </div>
               
                <div class="price-footer mt-auto">
                    <div class="badge-placeholder mb-2"></div>
                    <a href="login.php" class="btn btn-dark w-100">Suscribirme</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    </div>

    <footer class="footer-custom mt-5">
        <div class="container py-4 text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold">FITNESSGYM</h5>
                    <p class="mb-0 small">Tu revolución fitness digital.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>contacto@fitnessgym.com</p>
                    <p class="mb-0"><i class="fas fa-phone me-2"></i>+34 600 000 000</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>