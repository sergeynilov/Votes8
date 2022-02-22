@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')
    <?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-1 mb-2">
        {{ Breadcrumbs::render('profile', 'Profile preview', 'Edit details') }}
    </div>


    <!-- Page Content : profile edit details -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Profile : Details
            </h4>

            <form method="POST" action="{{ URL::route('profile-edit-details-post') }}" accept-charset="UTF-8" id="form_profile_details_edit" enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}


                @include($frontend_template_name.'.layouts.page_header')

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="username">Username<span class="required"> * </span></label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('username', !empty(old('username'))?Purifier::clean(old('username')): $userProfile['username'], "form-control editable_field", [ "disabled"=>"disabled" ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="email">Email<span class="required"> * </span></label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('email', !empty(old('email'))? Purifier::clean(old('email')) : ( !$viewFuncs->wrpIsFakeEmail($userProfile['email']) ?
                        $userProfile['email'] : 'fake'), "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
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


                @if( !$viewFuncs->wrpIsFakeEmail($userProfile['email']) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label">Password</label>
                        <div class="col-12 col-sm-8">
                            <button type="button" class=" btn btn-outline-primary" onclick="javascript: frontendProfile.generatePassword() ; return false; ">&nbsp;
                                <i class="fa fa-universal-access"></i>&nbsp;Generate
                                Password
                            </button>
                        </div>
                    </div>
                @endif

                <div class="form-row mb-3 {{ in_array('first_name', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="first_name">First name</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('first_name', !empty(old('first_name'))? Purifier::clean(old('first_name')): $userProfile['first_name'], "form-control editable_field",[
                        "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3 {{ in_array('last_name', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="last_name">Last name</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('last_name', !empty(old('last_name'))? Purifier::clean(old('last_name')): $userProfile['last_name'], "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3 {{ in_array('phone', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="phone">Phone</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('phone', !empty(old('phone'))? Purifier::clean(old('phone')): $userProfile['phone'], "form-control editable_field", [ "maxlength"=>"50",
                        "autocomplete"=>"off"  ] ) !!}
                    </div>
                </div>


                <div class="form-row mb-3 {{ in_array('website', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="website">Website</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('website', !empty(old('website'))? Purifier::clean(old('website')): $userProfile['website'], "form-control editable_field", [
                        "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                    </div>
                </div>

                <div class="form-row mb-3 {{ in_array('sex', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="sex">Sex</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->select('sex', $userSexLabelValueArray, !empty(old('sex'))?old('sex'): !empty($userProfile['sex']) ? $userProfile['sex'] : '', "form-control editable_field chosen_select_box",  ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Sex- "]) !!}
                        <input type="text" id="sex" name=sexstatus" value="{{ isset($userProfile['sex']) ? $userProfile['sex'] : ''}}"
                               style="visibility: hidden; width: 1px; height: 1px">
                    </div>
                </div>

                <div class="form-row mb-3 {{ in_array('notes', $errorFieldsArray) ? 'validation_error' : '' }}">
                    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="notes_container">Notes</label>
                    <div class="col-12 col-sm-12 col-md-8">
                        {!! $viewFuncs->textarea('notes_container', !empty(old('notes'))? Purifier::clean(old('notes')): !empty($userProfile['notes']) ? $userProfile['notes'] : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
                        {!! $viewFuncs->textarea('notes', isset($vote->notes) ? $vote->notes : '', "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
                    </div>
                </div>


                @if(!empty($account_register_details_text))
                    <div class="form-row mb-3">
                        <fieldset class="notes-block text-muted">
                            <legend class="notes-blocks">Notes</legend>
                            {!! $account_register_details_text !!}
                        </fieldset>
                    </div>
                @endif


                <div class="form-row mb-3">

                    <fieldset class="notes-block text-muted">
                        <legend class="notes-blocks">Address</legend>

                        <div class="form-row mb-3 {{ in_array('address_line1', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="address_line1">Line 1</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('address_line1', !empty(old('address_line1'))? Purifier::clean(old('address_line1')): $userProfile['address_line1'],
                                "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('address_city', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="address_city">City</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('address_city', !empty(old('address_city'))? Purifier::clean(old('address_city')): $userProfile['address_city'],
                                "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('address_state', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="address_state">State</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('address_state', !empty(old('address_state'))? Purifier::clean(old('address_state')): $userProfile['address_state'],
                                "form-control editable_field", [ "maxlength"=>"5", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('address_postal_code', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="address_postal_code">Postal code</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('address_postal_code', !empty(old('address_postal_code'))? Purifier::clean(old('address_postal_code')): $userProfile['address_postal_code'],
                                "form-control editable_field", [ "maxlength"=>"5", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('address_country_code', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="address_country_code">Country code</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('address_country_code', !empty(old('address_country_code'))? Purifier::clean(old('address_country_code')): $userProfile['address_country_code'],
                                "form-control editable_field", [ "maxlength"=>"2", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>

                    </fieldset>




                    <fieldset class="notes-block text-muted">
                        <legend class="notes-blocks">Shipping address</legend>

                        <div class="form-row mb-3 {{ in_array('shipping_address_line1', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_line1">Line 1</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('shipping_address_line1', !empty(old('shipping_address_line1'))? Purifier::clean(old('shipping_address_line1')): $userProfile['shipping_address_line1'],
                                "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('shipping_address_city', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_city">City</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('shipping_address_city', !empty(old('shipping_address_city'))? Purifier::clean(old('shipping_address_city')): $userProfile['shipping_address_city'],
                                "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('shipping_address_state', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_state">State</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('shipping_address_state', !empty(old('shipping_address_state'))? Purifier::clean(old('shipping_address_state')): $userProfile['shipping_address_state'],
                                "form-control editable_field", [ "maxlength"=>"5", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('shipping_address_postal_code', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_postal_code">Postal code</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('shipping_address_postal_code', !empty(old('shipping_address_postal_code'))? Purifier::clean(old('shipping_address_postal_code')): $userProfile['shipping_address_postal_code'],
                                "form-control editable_field", [ "maxlength"=>"5", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>


                        <div class="form-row mb-3 {{ in_array('shipping_address_country_code', $errorFieldsArray) ? 'validation_error' : '' }}">
                            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_country_code">Country code</label>
                            <div class="col-12 col-sm-8">
                                {!! $viewFuncs->text('shipping_address_country_code', !empty(old('shipping_address_country_code'))? Purifier::clean(old('shipping_address_country_code')): $userProfile['shipping_address_country_code'],
                                "form-control editable_field", [ "maxlength"=>"2", "autocomplete"=>"off"  ] ) !!}
                            </div>
                        </div>

                    </fieldset> <!-- fieldset="Shipping address" -->

                    <fieldset class="notes-block text-muted">
                        <legend class="notes-blocks">Subscriptions</legend>




                    </fieldset> <!-- fieldset="Subscriptions" -->

                </div>




                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->text('created_at', isset($userProfile['created_at']) ? $viewFuncs->getFormattedDateTime($userProfile['created_at']) : '', "form-control", [
                        "disabled"=> "disabled" ] ) !!}
                    </div>
                </div>


                @if(isset($userProfile['updated_at']))
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('updated_at', isset($userProfile['updated_at']) ? $viewFuncs->getFormattedDateTime($userProfile['updated_at']) : '', "form-control",
                            [ "disabled"=> "disabled" ] ) !!}
                        </div>
                    </div>
                @endif



                <div class="form-row mb-3 float-right">
                    <div class="row btn-group editor_btn_group ">
                        <button type="submit" class="btn btn-primary" style=""><span
                                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
                        </button>&nbsp;&nbsp;
                        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('profile-view') }}'"
                                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>


        </section> <!-- class="card-body" -->
    </div> <!-- class="card-body" -->

    <!-- /.page-wrapper Page Content : profile edit details -->



@endsection


@section('scripts')

    <script src="{{ asset('js/'.$frontend_template_name.'/profile.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor("notes_container", "notes", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ProfileUserDetailsRequest', '#form_profile_details_edit'); !!}


    <script>
        /*<![CDATA[*/

        var frontendProfile = new frontendProfile('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendProfile.onFrontendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection