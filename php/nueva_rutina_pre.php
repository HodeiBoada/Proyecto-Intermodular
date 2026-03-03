<?php
include 'seguridad.php';
verificarRol('administrador');
include 'conexion.php';

$id_autor = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $objetivo = mysqli_real_escape_string($conexion, $_POST['objetivo']);
    $es_predefinida = 1; // Siempre 1 porque este archivo es del Admin

    // 1. Insertar Cabecera
    $sql = "INSERT INTO rutinas (nombre, objetivo, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $objetivo, $id_autor, $es_predefinida);
    mysqli_stmt_execute($stmt);
    
    $id_rutina = mysqli_insert_id($conexion);
    $orden = 1;

    // 2. Insertar Ejercicios seleccionados
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
    header("Location: gestion_rutinas_pre.php?msg=ok");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Plantilla de Rutina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-primary mb-4">Nueva Plantilla de Rutina (Admin)</h2>
            <form method="POST">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre de la Rutina</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Full Body Pro" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Objetivo</label>
                        <input type="text" name="objetivo" class="form-control" placeholder="Ej: Hipertrofia" required>
                    </div>
                </div>

                <h4 class="mb-3">Seleccionar Ejercicios</h4>
                <div class="row">
                    <?php
                    $res = mysqli_query($conexion, "SELECT * FROM ejercicios ORDER BY nombre ASC");
                    while ($ej = mysqli_fetch_assoc($res)) {
                        $id = $ej['id_ejercicio'];
                        echo "
                        <div class='col-12 mb-2'>
                            <div class='card p-3 border-light shadow-sm'>
                                <div class='form-check'>
                                    <input type='checkbox' name='ejercicio[$id]' value='1' class='form-check-input check-ejercicio' data-id='$id'>
                                    <label class='form-check-label fw-bold'>{$ej['nombre']}</label>
                                </div>
                                <div class='row mt-2' id='inputs-$id' style='display:none;'>
                                    <div class='col-md-3'>
                                        <small>Día:</small>
                                        <select name='dia[$id]' class='form-select form-select-sm'>
                                            <option value='1'>Lunes</option><option value='2'>Martes</option>
                                            <option value='3'>Miércoles</option><option value='4'>Jueves</option>
                                            <option value='5'>Viernes</option><option value='6'>Sábado</option>
                                            <option value='7'>Domingo</option>
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Series:</small>
                                        <input type='number' name='series[$id]' class='form-control form-control-sm' min='1'>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Reps:</small>
                                        <input type='number' name='repeticiones[$id]' class='form-control form-control-sm' min='1'>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Descanso (s):</small>
                                        <input type='number' name='descanso[$id]' class='form-control form-control-sm' min='0'>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-danger btn-lg">Guardar Plantilla Maestra</button>
                    <a href="gestion_rutinas_pre.php" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.querySelectorAll('.check-ejercicio').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const id = this.dataset.id;
            const divInputs = document.getElementById('inputs-' + id);
            const inputs = divInputs.querySelectorAll('input, select');
            if (this.checked) {
                divInputs.style.display = 'flex';
                inputs.forEach(i => i.required = true);
            } else {
                divInputs.style.display = 'none';
                inputs.forEach(i => { i.required = false; i.value = ''; });
            }
        });
    });
    </script>
</body>
</html>