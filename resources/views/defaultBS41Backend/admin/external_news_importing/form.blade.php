@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if( isset($externalNewsImporting) and isset($externalNewsImporting->id) )
	<div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('id', isset($externalNewsImporting->id) ? $externalNewsImporting->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
		</div>
	</div>
@endif


<div class="form-row mb-3 {{ in_array('title', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="title">Title<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->text('title', isset($externalNewsImporting->title) ? $externalNewsImporting->title : '', "form-control editable_field", [ "maxlength"=>"100",
		"autocomplete"=>"off" ] ) !!}
	</div>
</div>

<div class="form-row mb-3 {{ in_array('url', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="url">Url<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->text('url', isset($externalNewsImporting->url) ? $externalNewsImporting->url : '', "form-control editable_field", [ "maxlength"=>"100",
		"autocomplete"=>"off" ] ) !!}
	</div>
</div>


<div class="form-row mb-3 {{ in_array('status', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="chosen_status">Status<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->select('chosen_status', $externalNewsImportingStatusValueArray, isset($externalNewsImporting->status) ? $externalNewsImporting->status : '', "form-control
		editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Status- "] ) !!}
		<input type="text" id="status" name="status" value="{{ isset($externalNewsImporting->status) ? $externalNewsImporting->status : ''}}" style="visibility: hidden; width: 1px; height: 1px">
	</div>
</div>


<div class="form-row mb-3 {{ in_array('import_image', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="chosen_import_image">Import image<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->select('chosen_import_image', $externalNewsImportingImportImageValueArray, isset($externalNewsImporting->import_image) ? $externalNewsImporting->import_image :
		'', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('import_image'); ",'data-placeholder'=>" -Select Import Image- "] ) !!}
		<input type="text" id="import_image" name="import_image" value="{{ isset($externalNewsImporting->import_image) ? $externalNewsImporting->import_image : ''}}" style="visibility: hidden; width:
		 1px; height: 1px">
	</div>
</div>


@if(isset($externalNewsImporting) and isset($externalNewsImporting->created_at) )
	<div class="form-row mb-3">
		<label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('created_at', isset($externalNewsImporting->created_at) ? $viewFuncs->getFormattedDateTime($externalNewsImporting->created_at) : '', "form-control", [
			"readonly"=> "readonly" ] ) !!}
		</div>
	</div>
@endif

@if(isset($externalNewsImporting) and isset($externalNewsImporting->updated_at) )
	<div class="form-row mb-3">
		<label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('updated_at', isset($externalNewsImporting->updated_at) ? $viewFuncs->getFormattedDateTime($externalNewsImporting->updated_at) : '', "form-control", [
			"readonly"=> "readonly" ] ) !!}
		</div>
	</div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
	<div class="row btn-group editor_btn_group">
		<button type="button" class="btn btn-primary" onclick="javascript: backendExternalNewsImporting.onSubmit() ; return false; " style=""><span
					class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
		</button>&nbsp;&nbsp;
		<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/external-news-importing') }}'"
		        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
		</button>&nbsp;&nbsp;
	</div>
</div>
