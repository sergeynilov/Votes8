@extends($current_admin_template.'.layouts.backend')

@section('content')
	{{ csrf_field() }}


	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : votes by days report -->
	<div id="page-wrapper" class="card">

		@include($current_admin_template.'.layouts.page_header')

		<section class="card-body content_block_admin_votes_wrapper ">

			<h4 class="card-title ">{!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Votes by days</h4>

			@if( !empty($is_developer_comp) )


				{{--Route::post('report-save-to-excel', 'Admin\ReportsController@report_save_to_excel')->name('report-save-to-excel');--}}
				{{--$("#report_name").val(report_name)--}}
				{{--$("#monthsXCoordItems").val(this.this_monthsXCoordItems)--}}
				{{--$("#voteValuesCorrect").val(this.this_voteValuesCorrect)--}}
				{{--$("#voteValuesNoneCorrect").val(this.this_voteValuesNoneCorrect)--}}


				{{--<form method="POST" action="{{ url('/admin/report-save-to-excel') }}" accept-charset="UTF-8" id="form_report_save_to_excel"--}}
				      {{--name="form_print_to_pdf_content form_report_save_to_excel"--}}
				      {{--enctype="multipart/form-data">--}}
					{{--{!! csrf_field() !!}--}}
					{{--<input type="__hidden" id="report_name" name="report_name" value=""><br>--}}
					{{--<input type="__hidden" id="monthsXCoordItems" name="monthsXCoordItems" value=""><br>--}}
					{{--<input type="__hidden" id="voteValuesCorrect" name="voteValuesCorrect" value=""><br>--}}
					{{--<input type="__hidden" id="voteValuesNoneCorrect" name="voteValuesNoneCorrect" value=""><br>--}}

					{{--<div class="row">--}}
						{{--<input type="submit" class="btn btn-sm" value="Export to excel" onclick="javascript:backendReports.showExportToExcel('votes_by_days'); return false;"--}}
						       {{--id="btn_show_export_to_excel">--}}
					{{--</div>--}}

					{{--<div class="row" id="div_save_export_to_excel" style="display:block">--}}
						{{--{!! $viewFuncs->textarea('notes', '1234', "form-control editable_field ", [ "rows"=>"3", "cols"=> 80, "placeholder"=>"Fill notes on these data"  ] ) !!}--}}
						{{--<input type="submit" class="btn btn-sm" value="Save" onclick="javascript:backendReports.saveExportToExcel('votes_by_days'); return false;"--}}
						       {{--id="btn_save_export_to_excel">--}}
					{{--</div>--}}
				{{--</form>--}}
			@endif
		</section>

	</div>


	<div class="form-row">


		<div class="col-12 col-sm-6">
			<label class="col-12 col-sm-12 col-form-label pb-0 mb-0 mt-3" for="filter_voted_at_from_till">
				Voted at range
				(
				<small>You can pick one date and then scroll to the second month and select a date. The range will be selected but not all visible. By default dates
					with demo data are selected.
				</small>
				)
			</label>
			<div class="col-12 col-sm-12">
				{!! $viewFuncs->text('filter_voted_at_from_till', '', "form-control", [ "readonly"=>"readonly" ] ) !!}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<label class="col-12 col-form-label pb-0 mb-0  mt-3" for="filter_user_id"> Select user(s)
			</label>
			<div class="col-12">
				{!! $viewFuncs->select('filter_user_id', $usersSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<label class="col-12 col-form-label  pb-0 mb-0 mt-3" for="filter_vote_category_id"> Select category(ies)
			</label>
			<div class="col-12">
				{!! $viewFuncs->select('filter_vote_category_id', $voteCategoriesSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<label class="col-12 col-form-label  pb-0 mb-0 mt-3" for="filter_vote_id"> Select vote(s)
			</label>
			<div class="col-12">
				{!! $viewFuncs->select('filter_vote_id', $votesSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
			</div>
		</div>
		<div class="col-12 col-sm-6 mt-2 mb-2">
			<input type="submit" class="btn btn-primary" value="Report" onclick="javascript:backendReports.runVotesReportByDays(); return false;" id="btn_run_search">&nbsp;&nbsp;
			<input type="submit" class="btn btn-sm" value="Clear" onclick="javascript:backendReports.clearrunVotesReportByDaysParameters(); return false;"
			       id="btn_clear">
		</div>

	</div>



	<div class="row" id="div_canvasVotesByDays" style="display: none">
		<div class="col-md-10 col-md-offset-1">

			<div class="card-body">
				<p class=" text-muted small">
					Clicking on legend items you can hide/show them. Clicking on visible dots you would see opened details by selected day.
				</p>
				<canvas id="canvasVotesByDays" height="560" width="800"></canvas>
			</div>


		</div>
	</div>



	</section>  <!-- class="card-body" -->


	</div>
	<!-- /.page-wrapper Page Content : votes by days report -->

	<div class="modal fade" tabindex="-1" role="dialog" id="div_vote_by_days_report_details_modal" aria-labelledby="vote-by-days-report-details-modal-label"
	     aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">
					{{--$('#span_vote_by_days_report_details_content_title').html(selected_day)--}}
					{{--$('#div_vote_by_days_report_details_content').html(response.html)--}}
					{{--$('#div_vote_by_days_report_details_correct_count').html(response.correct_count)--}}
					{{--$('#div_vote_by_days_report_details_not_correct_count').html(response.not_correct_count)--}}

					<h5 class="modal-title" id="vote-by-days-report-details-modal-label">Details on <span id="span_vote_by_days_report_details_content_title"> vote</span></h5>
					<h5 class="modal-title">&nbsp;&nbsp;:&nbsp;&nbsp;<small>
							<span id="div_vote_by_days_report_details_correct_count"></span> correct votes,
							<span id="div_vote_by_days_report_details_not_correct_count"></span> not correct votes
						</small>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body ">
					<div id="div_vote_by_days_report_details_content"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>

@endsection

@section('scripts')

	<link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
	<script src="{{ asset('/js/Chart.bundle.js') }}"></script>
	<script src="{{ asset('js/daterangepicker.min.js') }}"></script>

	<script src="{{ asset('/js/'.$current_admin_template.'/admin/reports.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var backendReports = new backendReports('report',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendReports.onBackendPageInit('votes_by_days')
        });

        /*]]>*/
	</script>


@endsection

