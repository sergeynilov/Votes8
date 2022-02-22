@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
<fieldset class="notes-block text-muted">
	<legend class="notes-blocks">Common</legend>
         {{--$page_orientation = 'P';/* P or Portrait (default) L or Landscape */--}}
	{{--$page_format = 'A4'; // A4  A6--}}
	{{--/* L', 'A4');--}}
	{{--$pdf->Cell(0, 0, 'A4 LANDSCAPE */--}}

	{{--// 'PDF_PAGE_FORMAT', 'A4'--}}
	{{--/* $pdf->AddPage('P', 'A5');--}}
	{{--$pdf->Cell(0, 0, 'A5 PORTRAIT */--}}

	{{--$page_lang = 'en';--}}
	{{--$page_unicode = true;--}}
	{{--$page_encoding = 'UTF-8';--}}
	{{--$page_margins = array(5, 5, 5, 8);--}}
	{{----}}


	<div class="form-row mb-3 {{ in_array('option_page_margin_left', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_page_margin_left">Page margin left in px</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('option_page_margin_left', $page_margin_left, "form-control editable_field", ["maxlength"=>"3", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('option_page_margin_top', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_page_margin_top">Page margin top in px</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('option_page_margin_top', $page_margin_top, "form-control editable_field", ["maxlength"=>"3", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('option_page_margin_right', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_page_margin_right">Page margin right in px</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('option_page_margin_right', $page_margin_right, "form-control editable_field", ["maxlength"=>"3", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('option_page_margin_bottom', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_page_margin_bottom">Page margin bottom in px</label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->text('option_page_margin_bottom', $page_margin_bottom, "form-control editable_field", ["maxlength"=>"3", "autocomplete"=>"off" ] ) !!}
		</div>
	</div>

	<div class="form-row mb-3 {{ in_array('option_background_color', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_background_color">Background color</label>
		<div class="col-12 col-sm-8">

			{!! $viewFuncs->blockButtonAtRightStart() !!}
			{!! $viewFuncs->text('option_background_color', $background_color, "form-control editable_field", ["maxlength"=>"7", "autocomplete"=>"off" ] ) !!}
			{!! $viewFuncs->blockButtonAtRightEnd("", "", "padding-bottom: 2px ; background-color : ".$background_color." ;", "option_background_color_mark", "fa fa-square-o fa-2x ") !!}

		</div>
	</div>
</fieldset>


<fieldset class="notes-block text-muted">
	<legend class="notes-blocks">Title</legend>

	<div class="form-row mb-3 {{ in_array('title_font_name', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_title_font_name">Title font name<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_title_font_name', $fontsList, $title_font_name, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('title_font_size', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_title_font_size">Title font size<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_title_font_size', $fontSizeItemsList, $title_font_size, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('option_title_font_color', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_title_font_color">Title font color</label>
		<div class="col-12 col-sm-8">

			{!! $viewFuncs->blockButtonAtRightStart() !!}
			{!! $viewFuncs->text('option_title_font_color', $title_font_color, "form-control editable_field", ["maxlength"=>"7", "autocomplete"=>"off" ] ) !!}
			{!! $viewFuncs->blockButtonAtRightEnd("", "", "padding-bottom: 2px ; background-color : ".$title_font_color." ;", "option_title_font_color_mark", "fa fa-square-o fa-2x ") !!}

		</div>
	</div>


</fieldset>


<fieldset class="notes-block text-muted">
	<legend class="notes-blocks">Subtitle</legend>

	<div class="form-row mb-3 {{ in_array('subtitle_font_name', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_subtitle_font_name">Subtitle font name<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_subtitle_font_name', $fontsList, $subtitle_font_name, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('subtitle_font_size', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_subtitle_font_size">Subtitle font size<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_subtitle_font_size', $fontSizeItemsList, $subtitle_font_size, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('subtitle_font_color', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_subtitle_font_color">Subtitle font color</label>
		<div class="col-12 col-sm-8">

			{!! $viewFuncs->blockButtonAtRightStart() !!}
			{!! $viewFuncs->text('option_subtitle_font_color', $subtitle_font_color, "form-control editable_field", ["maxlength"=>"7", "autocomplete"=>"off" ] ) !!}
			{!! $viewFuncs->blockButtonAtRightEnd("", "", "padding-bottom: 2px ; background-color : ".$subtitle_font_color." ;", "option_subtitle_font_color_mark", "fa fa-square-o fa-2x ") !!}

		</div>
	</div>

</fieldset>


<fieldset class="notes-block text-muted">
	<legend class="notes-blocks">Content</legend>

	<div class="form-row mb-3 {{ in_array('content_font_name', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_content_font_name">Content font name<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_content_font_name', $fontsList, $content_font_name, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('content_font_size', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_content_font_size">Content font size<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_content_font_size', $fontSizeItemsList, $content_font_size, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('option_content_font_color', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_content_font_color">Content font color</label>
		<div class="col-12 col-sm-8">

			{!! $viewFuncs->blockButtonAtRightStart() !!}
			{!! $viewFuncs->text('option_content_font_color', $content_font_color, "form-control editable_field", ["maxlength"=>"7", "autocomplete"=>"off" ] ) !!}
			{!! $viewFuncs->blockButtonAtRightEnd("", "", "padding-bottom: 2px ; background-color : ".$content_font_color." ;", "option_content_font_color_mark", "fa fa-square-o fa-2x ") !!}

		</div>
	</div>

</fieldset>


<fieldset class="notes-block text-muted">
	<legend class="notes-blocks">Notes</legend>
	<div class="form-row mb-3 {{ in_array('option_notes_font_name', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="notes_font_name">Notes font name<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_notes_font_name', $fontsList, $notes_font_name, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('option_notes_font_size', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="notes_font_size">Notes font size<span class="required"> * </span></label>
		<div class="col-12 col-sm-8">
			{!! $viewFuncs->select('option_notes_font_size', $fontSizeItemsList, $notes_font_size, "form-control editable_field chosen_select_box", [] ) !!}
		</div>
	</div>
	<div class="form-row mb-3 {{ in_array('option_notes_font_color', $errorFieldsArray) ? 'validation_error' : '' }}">
		<label class="col-12 col-sm-4 col-form-label" for="option_notes_font_color">Notes font color</label>
		<div class="col-12 col-sm-8">

			{!! $viewFuncs->blockButtonAtRightStart() !!}
			{!! $viewFuncs->text('option_notes_font_color', $notes_font_color, "form-control editable_field", ["maxlength"=>"7", "autocomplete"=>"off" ] ) !!}
			{!! $viewFuncs->blockButtonAtRightEnd("", "", "padding-bottom: 2px ; background-color : ".$notes_font_color." ;", "option_notes_font_color_mark", "fa fa-square-o fa-2x ") !!}

		</div>
	</div>

</fieldset>
