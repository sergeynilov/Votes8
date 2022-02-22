@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($siteSubscription))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($siteSubscription->id) ? $siteSubscription->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($siteSubscription->name) ? $siteSubscription->name : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('active', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_active">Active<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_active', $siteSubscriptionStatusValueArray, isset($siteSubscription->active) ? $siteSubscription->active : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('active'); ",'data-placeholder'=>" -Select Active- "]
         ) !!}
        <input type="text" id="active" name="active" value="{{ isset($siteSubscription->active) ? $siteSubscription->active : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('vote_category_id', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_vote_category_id">Vote category</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_vote_category_id', $voteCategoryValueArray, isset($siteSubscription->vote_category_id) ? $siteSubscription->vote_category_id : '', "form-control
        editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('vote_category_id'); ",'data-placeholder'=>" -Select Vote Category- "] ) !!}
        <input type="text" id="vote_category_id" name="vote_category_id" value="{{ isset($siteSubscription->vote_category_id) ? $siteSubscription->vote_category_id : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


@if(isset($siteSubscription))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($siteSubscription->created_at) ? $viewFuncs->getFormattedDateTime($siteSubscription->created_at) : '', "form-control", [
            "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendSiteSubscription.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/site-subscriptions') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
