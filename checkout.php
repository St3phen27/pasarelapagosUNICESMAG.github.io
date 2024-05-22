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



//session_destroy();

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
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })

        function updateQuantity(quantity, id){
            let url = 'class/update_cart.php'
            let formData = new FormData();
            formData.append('action', 'agregar')
            formData.append('id', id)
            formData.append('quantity', quantity)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }). then(response => response.json())
            .then(data=>{
                if(data.ok){
                    let divsubtotal = document.getElementById('subtotal_' + id)
                    divsubtotal.innerHTML = data.sub

                    let total = 0
                    let list = document.getElementsByName('subtotal[]')

                    for(let i = 0; i < list.lenght; i++){
                        total += parseFloat(list[i].innerHTML.replace(/[$][,][ ][COP]/g,''))
                    }

                    total = new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 0
                    }).format(total)
                    document.getElementById('total').innerHTML = '<?php echo CURRENCYREGION; ?>' + total + '<?php echo ' '.CURRENCY; ?>'
                }
            })
        }

        function eliminar(){
            let botonElimina = document.getElementById('btn-elimina')
            let id =botonElimina.value

            let url = 'class/update_cart.php'
            let formData = new FormData();
            formData.append('action', 'eliminar')
            formData.append('id', id)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }). then(response => response.json())
            .then(data=>{
                if(data.ok){
                    location.reload()
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

        <!--PRODUCTS-->

    <main>
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
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
                                <?php echo CURRENCYREGION.number_format($price,0,'.',',').' '.CURRENCY;?>
                            </td>
                            <td>
                                <input type="number" min="1" max="10" step="1" value="<?php echo $quantity ?>"
                                size="5" id="cantidad_<?php echo $_id; ?>" onchange="updateQuantity(this.value, <?php echo $_id; ?>)">
                            </td>
                            <td>
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo CURRENCYREGION.
                                number_format($subtotal,0,'.',',').' '.CURRENCY; ?></div>
                            </td>   
                            <td>
                                <a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php 
                                echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"><p class="h3">Total:</p></td>
                            <td colspan="2">
                                <p class="h3" id="total"><?php echo CURRENCYREGION.number_format($total,0,'.',',').' '.CURRENCY; ?></p>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            <?php if($cart_list != null){ ?>
            <div class="row">
                <div class="col-md-5 offset-md-7 d-grid gap-2">
                    <a href="payment.php" class="btn btn-primary btn-lg">Realizar pago</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ELIMINAR EL PRODUCTO
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn-elimina" type="button" class="btn btn-danger" onclick="elimina()">Eliminar</button>
        </div>
        </div>
    </div>
    </div>

</body>
</html>