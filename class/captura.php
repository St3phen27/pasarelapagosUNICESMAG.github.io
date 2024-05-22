<?php

require '../config/config.php';
require '../config/databasePDO.php';
$db = new Database();
$conn = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if(is_array($datos)){
    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id'];

    $sql = $conn->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES(
    ?,?,?,?,?,?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
    $id = $conn->lastInsertId();

    if($id > 0){
        $products = isset($_SESSION['cart']['products']) ? $_SESSION['cart']['products'] : null;
        if($products != null){
            foreach($products as $clave => $quantity){
                $sql = $conn->prepare("SELECT id_product, name, price FROM product
                WHERE id_product=?");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);
                $price = $row_prod['price'];
                $sql_insert = $conn->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre,
                precio,cantidad) VALUES (?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['name'], $price, $quantity]);
            }
        }
    }
    unset($_SESSION['cart']);
}

?>