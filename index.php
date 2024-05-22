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

        <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>

    <script>
        function addProduct(id, token){
            let url = 'class/cart.php'
            let formData = new FormData();
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }). then(response => response.json())
            .then(data=>{
                if(data.ok){
                    let element = document.getElementById("num_cart");
                    element.innerHTML = data.numero
                }
            })
        }
    </script>

    <script src="js/checkout.js"></script>

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
                        <button class="btn btn-outline-success" type="button" onclick="addProduct(<?php echo $row['id_product']; ?>, '<?php echo
                            hash_hmac('sha1', $row['id_product'], KEY_TOKEN); ?>')">
                            Agregar
                        </button>
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