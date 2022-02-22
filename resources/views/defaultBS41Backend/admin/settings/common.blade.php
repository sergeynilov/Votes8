@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_common_settings_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}
	<input type="hidden" id="tab_name_to_submit_common_settings" name="tab_name_to_submit" value="">


	<div class="form-row mb-3 {{ in_array('common_settings_site_name', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_site_name">Site name<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_site_name', old( 'common_settings_site_name', $common_settings_site_name) ,
			'form-control editable_field', ["maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('common_settings_site_heading', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_site_heading">Site heading<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_site_heading', old('common_settings_site_heading', $common_settings_site_heading), 'form-control editable_field', [	"maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('common_settings_site_subheading', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_site_subheading">Site subheading<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_site_subheading', old('common_settings_site_subheading', $common_settings_copyright_text), 'form-control editable_field', [	"maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('common_settings_copyright_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_copyright_text">Copyright text<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_copyright_text', old('common_settings_copyright_text', $common_settings_copyright_text), 'form-control editable_field', [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('common_settings_contact_us_email', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_contact_us_email">Contact us email<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_contact_us_email', old('common_settings_contact_us_email', $common_settings_contact_us_email),
			'form-control editable_field', [ 	"maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="form-row mb-3 {{ in_array('common_settings_contact_us_phone', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_contact_us_phone">Contact us phone<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_contact_us_phone', old('common_settings_contact_us_phone', $common_settings_contact_us_phone), 'form-control editable_field', [	"maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="form-row mb-3 {{ in_array('common_settings_noreply_email', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_noreply_email">Noreply email<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('common_settings_noreply_email', old('common_settings_noreply_email', $common_settings_noreply_email), 'form-control editable_field', [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="form-row mb-3 {{ in_array('common_settings_support_signature', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="common_settings_support_signature">Support signature<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->textarea('common_settings_support_signature_container', old('common_settings_support_signature_container', $common_settings_support_signature), "form-control editable_field ", [
			"rows"=>"3", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('common_settings_support_signature', old('common_settings_support_signature_container', $common_settings_support_signature), "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('common_settings') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>
	
</form>
