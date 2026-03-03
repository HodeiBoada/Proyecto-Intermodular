<?php
include './utilidades/seguridad.php';
// Permitimos que ambos entren en este archivo
if ($_SESSION['rol'] !== 'entrenador' && $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}
include './utilidades/conexion.php';

$id_autor = $_SESSION['id_usuario'];
$rol_autor = $_SESSION['rol'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $objetivo = $_POST['objetivo'];
    
    // LÓGICA AUTOMÁTICA: 
    // Si es admin, es predefinida (1). Si es entrenador, no lo es (0).
    $es_predefinida = ($rol_autor === 'administrador') ? 1 : 0;

    $sql = "INSERT INTO rutinas (nombre, objetivo, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $objetivo, $id_autor, $es_predefinida);
    mysqli_stmt_execute($stmt);

    $id_rutina = mysqli_insert_id($conexion);
    $orden = 1;

    if (isset($_POST['ejercicio'])) {
        foreach ($_POST['ejercicio'] as $id_ejercicio => $valor) {
            $dia = $_POST['dia'][$id_ejercicio];
            $series = $_POST['series'][$id_ejercicio];
            $reps = $_POST['repeticiones'][$id_ejercicio];
            $descanso = $_POST['descanso'][$id_ejercicio];

            $sql2 = "INSERT INTO rutina_ejercicio (id_rutina, id_ejercicio, dia, orden, series, repeticiones, tiempo_descanso) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conexion, $sql2);
            mysqli_stmt_bind_param($stmt2, "iiiiiii", $id_rutina, $id_ejercicio, $dia, $orden, $series, $reps, $descanso);
            mysqli_stmt_execute($stmt2);
            $orden++;
        }
    }

    echo "<p>Rutina creada correctamente como " . ($es_predefinida ? "PREDEFINIDA" : "PERSONALIZADA") . ".</p>";
    echo "<a href='menu_" . $rol_autor . ".php' class='btn btn-secondary'>Volver al menú</a>";
    exit;
}
?>

<?php include './utilidades/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Rutina</title>
  <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
  <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body>
  <div class="container">
    <h2>Crear Nueva Rutina</h2>
    <p>Estás creando esta rutina como: <strong><?php echo strtoupper($rol_autor); ?></strong></p>
    
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre de la rutina" required><br><br>
        <input type="text" name="objetivo" placeholder="Objetivo (ej: Ganar masa)" required><br><br>

        <h3>Selecciona los ejercicios y el día:</h3>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM ejercicios");
        while ($ej = mysqli_fetch_assoc($res)) {
            $id = $ej['id_ejercicio'];
            echo "<div class='bloque-ejercicio' style='border: 1px solid #ddd; padding: 10px; margin-bottom: 5px;'>
                    <label>
                      <input type='checkbox' name='ejercicio[$id]' value='1' class='check-ejercicio' data-id='{$id}'>
                      <strong>{$ej['nombre']}</strong>
                    </label>
                    <div class='inputs-ejercicio' id='inputs-{$id}' style='display:none; margin-top:10px;'>
                      Día: 
                      <select name='dia[$id]'>
                        <option value='1'>Lunes</option><option value='2'>Martes</option>
                        <option value='3'>Miércoles</option><option value='4'>Jueves</option>
                        <option value='5'>Viernes</option><option value='6'>Sábado</option>
                        <option value='7'>Domingo</option>
                      </select>
                      Series: <input type='number' name='series[$id]' min='1' style='width:40px'>
                      Reps: <input type='number' name='repeticiones[$id]' min='0' style='width:40px'>
                      Descanso: <input type='number' name='descanso[$id]' min='0' style='width:50px'> s
                    </div>
                  </div>";
        }
        ?>
        <br><button type="submit" class="btn btn-secondary">Guardar Rutina</button>
    </form>
    <a href="menu_entrenador.php" class=" volver btn btn-secondary">Volver al menú</a>

  </div>
</body>
</html>

<script>
// Script para mostrar inputs solo si el checkbox está marcado
document.querySelectorAll('.check-ejercicio').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const id = this.dataset.id;
        const divInputs = document.getElementById('inputs-' + id);
        const inputs = divInputs.querySelectorAll('input, select');
        
        if (this.checked) {
            divInputs.style.display = 'block';
            inputs.forEach(i => i.required = true);
        } else {
            divInputs.style.display = 'none';
            inputs.forEach(i => { i.required = false; i.value = ''; });
        }
    });
});
</script>