@extends($current_admin_template.'.layouts.backend')

@section('content')
	@inject('viewFuncs', 'App\library\viewFuncs')
	{{ csrf_field() }}

	<!-- Page Content : chat edit -->
	<div id="page-wrapper" class="card">

		<section class="card-body">
			<h4 class="card-title">{!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}Edit chat</h4>

			<form  method="POST" action="{{ url('/admin/chats/' . $chat->id) }}" accept-charset="UTF-8" id="form_chat_edit" enctype="multipart/form-data">
				@method('PUT')
				{!! csrf_field() !!}
				{{--@include($current_admin_template . '.admin.chat.form')--}}


				<ul class="nav nav-pills mb-3 " id="pills-tab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="chat-details-tab" data-toggle="pill" href="#chat-details" role="tab" aria-controls="-details"
						   aria-selected="true">Details
						</a>
					</li>

					@if(!empty($is_developer_comp))
					<li class="nav-item">
						<a class="nav-link " id="pills-chat-participants-tab" data-toggle="pill" href="#pills-chat-participants" role="tab" aria-controls="pills-chat-participants"
						   aria-selected="false">Participants
						</a>
					</li>
					@endif

				</ul>

				<div class="tab-content " id="pills-tabContent">
					<div class="tab-pane active" id="chat-details" role="tabpanel" aria-labelledby="chat-details-tab">
						@include($current_admin_template . '.admin.chat.form')
					</div>

					@if(!empty($is_developer_comp))
					<div class="tab-pane fade" id="pills-chat-participants" role="tabpanel" aria-labelledby="pills-chat-participants">
						<div id="div_related_chat_participants"></div>
					</div>
					@endif

				</div>

			</form>

		</section> <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper page Content : chat edit -->



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

