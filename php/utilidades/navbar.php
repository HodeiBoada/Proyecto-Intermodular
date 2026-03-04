<?php
// --- 1. LÓGICA DE LIMPIEZA DE NOTIFICACIONES (AJAX) ---
if (isset($_GET['accion']) && $_GET['accion'] === 'limpiar_ayuda') {
    include 'conexion.php';
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    if (isset($_SESSION['id_usuario'])) {
        $id_entrenador = $_SESSION['id_usuario'];
        $sql = "UPDATE notificaciones_sala 
                SET estado = 'leida' 
                WHERE id_entrenador = ? AND tipo = 'ayuda' AND estado = 'pendiente'";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_entrenador);
        mysqli_stmt_execute($stmt);
    }
    exit; 
}

// --- 2. CHEQUEO DE SUSCRIPCIÓN EN TIEMPO REAL ---
// Esto asegura que si el usuario paga, el menú cambie al instante
if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] === 'usuario') {
    include 'conexion.php'; // Aseguramos que la conexión esté disponible
    $id_nav = $_SESSION['id_usuario'];
    $check_v = mysqli_query($conexion, "SELECT suscrito FROM usuarios WHERE id_usuario = $id_nav");
    if ($user_v = mysqli_fetch_assoc($check_v)) {
        // Actualizamos la sesión con el valor real de la BD
        $_SESSION['suscrito'] = $user_v['suscrito']; 
    }
}

// Determinamos la página de inicio según el rol
$pagina_inicio = "menu_" . ($_SESSION['rol'] ?? 'usuario') . ".php";
?>
<nav class="fitness-nav">
    <div class="logo">
        <img src="../img/logoProyecto.png" alt="Logo" class="logo-img">
        <a href="<?php echo $pagina_inicio; ?>" class="nav-logo">
            FITNESSGYM
        </a>
    </div>
    
    <div class="nav-links">        
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'entrenador'): ?>
            <a href="menu_entrenador.php">Inicio</a>
            <a href="clientes.php">Mis Clientes</a>
            <a href="biblioteca_ejercicios.php">Ejercicios</a>
            <a href="biblioteca_comidas.php">Comidas</a>
            <a href="gestion_rutinas_pre.php">Rutinas</a>
            <a href="gestion_dietas_pre.php">Dietas</a>
        
        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <a href="menu_administrador.php">Inicio</a>
            <a href="baja_entrenador.php">Staff</a>
            <a href="asignar_clientes.php">Asignar Clientes</a>
            <a href="biblioteca_ejercicios.php">Ejercicios</a>
            <a href="biblioteca_comidas.php">Comidas</a>
            <a href="gestion_rutinas_pre.php">Rutinas</a>
            <a href="gestion_dietas_pre.php">Dietas</a>

        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario'): ?>
            <a href="menu_usuario.php">Inicio</a>
            <a href="ver_rutina_usuario.php">Mi Rutina</a>
            <a href="ver_dieta_usuario.php">Mi Dieta</a>
            <?php if ($_SESSION['suscrito'] == 1): ?>
                <a href="index.php">Sala Virtual</a>
            <?php else: ?>
                <a href="suscripcion.php">Sala Virtual</a>
            <?php endif; ?>
        <?php else: ?>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'entrenador' || isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador' || isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario'): ?>
    <div class="nav-user">
        <div>
            <a href="perfil.php" class="btn btn-dark btn-sm px-4"><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?></a>
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
    <link rel="stylesheet" href="../../css/estilos.css">
    <script src="../../js/navbar.js"></script>

<?php endif; ?>