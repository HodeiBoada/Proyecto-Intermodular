<?php
include './utilidades/seguridad.php';
verificarRoles(['entrenador', 'administrador']);
include './utilidades/conexion.php';

$rol_sesion = $_SESSION['rol'];
// Seleccionamos id_ejercicio para poder gestionar el CRUD
$resultado = mysqli_query($conexion, "SELECT id_ejercicio, nombre, categoria, dificultad, descripcion, instrucciones FROM ejercicios ORDER BY categoria, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Ejercicios | Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<?php include './utilidades/navbar.php'; ?> 

<div class="container mt-5">
    
    <?php if ($rol_sesion === 'administrador'): ?>
    <div class="card p-4 shadow-sm border-success mb-4" style="border-left-width: 5px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-success">Optimizador de Ejercicios (Pandas Core)</h4>
            <div class="d-flex gap-2">
                <select id="selectCategoria" class="form-select" style="width: 200px;">
                    <option value="todos">Todas las categorías</option>
                    <option value="pecho">Pecho</option>
                    <option value="pierna">Pierna</option>
                    <option value="espalda">Espalda</option>
                    <option value="brazo">Brazo</option>
                    <option value="core">Core</option>
                    <option value="hombro">Hombro</option>
                </select>
                <button id="btnAnalizarEjercicios" class="btn btn-success shadow-sm">
                    Analizar
                </button>
            </div>
        </div>
        <div id="contenedor-api-ejercicios" style="display: none;">
            <p class="text-muted small italic">Análisis de intensidad y descansos calculado en Python.</p>
            <div id="resultado-api-ejercicios"></div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Biblioteca de Ejercicios</h2>
            <?php if ($rol_sesion === 'administrador'): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEjercicio" onclick="limpiarFormularioEjercicio()">
                    <i class="fas fa-plus"></i> Añadir Ejercicio
                </button>
            <?php endif; ?>
        </div>

        <table id="tablaEjercicios" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Dificultad</th>
                    <th>Descripción</th>
                    <?php if ($rol_sesion === 'administrador'): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><span class="badge bg-secondary"><?= ucfirst($fila['categoria']) ?></span></td>
                        <td>
                            <span class="badge <?= $fila['dificultad'] == 'Principiante' ? 'bg-success' : ($fila['dificultad'] == 'Intermedio' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= $fila['dificultad'] ?>
                            </span>
                        </td>
                        <td><small><?= htmlspecialchars($fila['descripcion']) ?></small></td>
                        <?php if ($rol_sesion === 'administrador'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick='cargarEdicionEjercicio(<?= json_encode($fila) ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarEjercicio(<?= $fila['id_ejercicio'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-4">
                <?php 
                    $url_volver = ($_SESSION['rol'] === 'administrador') ? 'menu_administrador.php' : 'menu_entrenador.php';
                ?>
                <a href="<?= $url_volver ?>" class="btn btn-secondary shadow-sm">
                    Volver al Menú
                </a>
            </div>
    </div>
</div>

<div class="modal fade" id="modalEjercicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTituloE">Nuevo Ejercicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id_e">
                <div class="mb-3">
                    <label class="form-label">Nombre del Ejercicio</label>
                    <input type="text" id="nombre_e" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <select id="categoria_e" class="form-select">
                        <option value="pecho">Pecho</option>
                        <option value="pierna">Pierna</option>
                        <option value="espalda">Espalda</option>
                        <option value="brazo">Brazo</option>
                        <option value="core">Core</option>
                        <option value="hombro">Hombro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dificultad</label>
                    <select id="dificultad_e" class="form-select">
                        <option value="Principiante">Principiante</option>
                        <option value="Intermedio">Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="guardarEjercicio()">Guardar Ejercicio</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/ejercicios.js"></script>
</body>
</html>