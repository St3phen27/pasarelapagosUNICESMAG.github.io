<?php

define("KEY_TOKEN","TESTING.11");
define("CURRENCYREGION","$");
define("CURRENCY","COP");

session_start();

$num_cart = 0;
if(isset($_SESSION['cart']['products'])){
    $num_cart = count($_SESSION['cart']['products']);
}

?>