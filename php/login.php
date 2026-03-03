<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: menu_" . $_SESSION['rol'] . ".php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <div class="container">
    <h2>Acceso a FitnessGym</h2>
    <form action="./utilidades/validarlogin.php" method="post">
        <input type="email" name="correo" placeholder="Correo" required><br>
        <input type="password" name="clave" placeholder="Contraseña" required><br>
        <button class="btn btn-secondary" type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="registro.php" class="btn btn-secondary">Regístrate aquí</a></p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuración
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            // Detectar si el usuario viene de borrado_logico.php
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('status') === 'success_baja') {
                toastr.info('Tu cuenta ha sido desactivada correctamente. ¡Esperamos verte pronto!', 'Cuenta cerrada');
                // Limpiar la URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>