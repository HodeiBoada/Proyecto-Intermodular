<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';

$id_autor = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $es_predefinida = 1;

    // 1. Insertar Cabecera de Dieta
    $sql = "INSERT INTO dietas (nombre, descripcion, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $descripcion, $id_autor, $es_predefinida);
    mysqli_stmt_execute($stmt);
    
    $id_dieta = mysqli_insert_id($conexion);

    // 2. Insertar Comidas seleccionadas
    if (isset($_POST['comida'])) {
        $orden = 1; // Contador para orden_momento
        foreach ($_POST['comida'] as $id_comida => $valor) {
            $dia = $_POST['dia_semana'][$id_comida];
            $momento = $_POST['momento'][$id_comida];

            // Ajustado a tus columnas: id_dieta, id_comida, dia_semana, momento, orden_momento
            $sql2 = "INSERT INTO dieta_comida (id_dieta, id_comida, dia_semana, momento, orden_momento) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conexion, $sql2);
            
            // iissi -> int, int, string, string, int
            mysqli_stmt_bind_param($stmt2, "iissi", $id_dieta, $id_comida, $dia, $momento, $orden);
            mysqli_stmt_execute($stmt2);
            $orden++;
        }
    }
    header("Location: gestion_dietas_pre.php?msg=ok");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Plantilla de Dieta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-success mb-4">Nueva Plantilla de Dieta (Admin)</h2>
            <form method="POST">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre de la Dieta</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Dieta Definición" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Descripción (Objetivo)</label>
                        <input type="text" name="descripcion" class="form-control" placeholder="Ej: Baja en grasa" required>
                    </div>
                </div>

                <h4 class="mb-3">Seleccionar Alimentos</h4>
                <div class="row">
                    <?php
                    $res = mysqli_query($conexion, "SELECT * FROM comidas ORDER BY nombre ASC");
                    while ($com = mysqli_fetch_assoc($res)) {
                        $id = $com['id_comida'];
                        echo "
                        <div class='col-12 mb-2'>
                            <div class='card p-3 border-light shadow-sm'>
                                <div class='form-check'>
                                    <input type='checkbox' name='comida[$id]' value='1' class='form-check-input check-comida' data-id='$id'>
                                    <label class='form-check-label fw-bold'>{$com['nombre']}</label>
                                </div>
                                <div class='row mt-2' id='inputs-$id' style='display:none;'>
                                    <div class='col-md-4'>
                                        <small>Día:</small>
                                        <select name='dia_semana[$id]' class='form-select form-select-sm'>
                                            <option value='lunes'>Lunes</option>
                                            <option value='martes'>Martes</option>
                                            <option value='miercoles'>Miércoles</option>
                                            <option value='jueves'>Jueves</option>
                                            <option value='viernes'>Viernes</option>
                                            <option value='sabado'>Sábado</option>
                                            <option value='domingo'>Domingo</option>
                                        </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <small>Momento:</small>
                                        <select name='momento[$id]' class='form-select form-select-sm'>
                                            <option value='Desayuno'>Desayuno</option>
                                            <option value='Almuerzo'>Almuerzo</option>
                                            <option value='Comida'>Comida</option>
                                            <option value='Merienda'>Merienda</option>
                                            <option value='Cena'>Cena</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-danger btn-lg">Guardar Dieta Maestra</button>
                    <a href="gestion_dietas_pre.php" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.querySelectorAll('.check-comida').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const id = this.dataset.id;
            const divInputs = document.getElementById('inputs-' + id);
            const inputs = divInputs.querySelectorAll('select');
            if (this.checked) {
                divInputs.style.display = 'flex';
                inputs.forEach(i => i.required = true);
            } else {
                divInputs.style.display = 'none';
                inputs.forEach(i => i.required = false);
            }
        });
    });
    </script>
</body>
</html>