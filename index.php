<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

if ($rol === 'usuario') {
    $id_entrenador = $_SESSION['id_entrenador'];
    $id_cliente = $id_usuario;
} else {
    $id_entrenador = $id_usuario;
    $id_cliente = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;

    // Validar que ese usuario pertenece al entrenador
    $sql = "SELECT COUNT(*) FROM usuarios WHERE id_usuario = ? AND id_entrenador = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_cliente, $id_entrenador);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $existe);
    mysqli_stmt_fetch($stmt);
    if (!$existe) {
        die("Este usuario no está asignado a tu cuenta.");
    }
}

if (!$id_entrenador || !$id_cliente) {
    die("Faltan datos para generar la sala.");
}

$nombre_sala = "sala-$id_entrenador-$id_cliente";

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>FitnessGym - Sesión Privada</title>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Jitsi Meet API -->
  <script src="https://meet.jit.si/external_api.js"></script>

  <!-- Dropzone -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

  <!-- Estilos propios -->
  <link rel="stylesheet" href="estilos.css" />

  <!-- Variables PHP para JS -->
  <script>
    const idUsuario = <?= $id_usuario ?>;
    const idEntrenador = <?= $id_entrenador ?>;
    let nombreSala = "<?= $nombre_sala ?>";
  </script>

  <!-- Tu JS -->
  <script src="prueba.js" defer></script>
</head>
<body>
  <div class="container">
    <h1>FITNESSGYM</h1>
    <div class="subtitle">Sesión privada con tu entrenador personal</div>

    <div class="alert alert-success">
      Esta videollamada está cifrada de extremo a extremo. Solo tú y tu entrenador tenéis acceso.
    </div>

    <div class="card">
      <h2>Iniciar sesión de entrenamiento</h2>
      <div class="room-controls">
        <input type="text" id="room-name" class="room-input" placeholder="Nombre de la sala privada" value="<?= $nombre_sala ?>" />
        <button id="boton-unirse" class="btn">Unirse a la videollamada</button>
        <button id="boton-ayuda" class="btn btn-secondary">Solicitar ayuda</button>
      </div>
      <div id="room-status">
        <span class="active-room">Sala activa: <span id="current-room"><?= $nombre_sala ?></span></span>
      </div>
      <div id="jitsi-container"></div>

      <div style="margin-top: 10px;">
        <button id="boton-colgar" class="btn btn-danger" style="display: none;">Colgar</button>
      </div>

      <p class="note">Tu entrenador puede compartir pantalla, darte feedback en tiempo real y ayudarte con tu rutina o dieta.</p>
    </div>

    <div class="card">
      <h2>Compartir archivo (PDF)</h2>
      <form action="subir_archivo.php" id="zona-subida"></form>
      <p class="note">Puedes subir tu rutina o dieta en PDF para compartirla durante la sesión.</p>
      <ul id="lista-archivos" class="historial-list"></ul>
    </div>

    <div class="card">
      <h2>Historial de sesiones</h2>
      <ul id="historial-sesiones" class="historial-list"></ul>
    </div>
  </div>
</body>
</html>
