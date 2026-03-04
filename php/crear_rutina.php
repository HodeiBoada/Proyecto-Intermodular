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
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $objetivo = mysqli_real_escape_string($conexion, $_POST['objetivo']);
    
    // Lógica automática: Admin -> predefinida (1), Entrenador -> no (0)
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

    // Redirección dinámica según el rol
    $destino = ($rol_autor === 'administrador') ? 'gestion_rutinas_pre.php' : 'menu_entrenador.php';
    header("Location: $destino?msg=ok");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Rutina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body class="bg-light">
    <?php include './utilidades/navbar.php'; ?>
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4 border-0" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary"><i class="fa-solid fa-dumbbell me-2"></i>Nueva Rutina</h2>
                <span class="badge bg-dark px-3 py-2">Modo: <?php echo strtoupper($rol_autor); ?></span>
            </div>

            <form method="POST">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombre de la Rutina</label>
                        <input type="text" name="nombre" class="form-control form-control-lg" placeholder="Ej: Empuje y Tracción" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Objetivo Principal</label>
                        <input type="text" name="objetivo" class="form-control form-control-lg" placeholder="Ej: Fuerza Máxima" required>
                    </div>
                </div>

                <hr class="my-4">
                <h4 class="mb-4 text-secondary">Seleccionar Ejercicios y Configuración</h4>
                
                <div class="row">
                    <?php
                    $res = mysqli_query($conexion, "SELECT * FROM ejercicios ORDER BY nombre ASC");
                    while ($ej = mysqli_fetch_assoc($res)) {
                        $id = $ej['id_ejercicio'];
                        ?>
                        <div class="col-12 mb-3">
                            <div class="card p-3 border-light shadow-sm item-ejercicio">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" name="ejercicio[<?php echo $id; ?>]" value="1" 
                                           class="form-check-input check-ejercicio me-3" 
                                           data-id="<?php echo $id; ?>" style="width: 20px; height: 20px;">
                                    <label class="form-check-label fw-bold fs-5"><?php echo $ej['nombre']; ?></label>
                                </div>

                                <div class="row mt-3 g-2 animate__animated animate__fadeIn" id="inputs-<?php echo $id; ?>" style="display:none;">
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Día:</label>
                                        <select name="dia[<?php echo $id; ?>]" class="form-select">
                                            <option value="1">Lunes</option>
                                            <option value="2">Martes</option>
                                            <option value="3">Miércoles</option>
                                            <option value="4">Jueves</option>
                                            <option value="5">Viernes</option>
                                            <option value="6">Sábado</option>
                                            <option value="7">Domingo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Series:</label>
                                        <input type="number" name="series[<?php echo $id; ?>]" class="form-control" placeholder="0" min="1">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Reps:</label>
                                        <input type="number" name="repeticiones[<?php echo $id; ?>]" class="form-control" placeholder="0" min="1">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Descanso (s):</label>
                                        <input type="number" name="descanso[<?php echo $id; ?>]" class="form-control" placeholder="Segundos" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="mt-5 pt-3 border-top d-flex gap-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        Guardar Rutina
                    </button>
                    <a href="menu_entrenador.php" class="btn btn-outline-secondary btn-lg px-4">
                        Volver al menú
                    </a>
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
                // Añadimos una clase visual a la card padre
                this.closest('.item-ejercicio').classList.add('border-primary');
            } else {
                divInputs.style.display = 'none';
                inputs.forEach(i => { i.required = false; i.value = ''; });
                this.closest('.item-ejercicio').classList.remove('border-primary');
            }
        });
    });
    </script>
</body>
</html>