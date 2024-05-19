<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AS53jKQEm9zvX8Mcp698FS-gFIsZ4NDBnss1kF0QyKq57vJ2FTkrg5977MWCZiJrHrBM9frl46N13Es5&currency=MXN">
    </script>
</head>
<body>
    <div id="paypal-button-container"></div>
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
                            value: 100
                        }
                    }]
                });
            },

            onApprove: function(data, actions){
                actions.order.capture().then(function(detalles){
                    console.log(detalles);
                    window.location.href="order-information.html"
                });
            },

            onCancel: function(data){
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
    
    

    <form action="https://checkout.wompi.co/p/" method="GET">
      <input type="hidden" name="public-key" value="pub_test_501zz4iE1TnIknS0Ob5DEs22JFFVaxYe" />
      <input type="hidden" name="currency" value="COP" />
      <input id="totalPay" type="hidden" name="amount-in-cents" value="100"/>
      <input id="reference" type="hidden" name="reference" value="referencia_pago_web_checkout_ejemplo_01" />
      <input type="hidden" name="redirect-url" value="https://google.com" />
      <button class="checkPageBtn" type="submit">COMPRAR AHORA</button>
      </form>


</body>
</html>