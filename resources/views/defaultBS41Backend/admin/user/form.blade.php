@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($user))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($user->id) ? $user->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('username', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="username">Username</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('username', $user->username, "form-control", [ "maxlength"=>"255", "autocomplete"=>"off", "readonly"=> "readonly" ] ) !!}
    </div>
</div>



<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="email">Email</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('email', ( !$viewFuncs->wrpIsFakeEmail($user->email) ? $user->email : "fake" ), "form-control", [ "disabled"=>"disabled", "readonly"=>
        "readonly"
          ]
        ) !!}
    </div>
</div>


@if( !$viewFuncs->wrpIsFakeEmail($user->email) )
<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label">Password</label>
    <div class="col-12 col-sm-8">
        <button type="button" class=" btn btn-outline-primary" onclick="javascript: backendUser.generatePassword({{ $user->id }}) ; return false; ">&nbsp;<i class="fa fa-universal-access"></i>&nbsp;Generate Password</button>
    </div>
</div>
@endif

<div class="form-row mb-3 {{ in_array('status', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_status">Status<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_status', $userStatusValueArray, isset($user->status) ? $user->status : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Status- "] ) !!}
        <input type="text" id="status" name="status" value="{{ isset($user->status) ? $user->status : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label">Verified</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('verified', $viewFuncs->wrpGetUserVerifiedLabel($user->verified), "form-control", [ "disabled"=>"disabled", "readonly"=> "readonly"  ] ) !!}
    </div>
</div>
<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="activated_at">Activated at</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('activated_at', $viewFuncs->getFormattedDateTime($user->activated_at), "form-control ", [ "disabled"=>"disabled", "readonly"=>
        "readonly"  ] ) !!}
    </div>
</div>


<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label">Access</label>
    <div class="col-12 col-sm-8">
        {{ $viewFuncs->getUserDisplayAccessGroupsName($user->id) }}&nbsp;
        <button type="button" class=" btn btn-outline-primary" onclick="javascript: backendUser.editUserAccess({{ $user->id }}) ; return false; ">&nbsp;<i class="fa fa-group"></i>&nbsp;
            User's Access</button>
    </div>
</div>



@if(!empty($user->provider_name))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label">Provider name/Provider id</label>
        <div class="col-12 col-sm-8">
            {{ $user->provider_name }} / {{ $user->provider_id }}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('first_name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="first_name">First name</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('first_name', !empty(old('first_name'))?old('first_name'): $user->first_name, "form-control editable_field", [ "maxlength"=>"50",
        "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('last_name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="last_name">Last name</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('last_name', !empty(old('last_name'))?old('last_name'): $user->last_name, "form-control editable_field", [ "maxlength"=>"50",
        "autocomplete"=>"off"  ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('phone', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="phone">Phone</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('phone', !empty(old('phone'))?old('phone'): $user->phone, "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('website', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="website">Website</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('website', !empty(old('website'))?old('website'): $user->website, "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off"  ] ) !!}
    </div>
</div>



<div class="form-row mb-3 {{ in_array('avatar', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Avatar</label>
    <div class="col-12 col-sm-8">

        @if(isset($user->id))
            <?php $userAvatarPropsAttribute = $user->getUserAvatarPropsAttribute(); ?>
            @if(isset($userAvatarPropsAttribute['avatar_url']) )
                <a class="a_link" target="_blank" href="{{ $userAvatarPropsAttribute['avatar_url'] }}">
                    <img class="avatar_preview" src="{{ $userAvatarPropsAttribute['avatar_url'] }}{{  "?dt=".time()  }}" alt="{{ $user->name }}">
                </a><br>
                {!! Purifier::clean($userAvatarPropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="avatar" name="avatar">
        </div>
    </div>
</div>


<div class="form-row mb-3 {{ in_array('full_photo', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Full Photo</label>
    <div class="col-12 col-sm-8">

        @if(isset($user->id))
            <?php $userFullPhotoPropsAttribute = $user->getUserFullPhotoPropsAttribute(); ?>
            @if(isset($userFullPhotoPropsAttribute['full_photo_url']) )
                <a class="a_link" target="_blank" href="{{ $userFullPhotoPropsAttribute['full_photo_url'] }}">
                    <img class="full_photo_preview" src="{{ $userFullPhotoPropsAttribute['full_photo_url'] }}{{  "?dt=".time()  }}" alt="{{ $user->name }}">
                </a><br>
                {!! Purifier::clean($userFullPhotoPropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="full_photo" name="full_photo">
        </div>
    </div>
</div>




<div class="form-row mb-3 {{ in_array('sex', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="sex">Sex</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('sex', $userSexLabelValueArray, !empty(old('sex'))?old('sex'): !empty($user->sex) ? $user->sex : '', "form-control editable_field chosen_select_box",  ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Sex- "]) !!}
        <input type="text" id="sex" name=sexstatus" value="{{ isset($user->sex) ? $user->sex : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('notes', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="notes_container">Notes</label>
    <div class="col-12 col-sm-12 col-md-8">
        {!! $viewFuncs->textarea('notes_container', !empty(old('notes'))? Purifier::clean(old('notes')): !empty($user->notes) ? $user->notes : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('notes', isset($vote->notes) ? $vote->notes : '', "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
    </div>
</div>





@if(isset($user))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($user->created_at) ? $viewFuncs->getFormattedDateTime($user->created_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($user->updated_at) ? $viewFuncs->getFormattedDateTime($user->updated_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendUser.onSubmit() ; return false; " style="">
            <span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/users') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="div_user_access_modal"  aria-labelledby="user-access-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="user-access-modal-label">User's access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div id="div_user_access_results"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="javascript:backendUser.updateUserAccess( {{ $user->id }} ); return;"
                ><span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Update </button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>