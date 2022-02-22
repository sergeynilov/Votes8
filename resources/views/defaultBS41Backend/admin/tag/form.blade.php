@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($tag))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($tag->id) ? $tag->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($tag->name) ? $tag->name : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off", ( isset($tag) ? "readonly" : "" )=> ( isset($tag) ? "readonly" : "" )  ] ) !!}
    </div>
</div>


@if(isset($tag))
    <div class="form-row mb-3 {{ in_array('slug', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="slug">Slug<span class="required"> * </span></label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('slug', isset($tag->slug) ? $tag->slug : '', "form-control editable_field", [ "maxlength"=>"255", "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('order_column', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="order_column">Order column</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('order_column', isset($tag->order_column) ? $tag->order_column : '', "form-control", [ "placeholder"=>"Enter integer value.", "autocomplete"=>"off", ( isset($tag) ? "readonly" : "" )=> ( isset($tag) ? "readonly" : "" ) ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="description_container">Description</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->textarea('description_container', isset($tagDetail->description) ? $tagDetail->description : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120, "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('description', isset($tagDetail->description) ? $tagDetail->description : '', "form-control editable_field ", [ "rows"=>"5",
        "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden ;" ] ) !!}
    </div>
</div>




<div class="form-row mb-3 {{ in_array('image', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Image</label>
    <div class="col-12 col-sm-8">

        @if(isset($tagDetail->id))
            <?php $tagDetailImagePropsAttribute = $tagDetail->getTagDetailImagePropsAttribute();?>
            @if(isset($tagDetailImagePropsAttribute['image_url']) )
                <a class="a_link" target="_blank" href="{{ $tagDetailImagePropsAttribute['image_url'] }}">
                    <img class="image_preview" src="{{ $tagDetailImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $tag->name }}" >
                </a><br>
                {!! Purifier::clean($tagDetailImagePropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="image" name="image">
        </div>
    </div>
</div>



@if(isset($tag))
    <div class="form-row mb-3 ">
        <fieldset class="notes-block text-muted">
            <legend class="notes-blocks">Meta Keywords</legend>
            <div id="div_meta_keywords"></div>
        </fieldset>
    </div>
@endif


@if(isset($tag))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($tag->created_at) ? $viewFuncs->getFormattedDateTime($tag->created_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($tag->updated_at) ? $viewFuncs->getFormattedDateTime($tag->updated_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">

        {{--@if(!isset($tag))--}}
        <button type="button" class="btn btn-primary" onclick="javascript: backendTag.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        {{--@else--}}
            {{--<div class="alert alert-warning mt-5" role="alert">--}}
                {{--Not Editable--}}
            {{--</div>--}}
        {{--@endif--}}


        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/tags') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>