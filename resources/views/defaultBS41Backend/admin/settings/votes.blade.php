@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_votes_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}
	<input type="hidden" id="tab_name_to_submit_votes" name="tab_name_to_submit" value="-">

	{{--<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.showQuizQualityOptionsListing() ; return false; " style=""><span--}}
				{{--class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Load--}}
	{{--</button>&nbsp;&nbsp;--}}

	<div class="row">
			<fieldset class="blocks text-muted p-0 m-0">
				<legend class="blocks"><span class="legend_title">Quiz Quality Options</span></legend>
				<div id="div_settings_quiz_quality_options"></div>
			</fieldset>
	</div>


	<div class="form-row mb-3 {{ in_array('most_rating_quiz_quality_on_homepage', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="votes_most_rating_quiz_quality_on_homepage">Most rating quiz quality on homepage<span
					class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('votes_most_rating_quiz_quality_on_homepage', old("votes_most_rating_quiz_quality_on_homepage",$votes_most_rating_quiz_quality_on_homepage),
			 'form-control editable_field', ["maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="form-row mb-3 {{ in_array('most_votes_taggable_on_homepage', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="votes_most_votes_taggable_on_homepage">Most votes taggable on homepage<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('votes_most_votes_taggable_on_homepage', old("votes_most_votes_taggable_on_homepage",$votes_most_votes_taggable_on_homepage),
			'form-control editable_field', [ "maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('votes') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>


</form>
