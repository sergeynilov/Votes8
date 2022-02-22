@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_users_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}
	<input type="hidden" id="tab_name_to_submit_users" name="tab_name_to_submit" value="">

	<div class="form-row mb-3 {{ in_array('verification_token_days_expired', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="users_verification_token_days_expired">Users verification token days expired<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('users_verification_token_days_expired', old("users_verification_token_days_expired", $users_verification_token_days_expired),
			'form-control editable_field', [
			"maxlength"=>"3", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<fieldset class="blocks text-muted">
		<legend class="blocks">Social media authorization</legend>
		<div class="form-row mb-3 {{ in_array('allow_facebook_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_facebook_authorization">Allow facebook authorization</label>
			<div class="col-12 col-sm-8 ">
				{!! $viewFuncs->showStylingCheckbox('users_allow_facebook_authorization', 'Y', ( !empty($users_allow_facebook_authorization) and $users_allow_facebook_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

		<div class="form-row mb-3 {{ in_array('allow_google_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_google_authorization">Allow google authorization</label>
			<div class="col-12 col-sm-8">
				{!! $viewFuncs->showStylingCheckbox('users_allow_google_authorization', 'Y', ( !empty($users_allow_google_authorization) and $users_allow_google_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

		<div class="form-row mb-3 {{ in_array('allow_github_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_github_authorization">Allow github authorization</label>
			<div class="col-12 col-sm-8">
				{!! $viewFuncs->showStylingCheckbox('users_allow_github_authorization', 'Y', ( !empty($users_allow_github_authorization) and $users_allow_github_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

		<div class="form-row mb-3 {{ in_array('allow_linkedin_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_linkedin_authorization">Allow linkedin authorization</label>
			<div class="col-12 col-sm-8">
				{!! $viewFuncs->showStylingCheckbox('users_allow_linkedin_authorization', 'Y', ( !empty($users_allow_linkedin_authorization) and $users_allow_linkedin_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

		<div class="form-row mb-3 {{ in_array('allow_twitter_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_twitter_authorization">Allow twitter authorization</label>
			<div class="col-12 col-sm-8">
				{!! $viewFuncs->showStylingCheckbox('users_allow_twitter_authorization', 'Y', ( !empty($users_allow_twitter_authorization) and $users_allow_twitter_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

		<div class="form-row mb-3 {{ in_array('allow_instagram_authorization', $errorFieldsArray) ? 'validation_error' : '' }}">
			<label class="col-12 col-sm-4 col-form-label" for="users_allow_instagram_authorization">Allow instagram authorization</label>
			<div class="col-12 col-sm-8">
				{!! $viewFuncs->showStylingCheckbox('users_allow_instagram_authorization', 'Y', ( !empty($users_allow_instagram_authorization) and $users_allow_instagram_authorization == 'Y'),
				 '', []  ) !!}
			</div>
		</div>

	</fieldset>

</form>


<fieldset class="blocks text-muted">
	<legend class="blocks">Files sent to user on registration</legend>

	<div class="row p-2" id="div_files_on_registration_upload_image">

		<form action="upload" id="upload_file_on_registration" enctype="multipart/form-data">
			<div class="form-row  p-2 mb-3">
				<div class="col-12 col-sm-6 p-0 mr-3">
					<label for="fileupload" class="col-form-label">Upload&nbsp;file:&nbsp;</label>
				</div>
				<div class="col-12 col-sm-6 p-0 ml-3 mb-1">
					<input id="fileupload" type="file" class="file_on_registration_fileupload" name="files[]">
				</div>
			</div>
			<input type="hidden" id="hidden_selected_file_on_registration" name="hidden_selected_file_on_registration">
			{{ csrf_field() }}
		</form>
		
	</div>


	<div id="div_save_upload_image" class="row bordered p-3 ml-3" style="display: none">

		<div class="card" style="width: 100%;">

			<img class="" id="img_preview_image" alt="Preview" title="Preview" src="/images/spacer.png" width="1" height="1">
			<div class="card-body">
				<div class="row">
					<div id="img_preview_image_info" class="col-sm-12"></div>
					<div class="col-sm-12 p-0 m-0 alert alert-warning pl-5 pr-5" style="display: none" id="div_info_message" role="alert">
					</div>
				</div>

				<div class="row">
					<div id="div_show_votes_results" class="m-3">
						<button onclick="javascript:backendSettings.UploadFileOnRegistration()" class="a_link small btn btn-primary">
							<span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span>
							&nbsp;Save
						</button>
					</div>

					<div id="div_hide_votes_results" class="m-3">
						<button onclick="javascript:backendSettings.CancelUploadFileOnRegistration()" class="a_link small btn btn-inverse">
							<span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
						</button>
					</div>
				</div>

			</div>
		</div>

	</div> <!-- div_save_upload_image -->



	<div id="div-file-on-registrations" class="row p-2"></div>

</fieldset>


<div class="row float-right" style="padding: 0; margin: 0;">
	<div class="row btn-group editor_btn_group">
		<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('users') ; return false; " style=""><span
					class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
		</button>&nbsp;&nbsp;
		<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
		        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
		</button>&nbsp;&nbsp;
	</div>
</div>
