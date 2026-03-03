<?php
include 'conexion.php';
header('Content-Type: application/json');

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

if ($datos) {
    $id_transaccion = $datos['id_transaccion'];
    $cantidad = $datos['cantidad'];
    $dias = (int)$datos['dias'];
    $id_usuario = (int)$datos['id_usuario'];

    // 1. Obtener la fecha de fin actual
    $query_user = mysqli_query($conexion, "SELECT fecha_fin_suscripcion FROM usuarios WHERE id_usuario = $id_usuario");
    $user = mysqli_fetch_assoc($query_user);
    $fecha_actual_fin = $user['fecha_fin_suscripcion'];

    // 2. Lógica de cálculo de fecha robusta
    $hoy = new DateTime(); // Fecha de hoy
    
    if ($fecha_actual_fin) {
        $vencimiento_actual = new DateTime($fecha_actual_fin);
        
        // Si la suscripción aún no ha vencido, sumamos los días a la fecha de vencimiento
        if ($vencimiento_actual > $hoy) {
            $vencimiento_actual->modify("+$dias days");
            $nueva_fecha = $vencimiento_actual->format('Y-m-d');
        } else {
            // Si ya venció, sumamos desde hoy
            $hoy->modify("+$dias days");
            $nueva_fecha = $hoy->format('Y-m-d');
        }
    } else {
        // Si nunca ha tenido suscripción, sumamos desde hoy
        $hoy->modify("+$dias days");
        $nueva_fecha = $hoy->format('Y-m-d');
    }

    // 3. Insertar el registro en la tabla 'pagos'
    $sql_pago = "INSERT INTO pagos (id_usuario, fecha, cantidad, metodo, estado, referencia_pago) 
                 VALUES (?, NOW(), ?, 'PayPal Sandbox', 'completado', ?)";
    $stmt_pago = mysqli_prepare($conexion, $sql_pago);
    mysqli_stmt_bind_param($stmt_pago, "ids", $id_usuario, $cantidad, $id_transaccion);
    mysqli_stmt_execute($stmt_pago);

    // 4. Actualizar la tabla 'usuarios'
    $sql_update = "UPDATE usuarios SET suscrito = 1, fecha_fin_suscripcion = ?, activo = 1 WHERE id_usuario = ?";
    $stmt_update = mysqli_prepare($conexion, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "si", $nueva_fecha, $id_usuario);

    if (mysqli_stmt_execute($stmt_update)) {
        echo json_encode(['success' => true, 'nueva_fecha' => $nueva_fecha]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos no recibidos']);
}
?>