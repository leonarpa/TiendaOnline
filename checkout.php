<?php
session_start();

// Total de prueba (luego lo conectamos al carrito real)
$total = "10.00";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pago</title>

    <!-- SDK PayPal Sandbox -->
    <script src="https://www.paypal.com/sdk/js?client-id=AYaZ5zCibj8CfkEWdo9ArLpsgYCZBHrURjzcwOtbZwEZdApEFB8DHxNU-RNVfus-32Yy-xcMm5Gtslz9&currency=USD"></script>

    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        h2 { color: #333; }
        #paypal-button-container { margin-top: 20px; }
    </style>
</head>
<body>

    <h2>Resumen de compra</h2>
    <p>Total a pagar: <strong>$<?= $total ?> USD</strong></p>

    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },

            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?= $total ?>'
                        }
                    }]
                });
            },

            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('¡Pago completado! Gracias ' + details.payer.name.given_name);
                    window.location.href = 'completado.html';
                });
            },

            onError: function(err) {
                console.error(err);
                alert('Ocurrió un error con el pago. Intenta nuevamente.');
            }

        }).render('#paypal-button-container');
    </script>

</body>
</html>