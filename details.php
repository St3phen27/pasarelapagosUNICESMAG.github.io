<?php
require 'config/config.php';
require 'config/databasePDO.php';
$db = new Database();
$conn = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == ''){
    echo 'Error al procesar la peticion';
    exit;
}
else{
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if($token == $token_tmp){

        $sql = $conn->prepare("SELECT count(id_product) FROM product WHERE id_product=?");
        $sql->execute([$id]);
        if($sql->fetchColumn()>0){
            $sql = $conn->prepare("SELECT name, description, price FROM product WHERE id_product=?");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $dir_images = 'images/products/';
            $imageRoute = $dir_images.'product_'.$id.'.jpg';

            if(!file_exists($imageRoute)){
                $imageRoute = 'images/no-photo.jpg';
            }

            $imagesArray = array();
            $dir = dir($dir_images);

            while(($file = $dir->read()) != false){
                if($file != 'product_'.$id.'.jpg' && (strpos($file, 'jpg') || strpos($file, 'jpeg'))){
                    $imagesArray[] = $dir_images . $file;
                }
            }
            $dir->close();
        }
    }
    else{
        echo 'Error al procesar la peticion';
    }
}


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

    <!--CONTENIDO-->

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <img src="<?php echo $imageRoute; ?>" width="500" height="500">
                </div>
                <div class="col-md-6 order-md-2">
                    <h1><?php echo $name; ?></h1>
                    <h2><?php echo CURRENCYREGION.number_format($price, 0, '.', ',').' '.CURRENCY; ?></h2>
                    <p class="lead">
                        <?php echo $description; ?>
                    </p>
                    <div class ="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button">
                            Comprar ahora
                        </button>
                        <button class="btn btn-outline-primary" type="button" onclick="addProduct(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
                            Agregar al carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>