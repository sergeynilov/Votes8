@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($banner->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($banner->id) ? $banner->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('text', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="text">Text<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('text', isset($banner->text) ? $banner->text : '', "form-control editable_field", [ "maxlength"=>"20", "autocomplete"=>"off" ] ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('short_descr', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="short_descr">Short description</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('short_descr', isset($banner->short_descr) ? $banner->short_descr : '', "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off" ]
        ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('url', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="url">Url<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('url', isset($banner->url) ? $banner->url : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ]
        ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('active', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_active">Active<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_active', $bannerActiveValueArray, isset($banner->active) ? $banner->active : '', "form-control editable_field chosen_select_box
        ", ['onchange'=>"javascript:chosenSelectionOnChange('active'); ",'data-placeholder'=>" -Select Active- "] ) !!}
        <input type="text" id="active" name="active" value="{{ isset($banner->active) ? $banner->active : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('view_type', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_view_type">View Type<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_view_type', $bannerViewTypeValueArray, isset($banner->view_type) ? $banner->view_type : '', "form-control editable_field
        chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('view_type'); ",'data-placeholder'=>" -Select View Type- "]
         ) !!}
        <input type="text" id="view_type" name="view_type" value="{{ isset($banner->view_type) ? $banner->view_type : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('ordering', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="ordering">Ordering</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('ordering', isset($banner->ordering) ? $banner->ordering : '', "form-control", [ "placeholder"=>"Enter integer value.", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('logo', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Logo</label>
    <div class="col-12 col-sm-8">

<!--        --><?php //echo '<pre>$banner->id::'.print_r($banner->id,true).'</pre>';   ?>
        @if(isset($banner->id))
            <?php $bannerLogoPropsAttribute = $banner->getBannerLogoPropsAttribute(); ?>
            @if(isset($bannerLogoPropsAttribute['image_url']) )
                <a class="a_link" target="_blank" href="{{ $bannerLogoPropsAttribute['image_url'] }}">
                    <img class="image_preview" src="{{ $bannerLogoPropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $banner->name }}">
                </a><br>
                {!! Purifier::clean($bannerLogoPropsAttribute['file_info']) !!}
            @endif
        @endif


        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="logo" name="logo">
        </div>
        <p class="p-2"><small>Select image with size limit in 96px</small></p>
    </div>
</div>


@if(isset($banner->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($banner->created_at) ? $viewFuncs->getFormattedDateTime($banner->created_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendBanner.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ url('/admin/banners') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>