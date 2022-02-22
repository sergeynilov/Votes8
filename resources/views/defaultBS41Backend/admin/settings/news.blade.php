@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>


<form method="POST" action="{{ url('/admin/settings') }}" accept-charset="UTF-8" id="form_settings_news_edit" enctype="multipart/form-data">
	@method('PUT')
	{!! csrf_field() !!}
	<input type="hidden" id="tab_name_to_submit_news" name="tab_name_to_submit" value="-">

	<div class="form-row mb-3 {{ in_array('news_latest_news_on_homepage', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_latest_news_on_homepage">Latest news on homepage<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('news_latest_news_on_homepage', old('news_latest_news_on_homepage', $news_latest_news_on_homepage),
			'form-control editable_field', [ "maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('news_infinite_scroll_rows_per_scroll_step', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_infinite_scroll_rows_per_scroll_step">Infinite scroll rows per scroll step<span
					class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('news_infinite_scroll_rows_per_scroll_step', old("news_infinite_scroll_rows_per_scroll_step", $news_infinite_scroll_rows_per_scroll_step),
			'form-control editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('news_similar_news_on_limit', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_similar_news_on_limit">Similar news on limit<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('news_similar_news_on_limit', old("news_similar_news_on_limit",$news_similar_news_on_limit), 'form-control editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('news_other_news_on_limit', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_other_news_on_limit">Other news on limit<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('news_other_news_on_limit', old("news_other_news_on_limit",$news_other_news_on_limit), 'form-control editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('news_feed_items_on_limit', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_feed_items_on_limit">Feed items on limit<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('news_feed_items_on_limit', old("news_feed_items_on_limit", $news_feed_items_on_limit), 'form-control editable_field', [
			"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>


	<div class="form-row mb-3 {{ in_array('news_feed_import_creator_id', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="news_feed_import_creator_id">Feed import creator id<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('chosen_news_feed_import_creator_id', $usersArray, old("news_feed_import_creator_id", $news_feed_import_creator_id), "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('news_feed_import_creator_id'); ",'data-placeholder'=>" -Select news feed import creator- "]
	) !!}
			<input type="text" id="news_feed_import_creator_id" name="news_feed_import_creator_id"
			       value="{{ old("news_feed_import_creator_id", $news_feed_import_creator_id) }}" style="visibility: hidden; width: 1px; height: 1px">
		</div>
	</div>


	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('news') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>

</form>
