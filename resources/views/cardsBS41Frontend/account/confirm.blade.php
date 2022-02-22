@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')
    <?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>



    <!-- Page Content : account register confirm -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">
            <h1 class="text-center">
                @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
            </h1>

            <h4 class="card-title center-block">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Registration : Confirm registration
            </h4>

            <form method="POST" action="{{ URL::route('account-register-confirm-post') }}" accept-charset="UTF-8" id="form_account_confirm_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="row ml-1 mb-3">
                    {{ Breadcrumbs::render('account-register', 'Registration : Confirm') }}
                </div>


                @include($frontend_template_name.'.account.register_steps', ['current_step'=> 4])

                @include($frontend_template_name.'.layouts.page_header')

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="username">Username<span class="required"> * </span></label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('username', $accountData['username'], "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off",
                        "readonly"=>"readonly" ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="email">Email<span class="required"> * </span></label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('email', $accountData['email'], "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off", "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="first_name">First name</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('first_name', $accountData['first_name'], "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off",  "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="last_name">Last name</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('last_name', $accountData['last_name'], "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off",
                        "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="phone">Phone</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('phone', $accountData['phone'], "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off", "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="website">Website</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('website', $accountData['website'], "form-control editable_field", [ "autocomplete"=>"off", "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="sex">Sex</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('sex', $viewFuncs->wrpGetUserSexLabel($accountData['sex']), "form-control editable_field", [ "autocomplete"=>"off", "readonly"=>"readonly"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="notes">Notes</label>
                    <div class="col-12 col-sm-8">
                        {!! $accountData['notes'] !!}
                    </div>
                </div>

                @if(count($selectedSubscriptions) > 0)
                    <div class="form-row mb-5 mt-5">

                        <label class="col-12 col-sm-4 col-form-label">You selected {{ \Illuminate\Support\Str::plural('subscription', count($selectedSubscriptions)) }}</label>
                        <div class="col-12 col-sm-8">
                            <ul class="no_list_style list-group list-group-flush">
                                @foreach($selectedSubscriptions as $nextSelectedSubscription)
<!--                                    --><?php //echo '<pre>$nextSelectedSubscription::'.print_r($nextSelectedSubscription,true).'</pre>';  ?>
                                    <li class="list-group-item">
                                        {{ $nextSelectedSubscription->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                @endif

                @if( !empty($avatar_filename) and !empty($avatar_filename_url) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label">Preview of uploaded avatar</label>
                        <div class="col-12 col-sm-8">
                            {{ $avatar_filename }} :<br>
                            <img src="{{$avatar_filename_url}}{{  "?dt=".time()  }}" alt="avatar">
                            {{--$avatar_filename_url::{{ $avatar_filename_url  }}--}}
                        </div>
                    </div>
                @endif


                @if( !empty($full_photo_filename) and !empty($full_photo_filename_url) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label">Preview of uploaded full photo</label>
                        <div class="col-12 col-sm-8">
                            {{ $full_photo_filename }} :<br>
                            <img src="{{$full_photo_filename_url}}{{  "?dt=".time()  }}" alt="full photo">
                        </div>
                    </div>
                @endif

                @if(!empty($account_register_confirm_text))
                    <div class="form-row mb-3">
                        <fieldset class="notes-block text-muted">
                            <legend class="notes-blocks">Notes</legend>
                            {!! $account_register_confirm_text !!}
                        </fieldset>
                    </div>
                @endif


                <div class="form-row mb-3 {{ in_array('captcha', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-4 col-sm-4 col-form-label" for="captcha">Captcha</label>
                    <div class="col-4 col-sm-4">
                        {!! $viewFuncs->text('captcha', '', "form-control editable_field", [ "maxlength"=>"6", "autocomplete"=>"off"  ] )
                         !!}
                    </div>
                    <div class="col-4 col-sm-4">
                        {!! captcha_img('flat') !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col-8 col-sm-8">
                        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('account-register-cancel') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Cancel
                        </button>&nbsp;&nbsp;
                        <button type="button" class=" btn btn-secondary  " onclick="javascript:document.location='{{ URL::route('account-register-subscriptions') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Prior
                        </button>&nbsp;&nbsp;
                    </div>
                    <div class="col-4 col-sm-4">
                        <div class="btn-group float-right mt-2" role="group">
                            <button type="submit" class="btn btn-primary float-right"><span class="btn-label"></span> &nbsp;Create
                            </button>
                        </div>
                    </div>
                </div>

            </form>


        </section> <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Page Content : account register confirm -->



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
