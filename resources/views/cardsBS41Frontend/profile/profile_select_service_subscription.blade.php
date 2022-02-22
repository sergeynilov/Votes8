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
        {{ Breadcrumbs::render('profile', 'Profile preview', 'Select package') }}
    </div>

    <!-- Page Content : profile edit -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">


            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Profile : Select package
            </h4>

            <div class="container-fluid bg-gradient p-5">
                <div class="row m-auto text-center w-75">


                    @foreach($serviceSubscriptionsList as $nextServiceSubscription)
                        {{--                        $nextServiceSubscription::{{ $nextServiceSubscription }}--}}
                        <div class="col-6 package-block" style="color: {{ $nextServiceSubscription['color'] }} !important;background-color: {{
                        $nextServiceSubscription['background_color'] }} !important;">
                            <div class="pricing-divider">
                                <h3 class="text-light">{{ $nextServiceSubscription['name'] }}</h3>

                                @if(!$nextServiceSubscription['is_free'] )
                                    <p class="my-0 big_price_block text-light font-weight-normal">
                                        <span class="h4">{{ $currency_sign }}</span>
                                        <span class="big_price">{{ $nextServiceSubscription['price'] }}</span>
                                        <span class="h5">/month</span>
                                    </p>
                                @endif

                                @if($nextServiceSubscription['is_free'] )
                                    <p class="my-0 big_price_block text-light font-weight-normal">
                                        <span class="big_price">0</span>
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
                                {!! $nextServiceSubscription['description'] !!}
                            </div>

                            @if(!$nextServiceSubscription['is_subscribed'] )
                                <div class="card-footer bg-white mt-0 shadow mb-5">
                                    <button type="button" class="btn btn-lg btn-block  btn-custom"
                                            onclick="javascript:document.location='{{ URL::route('profile_confirm_subscribe_to_service_subscription',
                                            $nextServiceSubscription['id'] ) }}' ">
                                        <span style=" color: {{ $nextServiceSubscription['color'] }} !important; font-weight: bold;"><i class="fa fa-credit-card"></i>&nbsp;Subscribe {{
                                        $nextServiceSubscription['name']
                                        }}</span>
                                    </button>
                                </div>
                            @endif

                            @if($nextServiceSubscription['is_subscribed'] )
                                <div class="card-footer mt-0 shadow mb-5">
                                    <h4 class="my-0 big_price_block text-light font-weight-normal" style=" color: #232318 !important; font-weight: bold;">
                                        {{--                                        <span class="h5"> You are already</span>--}}
                                        <span class="h4"><i class="fa fa-sign-out"></i>&nbsp;<strong>Subscribed</strong></span>&nbsp;

                                        <button type="button" class="btn btn-inverse btn-sm p-3"
                                                onclick="javascript:profileSelectServiceSubscription.makeCancelSubscription( {{ $nextServiceSubscription['id'] }}, '{{ $nextServiceSubscription['name'] }}' )">
                                                <i class="fa fa-arrows-alt"></i> Cancel Subscription</span>
                                        </button>

                                    </h4>

                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

                <div class="container-fluid bg-gradient p-5 m-2 ml-5">
                    <button type="button" class="btn btn-inverse"
                            onclick="javascript:document.location='{{ URL::route('profile-view') }}'">
                        <i class="fa fa-arrows-alt"></i> Cancel
                    </button>
                </div>
            </div>


        </section> <!-- class="card-body" -->


    </div>


    <!-- /.page-wrapper Page Content : profile edit   -->


@endsection


@section('scripts')


    <script src="{{ asset('js/'.$frontend_template_name.'/profile_select_service_subscription.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var profileSelectServiceSubscription = new profileSelectServiceSubscription('listing',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            profileSelectServiceSubscription.onFrontendPageInit('listing')
        });

        /*]]>*/
    </script>


@endsection




<style lang="css">
    .big_price_block {
        border: 0px dotted red;
    }

    .big_price {
        font-size: xx-large;
        font-weight: bold;
        /*border: 2px dotted green;*/
    }

    .pricing-divider {
        border-radius: 20px;
        /*background: #C64545;*/
        padding: 5px !important;
        /*position: relative;*/
        border: 0px dotted green;
        height: 120px !important;
    }

</style>
