
@extends($current_admin_template.'.layouts.backend')

@section('content')
	{{ csrf_field() }}


	@inject('viewFuncs', 'App\library\viewFuncs')


	<!-- Page Content : chats index -->
	<div id="page-wrapper" class="card">

		@include($current_admin_template.'.layouts.page_header')


		<section class="card-body content_block_admin_chats_wrapper ">

			<h4 class="card-title ">{!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}Chats Listing</h4>

			<div class="form-row offset-1">

				<div class="col-12 col-sm-6 mb-3">
					{!! $viewFuncs->text('filter_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for name' ] ) !!}
				</div>


				<div class="col-12 col-sm-6 mb-3">
					{!! $viewFuncs->select('filter_status', $chatStatusValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Status- "] ) !!}
				</div>


				{{--CREATE TABLE `chats` (--}}
				{{--`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,--}}
				{{--`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',--}}
				{{--`description` MEDIUMTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',--}}
				{{--`creator_id` INT(10) UNSIGNED NOT NULL,--}}
				{{--`status` ENUM('A','C') NOT NULL COMMENT ' A=>Active, C=>Closed' COLLATE 'utf8mb4_unicode_ci',--}}
				{{--`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,--}}
				{{--`updated_at` TIMESTAMP NULL DEFAULT NULL,				--}}
				<div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
					<input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendChat.runSearch(oTable); return false;" id="btn_run_search">
					<a onclick="javascript:document.location='{{ route('admin.chats.create') }}'" class="a_link">
						&nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
					</a>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-striped text-primary" id="get-chat-dt-listing-table">
						<thead>
						<tr>
							<th>+</th>
							<th>Id</th>
							<th>Name</th>
							<th>Status</th>
							<th>Creator</th>
							<th>Description</th>
							<th>Created At</th>
							<th>Updated At</th>
							<th></th>
							<th></th>
						</tr>
						</thead>
					</table>
				</div>

			</div>

			<div class="row mt-2 ml-2">
				<button type="button" onclick="javascript:document.location='/admin/chats/create'" class="btn btn-primary">
					&nbsp;Add
				</button>&nbsp;&nbsp;
			</div>


		</section>  <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper Page Content : chats index -->


	<script id="chat_details_info_template" type="mustache/x-tmpl">
<div id="div_chat_details_info_<%id%>"></div>


    </script>

	<!-- DataTables -->


@endsection


@section('scripts')

	<link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/mustache.min.js') }}"></script>

	<script src="{{ asset('js/'.$current_admin_template.'/admin/chat.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var oTable
        var backendChat = new backendChat('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendChat.onBackendPageInit('list')
        });

        /*]]>*/
	</script>


@endsection

