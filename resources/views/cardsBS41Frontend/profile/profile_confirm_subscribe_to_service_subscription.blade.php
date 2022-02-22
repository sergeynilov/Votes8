@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-1 mb-3">
        {{ Breadcrumbs::render('profile', 'Profile preview', 'Confirm selected package') }}
    </div>

    <!-- Page Content : profile edit -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">


            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Profile : Confirm selected package
            </h4>

            <div class="container-fluid bg-gradient p-5">
                <div class="row m-auto text-center w-75 column_content_left_aligned">


                    <div class="col-12 package-block" style="color: {{ $selectedServiceSubscription->color }} !important;background-color: {{
                        $selectedServiceSubscription->background_color }} !important; margin: 10px;">
                        <div class="pricing-divider ">
                            <h3 class="text-light">{{ $selectedServiceSubscription->name }}</h3>

                            @if(!$selectedServiceSubscription->is_free )
                                <p class="my-0 display-2 text-light font-weight-normal">
                                    <span class="h3">{{ $currency_sign }}</span>
                                    {{ $selectedServiceSubscription->price }}
                                    <span class="h5">/month</span>
                                </p>
                            @endif

                            @if($selectedServiceSubscription->is_free )
                                <p class="my-0 display-2 text-light font-weight-normal">
                                    <span class="h3"> </span>
                                    Free
                                    <span class="h5">/month</span>
                                </p>
                            @endif


                            <svg class='pricing-divider-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none'
                                 version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink'
                                 xmlns='http://www.w3.org/2000/svg' y='0px'>
          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729
	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                                <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729
	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                                <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716
	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                                <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428
	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
        </svg>
                        </div>
                        <div class="card-body bg-white mt-0 shadow mb-5">
                            {!! $selectedServiceSubscription ->description !!}
                        </div>
                    </div>


                    <div class="stripe_form_wrapper column_content_centered">
                        <div>
                            sample card : 4242424242424242
                        </div>



                        <div class="alert alert-info" role="alert" id="div_payment_select" style="display: flex; flex-direction: row">

{{--                            //profile_subscribe_to_service_subscription
profile_subscribe_to_service_subscription_paypal
--}}
                            <form id="stripe_confirm_subscribe_payment_form" method="POST" action="{{ url('/profile/profile_subscribe_to_service_subscription_paypal') }}" accept-charset="UTF-8"                                 enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input type="__hidden" id="selected_service_subscription_id" name="selected_service_subscription_id" value="{{$selectedServiceSubscription->id }}">
                            <div class="custom-control custom-radio custom-control-inline p-2 pl-5">
                                <input type="radio" class="custom-control-input" id="payment_type_paypal" name="payment_type" value="paypal">
                                <label class="custom-control-label" for="payment_type_paypal">
                                    {!! $viewFuncs->showAppIcon('paypal', 'transparent_on_white', 'Select Paypal payment') !!} Paypal
                                </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline p-2 pl-5">
                                {!! $viewFuncs->showAppIcon('stripe','selected_dashboard') !!}
                                <input type="radio" class="custom-control-input" id="payment_type_stripe" name="payment_type" value="stripe">
                                <label class="custom-control-label" for="payment_type_stripe">
                                    {!! $viewFuncs->showAppIcon('stripe', 'transparent_on_white', 'Select stripe payment') !!} Stripe
                                </label>
                            </div>

                            <input id="hidden_selected_downloads" type="hidden" value="">

                            <button type="button" class="btn btn-primary" onclick="javascript: profileSelectServiceSubscription.onSelectPaymentMethod({{
                            $selectedServiceSubscription->id }}) ; return
                            false; " style=""><span
                                        class="btn-label"><i class="fa fa-credit-card fa-submit-button"></i></span> &nbsp;Pay
                            </button>&nbsp;&nbsp;
                            </form>
                        </div>


                        <div class="alert alert-info" role="alert" id="div_payment_form_stripe" style="flex-direction: row; display:none; ">
                        <form id="payment-form" style="justify-content: center; width: 90%;" class="column_content_centered; ">
                            <h4>Enter Credit or debit card</h4>
                            <div id="card-element" style="justify-content: center">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>

                            <!-- Used to display form errors. -->
                            <div id="card-errors"></div>

                            @if($selectedServiceSubscription->is_free)
                            <div class="alert alert-info m-1 p-1" role="alert">
                                <p>You selected <strong>free</strong> subscription.</p>
                                <p>Anyway we need to check your payment card.</p>
                            </div>
                            @endif


                            <div class="row btn-group editor_btn_group">
                                <button class="btn btn-primary btn-save save-btn-filters p-2">
                                    <i class="fa fa-credit-card"></i>&nbsp;Submit Payment
                                </button>


                                <button type="button" class="btn btn-inverse m-1 p-1"
                                        onclick="javascript:document.location='{{ URL::route('profile_select_service_subscription') }}'">
                                    <i class="fa fa-arrows-alt"></i> Cancel
                                </button>
                            </div>
                        </form>
                        </div>



                        {{--                        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('account-register-cancel') }}'"--}}
                        {{--                                style=""><span class="btn-label"></span> &nbsp;Cancel--}}
                        {{--                        </button>&nbsp;&nbsp;--}}


                    </div>

                </div>
            </div>


        </section> <!-- class="card-body" -->


    </div>


    <!-- /.page-wrapper Page Content : profile edit   -->


@endsection


@section('scripts')


    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/'.$frontend_template_name.'/profile_select_service_subscription.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var profileSelectServiceSubscription = new profileSelectServiceSubscription('confirm_selected',  // must be called before jQuery(document).ready(function
            // ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            profileSelectServiceSubscription.onFrontendPageInit('confirm_selected')
        });

        /*]]>*/
    </script>


@endsection

<style lang="css">
    .stripe_form_wrapper {
        /*border: 2px dotted green;*/
        margin: 10px;
        padding: 10px;
        width: 100% !important;
    }

    #card-errors {
        color: red !important;
        margin: 5px !important;
        padding: 2px !important;
        width: 100% !important;
        display: block;
    }

    #card-element {
        line-height: 1.5rem;
        min-width: 360px;
        margin: 5px;
        padding: 5px;
        display: block;
        border: 0px dotted green;
    }

    .__PrivateStripeElement {
        min-width: 300px !important;
        min-height: 40px !important;
        color: #00001D;
        /*border: 4px solid blue;*/
    }

</style>
