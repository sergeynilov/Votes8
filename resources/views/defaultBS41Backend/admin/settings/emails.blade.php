@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>


	<div class="form-row mb-3">
		<fieldset class="all-emails-copy-block text-muted">
			<legend class="all-emails-copy-blocks">All emails copy</legend>

			<div class="all-emails-copy-controls">
				<form role="form" autocomplete="off">


                    <?php $odd = true ?>
                    <?php $index0 = 0 ?>

					{{--@foreach($todosResults as $nextTodo)--}}

					{{--<div class="all-emails-copy-entry input-group col-xs-3">--}}
						{{--<input class="form-control" name="cron_tasks_receivers_fields[]" type="text" placeholder="Type something" />--}}
						{{--<span class="input-group-btn">--}}
                            {{--<button class="btn all-emails-copy-btn-success all-emails-copy-btn-add" type="button">--}}
                                {{--<span class="fa fa-plus"></span>--}}
                            {{--</button>--}}
                        {{--</span>--}}
					{{--</div>--}}
                            {{--<?php $odd = ! $odd  ?>--}}
                            {{--<?php $index0++  ?>--}}
	                    {{--@endforeach--}}



				</form>
				<br>
				<small>Press <span class="fa fa-plus"></span> to add another form field :)</small>
			</div>

		</fieldset>
	</div>

	<div class="form-row mb-3">
		<fieldset class="cron-tasks-receivers-block text-muted">
			<legend class="cron-tasks-receivers-blocks">Cron Tasks Notifications Receivers</legend>

			<div class="cron-tasks-receivers-controls">
				<form role="form" autocomplete="off">

                    <?php $odd = true ?>
                    <?php $index0 = 0 ?>

					{{--@foreach($todosResults as $nextTodo)--}}


					{{--<div class="cron-tasks-receivers-entry input-group col-xs-3">--}}
						{{--<input class="form-control" name="cron_tasks_receivers_fields[]" type="text" placeholder="Type something" />--}}

						{{--<div class=" col-12 p-1">--}}
							{{--<input type="hidden" id="all-emails-copy_id_{{ $index0 }}" name="all-emails-copy_id_{{ $index0 }}" value="{{$nextTodo->id}}" class="all-emails-copy_editable_field all-emails-copy_editable_field_id">--}}
							{{--<input type="hidden" id="all-emails-copy_modified_{{ $index0 }}" name="all-emails-copy_modified_{{ $index0 }}" value="" class="all-emails-copy_editable_field--}}
							{{--all-emails-copy_editable_field_modified">--}}
							{{--{!! $viewFuncs->text('all-emails-copy_text_'.$index0, $nextTodo->text,	'form-control all-emails-copy_editable_field all-emails-copy_editable_field_text editable_field',	["maxlength"=>"255", "autocomplete"=>"off",--}}
							{{--"placeholder"=> "Enter new all-emails-copy task", 'onchange'=>"javascript:backendTodoContainerPage.all-emails-copyOnChange(this); " ] ) !!}--}}
						{{--</div>--}}

						{{--<span class="input-group-btn">--}}
                            {{--<button class="btn cron-tasks-receivers-btn-success cron-tasks-receivers-btn-add" type="button">--}}
                                {{--<span class="fa fa-plus"></span>--}}
                            {{--</button>--}}
                        {{--</span>--}}
					{{--</div>--}}
                            {{--<?php $odd = ! $odd  ?>--}}
                            {{--<?php $index0++  ?>--}}
	                    {{--@endforeach--}}



				</form>
				<br>
				<small>Press <span class="fa fa-plus"></span> to add another form field :)</small>
			</div>

		</fieldset>
	</div>

	{{--<div class="form-row mb-3 {{ in_array('backend_per_page', $errorFieldsArray) ? 'validation_error' : '' }}">--}}
		{{--<label class="col-12 col-sm-4 col-form-label" for="emails_backend_per_page">Backend items per pagination<span class="required"> * </span></label>--}}
		{{--<div class="col-12 col-sm-8">--}}
			{{--{!! $viewFuncs->text('emails_backend_per_page', old("emails_backend_per_page",$emails_backend_per_page), 'form-control--}}
			{{--editable_field', [--}}
			{{--"maxlength"=>"8", "autocomplete"=>"off" ] ) !!}--}}
		{{--</div>--}}
	{{--</div>--}}



	{{--<div class="form-row mb-3 {{ in_array('backend_todo_tasks_popup', $errorFieldsArray) ? 'validation_error' : '' }}">--}}
		{{--<label class="col-12 col-sm-4 col-form-label" for="emails_backend_todo_tasks_popup">Todo tasks popup</label>--}}
		{{--<div class="col-12 col-sm-8 ">--}}
			{{--{!! $viewFuncs->showStylingCheckbox('emails_backend_todo_tasks_popup', 'Y', ( !empty($emails_backend_todo_tasks_popup) and $emails_backend_todo_tasks_popup == 'Y'),--}}
			 {{--'', []  ) !!}--}}
		{{--</div>--}}
	{{--</div>--}}



	<div class="row float-right" style="padding: 0; margin: 0;">
		<div class="row btn-group editor_btn_group">
			<button type="button" class="btn btn-primary" onclick="javascript: backendSettings.onSubmit('emails') ; return false; " style=""><span
						class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
			</button>&nbsp;&nbsp;
			<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
			        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
			</button>&nbsp;&nbsp;
		</div>
	</div>
