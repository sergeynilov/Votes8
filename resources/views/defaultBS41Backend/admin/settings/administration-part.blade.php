@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_administration_part_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}
	<input type="hidden" id="tab_name_to_submit_administration_part" name="tab_name_to_submit" value="">

	<div class="form-row mb-3 {{ in_array('backend_per_page', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="administration_part_backend_per_page">Backend items per pagination<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('administration_part_backend_per_page', old("administration_part_backend_per_page",$administration_part_backend_per_page), 'form-control
			editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>



			<div class="form-row mb-3 {{ in_array('backend_todo_tasks_popup', $errorFieldsArray) ? 'validation_error' : '' }}">
				<label class="col-12 col-sm-4 col-form-label" for="administration_part_backend_todo_tasks_popup">Todo tasks popup</label>
				<div class="col-12 col-sm-8 ">
					{!! $viewFuncs->showStylingCheckbox('administration_part_backend_todo_tasks_popup', 'Y', ( !empty($administration_part_backend_todo_tasks_popup) and $administration_part_backend_todo_tasks_popup == 'Y'),
					 '', []  ) !!}
				</div>
			</div>



	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('administration_part') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>
</form>
