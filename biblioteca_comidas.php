<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';

$resultado = mysqli_query($conexion, "SELECT * FROM comidas ORDER BY tipo, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Comidas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .volver { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>

<h2>Biblioteca de Comidas</h2>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Calorías</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= ucfirst($fila['tipo']) ?></td>
                <td><?= $fila['calorias'] ?> kcal</td>
                <td><?= htmlspecialchars($fila['descripcion']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a class="volver" href="menu_entrenador.php">← Volver al menú</a>

</body>
</html>
