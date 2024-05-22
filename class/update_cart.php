<?php

require '../config/config.php';
require '../config/databasePDO.php';

if(isset($_POST['action'])){
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if($action == 'agregar'){
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
        $response = agregar($id, $quantity);
        if($response>0){
            $datos['ok'] = true;
        }
        else{
            $datos['ok'] = false;
        }
        $datos['sub'] = CURRENCYREGION. number_format($response, 0, '.', ',').' '.CURRENCY;
    }
    else if($action == 'eliminar'){
        $datos['ok'] = eliminar($id);
    }
    else{
        $datos['ok'] = false;
    }
}
else{
    $datos['ok'] = false;
}

echo json_encode($datos);

function agregar($id, $quantity){
    $res = 0;
    if($id > 0 && $quantity > 0 && is_numeric(($quantity))){
        if(isset($_SESSION['cart']['products'][$id])){
            $_SESSION['cart']['products'][$id] = $quantity;
            $db = new Database();
            $conn = $db->conectar();
            $sql = $conn->prepare("SELECT price FROM product WHERE id_product=?");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $price = $row['price'];
            $res = $quantity * $price;
            return $res;
        }
    }
    else{
        return $res;
    }
}

function eliminar($id){
    if($id > 0){
        if(isset($_SESSION['cart']['products'][$id])){
            unset($_SESSION['cart']['products'][$id]);
            return true;
        }
    }
    else{
        return false;
    }
} 

?>