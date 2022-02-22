@extends($current_admin_template.'.layouts.backend')

@section('content')
	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : external news importings create -->
	<div id="page-wrapper" class="card">

		<section class="card-body">
			<h4 class="card-title">{!! $viewFuncs->showAppIcon('external-link','transparent_on_white') !!}Create external news importing</h4>

			<form method="POST" action="{{ url('/admin/external-news-importing') }}" accept-charset="UTF-8" id="form_external_news_importing_edit"
			      enctype="multipart/form-data">
				{!! csrf_field() !!}
				@include($current_admin_template.'.admin.external_news_importing.form')
			</form>

		</section> <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper Page Content : external news importings create End -->



@endsection


@section('scripts')

	<script src="{{ asset('js/'.$current_admin_template.'/admin/external_news_importing.js') }}{{  "?dt=".time()  }}"></script>

	<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	{!! JsValidator::formRequest('App\Http\Requests\ExternalNewsImportingRequest', '#form_external_news_importing_edit'); !!}

	<script>
        /*<![CDATA[*/

        var backendExternalNewsImporting = new backendExternalNewsImporting('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendExternalNewsImporting.onBackendPageInit('edit')
        });

        /*]]>*/
	</script>


@endsection

