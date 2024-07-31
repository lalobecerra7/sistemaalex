<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
</head>
<body>
    <div id="paypal-button-container" style="max-width:1000px;"></div>

    <script src="https://www.paypal.com/sdk/js?client-id=AZFkpoP-dLwZxkUJjVzzlgIzmaQMWpbNIN51188qhVikS-CAd1GJRitOFQU8EECTVbNJtOsGqBAIRadG&components=buttons"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch("/my-server/create-paypal-order", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        cart: [
                            {
                                sku: "YOUR_PRODUCT_STOCK_KEEPING_UNIT",
                                quantity: "YOUR_PRODUCT_QUANTITY",
                            },
                        ]
                    })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(order) {
                    return order.id;
                });
            },
            onApprove: function(data, actions) {
                // This function captures the funds from the transaction.
                return actions.order.capture().then(function(details) {
                    // This function shows a transaction success message to your buyer.
                    alert('Transaction completed by ' + details.payer.name.given_name);
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>