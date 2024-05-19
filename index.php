<?php
require 'config/config.php';
require 'config/databasePDO.php';
$db = new Database();
$conn = $db->conectar();
$sql = $conn->prepare("SELECT id_product, name, price FROM product");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>

    <!--NAVBAR-->

    <header>
    <div class="navbar navbar-exapand-lg navbar-dark bg-dark">
        <div class="container">
        <a href="#" class="navbar-brand">
            <strong>Tienda CESMAG</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarHeader" aria-controls="navbarHeader" 
        aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarHeader">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="#" calss="nav-link active">Catalogo</a>
                </li>
            </ul>
            <a href="cart.php" class="btn btn-primary">Carrito</a>
        </div> 
        </div>
    </div>
    </header>

        <!--PRODUCTS-->

    <main>
            <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php
                foreach($resultado as $row) {
                ?>
                <div class="col">
                <div class="card shadow-sm">
                    <?php
                        $id = $row['id_product'];
                        $image = "images/products/product_" . $id . ".jpg";
                        if(!file_exists($image)){
                            $image = "images/no-photo.jpg";
                        }
                    ?>
                    <img src="<?php echo $image; ?>"></img>
                    <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text">$ <?php echo number_format($row['price'], 0, '.', ','); ?> COP</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="details.php?id=<?php echo $row['id_product']; ?>&token=<?php echo
                            hash_hmac('sha1', $row['id_product'], KEY_TOKEN); ?>" class="btn 
                            btn-primary">Detalles</a>
                        </div>
                        <a href="" class="btn btn-success">Agregar</a>
                    </div>
                    </div>
                </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>


</body>
</html>