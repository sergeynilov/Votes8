<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
{{--<form action="/subscribe_process" method="post">--}}

<form method="POST" action="{{ url('/subscribe_process') }}" accept-charset="UTF-8" id="form_payment_execute" enctype="multipart/form-data">
    {{ csrf_field() }}
    <script
            src="https://checkout.stripe.com/checkout.js"
            class="stripe-button"
            data-key="{{ $stripe_public_key }}"
            data-amount="987"
            data-name="{{ $site_name }}"
            data-description="Online course about integrating Stripe"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
            data-locale="auto"
            data-currency="usd">
    </script>
    22EEEEEE


</form>
</body>
</html>