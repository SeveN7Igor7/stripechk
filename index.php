<?php

// Inclua os arquivos necessários do Stripe
require_once('stripe-php/init.php');

// Defina sua chave secreta do Stripe
\Stripe\Stripe::setApiKey('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');

// Dados do pagamento
$paymentIntent = \Stripe\PaymentIntent::create([
  'amount' => 500, // Valor em centavos (R$ 5,00)
  'currency' => 'brl', // Moeda (Reais brasileiros)
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f4f4;
    }

    .payment-form {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      width: 400px; /* Aumentando a largura do formulário */
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    #card-element {
      margin-bottom: 20px;
    }

    #card-button {
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px 20px;
      cursor: pointer;
    }

    #card-button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="payment-form">
    <h2>Checkout</h2>
    <form id="payment-form">
      <input type="text" id="card-holder-name" placeholder="Nome no Cartão">
      <input type="text" id="postal-code" placeholder="CEP"> <!-- Adicionando campo para o CEP -->
      <div id="card-element"></div>
      <button id="card-button" data-secret="<?php echo $paymentIntent->client_secret; ?>">
        Pagar Agora
      </button>
    </form>
  </div>

  <script src="https://js.stripe.com/v3/"></script>
  <script>
    var stripe = Stripe('pk_live_51OtgeFDvkxsAUVgYvDdU9n8kGTnP2TqegfCiGr76jeXu5bNGjdTfKtgQy5TFZHmGyQeTwIdZhxPartt2MBZCAsHX00JXkCPdoY');
    var elements = stripe.elements();
    
    var style = {
      base: {
        fontSize: '16px',
        color: '#32325d',
      },
    };
    
    var card = elements.create('card', { style: style });
    card.mount('#card-element');
    
    var form = document.getElementById('payment-form');
    var clientSecret = form.querySelector('#card-button').dataset.secret;
    
    form.addEventListener('submit', function(event) {
      event.preventDefault();
    
      stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: card,
          billing_details: {
            name: document.getElementById('card-holder-name').value,
            address: {
              postal_code: document.getElementById('postal-code').value // Obtendo o valor do CEP
            }
          }
        }
      }).then(function(result) {
        if (result.error) {
          console.error(result.error.message);
        } else {
          console.log(result.paymentIntent);
          alert('Pagamento bem-sucedido!');
        }
      });
    });
  </script>
</body>
</html>
