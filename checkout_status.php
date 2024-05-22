<?php
require 'config/config.php';
require 'config/databasePDO.php';
$db = new Database();
$conn = $db->conectar();
$products = isset($_SESSION['cart']['products']) ? $_SESSION['cart']['products'] : null;
$cart_list = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>

    <!--NAVBAR-->

    <header>
    <div class="navbar navbar-exapand-lg navbar-dark bg-dark">
        <div class="container">
        <a href="." class="navbar-brand">
            <strong>Tienda CESMAG</strong>
        </a>
        <a href="checkout.php" class="btn btn-primary">
                Carrito <span id="num_cart" class="badge bg-secondary">
                    <?php echo $num_cart; ?>
                </span>
            </a> 
        </div> 
        </div>
    </div>
    </header>

        
    <!-- Contenido Principal -->
    <div class="container">
        <!-- Aquí va tu contenido principal -->
    </div>

    <!-- Mensaje de Agradecimiento -->
    <div class="container text-center mt-5">
        <h2>Gracias por tu compra!</h2>
    </div>


        <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>
    
    <script src="js/checkout.js"></script>

</body>
</html>