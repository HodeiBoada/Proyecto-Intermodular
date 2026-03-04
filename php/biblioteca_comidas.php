<?php
include './utilidades/seguridad.php';
verificarRoles(['entrenador', 'administrador']);
include './utilidades/conexion.php';

$rol_sesion = $_SESSION['rol'];
// Seleccionamos id_comida para poder borrar/editar
$resultado = mysqli_query($conexion, "SELECT id_comida, nombre, tipo, calorias, descripcion FROM comidas ORDER BY tipo, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca de Comidas | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<?php include './utilidades/navbar.php'; ?> 

<div class="container mt-5">
    
    <?php if ($rol_sesion === 'administrador'): ?>
    <div class="card p-4 shadow-sm border-primary mb-4" style="border-left-width: 5px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary">Optimizador de Comidas (Pandas Core)</h4>
            <div class="d-flex gap-2">
                <input type="number" id="inputCalorias" class="form-control" style="width: 150px;" placeholder="Calorías máx.">
                <button id="btnAnalizar" class="btn btn-primary shadow-sm">
                    Analizar con Python
                </button>
            </div>
        </div>
        <div id="contenedor-api" style="display: none;">
            <div id="resultado-api"></div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Biblioteca</h2>
            <?php if ($rol_sesion === 'administrador'): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalComida" onclick="limpiarFormulario()">
                    <i class="fas fa-plus"></i> Nueva Comida
                </button>
            <?php endif; ?>
        </div>

        <table id="tablaComidas" class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Calorías</th>
                    <th>Descripción</th>
                    <?php if ($rol_sesion === 'administrador'): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr id="fila-<?= $fila['id_comida'] ?>">
                        <td class="fw-bold"><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= ucfirst($fila['tipo']) ?></span></td>
                        <td><?= $fila['calorias'] ?> kcal</td>
                        <td><small><?= htmlspecialchars($fila['descripcion']) ?></small></td>
                        <?php if ($rol_sesion === 'administrador'): ?>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="cargarEdicion(<?= htmlspecialchars(json_encode($fila)) ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarComida(<?= $fila['id_comida'] ?>)">
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

<div class="modal fade" id="modalComida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitulo">Añadir Comida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" id="nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Calorías</label>
                    <input type="number" id="calorias" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select id="tipo" class="form-select">
                        <option value="desayuno">Desayuno</option>
                        <option value="almuerzo">Almuerzo</option>
                        <option value="cena">Cena</option>
                        <option value="snack">Snack</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarComida()">Guardar Cambios</button>
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
<script src="../js/comidas.js"></script>
</body>
</html>