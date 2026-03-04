<?php
include './utilidades/seguridad.php';
verificarRoles(['administrador', 'entrenador']);
include './utilidades/conexion.php';

$id_entrenador = $_SESSION['id_usuario'];
$mensaje_toastr = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $es_predefinida = ($_SESSION['rol'] === 'administrador') ? 1 : 0;

    $sql = "INSERT INTO dietas (nombre, descripcion, creada_por, es_predefinida) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $descripcion, $id_entrenador, $es_predefinida);
    
    if (mysqli_stmt_execute($stmt)) {
        $id_dieta = mysqli_insert_id($conexion);
        if (isset($_POST['comida'])) {
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
        }
        $mensaje_toastr = "success";
    } else {
        $mensaje_toastr = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Plantilla de Dieta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <style>
        /* Estilos específicos para clonar la imagen 2 */
        body { background-color: #f8f9fa; }
        
        .card-custom {
            background: white;
            border-radius: 20px;
            padding: 40px;
            border: none;
            margin-top: 30px;
        }

        .titulo-verde {
            color: #2d8a5d; /* Verde similar al de la imagen */
            font-weight: 500;
            margin-bottom: 30px;
        }

        .form-label-custom {
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
        }

        .input-pill {
            border-radius: 50px !important;
            border: 1px solid #dee2e6;
            padding: 10px 20px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .subtitulo-seccion {
            font-size: 1.4rem;
            font-weight: 500;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .item-alimento {
            background: white;
            border: 1px solid #f0f0f0;
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: 0.3s;
        }

        .item-alimento:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .check-custom {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            margin-right: 15px;
        }

        .inputs-ocultos {
            background: #fafafa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 15px;
            border-left: 4px solid #7d5fff;
        }

        .btn-principal {
            background: #7d5fff;
            color: white;
            border-radius: 50px;
            padding: 12px 40px;
            border: none;
            font-weight: bold;
        }
        
        .btn-principal:hover { background: #6a49e6; color: white; }
    </style>
</head>
<body>
    <?php include './utilidades/navbar.php'; ?>

    <div class="container pb-5">
        <div class="card-custom shadow-sm">
            <h2 class="titulo-verde">Nueva Plantilla de Dieta (Admin)</h2>
            
            <form method="post" id="formDieta">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Nombre de la Dieta</label>
                        <input type="text" name="nombre" class="form-control input-pill" placeholder="Ej: Dieta Definición" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Descripción (Objetivo)</label>
                        <input type="text" name="descripcion" class="form-control input-pill" placeholder="Ej: Baja en grasa" required>
                    </div>
                </div>

                <h3 class="subtitulo-seccion">Seleccionar Alimentos</h3>
                
                <div class="lista-alimentos">
                    <?php
                    $res = mysqli_query($conexion, "SELECT * FROM comidas ORDER BY nombre ASC");
                    while ($c = mysqli_fetch_assoc($res)) {
                        $id = $c['id_comida'];
                        echo "
                        <div class='item-alimento'>
                            <div class='d-flex align-items-center'>
                                <input type='checkbox' name='comida[$id]' value='1' class='form-check-input check-custom check-comida' data-id='{$id}'>
                                <span class='fw-bold text-dark'>{$c['nombre']}</span>
                            </div>
                            
                            <div class='inputs-ocultos' id='inputs-{$id}' style='display:none;'>
                                <div class='row g-3'>
                                    <div class='col-md-4'>
                                        <label class='small fw-bold'>Día de la semana</label>
                                        <select name='dia[$id]' class='form-select input-pill form-select-sm'>
                                            <option value='lunes'>Lunes</option><option value='martes'>Martes</option>
                                            <option value='miércoles'>Miércoles</option><option value='jueves'>Jueves</option>
                                            <option value='viernes'>Viernes</option><option value='sábado'>Sábado</option>
                                            <option value='domingo'>Domingo</option>
                                        </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label class='small fw-bold'>Momento</label>
                                        <select name='momento[$id]' class='form-select input-pill form-select-sm'>
                                            <option value='desayuno'>Desayuno</option><option value='almuerzo'>Almuerzo</option>
                                            <option value='merienda'>Merienda</option><option value='cena'>Cena</option>
                                        </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label class='small fw-bold'>Orden</label>
                                        <input type='number' name='orden[$id]' class='form-control input-pill form-control-sm' value='1' min='1'>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-dark shadow">Crear Dieta</button>
                    <a href="menu_entrenador.php" class="btn btn-dark shadow">Volver al menú</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    toastr.options = { "progressBar": true, "positionClass": "toast-top-right" };

    <?php if($mensaje_toastr === 'success'): ?>
        toastr.success("Dieta guardada con éxito");
        setTimeout(() => { window.location.href = 'gestionar_dietas.php'; }, 2000);
    <?php endif; ?>

    document.querySelectorAll('.check-comida').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const id = this.dataset.id;
            const div = document.getElementById('inputs-' + id);
            if (this.checked) {
                $(div).slideDown(300);
            } else {
                $(div).slideUp(300);
            }
        });
    });
    </script>
</body>
</html>