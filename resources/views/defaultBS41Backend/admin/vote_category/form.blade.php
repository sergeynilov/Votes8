@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

@if(isset($voteCategory))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" id="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($voteCategory->id) ? $voteCategory->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($voteCategory->name) ? $voteCategory->name : '', "form-control editable_field", [ "maxlength"=>"255",
        "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('active', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_active">Active<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_active', $voteCategoryStatusValueArray, isset($voteCategory->active) ? $voteCategory->active : '', "form-control editable_field chosen_select_box",  ['onchange'=>"javascript:chosenSelectionOnChange('active'); ",'data-placeholder'=>" -Select Active- "]) !!}
        <input type="text" id="active" name="active" value="{{ isset($voteCategory->active) ? $voteCategory->active : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('in_subscriptions', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_in_subscriptions">In Subscriptions</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_in_subscriptions', $voteCategoryInSubscriptionValueArray, isset($voteCategory->in_subscriptions) ? $voteCategory->in_subscriptions : '',
        "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('in_subscriptions'); ",'data-placeholder'=>" -Select In Subscriptions- "] ) !!}
        <input type="text" id="in_subscriptions" name="in_subscriptions" value="{{ isset($voteCategory->in_subscriptions) ? $voteCategory->in_subscriptions : ''}}"
               style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


{{--<div class="form-row p-2 mb-3">--}}
{{--    <label for="vote_category_meta_description" class="col-12 col-form-label">Meta description--}}
{{--        <small>( if empty, then "site name" : "vote category title" would be used)</small>--}}
{{--    </label>--}}
{{--    <div class="col-9">--}}
{{--        {!! $viewFuncs->text( 'vote_category_meta_description', isset($voteCategory->meta_description) ? $voteCategory->meta_description : '' , "form-control editable_field", [ "maxlength"=>"255" ] ) !!}--}}
{{--    </div>--}}
{{--</div>--}}
<div class="form-row mb-3 {{ in_array('meta_description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="meta_description">Meta description</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('meta_description', isset($voteCategory->meta_description) ? $voteCategory->meta_description : '', "form-control editable_field", [ "maxlength"=>"255",
        "autocomplete"=>"off" ] ) !!}
    </div>
</div>




@if(isset($voteCategory))
<div class="form-row mb-3 ">
    <fieldset class="notes-block text-muted">
        <legend class="notes-blocks">Meta Keywords</legend>
        <div id="div_meta_keywords"></div>
    </fieldset>
</div>
@endif


@if(isset($voteCategory))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($voteCategory->created_at) ? $viewFuncs->getFormattedDateTime($voteCategory->created_at) : '', "form-control", ["readonly"=> "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($voteCategory->updated_at) ? $viewFuncs->getFormattedDateTime($voteCategory->updated_at) : '', "form-control", ["readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendVoteCategory.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/vote-categories') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
