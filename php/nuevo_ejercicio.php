<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $dificultad = mysqli_real_escape_string($conexion, $_POST['dificultad']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $instrucciones = mysqli_real_escape_string($conexion, $_POST['instrucciones']);
    
    // Gestión de la imagen
    $nombre_imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $nombre_imagen = time() . "_" . $_FILES['imagen']['name'];
        $ruta_destino = "imagenes/" . $nombre_imagen;
        move_uploaded_file($_FILES['imagen']['tmp_tmp_name'], $ruta_destino);
    }

    $sql = "INSERT INTO ejercicios (nombre, categoria, dificultad, descripcion, instrucciones, imagen_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $categoria, $dificultad, $descripcion, $instrucciones, $nombre_imagen);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: biblioteca_ejercicios.php?msg=ok");
        exit;
    } else {
        $mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ejercicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow p-4">
                <h2 class="text-primary mb-4">Añadir Nuevo Ejercicio</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Ejercicio</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="Pecho">Pecho</option>
                                <option value="Espalda">Espalda</option>
                                <option value="Pierna">Pierna</option>
                                <option value="Hombro">Hombro</option>
                                <option value="Brazo">Brazo</option>
                                <option value="Core">Core / Cardio</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dificultad</label>
                            <select name="dificultad" class="form-select">
                                <option value="Principiante">Principiante</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Avanzado">Avanzado</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción Corta</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Instrucciones Detalladas</label>
                        <textarea name="instrucciones" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Imagen del Ejercicio</label>
                        <input type="file" name="imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Ejercicio</button>
                        <a href="biblioteca_ejercicios.php" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>