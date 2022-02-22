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
        {{ Breadcrumbs::render('profile', 'Profile preview') }}
    </div>


    <!-- Page Content : profile preview -->

    <div class="row bordered card" id="page-wrapper">
        <section class="card-body">

            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Profile : {{ $userProfile['username'] }}
            </h4>


            @include($frontend_template_name.'.layouts.page_header')

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label">To PDF</label>
                <div class="col-12 col-sm-8">
                    <button type="button" class=" btn btn-outline-primary"
                            onclick="javascript: frontendProfile.printToPdf({{ $userProfile['id'] }}) ; return false; ">&nbsp;<i
                                class="fa  fa-file-pdf-o
"></i>&nbsp;Print to pdf/png/jpeg
                    </button>
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="id">Id</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('id', $userProfile['id'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="username">Username</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('username', $userProfile['username'], "form-control", [ "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="full_name">Full name</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('full_name', $userProfile['full_name'], "form-control editable_field", [ "disabled"=>"disabled"  ]
                    ) !!}
                </div>
            </div>


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="email">Email</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('email',  !$viewFuncs->wrpIsFakeEmail($userProfile['email']) ? $userProfile['email'] : 'fake', "form-control", [
                    "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label">Access</label>
                <div class="col-12 col-sm-8">
                    {{ $viewFuncs->getLoggedUserDisplayAccessGroupsName() }}
                    {{--@getLoggedUserDisplayAccessGroupsName()--}}
                    {{--{!! $viewFuncs->text('email', $userProfile['email'], [ "class"=>"form-control editable_field ", "id"=>"email", "disabled"=>"disabled"  ] ) }}--}}
                </div>
            </div>

            @if(!empty($userProfile['provider_name']))
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label">Provider name/Provider id</label>
                    <div class="col-12 col-sm-8">
                        {{ $viewFuncs->wrpCapitalize($userProfile['provider_name']) }} / {{ $userProfile['provider_id'] }}
                    </div>
                </div>
            @endif


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="status">Status</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('status', $viewFuncs->wrpGetUserStatusLabel($userProfile['status']), "form-control editable_field", [
                    "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="verified">Verified</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('verified', $viewFuncs->wrpGetUserVerifiedLabel($userProfile['verified']), "form-control editable_field", [
                    "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="activated_at">Activated at</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('activated_at', $viewFuncs->getFormattedDateTime($userProfile['activated_at']), "form-control editable_field", [
                    "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="phone">Phone</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('phone', $userProfile['phone'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="sex">Sex</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('sex', $viewFuncs->wrpGetUserSexLabel( $userProfile['sex'] ), "form-control editable_field", [ "disabled"=>"disabled" ] ) !!}
                </div>
            </div>


            <fieldset class="notes-block text-muted">
                <legend class="notes-blocks">Address</legend>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="address_line1">Line 1</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('address_line1', $userProfile['address_line1'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="address_city">City</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('address_city', $userProfile['address_city'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="address_state">State</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('address_state', $userProfile['address_state'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="address_postal_code">Postal code</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('address_postal_code', $userProfile['address_postal_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="address_country_code">Country code</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('address_country_code', $userProfile['address_country_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

            </fieldset>


            <fieldset class="notes-block text-muted">
                <legend class="notes-blocks">Shipping address</legend>
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="shipping_address_line1">Line 1</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('shipping_address_line1', $userProfile['shipping_address_line1'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="shipping_address_city">City</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('shipping_address_city', $userProfile['shipping_address_city'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="shipping_address_state">State</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('shipping_address_state', $userProfile['shipping_address_state'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="shipping_address_postal_code">Postal code</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('shipping_address_postal_code', $userProfile['shipping_address_postal_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="shipping_address_country_code">Country code</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('shipping_address_country_code', $userProfile['shipping_address_country_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
                    </div>
                </div>

            </fieldset> <!-- Shipping address-->


            <fieldset class="notes-block text-muted">
                <legend class="notes-blocks">Subscriptions</legend>

                @if( empty($profileSubscription) )
                    <div class="alert alert-warning small" role="alert">
                        You have no any Subscriptions yet.
                    </div>

                    <div class="form-row m-3 ">
                        You can select package to subscribe&nbsp;
                        <button type="button" class=" btn btn-primary float-right a_link" onclick="javascript:document.location='{{ URL::route
                    ('profile_select_service_subscription') }}'"
                                style="margin-right:50px;">&nbsp;<span class="btn-label"><i class="fa fa-list-ul"></i></span>&nbsp;Select package
                        </button>&nbsp;&nbsp;
                    </div>
                @else
                    <div class="alert alert-info small" role="alert">

                        <div class="key_values_rows">
                            <div class="key_values_rows_label_200_label">
                                You are subscribed to plan:
                            </div>
                            <div class="key_values_rows_value_200_label">
                                <strong>
                                    {{ $profileSubscription['service_subscription_name'] }}
                                    &nbsp;{!! $viewFuncs->showAppIcon('subscription', $current_subscription_icon_type,
                                    $current_subscription_text ) !!} / {{ $profileSubscription['stripe_id'] }}
                                    &nbsp;{{ $current_subscription_text }}
                                </strong>
                            </div>
                        </div>

                        @if( !empty($profileSubscription['service_subscription_price']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Price:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>{{  $viewFuncs->getFormattedCurrency( $profileSubscription['service_subscription_price'] ) }}</strong>
                                </div>
                            </div>
                        @endif

                        @if( !empty($profileSubscription['service_subscription_is_free']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Free:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>Free</strong>
                                </div>
                            </div>
                        @endif

                        @if( !empty($profileSubscription['service_subscription_is_premium']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Premium:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>Premium</strong>
                                </div>
                            </div>
                        @endif

                        @if( !empty($profileSubscription['service_subscription_description']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Description:
                                </div>
                                <div class="key_values_rows_value_200_label small_text">
                                    {!! Purifier::clean($profileSubscription['service_subscription_description'])  !!}
                                </div>
                            </div>
                        @endif


                        @if(!empty( $profileSubscription['trial_ends_at']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Trial ends at:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>{{  $viewFuncs->getFormattedDateTime( $profileSubscription['trial_ends_at'] ) }}</strong>
                                </div>
                            </div>
                        @endif

                        @if(!empty( $profileSubscription['ends_at']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Ends at:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>{{  $viewFuncs->getFormattedDateTime( $profileSubscription['ends_at'] ) }}</strong>
                                </div>
                            </div>
                        @endif

                        @if(!empty( $profileSubscription['created_at']) )
                            <div class="key_values_rows">
                                <div class="key_values_rows_label_200_label">
                                    Created at:
                                </div>
                                <div class="key_values_rows_value_200_label">
                                    <strong>{{  $viewFuncs->getFormattedDateTime( $profileSubscription['created_at'] ) }}</strong>
                                </div>
                            </div>
                        @endif

                        <div class="form-row m-3 ">
                            You can change your subscription package&nbsp;
                            <button type="button" class=" btn btn-primary float-right a_link" onclick="javascript:document.location='{{ URL::route
                    ('profile_select_service_subscription') }}'"
                                    style="margin-right:50px;">&nbsp;<span class="btn-label"><i class="fa fa-list-ul"></i></span>&nbsp;Select package
                            </button>&nbsp;&nbsp;
                        </div>

                    </div>
                @endif

            </fieldset> <!-- fieldset="Subscriptions" -->

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label">Notes</label>
                <div class="col-12 col-sm-8">
                    {!! Purifier::clean($viewFuncs->nl2br2($userProfile['notes'])) !!}
                </div>
            </div>

            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="website">Website</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('website', $userProfile['website'], "form-control editable_field", [ "disabled"=>"disabled" ] ) !!}
                </div>
            </div>

            @if(count($profileUsersSiteSubscriptions) > 0)
                <div class="form-row mb-5 mt-5">

                    <label class="col-12 col-sm-4 col-form-label">Subscription(s)</label>
                    <div class="col-12 col-sm-8">
                        <ul class="no_list_style list-group list-group-flush">
                            @foreach($profileUsersSiteSubscriptions as $nextSelectedSubscription)
                                <li class="list-group-item">
                                    {{ $nextSelectedSubscription->site_subscription_name }}
                                    @if( !empty($nextSelectedSubscription->vote_category_name) )
                                        (
                                        <small>{{ $nextSelectedSubscription->vote_category_name }} )</small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            @endif

            @if( !empty($avatarData['avatar_url']) and !empty($avatarData['avatar']) )
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label">Avatar</label>
                    <div class="col-12 col-sm-8">
                        {{ $avatarData['avatar'] }} <br>
                        <img src="{{ $avatarData['avatar_url'] }}{{  "?dt=".time()  }}" alt="avatar">
                    </div>

                </div>
                <div class="alert alert-warning small mb-5" role="alert">
                    Please, restrict size of avatar within {{$avatar_dimension_limits['max_width']}}*{{$avatar_dimension_limits['max_height']}} px.
                </div>

            @endif


            @if( !empty($fullPhotoData['full_photo_url']) and !empty($fullPhotoData['full_photo']) )
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label">Full photo</label>
                    <div class="col-12 col-sm-8">
                        {{ $fullPhotoData['full_photo'] }} <br>
                        <img src="{{ $fullPhotoData['full_photo_url'] }}{{  "?dt=".time()  }}" alt="full photo">
                    </div>

                </div>
                <div class="alert alert-warning small" role="alert">
                    Please, restrict size of full photo within {{$full_photo_dimension_limits['max_width']}}*{{$full_photo_dimension_limits['max_height']}} px.
                </div>

            @endif


            <div class="form-row mb-3">
                <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('created_at', isset($userProfile['created_at']) ? $viewFuncs->getFormattedDateTime($userProfile['created_at']) : '', "form-control",
                    [ "disabled"=> "disabled" ] ) !!}
                </div>
            </div>


            @if(isset($userProfile['updated_at']))
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('updated_at', isset($userProfile['updated_at']) ? $viewFuncs->getFormattedDateTime($userProfile['updated_at']) : '',
                        "form-control", [ "disabled"=> "disabled" ] ) !!}
                    </div>
                </div>
            @endif


            <div class="col-12 ">
                <div class="btn-group editor_btn_group float-right">
                    <button type="button" class="btn btn-primary" onclick="javascript:document.location='{{ URL::route('profile-edit-details') }}'"><span
                                class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Edit
                    </button>

                    <button type="button" class=" btn btn-inverse float-right" onclick="javascript:document.location='{{ URL::route('home') }}'"
                            style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
                    </button>&nbsp;&nbsp;
                </div>
            </div>


            <div class="col-12">
                <div class="btn-group editor_btn_group float-right">
                    <button type="button" class="btn btn-primary" onclick="javascript:document.location='{{ URL::route('profile-payments') }}'">
                        <span class="btn-label"><i class="fa fa fa-credit-card fa-submit-button"></i></span>&nbsp;Payments
                    </button>

                    <button type="button" class="btn btn-primary" onclick="javascript:document.location='{{ URL::route('profile-edit-avatar') }}'">
                        <span class="btn-label"><i class="fa fa-user-circle fa-submit-button"></i></span>&nbsp;Avatar
                    </button>

                    <button type="button" class=" btn btn-primary float-right"
                            onclick="javascript:document.location='{{ URL::route('profile-edit-subscriptions') }}'"
                            style="margin-right:10px;"><span class="btn-label"><i class="fa fa-list-ul"></i></span>&nbsp;Subscriptions
                    </button>&nbsp;&nbsp;
                </div>
            </div>

        </section> <!-- class="card-body" -->
    </div>

    <!-- /.page-wrapper Page Content : profile preview -->

@endsection


@section('scripts')

    <script src="{{ asset('js/'.$frontend_template_name.'/profile.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/


        var frontendProfile = new frontendProfile('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendProfile.onFrontendPageInit('view')
        });


        /*]]>*/
    </script>


@endsection