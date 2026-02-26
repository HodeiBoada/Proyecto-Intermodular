<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';

$id_entrenador = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $es_predefinida = isset($_POST['es_predefinida']) ? 1 : 0;

    $sql = "INSERT INTO dietas (nombre, descripcion, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $descripcion, $id_entrenador, $es_predefinida);
    mysqli_stmt_execute($stmt);

    $id_dieta = mysqli_insert_id($conexion);

    foreach ($_POST['comida'] as $id_comida => $valor) {
        $dia = $_POST['dia'][$id_comida];
        $momento = $_POST['momento'][$id_comida];
        $orden = $_POST['orden'][$id_comida];

        $sql2 = "INSERT INTO dieta_comida (id_dieta, id_comida, dia_semana, momento, orden_momento) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conexion, $sql2);
        mysqli_stmt_bind_param($stmt2, "iissi", $id_dieta, $id_comida, $dia, $momento, $orden);
        mysqli_stmt_execute($stmt2);
    }

    echo "<p>Dieta creada correctamente.</p><a href='menu_entrenador.php'>Volver al menú</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Dieta</title>
</head>
<body>
<h2>Crear Dieta</h2>
<form method="post">
    <input type="text" name="nombre" placeholder="Nombre de la dieta" required><br>
    <textarea name="descripcion" placeholder="Descripción" required></textarea><br>
    <label><input type="checkbox" name="es_predefinida"> ¿Es predefinida?</label><br><br>

    <h3>Agregar comidas</h3>
    <?php
    $res = mysqli_query($conexion, "SELECT * FROM comidas");
    while ($c = mysqli_fetch_assoc($res)) {
        $id = $c['id_comida'];
        echo "<div class='bloque-comida'>
                <label>
                  <input type='checkbox' name='comida[$id]' value='1' class='check-comida' data-id='{$id}'>
                  {$c['nombre']}
                </label><br>
                <div class='inputs-comida' id='inputs-{$id}' style='display:none; margin-left:20px;'>
                  Día: 
                  <select name='dia[$id]'>
                    <option value='lunes'>lunes</option>
                    <option value='martes'>martes</option>
                    <option value='miércoles'>miércoles</option>
                    <option value='jueves'>jueves</option>
                    <option value='viernes'>viernes</option>
                    <option value='sábado'>sábado</option>
                    <option value='domingo'>domingo</option>
                  </select>
                  Momento: 
                  <select name='momento[$id]'>
                    <option value='mañana'>mañana</option>
                    <option value='mediodía'>mediodía</option>
                    <option value='tarde'>tarde</option>
                    <option value='noche'>noche</option>
                  </select>
                  Orden: <input type='number' name='orden[$id]' min='1' value='1'><br><br>
                </div>
              </div>";
    }
    ?>
    <button type="submit">Crear Dieta</button>
</form>
<a href="menu_entrenador.php">← Volver al menú</a>

<script>
document.querySelectorAll('.check-comida').forEach(checkbox => {
  checkbox.addEventListener('change', function () {
    const id = this.dataset.id;
    const inputs = document.getElementById('inputs-' + id);
    const campos = inputs.querySelectorAll('select, input');

    if (this.checked) {
      inputs.style.display = 'block';
      campos.forEach(el => el.required = true);
    } else {
      inputs.style.display = 'none';
      campos.forEach(el => {
        el.required = false;
        if (el.tagName === 'INPUT') el.value = '';
      });
    }
  });
});
</script>
</body>
</html>
