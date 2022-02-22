@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_site_content_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}

	<input type="hidden" id="tab_name_to_submit_site_content" name="tab_name_to_submit" value="">
	<div class="form-row mb-3 {{ in_array('home_page_ref_items_per_pagination', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="site_content_home_page_ref_items_per_pagination">Home page items per pagination<span
					class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('site_content_home_page_ref_items_per_pagination', old("site_content_home_page_ref_items_per_pagination",
			$site_content_home_page_ref_items_per_pagination), 'form-control editable_field', [	"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('news_per_page', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="site_content_news_per_page">News per page<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('site_content_news_per_page', old("site_content_news_per_page", $site_content_news_per_page), 'form-control editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('account_register_details_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-12 col-md-4 col-form-label" for="site_content_account_register_details_text_container">Site content account register details text</label>
		<div class="col-12 col-sm-12 col-md-8">
			{!! $viewFuncs->textarea('site_content_account_register_details_text_container', old("site_content_account_register_details_text", $site_content_account_register_details_text), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('site_content_account_register_details_text', old("site_content_account_register_details_text", $site_content_account_register_details_text), "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('account_register_avatar_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-12 col-md-4 col-form-label" for="site_content_account_register_avatar_text_container">Site content account register avatar text</label>
		<div class="col-12 col-sm-12 col-md-8">
			{!! $viewFuncs->textarea('site_content_account_register_avatar_text_container', old("site_content_account_register_avatar_text",$site_content_account_register_avatar_text), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('site_content_account_register_avatar_text', old("site_content_account_register_avatar_text",$site_content_account_register_avatar_text), "form-control editable_field " , [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('account_register_subscriptions_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-12 col-md-4 col-form-label" for="site_content_account_register_subscriptions_text_container">Site content account subscriptions text</label>
		<div class="col-12 col-sm-12 col-md-8">
			{!! $viewFuncs->textarea('site_content_account_register_subscriptions_text_container', old("site_content_account_register_subscriptions_text",$site_content_account_register_subscriptions_text), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('site_content_account_register_subscriptions_text', old("site_content_account_register_subscriptions_text", $site_content_account_register_subscriptions_text), "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('account_register_confirm_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-12 col-md-4 col-form-label" for="site_content_account_register_confirm_text_container">Site content account register confirm text</label>
		<div class="col-12 col-sm-12 col-md-8">
			{!! $viewFuncs->textarea('site_content_account_register_confirm_text_container', old("site_content_account_register_confirm_text", $site_content_account_register_confirm_text), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('site_content_account_register_confirm_text', old("site_content_account_register_confirm_text", $site_content_account_register_confirm_text), "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('account_contacts_us_text', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-12 col-md-4 col-form-label" for="site_content_account_contacts_us_text_container">Site content account contacts us text</label>
		<div class="col-12 col-sm-12 col-md-8">
			{!! $viewFuncs->textarea('site_content_account_contacts_us_text_container', old("site_content_account_contacts_us_text", $site_content_account_contacts_us_text), "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
			{!! $viewFuncs->textarea('site_content_account_contacts_us_text', old("site_content_account_contacts_us_text", $site_content_account_contacts_us_text), "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
		</div>
	</div>

	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('site_content') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>

</form>