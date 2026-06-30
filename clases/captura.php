<?php

require '../config/config.php'; 
require '../config/database.php';

header('Content-Type: application/json');

$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if(is_array($datos)){


    $id_transaccion = $datos['detalles']['id'];

    $sql_check = $con->prepare("SELECT COUNT(*) FROM compra WHERE id_transaccion=?");
    $sql_check->execute([$id_transaccion]);
    if($sql_check->fetchColumn() > 0){
        echo json_encode(['ok' => true]);
        exit;
    }

    $monto = $datos['detalles']['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id'];

    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES (?,?,?,?,?,?)");
    $res = $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $monto]);
    $id = $con->lastInsertId();

    if($res){
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
        
        if($productos != null){
            foreach($productos as $clave => $cantidad){
                $sql_prod = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id=? AND activo=1");
                $sql_prod->execute([$clave]);
                $row_prod = $sql_prod->fetch(PDO::FETCH_ASSOC);

                $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['nombre'], $row_prod['precio'], $cantidad]);
            }
            include 'enviar_email.php';
        }

        unset($_SESSION['carrito']);
        echo json_encode(['ok' => true, 'orden' => $id]);
    } else {
        echo json_encode(['ok' => false]);
    }
} else {
    echo json_encode(['ok' => false]);
}