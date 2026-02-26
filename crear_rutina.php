<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';

$id_entrenador = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $objetivo = $_POST['objetivo'];
    $es_predefinida = isset($_POST['es_predefinida']) ? 1 : 0;

    $sql = "INSERT INTO rutinas (nombre, objetivo, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $objetivo, $id_entrenador, $es_predefinida);
    mysqli_stmt_execute($stmt);

    $id_rutina = mysqli_insert_id($conexion);
    $orden = 1;

    foreach ($_POST['ejercicio'] as $id_ejercicio => $valor) {
        $series = $_POST['series'][$id_ejercicio];
        $reps = $_POST['repeticiones'][$id_ejercicio];
        $descanso = $_POST['descanso'][$id_ejercicio];

        $sql2 = "INSERT INTO rutina_ejercicio (id_rutina, id_ejercicio, orden, series, repeticiones, tiempo_descanso) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conexion, $sql2);
        mysqli_stmt_bind_param($stmt2, "iiiiii", $id_rutina, $id_ejercicio, $orden, $series, $reps, $descanso);
        mysqli_stmt_execute($stmt2);
        $orden++;
    }

    echo "<p>Rutina creada correctamente.</p><a href='menu_entrenador.php'>Volver al menú</a>";
    exit;
}
?>

<h2>Crear Rutina</h2>
<form method="post">
    <input type="text" name="nombre" placeholder="Nombre de la rutina" required><br>
    <input type="text" name="objetivo" placeholder="Objetivo" required><br>
    <label><input type="checkbox" name="es_predefinida"> ¿Es predefinida?</label><br><br>

    <?php
    $res = mysqli_query($conexion, "SELECT * FROM ejercicios");
    while ($ej = mysqli_fetch_assoc($res)) {
        $id = $ej['id_ejercicio'];
        echo "<div class='bloque-ejercicio'>
                <label>
                  <input type='checkbox' name='ejercicio[$id]' value='1' class='check-ejercicio' data-id='{$id}'>
                  {$ej['nombre']}
                </label><br>
                <div class='inputs-ejercicio' id='inputs-{$id}' style='display:none; margin-left:20px;'>
                  Series: <input type='number' name='series[$id]' min='1'>
                  Repeticiones: <input type='number' name='repeticiones[$id]' min='1'>
                  Descanso (s): <input type='number' name='descanso[$id]' min='0'><br><br>
                </div>
              </div>";
    }
    ?>
    <button type="submit">Crear Rutina</button>
</form>
<a href="menu_entrenador.php">Volver al menú</a>

<script>
document.querySelectorAll('.check-ejercicio').forEach(checkbox => {
  checkbox.addEventListener('change', function () {
    const id = this.dataset.id;
    const inputs = document.getElementById('inputs-' + id);
    const requiredInputs = inputs.querySelectorAll('input');

    if (this.checked) {
      inputs.style.display = 'block';
      requiredInputs.forEach(input => input.required = true);
    } else {
      inputs.style.display = 'none';
      requiredInputs.forEach(input => {
        input.required = false;
        input.value = '';
      });
    }
  });
});
</script>
