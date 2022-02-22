@extends($frontend_template_name.'.layouts.frontend')

@section('content')

	@inject('viewFuncs', 'App\library\viewFuncs')


	<div class="modal fade" tabindex="-1" role="dialog" id="div_user_chat_members_modal" aria-labelledby="div_user_chat_member-modal-label"
	     aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title" id="div_user_chat_members_modal-label">Members of the chat</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body" style="width: 100%;">
					<div id="div_user_chat_members_content" style="width: 100%;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>


	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
			<small>{{ $site_subheading }}</small>@endif
	</h1>

	@include($frontend_template_name.'.layouts.logged_user')

	{{--<div class="row ml-1 mb-3">--}}
	{{--{{ Breadcrumbs::render('profile', 'Profile preview') }}--}}
	{{--</div>--}}


	<!-- Page Content : user_chat_container -->

	<div class="row bordered " id="page-wrapper">
		<section class="card-body ">

			<h4 class="card-title">
				<img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
				{!! $viewFuncs->showAppIcon('profile','transparent_on_white') !!}Chat : {{ $loggedUser['username'] }}
			</h4>
			@include($frontend_template_name.'.layouts.page_header')
			<div id="div_user_chat_container" style="width: 100%; border:2px dotted green;"></div>
		</section> <!-- class="card-body" -->
	</div>

	<!-- /.page-wrapper page Content : user_chat_container -->

@endsection


@section('scripts')

	<link rel="stylesheet" href="{{ url('css/jquery.fileupload.css') }} "/>

	<script src="{{ url('js/fileupload/jquery.ui.widget.js') }}"></script>

	<script src="{{ url('js/fileupload/jquery.fileupload.js') }}"></script>
	<script src="{{ url('js/fileupload/jquery.fileupload-process.js') }}"></script>
	<script src="{{ url('js/fileupload/jquery.iframe-transport.js') }}"></script>

	<script src="{{ asset('js/'.$frontend_template_name.'/user_chat.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/


        var frontendUserChat = new frontendUserChat('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendUserChat.onFrontendPageInit('view')
        });


        /*]]>*/
	</script>


@endsection
