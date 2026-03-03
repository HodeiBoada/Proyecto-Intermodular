<?php
// --- 1. LÓGICA DE LIMPIEZA DE NOTIFICACIONES (AJAX) ---
if (isset($_GET['accion']) && $_GET['accion'] === 'limpiar_ayuda') {
    include 'conexion.php';
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    if (isset($_SESSION['id_usuario'])) {
        $id_entrenador = $_SESSION['id_usuario'];
        // Marcamos como LEÍDA la notificación de ayuda pendiente para este entrenador
        // Esto evita que el bucle JS vuelva a lanzarla cada 10 segundos
        $sql = "UPDATE notificaciones_sala 
                SET estado = 'leida' 
                WHERE id_entrenador = ? AND tipo = 'ayuda' AND estado = 'pendiente'";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_entrenador);
        mysqli_stmt_execute($stmt);
    }
    exit; // Detiene la carga del HTML cuando es una petición de limpieza
}

// Determinamos la página de inicio según el rol
$pagina_inicio = "menu_" . ($_SESSION['rol'] ?? 'usuario') . ".php";
?>

<style>
    .fitness-nav {
        background: rgba(255, 255, 255, 0.95);
        padding: 15px 30px;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: sticky;
        top: 0;
        z-index: 1000;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }
    .nav-logo { font-size: 1.5rem; font-weight: bold; color: #764ba2; text-decoration: none; display: flex; align-items: center; gap: 10px; }
    .nav-links { display: flex; gap: 20px; align-items: center; }
    .nav-links a { text-decoration: none; color: #666; font-weight: 500; transition: color 0.3s; }
    .nav-links a:hover { color: #667eea; }
    .nav-user { display: flex; align-items: center; gap: 15px; }
    .user-chip { background: #f1f2f6; padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; }
</style>

<nav class="fitness-nav">
    <a href="<?php echo $pagina_inicio; ?>" class="nav-logo">
        <div class="online-dot"></div> FITNESSGYM
    </a>

    <div class="nav-links">        
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'entrenador'): ?>
                    <a href="<?php echo $pagina_inicio; ?>">Inicio</a>
            <a href="clientes.php">Mis Clientes</a>
            <a href="biblioteca_ejercicios.php">Ejercicios</a>
            <a href="biblioteca_comidas.php">Comidas</a>
            <a href="gestion_rutinas_pre.php">Rutinas</a>
            <a href="gestion_dietas_pre.php">Dietas</a>
        
        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                    <a href="<?php echo $pagina_inicio; ?>">Inicio</a>
            <a href="baja_entrenador.php">Staff</a>
            <a href="asignar_clientes.php">Asignar Clientes</a>
            <a href="biblioteca_ejercicios.php">Ejercicios</a>
            <a href="biblioteca_comidas.php">Comidas</a>
            <a href="gestion_rutinas_pre.php">Rutinas</a>
            <a href="gestion_dietas_pre.php">Dietas</a>

        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario'): ?>
                <a href="<?php echo $pagina_inicio; ?>">Inicio</a>
            <a href="ver_rutina_usuario.php">Mi Rutina</a>
            <a href="ver_dieta_usuario.php">Mi Dieta</a>
            <a href="index.php">Sala virtual</a>
            <a href="perfil.php">Mi Perfil</a>
        <?php else: ?>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'entrenador' || isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador' || isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario'): ?>
    <div class="nav-user">
        <div class="user-chip">
            <strong><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?></strong>
        </div>
        <a href="logout.php" class="btn btn-dark btn-sm px-4" style="border-radius: 20px;">Salir</a>
    </div>
    <?php else: ?>
    <div class="nav-user">
        <a href="login.php" class="btn btn-dark btn-sm px-4" style="border-radius: 20px;">Iniciar sesión</a>
    </div>
    <?php endif; ?>
</nav>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'entrenador'): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    $(document).ready(function() {
        let ultimaAyudaDetectada = ""; // ID de la última notificación para evitar duplicados visuales
        let tituloOriginal = document.title;

        // Función para limpiar la notificación en la pestaña y en la Base de Datos
        function limpiarNotificacion() {
            document.title = tituloOriginal; 
            ultimaAyudaDetectada = ""; 
            // Llamada AJAX para marcar como leída en la BD y que no vuelva a saltar
            $.get('navbar.php?accion=limpiar_ayuda');
        }

        // Configuración de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "0", // No se quita sola para asegurar que el entrenador la vea
            "extendedTimeOut": "0",
            "onHidden": function() { limpiarNotificacion(); },
            "onCloseClick": function() { limpiarNotificacion(); }
        };

        function revisarAyudaGlobal() {
            // Consultamos el archivo JSON que busca registros 'pendientes'
            $.getJSON('notificaciones_globales.php', function(data) {
                if (data.hay_ayuda) {
                    // Solo mostramos si el mensaje es distinto (o el ID de ayuda es nuevo)
                    if (data.mensaje !== ultimaAyudaDetectada) {
                        ultimaAyudaDetectada = data.mensaje;
                        
                        toastr.error(data.mensaje, "¡SOLICITUD DE AYUDA!", {
                            onclick: function() {
                                // Al hacer clic, limpiamos y redirigimos a la sala
                                limpiarNotificacion();
                                window.location.href = data.url_sala;
                            }
                        });
                        
                        document.title = "⚠️ AYUDA PENDIENTE";
                    }
                } else {
                    // Si ya no hay ayudas pendientes, restauramos el título de la pestaña
                    if (document.title.includes("AYUDA PENDIENTE")) {
                        document.title = tituloOriginal;
                    }
                }
            });
        }
        // Revisión cada 10 segundos
        setInterval(revisarAyudaGlobal, 10000);
    });
    </script>
<?php endif; ?>