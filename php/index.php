<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

// Inicializamos variables para evitar errores de "undefined"
$id_entrenador = null;
$id_cliente = null;

if ($rol === 'usuario') {
    // Si es usuario, sacamos el entrenador de su sesión
    $id_entrenador = $_SESSION['id_entrenador'] ?? null;
    $id_cliente = $id_usuario;
} else {
    // Si es entrenador, el entrenador es él mismo y el cliente viene por URL
    $id_entrenador = $id_usuario;
    $id_cliente = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;

    if ($id_cliente) {
        // Validar que ese usuario pertenece al entrenador
        $sql = "SELECT COUNT(*) FROM usuarios WHERE id_usuario = ? AND id_entrenador = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id_cliente, $id_entrenador);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existe);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if (!$existe) {
            die("Este usuario no está asignado a tu cuenta.");
        }
    }
}

// Solo generamos el nombre si ambos existen
$nombre_sala = ($id_entrenador && $id_cliente) ? "sala-$id_entrenador-$id_cliente" : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>FitnessGym - Sesión Privada</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://meet.jit.si/external_api.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
  <link rel="stylesheet" href="../css/estilos.css" />
  <link rel="stylesheet" href="../css/estilo_global.css">
  <script>
    const idUsuario = <?= json_encode($id_usuario) ?>;
    const idEntrenador = <?= json_encode($id_entrenador) ?>;
    let nombreSala = "<?= $nombre_sala ?>";
  </script>
  <script src="../js/prueba.js" defer></script>

</head>
<body>
  <?php include 'navbar.php'; ?> 

  <div class="container mt-5">
    <?php if (!$id_entrenador || !$id_cliente): ?>
        <div class="card p-5 text-center" style="background: rgba(255,255,255,0.8);">
          <h3 class="text-muted">No tienes un entrenador asignado</h3>
          <p>Los administradores aún no han configurado la gestión de tu entrenador.</p>
          <div class="mt-4 text-center">
            <a href="menu_usuario.php" class="btn btn-secondary">Volver al menú</a>
          </div>
        </div>
    <?php else: ?>
        <div class="text-center mb-4">
            <h1 class="fw-bold">FITNESSGYM</h1>
            <div class="subtitle text-muted">Sesión privada con tu entrenador personal</div>
        </div>

        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px; background-color: #d4edda; color: #155724;">
          <strong><i class="fas fa-lock"></i> Seguridad:</strong> Esta videollamada está cifrada de extremo a extremo. Solo tú y tu entrenador tenéis acceso.
        </div>

        <div class="card shadow-sm mb-4" style="border-radius: 20px; border: none; padding: 25px;">
          <h2 class="mb-3">Iniciar sesión de entrenamiento</h2>
          <div class="room-controls d-flex gap-2 flex-wrap mb-3">
            <input type="hidden" id="room-name" value="<?= $nombre_sala ?>" />
            <button id="boton-unirse" class="btn btn-primary px-4">Unirse a la videollamada</button>
            <?php if ($rol === 'usuario'): ?>
                <button id="boton-ayuda" class="btn btn-primary px-4">Solicitar ayuda</button>
            <?php endif; ?>
            <a href="menu_usuario.php" class="btn btn-primary px-4">Volver al menú</a>
          </div>
          <br>
          <div id="room-status" class="mb-3">
            <span class="badge bg-light text-dark p-2">Sala: <?= $nombre_sala ?></span>
          </div>

          <div id="jitsi-container" style="background: #f8f9fa; border-radius: 15px; overflow: hidden; min-height: 400px;"></div>

          <div class="mt-3">
            <button id="boton-colgar" class="btn btn-danger w-100" style="display: none; border-radius: 10px;">Finalizar Llamada</button>
          </div>

          <p class="note mt-3 text-muted small">Tu entrenador puede compartir pantalla, darte feedback en tiempo real y ayudarte con tu rutina o dieta.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm p-4 h-100" style="border-radius: 20px; border: none;">
                  <h2 class="h5 fw-bold">Compartir archivo (PDF)</h2>
                  <form action="subir_archivo.php" id="zona-subida" class="dropzone mt-2" style="border-radius: 15px; border: 2px dashed #ccc;"></form>
                  <p class="note mt-2 small text-muted">Sube tu rutina o dieta en PDF para verla en la sesión.</p>
                  <ul id="lista-archivos" class="historial-list mt-3"></ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm p-4 h-100" style="border-radius: 20px; border: none;">
                  <h2 class="h5 fw-bold">Historial de sesiones</h2>
                  <ul id="historial-sesiones" class="historial-list mt-2"></ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
              
  </div>
</body>
</html>