<?php
include './utilidades/seguridad.php';
verificarRol('administrador');
include './utilidades/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $calorias = intval($_POST['calorias']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);

    $sql = "INSERT INTO comidas (nombre, tipo, calorias, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssis", $nombre, $tipo, $calorias, $descripcion);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: biblioteca_comidas.php?msg=ok");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Comida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>
    <div class="container mt-5">
        <div class="col-md-6 mx-auto">
            <div class="card shadow p-4">
                <h2>Añadir Nueva Comida</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Alimento/Plato</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Pechuga de Pollo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Comida</label>
                        <select name="tipo" class="form-select">
                            <option value="desayuno">Desayuno</option>
                            <option value="almuerzo">Almuerzo</option>
                            <option value="cena">Cena</option>
                            <option value="snack">Snack</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Calorías (por 100g o ración)</label>
                        <div class="input-group">
                            <input type="number" name="calorias" class="form-control" required>
                            <span class="input-group-text">kcal</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción / Notas nutricionales</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Guardar Comida</button>
                        <a href="biblioteca_comidas.php" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>