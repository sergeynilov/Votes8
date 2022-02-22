@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')


    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>


    @include($frontend_template_name.'.layouts.logged_user')

{{--        <div class="row ml-2 mb-3">--}}
{{--            {{ Breadcrumbs::render('page-about', 'Payments') }}--}}
{{--        </div>--}}


    <div class="row ml-1 mr-1">

        <div class="column_content p-3 m-3">

            <h1 class="text-center">
                Pay for services your selected !
            </h1>

            <h4 class=" m-3" style="justify-self: center;">You selected :</h4>

            <div class="row p-2 m-1" style="justify-self: flex-start;">

                <ul class="list-group list-group-flush">
                    @foreach($selectedItems as $nextSelectedItem)
                        <li class="list-group-item p-3 m-3">{{ $nextSelectedItem['title'] }}, {{ $nextSelectedItem['quantity'] }} *
                            {{ $viewFuncs->getFormattedCurrency($nextSelectedItem['price']) }} = {{ $viewFuncs->getFormattedCurrency( $nextSelectedItem['price'] * $nextSelectedItem['quantity'])
                         }}
                        </li>
                    @endforeach
                    <li class="list-group-item top_split_border">
                        Quantity: <strong>{{ $all_quantity }}</strong>, Sub total: <strong>{{ $viewFuncs->getFormattedCurrency($subtotal_value) }}</strong>
                    </li>
                </ul>
            </div>

            <div class="row p-2 m-1" style="justify-self: flex-start; ">
                {!! Purifier::clean( $viewFuncs->nl2br2($payment_description) ) !!}
            </div>

            <div class="row row_content_centered p-2 m-1" style="justify-self: flex-start;">
                <form action="{{ url('stripe_express_payment_callback') }}" method="GET">
                    <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="{{ $stripe_public_key }}"
                            data-amount="{{ $subtotal_value * 100 }}"
                            data-name="{{ $site_name }}"
                            data-description=" selected {{ $all_quantity }} services"
                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                            data-locale="auto"
                            data-email="{{ $subscriptions_notification_email }}"
                            data-currency="{{ $currency_stripe_code }}">
                    </script>
                </form>
                <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ URL::route('home') }}'"
                        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
                </button>&nbsp;&nbsp;

            </div>

        </div>
    </div>