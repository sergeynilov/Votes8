@extends($current_admin_template.'.layouts.backend')

@section('content')
	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : chats create -->
	<div id="page-wrapper" class="card">

		<section class="card-body">
			<h4 class="card-title">{!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}Create chat</h4>

			<form method="POST" action="{{ url('/admin/chats') }}" accept-charset="UTF-8" id="form_chat_edit" enctype="multipart/form-data">
				{!! csrf_field() !!}
				@include($current_admin_template.'.admin.chat.form')
			</form>

		</section> <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper Page Content : chats create -->



@endsection


@section('scripts')

	<script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
	<script>
        initTinyMCEEditor("description_container", "description", 460, 360);
	</script>

	<script src="{{ asset('js/'.$current_admin_template.'/admin/chat.js') }}{{  "?dt=".time()  }}"></script>

	<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	{!! JsValidator::formRequest('App\Http\Requests\ChatRequest', '#form_chat_edit'); !!}

	<script>
        /*<![CDATA[*/

        var backendChat = new backendChat('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendChat.onBackendPageInit('edit')
        });

        /*]]>*/
	</script>


@endsection

