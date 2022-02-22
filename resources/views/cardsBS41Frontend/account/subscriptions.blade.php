@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : account register subscriptions -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">

            <h1 class="text-center">
                @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
            </h1>

            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Registration : Your Subscriptions
            </h4>

            <form method="POST" action="{{ URL::route('account-register-subscriptions-post') }}" accept-charset="UTF-8" id="form_account_subscriptions_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="row ml-1 mb-3">
                    {{ Breadcrumbs::render('account-register', 'Registration : Subscriptions') }}
                </div>


                @include($frontend_template_name.'.account.register_steps', ['current_step'=> 3])

                @include($frontend_template_name.'.layouts.page_header')


                <div class="form-row mb-3">
                    @foreach($siteSubscriptionsList as $nextSiteSubscription)

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    {!! $viewFuncs->showStylingCheckbox('cbx_subscription_' . $nextSiteSubscription->id, 1, !empty($nextSiteSubscription->checked), '', []  ) !!}
                                </div>
                            </div>
                            &nbsp;&nbsp;{{ $nextSiteSubscription->name }}
                        </div>

                    @endforeach
                </div>


                @if(!empty($account_register_subscriptions_text))
                    <div class="form-row mb-3">
                        <fieldset class="notes-block text-muted">
                            <legend class="blocks">Notes</legend>
                            {!! $account_register_subscriptions_text !!}
                        </fieldset>
                    </div>
                @endif


                <div class="row">
                    <div class="col-8 col-sm-8">
                        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('account-register-cancel') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Cancel
                        </button>&nbsp;&nbsp;
                        <button type="button" class=" btn btn-secondary  " onclick="javascript:document.location='{{ URL::route('account-register-avatar') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Prior
                        </button>&nbsp;&nbsp;
                    </div>
                    <div class="col-4 col-sm-4">
                        <div class="btn-group float-right mt-2" role="group">
                            <button type="submit" class="btn btn-primary float-right"><span class="btn-label"></span> &nbsp;Next
                            </button>
                        </div>
                    </div>
                </div>


            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : account register subscriptions   -->


@endsection


@section('scripts')

    <script src="{{ asset('js/cardsBS41Frontend/register.js') }}{{  "?dt=".time()  }}"></script>
    <script>
        /*<![CDATA[*/

        var frontendRegister = new frontendRegister('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendRegister.onFrontendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection
