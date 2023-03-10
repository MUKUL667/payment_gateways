<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Saving a Card sample</title>
    <meta name="description" content="A demo of Stripe Payment Intents" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/global.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <script src="/script.js" defer></script>
  </head>

  <body>
    <div class="sr-root">
      <div class="sr-main">
        <div class="sr-payment-form card">
          <div class="sr-form-row">
            <label>
              Account details
            </label>
            <input type="text" id="email" placeholder="Email address" />
          </div>

          <div class="sr-form-row">
            <label>
              Payment details
            </label>
            <div class="sr-input sr-element sr-card-element" id="card-element">
              <!-- A Stripe card Element will be inserted here. -->
            </div>
          </div>
          <div class="sr-field-error" id="card-errors" role="alert"></div>
          <button id="submit">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Link your card to your account</span>
          </button>
        </div>
        <div class="sr-result hidden">
          <p>Card setup completed<br /></p>
          <pre>
            <code></code>
          </pre>
        </div>
      </div>
    </div>
  </body>
</html>


















