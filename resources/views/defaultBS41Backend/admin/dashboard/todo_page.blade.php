@inject('viewFuncs', 'App\library\viewFuncs')


	<div class="container m-1 ml-3 p-0">
		<h5> <span id="todos_count_label">{{ $todos_count }}</span> To Do {{ \Illuminate\Support\Str::plural('item', $todos_count) }} </h5>
	</div>

	<div class="form m-0" id="todo_fields">
		<input type="hidden" id="todos_count" name="todos_count" value="{{$todos_count}}">
		<div class="controls">
			<form role="form" autocomplete="off">
				<?php $odd = true ?>
                <?php $index0 = 0 ?>

				@foreach($todosResults as $nextTodo)
					<div class="row entry input-group-append m-0 p-0 pb-5" style="width: 100%;">

						<div class=" col-12 p-1">
							<input type="hidden" id="todo_id_{{ $index0 }}" name="todo_id_{{ $index0 }}" value="{{$nextTodo->id}}" class="todo_editable_field todo_editable_field_id">
							<input type="hidden" id="todo_modified_{{ $index0 }}" name="todo_modified_{{ $index0 }}" value="" class="todo_editable_field
							todo_editable_field_modified">
							{!! $viewFuncs->text('todo_text_'.$index0, $nextTodo->text,	'form-control todo_editable_field todo_editable_field_text editable_field',	["maxlength"=>"255", "autocomplete"=>"off",
							"placeholder"=> "Enter new todo task", 'onchange'=>"javascript:backendTodoContainerPage.todoOnChange(this); " ] ) !!}
						</div>

						<div class=" col-3 p-1 m-0 mb-4">
							{!! $viewFuncs->select('todo_priority_'.$index0, $todoPrioritiesValueArray, isset($nextTodo->priority) ? $nextTodo->priority : '', "form-control todo_editable_field editable_field", ['data-placeholder'=>" -Select Is Featured- ", 'onchange'=>"javascript:backendTodoContainerPage.todoOnChange(this); "] ) !!}
						</div>
						<div class=" col-3 p-1">
							{!! $viewFuncs->select('todo_completed_'.$index0, $todoCompletedValueArray, isset($nextTodo->completed) ? $nextTodo->completed : '', "form-control todo_editable_field todo_editable_field_completed editable_field", ['data-placeholder'=>" -Select Is Featured- ", 'onchange'=>"javascript:backendTodoContainerPage.todoOnChange(this); "] ) !!}
						</div>
						<div class=" col-5 p-1">
							{!! $viewFuncs->select('todo_foruserid_'.$index0, $todoUserValueArray, isset($nextTodo->for_user_id) ? $nextTodo->for_user_id : '', "form-control chosen_select_box todo_editable_field todo_editable_field_foruserid editable_field", ['data-placeholder'=>" -Select User- ", 'onchange'=>"javascript:backendTodoContainerPage.todoOnChange(this); "] ) !!}
						</div>

						<div class=" col-1 p-1">
							@if($index0 >= count($todosResults)-1)
								<span class="input-group-append-btn">
								<button class="btn btn-success todo-btn-add" type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
							</span>
							@else
								<span class="input-group-append-btn">
								<button class="btn btn-danger todo-btn-add" type="button">
                                    <span class="fa fa-minus"></span>
                                </button>
							</span>
							@endif
						</div>

					</div>
                    <?php $odd = ! $odd  ?>
                    <?php $index0++  ?>
				@endforeach

			</form>
			<br>
			<small>Press <span class="fa fa-plus"></span> to add to do item</small>
		</div>
	</div>


