@extends($current_admin_template.'.layouts.backend')

@section('content')


	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : dashboard index -->
	<div id="page-wrapper" class="card">

		@include($current_admin_template.'.layouts.page_header')

		<section class="card-body content_block_admin_dashboard_wrapper ">

			<h4 class="card-title ">Test : {{$loggedUser->username}}</h4>




			<div class="row">
				<div class="col-sm-12">
					<div class="card border-success mb-3" style="width: 100%;">
						<div class="card-header bg-transparent border-success"><i class="fa fa-archive"></i>&nbsp;Sacebook testing</div>
						<div class="card-body text-success">
							<div id="div_activity_log_content"></div>
							<input type="button" class="btn btn-primary" value="publish To Profile" onclick="javascript:backendTest.publishToProfile(); return false;">
						</div>
					</div>
				</div>
			</div>


			@if(!empty($is_developer_comp))
				{{--<div class="col-12 col-sm-12">--}}
					{{--<div class="card border-success mb-3" style="width: 100%;">--}}

						{{--<div class="card-header bg-transparent border-success"><i class="fa fa-external-link-square "></i>&nbsp;External links</div>--}}
						{{--<div class="card-body text-success">--}}
							{{--<a href="https://fontawesome.com/v4.7.0/icons/" class="a_link">https://fontawesome.com/v4.7.0/icons/</a>--}}
							{{--<br><a href="chrome://settings/?search=cach" class="a_link">chrome://settings/?search=cach</a>--}}
							{{--<br><a href="https://getbootstrap.com/docs/4.1/components/card/" class="a_link">--}}
								{{--Bootstrap 4.1 Card--}}
							{{--</a>--}}
							{{--<br><a href="http://bootstrap-4.ru/docs/4.1/migration/" class="a_link">--}}
								{{--bootstrap-41.ru Migration--}}
							{{--</a>--}}


						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
			@endif

		</section>  <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper  Page Content : dashboard index -->



@endsection


@section('scripts')


	<script src="{{ asset('js/'.$current_admin_template.'/admin/backendTest.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var oTable
        var backendTest = new backendTest('index',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendTest.onBackendPageInit('index')
        });

        /*]]>*/
	</script>


@endsection

