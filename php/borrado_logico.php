<?php
include 'seguridad.php'; // Verifica que haya una sesión iniciada
include 'conexion.php';

// 1. Capturamos el ID del usuario que se quiere dar de baja
// Si viene por GET (desde el panel de Admin), lo usamos. 
// Si no viene nada, entendemos que el usuario se está dando de baja a sí mismo.
$id_a_borrar = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['id_usuario'];

$id_sesion = $_SESSION['id_usuario'];
$rol_sesion = $_SESSION['rol'];

// 2. Verificación de permisos: Solo se puede borrar si:
// - El usuario se borra a sí mismo.
// - El que ejecuta el script es administrador.
if ($id_a_borrar == $id_sesion || $rol_sesion === 'administrador') {

    // Iniciamos una transacción para que si algo falla, no se rompa la integridad de la BD
    mysqli_begin_transaction($conexion);

    try {
        // A. Realizamos el borrado lógico (activo = 0)
        $sql_baja = "UPDATE usuarios SET activo = 0 WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql_baja);
        mysqli_stmt_bind_param($stmt, "i", $id_a_borrar);
        mysqli_stmt_execute($stmt);

        // B. Liberar alumnos: Si el ID era un entrenador, sus usuarios quedan sin asignar (id_entrenador = 0)
        // Esto alimentará tu lógica de "Usuarios sin entrenador"
        $sql_liberar = "UPDATE usuarios SET id_entrenador = NULL WHERE id_entrenador = ?";
        $stmt2 = mysqli_prepare($conexion, $sql_liberar);
        mysqli_stmt_bind_param($stmt2, "i", $id_a_borrar);
        mysqli_stmt_execute($stmt2);

        // Si todo ha ido bien, confirmamos los cambios en la BD
        mysqli_commit($conexion);

        // 3. Redirección y Notificación
        if ($id_a_borrar == $id_sesion) {
            // Caso: El usuario/entrenador se dio de baja a sí mismo
            session_destroy();
            header("Location: login.php?status=success_baja");
            exit();
        } else {
            // Caso: El Admin dio de baja a un entrenador
            header("Location: baja_entrenador.php?status=success_baja");
            exit();
        }

    } catch (Exception $e) {
        // Si hay error, deshacemos cualquier cambio
        mysqli_rollback($conexion);
        echo "Error crítico: " . $e->getMessage();
    }

} else {
    // Si un usuario intenta poner un ID de otro en la URL sin ser Admin
    header("Location: perfil.php?error=permiso_denegado");
    exit();
}
?>