<?php

define("KEY_TOKEN","TESTING.11");
define("CLIENT_ID","AS53jKQEm9zvX8Mcp698FS-gFIsZ4NDBnss1kF0QyKq57vJ2FTkrg5977MWCZiJrHrBM9frl46N13Es5");
define("CURRENCYREGION","$");
define("CURRENCY","COP");

session_start();

$num_cart = 0;
if(isset($_SESSION['cart']['products'])){
    $num_cart = count($_SESSION['cart']['products']);
}

?>