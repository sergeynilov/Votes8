@inject('viewFuncs', 'App\library\viewFuncs')

<div class="card p-1 m-1" >

	@if( count($settingsShowQuizQualityOptions ) == 0)
		<h3 class="card-subtitle">No quiz quality options added</h3>
	@else
		<h3 class="card-subtitle pl-2">Has {{ count($settingsShowQuizQualityOptions) }} quiz quality {{ \Illuminate\Support\Str::plural('option', count($settingsShowQuizQualityOptions)) }} </h3>

		<div class="table-responsive dataTables_header">
			<table class="table table-bordered table-striped text-primary dataTable no-footer">
				@foreach($settingsShowQuizQualityOptions as $next_key => $nextSettingsShowQuizQualityOption)

					@if(!empty($nextSettingsShowQuizQualityOption['quiz_quality_id']) and !empty($nextSettingsShowQuizQualityOption['quiz_quality_label']))
					<tr role="row" class="@if($loop->iteration  % 2 == 0) even @else odd @endif">
						<td class="td_details_content">
							{{ $nextSettingsShowQuizQualityOption['quiz_quality_id'] }}
						</td>
						<td class="td_details_content">
							{{ $nextSettingsShowQuizQualityOption['quiz_quality_label'] }}
						</td>
						<td>
							<a href="#"
							   onclick="javascript:backendSettings.deleteQuizQualityOption({{$nextSettingsShowQuizQualityOption['quiz_quality_id']}}, '{{$nextSettingsShowQuizQualityOption['quiz_quality_label']}}')" class="a_link"><i class=" fa fa-remove"></i></a>
						</td>
					</tr>
					@endif

				@endforeach

			</table>
		</div>


	@endif

	<div class="row p-1 m-0">
		<div class="col-12 p-0 m-0" style="width: 100%">
			<div class="form-row mb-3">
				<label for="add_new_quiz_quality_id" class="col-12 col-md-4 col-form-label">Quiz quality Id<span class="required"> * </span></label>
				<div class="col-12 col-md-8">
					{!! $viewFuncs->text('add_new_quiz_quality_id', '', "form-control editable_field", [ "maxlength"=>"255",	"autocomplete"=>"off" ,	"placeholder"=>"Enter new
 quiz quality id" ] ) !!}
				</div>
			</div>

			<div class="form-row mb-3">
				<label for="add_new_quiz_quality_label" class="col-12 col-md-4 col-form-label">Quiz quality label<span class="required"> * </span></label>
				<div class="col-12 col-md-6">
					{!! $viewFuncs->text('add_new_quiz_quality_label', '', "form-control editable_field", [ "maxlength"=>"255",	"autocomplete"=>"off" ,	"placeholder"=>"Enter new
quiz quality label"
					 ] ) !!}
				</div>
				<div class="col-12 col-md-2">
					<button type="button" onclick="javascript:backendSettings.addQuizQualityOption()" class="btn btn-primary">
						Add
					</button>
				</div>
			</div>

		</div>
	</div>

</div>
