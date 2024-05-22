<?php
require 'config/config.php';
require 'config/databasePDO.php';
$db = new Database();
$conn = $db->conectar();

$products = isset($_SESSION['cart']['products']) ? $_SESSION['cart']['products'] : null;

$cart_list = array();

if($products != null){
    foreach($products as $key => $quantity){
        $sql = $conn->prepare("SELECT id_product, name, price, $quantity AS quantity FROM product
        WHERE id_product=?");
        $sql->execute([$key]);
        $cart_list[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}
else{
    header("Location: index.php");
    exit;
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
        
        <div class="row">
            <div class="col-6">
                <h4>Detalles de pago</h4>
                <div id="paypal-button-container"></div>
            </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($cart_list == null){
                                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                                }
                                else{
                                    $total = 0;
                                    foreach($cart_list as $products){
                                        $_id = $products['id_product'];
                                        $name = $products['name'];
                                        $price = $products['price'];
                                        $quantity = $products['quantity'];
                                        $subtotal = $quantity * $price;
                                        $total += $subtotal;
                                    ?>
                                
                                <tr>
                                    <td><?php echo $name; ?></td>
                                    <td>
                                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo CURRENCYREGION.
                                        number_format($subtotal,0,'.',',').' '.CURRENCY; ?></div>
                                    </td>   
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="1"><p class="h3">Total:</p></td>
                                    <td colspan="2">
                                        <p class="h3" id="total"><?php echo CURRENCYREGION.number_format($total,0,'.',',').' '.CURRENCY; ?></p>
                                    </td>
                                </tr>
                                <form action="https://checkout.wompi.co/p/" method="GET">
                                <input type="hidden" name="public-key" value="pub_test_501zz4iE1TnIknS0Ob5DEs22JFFVaxYe" />
                                <input type="hidden" name="currency" value="COP" />
                                <input id="totalPay" type="hidden" name="amount-in-cents" value="<?php echo $total*100; ?>"/>
                                <input id="reference" type="hidden" name="reference" value="<?php echo hash_hmac('sha1', $total, KEY_TOKEN); ?>" />
                                <input type="hidden" name="redirect-url" value="http://localhost/pasarelapagosUNICESMAG.github.io/checkout_status.php"/>
                                <button class="btn btn-primary btn-lg" type="submit" data-dismiss="modal">Comprar con WOMPI</button>
                                </form>
                            </tbody>
                            <?php } ?>
                        </table>

                    </div>
                </div>
            </div>
            
                    

        </div>
    </main>

    
        <!--Bootstrap-->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID;?>&currency=USD">
    </script>

    <script>
            paypal.Buttons({
                style:{
                    color: 'blue',
                    shape: 'pill',
                    label: 'pay'
                },
                createOrder: function(data, actions){
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: <?php echo number_format($total/3820,0,'.',','); ?>
                            }
                        }]
                    });
                },

                onApprove: function(data, actions){
                    actions.order.capture().then(function(detalles){
                        console.log(detalles);
                        let url = 'class/captura.php'
                        window.location.href="checkout_status.php"
                        return fetch(url, {
                            method: 'post',
                            headers: {
                                'content-type' : 'application/json'
                            },
                            body: JSON.stringify({
                                detalles: detalles
                            })
                        })
                    });
                },

                onCancel: function(data){
                    alert("Pago cancelado");
                    console.log(data);
                }
            }).render('#paypal-button-container');
        </script>
    

</body>
</html>