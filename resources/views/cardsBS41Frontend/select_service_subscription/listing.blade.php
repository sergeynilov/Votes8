@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')


    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-2 mb-3">
        {{ Breadcrumbs::render('event', 'Events') }}
    </div>


    <div class="row ml-1 mr-1">
        <div class="col-sm-8 ">




{{--            <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,900" rel="stylesheet">--}}
{{--            <link href="https://fonts.googleapis.com/css?family=Oleo+Script" rel="stylesheet">--}}
            <section class="services pt-60 pb-60" id="services">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section-title text-center mb-60">
{{--                                <p>Best place for friends & family</p>--}}
                                <h4>Select Service Subscription</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">

                            @foreach($leftSelectServiceSubscriptionList as $nexSelectServiceSubscription)
                            <div class="single_service service_right">
                                <img src="/images/service.png" alt="">

                                <h4>{{ $nexSelectServiceSubscription->name }}</h4>
                                <p>{!! Purifier::clean($nexSelectServiceSubscription->description) !!}</p>
                            </div>
                            @endforeach


                        </div>
                        <div class="col-md-4 col-sm-12 text-center">
                            <div class="single_mid">
{{--                                <img src="http://infinityflamesoft.com/html/restarunt-preview/assets/img/service_mid.png" alt="">--}}
                                <img src="images/Subscription-Services.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            @foreach($rightSelectServiceSubscriptionList as $nexSelectServiceSubscription)
                                <div class="single_service service_right">
                                    <img src="/images/service.png" alt="">

                                    <h4>{{ $nexSelectServiceSubscription->name }}</h4>
                                    <p>{!! Purifier::clean($nexSelectServiceSubscription->description) !!}</p>
                                </div>
                            @endforeach

                            {{--                            <div class="single_service">--}}
{{--                                <img src="http://infinityflamesoft.com/html/restarunt-preview/assets/img/services/service-4.png" alt="">--}}
{{--                                <h4>24/7 Service</h4>--}}
{{--                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>--}}
{{--                            </div>--}}
{{--                            <div class="single_service">--}}
{{--                                <img src="http://infinityflamesoft.com/html/restarunt-preview/assets/img/services/service-5.png" alt="">--}}
{{--                                <h4>Candle Light Dinner</h4>--}}
{{--                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>--}}
{{--                            </div>--}}
{{--                            <div class="single_service">--}}
{{--                                <img src="http://infinityflamesoft.com/html/restarunt-preview/assets/img/services/service-6.png" alt="">--}}
{{--                                <h4>Special Local Foods</h4>--}}
{{--                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </section>

        </div>


        @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

    </div>

@endsection

<style>


    .pb-60 {
        padding-bottom: 60px;
    }
    .pt-60 {
        padding-top: 60px;
    }
    .mb-60 {
        margin-bottom: 60px;
    }
    p {
        font-weight: 300;
        font-size: 14px;
    }
    .section-title p {
        font-size: 24px;
        font-family: Oleo Script;
        margin-bottom: 0px;
    }
    .section-title h4 {
        font-size: 40px;
        text-transform: capitalize;
        color: #FF5E18;
        position: relative;
        display: inline-block;
        padding-bottom: 25px;
    }
    .section-title h4::before {
        width: 80px;
        height: 1.5px;
        bottom: 0;
        left: 50%;
        margin-left: -40px;
    }
    .section-title h4::before, .section-title h4::after {
        position: absolute;
        content: "";
        background-color: #FF5E18;
    }
    .section-title h4::after {
        width: 40px;
        height: 1.5px;
        bottom: -5px;
        left: 50%;
        margin-left: -20px;
    }
    .single_service.service_right {
        padding-right: 70px;
        padding-left: 0;
        text-align: right;
    }
    .single_service.service_right img {
        right: 0;
        left: auto;
        margin-top: 0;
    }
    .single_service:nth-child(1), .single_service:nth-child(2) {
        border-bottom: 1px dashed #333;
        padding-bottom: 15px;
    }
    .single_service img {
        max-width: 45px;
        position: absolute;
        left: 0;
        top: 0;
    }
    .single_service {
        position: relative;
        padding-left: 70px;
        margin-bottom: 35px;
    }



</style>