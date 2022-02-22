
@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($chat))
	<div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('id', isset($chat->id) ? $chat->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
		</div>
	</div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->text('name', isset($chat->name) ? $chat->name : '', "form-control editable_field", [ "maxlength"=>"255",
		"autocomplete"=>"off" ] ) !!}
	</div>
</div>


<div class="form-row mb-3 {{ in_array('status', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-4 col-form-label" for="chosen_status">Status<span class="required"> * </span></label>
	<div class="col-12 col-sm-8">
		{!! $viewFuncs->select('chosen_status', $chatStatusValueArray, isset($chat->status) ? $chat->status : '', "form-control editable_field chosen_select_box",  ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Status- "]) !!}
		<input type="text" id="status" name="status" value="{{ isset($chat->status) ? $chat->status : ''}}" style="visibility: hidden; width: 1px; height: 1px">
	</div>
</div>



<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
	<label class="col-12 col-sm-12 col-md-4 col-form-label" for="description_container">Description<span class="required"> * </span></label>
	<div class="col-12 col-sm-12 col-md-8">
		{!! $viewFuncs->textarea('description_container', isset($chat->description) ? $chat->description : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
		{!! $viewFuncs->textarea('description', isset($chat->description) ? $chat->description : '', "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
	</div>
</div>


@if(isset($chatCreator))
	<div class="form-row mb-3 {{ in_array('chat_creator', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label">Creator</label>
		<div class="col-12 col-sm-8">
			<a href="{{ route('public-profile-view', $chatCreator->id ) }}" target="_blank">
				{{ $chatCreator->username }}
			</a>
		</div>
	</div>
@endif


@if(isset($chat))
	<div class="form-row mb-3">
		<label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('created_at', isset($chat->created_at) ? $viewFuncs->getFormattedDateTime($chat->created_at) : '', "form-control", ["readonly"=> "readonly" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3">
		<label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('updated_at', isset($chat->updated_at) ? $viewFuncs->getFormattedDateTime($chat->updated_at) : '', "form-control", ["readonly"=> "readonly" ] ) !!}
		</div>
	</div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
	<div class="row btn-group editor_btn_group">
		<button type="button" class="btn btn-primary" onclick="javascript: backendChat.onSubmit() ; return false; " style=""><span
					class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
		</button>&nbsp;&nbsp;
		<button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ url('/admin/chats') }}'"
		        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
		</button>&nbsp;&nbsp;
	</div>
</div>
