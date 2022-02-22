@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($pageContent->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($pageContent->id) ? $pageContent->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('title', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="title">Title<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('title', isset($pageContent->title) ? $pageContent->title : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>

@if(isset($pageContent->id))
    <div class="form-row mb-3 {{ in_array('slug', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="slug">Slug</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('slug', isset($pageContent->slug) ? $pageContent->slug : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="form-row mb-3 {{ in_array('content_shortly', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="content_shortly">Content shortly</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('content_shortly', isset($pageContent->content_shortly) ? $pageContent->content_shortly : '', "form-control editable_field", [
        "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('content', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="content">Content<span class="required"> * </span></label>
    <div class="col-12 col-sm-12 col-md-8">
        {!! $viewFuncs->textarea('content_container', isset($pageContent->content) ? $pageContent->content : '', "form-control editable_field", [ "rows"=>"5", "cols"=> 120, "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('content', isset($pageContent->content) ? $pageContent->content : '', "form-control editable_field", [  "rows"=>"1", "cols"=>
        120, "autocomplete"=>"off", "style"=>"visibility: hidden ;" ] ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('published', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_published">Published<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_published', $pageContentPublishedValueArray, isset($pageContent->published) ? $pageContent->published : '', "form-control editable_field chosen_select_box
        ", ['onchange'=>"javascript:chosenSelectionOnChange('published'); ",'data-placeholder'=>" -Select Published- "] ) !!}
        <input type="text" id="published" name="published" value="{{ isset($pageContent->published) ? $pageContent->published : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('page_type', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_page_type">Page Type<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_page_type', $pageContentPageTypeValueArray, isset($pageContent->page_type) ? $pageContent->page_type : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('page_type'); ",'data-placeholder'=>" -Select Page Type- "]
         ) !!}
        <input type="text" id="page_type" name="page_type" value="{{ isset($pageContent->page_type) ? $pageContent->page_type : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('is_featured', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_featured">Is Featured<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_featured', $pageContentIsFeaturedValueArray, isset($pageContent->is_featured) ? $pageContent->is_featured : '', "form-control editable_field chosen_select_box
       ", ['onchange'=>"javascript:chosenSelectionOnChange('is_featured'); ",'data-placeholder'=>" -Select Is Featured- "] ) !!}
        <input type="text" id="is_featured" name="is_featured" value="{{ isset($pageContent->is_featured) ? $pageContent->is_featured : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('is_homepage', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_homepage">Is Homepage<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_homepage', $pageContentIsHomepageValueArray, isset($pageContent->is_homepage) ? $pageContent->is_homepage : '', "form-control
        editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('is_homepage'); ",'data-placeholder'=>" -Select Is Homepage- "] ) !!}
        <input type="text" id="is_homepage" name="is_homepage" value="{{ isset($pageContent->is_homepage) ? $pageContent->is_homepage : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('image', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Image</label>
    <div class="col-12 col-sm-8">

        @if(isset($pageContent->id))
            <?php $pageContentImagePropsAttribute = $pageContent->getPageContentImagePropsAttribute();?>
            @if(isset($pageContentImagePropsAttribute['image_url']) )
                <a class="a_link" target="_blank" href="{{ $pageContentImagePropsAttribute['image_url'] }}">
                    <img class="image_preview" src="{{ $pageContentImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $pageContent->name }}">
                </a><br>
                {!! Purifier::clean($pageContentImagePropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="image" name="image">
        </div>
    </div>
</div>


<div class="form-row mb-3 {{ in_array('source_type', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="source_type">Source type</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('source_type', isset($pageContent->source_type) ? $pageContent->source_type : '', "form-control editable_field", [ "maxlength"=>"20",
        "autocomplete"=>"off" ] ) !!}
    </div>
</div>
<div class="form-row mb-3 {{ in_array('source_url', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="source_url">Source url</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('source_url', isset($pageContent->source_url) ? $pageContent->source_url : '', "form-control editable_field", [ "maxlength"=>"255",
        "autocomplete"=>"off" ] ) !!}
    </div>
</div>


@if(isset($pageContent->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($pageContent->created_at) ? $viewFuncs->getFormattedDateTime($pageContent->created_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($pageContent->updated_at) ? $viewFuncs->getFormattedDateTime($pageContent->updated_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendPageContent.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ url('/admin/page-contents') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>