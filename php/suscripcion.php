<?php
include './utilidades/seguridad.php';
verificarRol('usuario');
include './utilidades/conexion.php';
$id_usuario = $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planes de Suscripción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AZkfX5X9wsmTLljFwak3oIHgUmiQDt4RHZN1fwqVGyfZVSyF_6KwL1_cam-KDhmULRLKfVvHgYBG8WJD&currency=EUR"></script>
    <link rel="stylesheet" href="../css/estilo_suscripcion.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-sm p-4 p-md-5 mb-5" style="border-radius: 25px; border: none;">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 mb-3 d-block text-center">Elige tu Plan Premium</h2>
                <div class="mx-auto mb-3" ></div>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Desbloquea asesoría personalizada, videollamadas y seguimiento en tiempo real con tu entrenador personal.
                </p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card pricing-card shadow-sm h-100 border">
                        <div class="card-header-gym">
                            <h4>Mensual</h4>
                            <p class="price-tag">9.99€</p>
                            <span class="small opacity-75">30 días de acceso</span>
                        </div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Sala Virtual</li>
                            <li><i class="fas fa-check-circle"></i> Videollamadas</li>
                            <li><i class="fas fa-check-circle"></i> Chat Directo</li>
                        </ul>
                        <div class="btn-paypal-container" id="paypal-mensual"></div>
                    </div>
                </div>

                <div class="col-md-4 position-relative">
                    <span class="popular-badge shadow-sm">RECOMENDADO</span>
                    <div class="card pricing-card shadow-lg h-100 border-primary" style="border: 2px solid var(--gym-purple) !important;">
                        <div class="card-header-gym">
                            <h4>Trimestral</h4>
                            <p class="price-tag">24.99€</p>
                            <span class="small opacity-75">90 días de acceso</span>
                        </div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> <strong>Ahorras un 15%</strong></li>
                            <li><i class="fas fa-check-circle"></i> Sala Virtual</li>
                            <li><i class="fas fa-check-circle"></i> Videollamadas</li>
                        </ul>
                        <div class="btn-paypal-container" id="paypal-trimestral"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card pricing-card shadow-sm h-100 border">
                        <div class="card-header-gym">
                            <h4>Anual</h4>
                            <p class="price-tag">89.99€</p>
                            <span class="small opacity-75">365 días de acceso</span>
                        </div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> <strong>2 meses GRATIS</strong></li>
                            <li><i class="fas fa-check-circle"></i> Acceso Total VIP</li>
                            <li><i class="fas fa-check-circle"></i> Regalo Gym</li>
                        </ul>
                        <div class="btn-paypal-container" id="paypal-anual"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-6">
                    <a href="menu_usuario.php" class="btn btn-danger w-100 shadow-sm">
                        </i> Volver al Menú Principal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function initPayPalButton(containerId, cantidad, dias) {
            paypal.Buttons({
                style: { shape: 'pill', color: 'gold', layout: 'vertical', label: 'pay' },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            description: "Suscripción FitnessGym " + dias + " días",
                            amount: { value: cantidad }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(orderData) {
                        fetch('./utilidades/procesar_pago.php', {
                            method: 'POST',
                            headers: { 'content-type': 'application/json' },
                            body: JSON.stringify({
                                id_transaccion: orderData.id,
                                cantidad: cantidad,
                                dias: dias,
                                id_usuario: <?php echo $id_usuario; ?>
                            })
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert('¡Pago completado con éxito!');
                                window.location.href = "menu_usuario.php";
                            } else {
                                alert('Error al procesar la suscripción.');
                            }
                        });
                    });
                }
            }).render(containerId);
        }
        const fecha = new Date();
        initPayPalButton('#paypal-mensual', '9.99', fecha.getDate() + 30);
        initPayPalButton('#paypal-trimestral', '24.99', fecha.getDate() + 90);
        initPayPalButton('#paypal-anual', '89.99', fecha.getDate() + 365);
    </script>
</body>
</html>