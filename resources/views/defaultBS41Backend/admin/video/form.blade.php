@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($video->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($video->id) ? $video->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($video->name) ? $video->name : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


@if(isset($video->id))
    <div class="form-row mb-3 {{ in_array('slug', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="slug">Slug</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('slug', isset($video->slug) ? $video->slug : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif



<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="description_container">Description</label>
    <div class="col-12 col-sm-12 col-md-8">
        {!! $viewFuncs->textarea('description_container', isset($video->description) ? $video->description : '', "form-control editable_field ", [ "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('description', isset($video->description) ? $video->description : '', "form-control editable_field", [ "rows"=>"5", "cols"=>
         120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('video', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Image</label>
    <div class="col-12 col-sm-8">

        @if(isset($video->id))
            <?php $videoImagePropsAttribute = $video->getVideoImagePropsAttribute();?>
        <?php echo '<pre>$videoImagePropsAttribute::'.print_r($videoImagePropsAttribute,true).'</pre>';  ?>
            @if(isset($videoImagePropsAttribute['video_url']) )
                <a class="a_link" target="_blank" href="{{ $videoImagePropsAttribute['video_url'] }}">
                    <img class="video_preview" src="{{ $videoImagePropsAttribute['video_url'] }}{{  "?dt=".time()  }}" alt="{{ $video->name }}">
                </a><br>
                {!! Purifier::clean($videoImagePropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="video" name="video">
        </div>
    </div>
</div>




<div class="form-row mb-3 {{ in_array('ordering', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="ordering">Ordering</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('ordering', isset($video->ordering) ? $video->ordering : '', "form-control", [ "placeholder"=>"Enter integer value.", "autocomplete"=>"off" ] ) !!}
    </div>
</div>

@if(isset($video->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($video->created_at) ? $viewFuncs->getFormattedDateTime($video->created_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($video->updated_at) ? $viewFuncs->getFormattedDateTime($video->updated_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendVideo.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/videos') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
