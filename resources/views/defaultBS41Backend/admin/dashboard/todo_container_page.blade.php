@extends($current_admin_template.'.layouts.backend')

@section('content')


	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : todo_container_page -->
	<div id="page-wrapper" class="card">

		@include($current_admin_template.'.layouts.page_header')

		<section class="card-body content_block_admin_todo_container_page_wrapper ">

			<h4 class="card-title ">{!! $viewFuncs->showAppIcon('todo','transparent_on_white') !!}Todos</h4>

			<div class="row" style="width: 100%" id="div_todo_container_page_content"></div>


			<div class="row float-right" style="padding: 0; margin: 0;">
				<div class="row btn-group editor_btn_group">

					<button type="button" class="btn btn-primary" onclick="javascript: backendTodoContainerPage.saveTodoDialog() ; return false; " style=""><span
								class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
					</button>&nbsp;&nbsp;

					<button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/dashboard') }}'"
					        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
					</button>&nbsp;&nbsp;
				</div>
			</div>

		</section>  <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper  Page Content : todo_container_page -->

@endsection


@section('scripts')


	<script src="{{ asset('js/'.$current_admin_template.'/admin/todo_container_page.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var oTable
        var backendTodoContainerPage = new backendTodoContainerPage('index',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendTodoContainerPage.onBackendPageInit('index')
        });

        /*]]>*/
	</script>


@endsection

