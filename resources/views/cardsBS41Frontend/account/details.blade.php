@extends($frontend_template_name.'.layouts.frontend')

@section('content')

        @inject('viewFuncs', 'App\library\viewFuncs')
        <?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

        <!-- Page Content : account register details -->
        <div class="row bordered card" id="page-wrapper">

            <section class="card-body">

                <h1 class="text-center">
                    @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
                </h1>

                <h4 class="card-title">
                    <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                    Registration : Details
                </h4>

                <div class="row ml-1 mb-3">
                    {{ Breadcrumbs::render('account-register', 'Registration : Details') }}
                </div>

                <form method="POST" action="{{ URL::route('account-register-details-post') }}" accept-charset="UTF-8" id="form_account_details_edit" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    @include($frontend_template_name.'.account.register_steps', ['current_step'=> 1])

                    @include($frontend_template_name.'.layouts.page_header')


                    <div class="form-row mb-3 {{ in_array('username', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="username">Username<span class="required"> * </span></label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('username', !empty(old('username'))? Purifier::clean(old('username')): !empty($defaultValues['username']) ? $defaultValues['username'] : '', "form-control editable_field", [
                            "maxlength"=>"100", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('email', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="email">Email<span class="required"> * </span></label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('email', !empty(old('email'))? Purifier::clean(old('email')) : !empty($defaultValues['email']) ? $defaultValues['email'] : '', "form-control editable_field", [
                            "maxlength"=>"100", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('password', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="password">Password<span class="required"> * </span></label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->password('password', "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('password_conf', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="password_conf">Confirm Password<span class="required"> * </span></label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->password('password_conf', "form-control editable_field", [ "maxlength"=>"12", "autocomplete"=>"off" ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('first_name', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="first_name">First name</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('first_name', !empty(old('first_name'))? Purifier::clean(old('first_name')): ( !empty($defaultValues['first_name']) ? $defaultValues['first_name'] : '' ), "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('last_name', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="last_name">Last name</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('last_name', !empty(old('last_name'))? Purifier::clean(old('last_name')): ( !empty($defaultValues['last_name']) ? $defaultValues['last_name'] : '' ), "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('phone', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="phone">Phone</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('phone', !empty(old('phone'))? Purifier::clean(old('phone')): ( !empty($defaultValues['phone']) ? $defaultValues['phone'] : '' ), "form-control editable_field", [
                            "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('website', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="website">Website</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('website', !empty(old('website'))? Purifier::clean(old('website')): ( !empty($defaultValues['website']) ? $defaultValues['website'] : '' ), "form-control editable_field ", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('sex', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-4 col-form-label" for="chosen_sex">Sex</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->select('chosen_sex', $userSexLabelValueArray, !empty(old('sex'))? Purifier::clean(old('sex')): ( !empty($defaultValues['sex']) ? $defaultValues['sex'] : '' ), "form-control editable_field chosen_select_box",  ['onchange'=>"javascript:chosenSelectionOnChange('sex'); ",'data-placeholder'=>" -Select Sex- "]) !!}
                            <input type="text" id="sex" name="sex" value="" style="visibility: hidden; width: 1px; height: 1px">
                            {{--{!! $viewFuncs->select('chosen_vote_category_id', $voteCategoriesSelectionArray, isset($vote->vote_category_id) ? $vote->vote_category_id : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('vote_category_id'); ",'data-placeholder'=>" -Select Vote Category- "] ) !!}--}}
                            {{--<input type="text" id="vote_category_id" name="vote_category_id" value="{{ isset($vote->vote_category_id) ? $vote->vote_category_id : ''}}" style="visibility: hidden; width: 1px; height: 1px">--}}

                        </div>
                    </div>


                    <div class="form-row mb-3 {{ in_array('notes', $errorFieldsArray) ? 'validation_error' : '' }}">
                        <label class="col-12 col-sm-12 col-md-4 col-form-label" for="notes_container">Notes</label>
                        <div class="col-12 col-sm-12 col-md-8">
                            {!! $viewFuncs->textarea('notes_container', !empty(old('notes'))?old('notes'): ( !empty($defaultValues['notes']) ? Purifier::clean($defaultValues['notes']) : '' ), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
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


                    <div class="row">
                        <div class="col-8 col-sm-8">
                            <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('account-register-cancel') }}'"
                                    style=""><span class="btn-label"></span> &nbsp;Cancel
                            </button>&nbsp;&nbsp;
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="btn-group float-right mt-2" role="group">
                                <button type="submit" class=" btn btn-primary float-right"><span class="btn-label"></span> &nbsp;Next
                                </button>
                            </div>
                        </div>
                    </div>


                </form>


            </section> <!-- class="card-body" -->


            <!-- /.page-wrapper Page Content : account register details -->

        </div>

@endsection


@section('scripts')

    <script src="{{ asset('js/cardsBS41Frontend/register.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "notes_container", "notes", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\VoteCategoryRequest', '#account_register'); !!}


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