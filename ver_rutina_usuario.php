<?php
include 'seguridad.php';
verificarRol('usuario');
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT r.nombre AS nombre_rutina, r.objetivo, e.nombre AS nombre_ejercicio, e.descripcion, re.series, re.repeticiones, re.tiempo_descanso
        FROM usuarios u
        JOIN rutinas r ON u.id_rutina_activa = r.id_rutina
        JOIN rutina_ejercicio re ON r.id_rutina = re.id_rutina
        JOIN ejercicios e ON re.id_ejercicio = e.id_ejercicio
        WHERE u.id_usuario = ?
        ORDER BY re.orden ASC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    $primera = true;
    while ($fila = mysqli_fetch_assoc($resultado)) {
        if ($primera) {
            echo "<h2>Rutina Activa: " . htmlspecialchars($fila['nombre_rutina']) . "</h2>";
            echo "<p><strong>Objetivo:</strong> " . htmlspecialchars($fila['objetivo']) . "</p>";
            echo "<table border='1'>
                    <tr><th>Ejercicio</th><th>Descripción</th><th>Series</th><th>Repeticiones</th><th>Descanso (s)</th></tr>";
            $primera = false;
        }
        echo "<tr>
                <td>" . htmlspecialchars($fila['nombre_ejercicio']) . "</td>
                <td>" . htmlspecialchars($fila['descripcion']) . "</td>
                <td>" . $fila['series'] . "</td>
                <td>" . $fila['repeticiones'] . "</td>
                <td>" . $fila['tiempo_descanso'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No tienes una rutina activa asignada.</p>";
}
?>
<a href="menu_usuario.php">Volver al menú</a>
