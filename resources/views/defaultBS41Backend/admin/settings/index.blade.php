@extends($current_admin_template.'.layouts.backend')

@section('content')
	@inject('viewFuncs', 'App\library\viewFuncs')

    <?php $errorFieldsArray = array_values($errors->keys());
    if (!empty($errorFieldsArray)) {
        $active_tab= Session::get('tab_name_to_submit', []);
        Session::put('tab_name_to_submit', '');
    }
    ?>

	<!-- Page Content : settings -->
	<div id="page-wrapper" class="card">

		@if(!empty($is_developer_comp))
		<?php $active_tab= 'common_settings'; ?>
        <?php $active_tab= 'logs'; ?>
		@endif
		<section class="card-body">
			<h4 class="card-title">{!! $viewFuncs->showAppIcon('settings','transparent_on_white') !!}Settings</h4>

			@include($current_admin_template.'.layouts.page_header')

			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
				<li class="nav-item">
					<a class="nav-link @if( $active_tab == 'common_settings' || empty($active_tab) ) active @endif" id="settings-common-settings-tab" data-toggle="pill"
					   href="#settings-details"
					   role="tab" aria-controls="settings-details"
					   aria-selected="true">Common settings
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'site_content') active @endif" id="pills-settings-site-content-tab" data-toggle="pill" href="#pills-settings-site-content"
					   role="tab" aria-controls="pills-settings-site-content"
					   aria-selected="false">Site content
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'administration_part') active @endif" id="administration-part-tab" data-toggle="pill" href="#pills-administration-part"
					   role="tab" aria-controls="pills-administration-part" aria-selected="true">Administration part
					</a>
				</li>
				<li class="nav-item ">
					<a class="nav-link @if($active_tab == 'users') active @endif" id="users-tab" data-toggle="pill" href="#pills-users" role="tab" aria-controls="pills-users"
					   aria-selected="true">Users
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'votes') active @endif" id="votes-tab" data-toggle="pill" href="#pills-votes" role="tab" aria-controls="pills-votes"
					   aria-selected="true">Votes
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'news') active @endif" id="news-tab" data-toggle="pill" href="#pills-news" role="tab" aria-controls="pills-news"
					   aria-selected="true">News
					</a>
				</li>

				@if(!empty($is_developer_comp))
				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'emails') active @endif" id="emails-tab" data-toggle="pill" href="#pills-emails" role="tab" aria-controls="pills-emails"
					   aria-selected="true">Emails
					</a>
				</li>
				@endif

				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'services') active @endif" id="services-tab" data-toggle="pill" href="#pills-services" role="tab" aria-controls="pills-services"
					   aria-selected="true">Services
					</a>
				</li>

				<li class="nav-item">
					<a class="nav-link @if($active_tab == 'logs') active @endif" id="logs-tab" data-toggle="pill" href="#pills-logs" role="tab" aria-controls="pills-logs" 
					   aria-selected="true">Logs
					</a>
				</li>

			</ul>


			<div class="tab-content " id="pills-tabContent">
				<div class="tab-pane @if($active_tab == 'common_settings' || empty($active_tab)) active @endif" id="settings-details" role="tabpanel"
				     aria-labelledby="settings-common-settings-tab">
					@include($current_admin_template.'.admin.settings.common')
				</div>
				<div class="tab-pane @if($active_tab == 'site_content') active @endif" id="pills-settings-site-content" role="tabpanel"
				     aria-labelledby="pills-settings-site-content-tab">
					@include($current_admin_template.'.admin.settings.site-content')
				</div>
				<div class="tab-pane @if($active_tab == 'administration_part') active @endif" id="pills-administration-part" role="tabpanel"
				     aria-labelledby="pills-administration-part">
					@include($current_admin_template.'.admin.settings.administration-part')
				</div>

				<div class="tab-pane @if($active_tab == 'users') active @endif" id="pills-users" role="tabpanel" aria-labelledby="pills-users">
					@include($current_admin_template.'.admin.settings.users')
				</div>
				<div class="tab-pane @if($active_tab == 'votes') active @endif" id="pills-votes" role="tabpanel" aria-labelledby="pills-votes">
					@include($current_admin_template.'.admin.settings.votes')
				</div>
				<div class="tab-pane @if($active_tab == 'news') active @endif" id="pills-news" role="tabpanel" aria-labelledby="pills-news">
					@include($current_admin_template.'.admin.settings.news')
				</div>

				@if(!empty($is_developer_comp))
				<div class="tab-pane @if($active_tab == 'emails') active @endif" id="pills-emails" role="tabpanel" aria-labelledby="pills-emails">
					@include($current_admin_template.'.admin.settings.emails')
				</div>
				@endif

				<div class="tab-pane @if($active_tab == 'services') active @endif" id="pills-services" role="tabpanel" aria-labelledby="pills-services">
					@include($current_admin_template.'.admin.settings.services')
				</div>

				<div class="tab-pane @if($active_tab == 'logs') active @endif" id="pills-logs" role="tabpanel" aria-labelledby="pills-logs">
					@include($current_admin_template.'.admin.settings.logs')
				</div>

			</div>

		</section> <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper Page Content : settings End -->



@endsection


@section('scripts')

	<script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
	<script>
        initTinyMCEEditor("common_settings_support_signature_container", "common_settings_support_signature", 460, 160);
        initTinyMCEEditor("site_content_account_register_details_text_container", "site_content_account_register_details_text", 460, 160);
        initTinyMCEEditor("site_content_account_register_avatar_text_container", "site_content_account_register_avatar_text", 460, 160);
        initTinyMCEEditor("site_content_account_register_subscriptions_text_container", "site_content_account_register_subscriptions_text", 460, 160);
        initTinyMCEEditor("site_content_account_register_confirm_text_container", "site_content_account_register_confirm_text", 460, 160);
        initTinyMCEEditor("site_content_account_contacts_us_text_container", "site_content_account_contacts_us_text", 460, 160);
	</script>

	<link rel="stylesheet" href="{{ url('css/jquery.fileupload.css') }} "/>

	<script src="{{ url('js/fileupload/jquery.ui.widget.js') }}"></script>

	<script src="{{ url('js/fileupload/jquery.fileupload.js') }}"></script>
	<script src="{{ url('js/fileupload/jquery.fileupload-process.js') }}"></script>
	<script src="{{ url('js/fileupload/jquery.iframe-transport.js') }}"></script>


	<script src="{{ asset('/js/'.$current_admin_template.'/admin/settings.js') }}{{  "?dt=".time()  }}"></script>

	<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	{!! JsValidator::formRequest('App\Http\Requests\VoteRequest', '#form_settings_edit'); !!}


	<script>
        /*<![CDATA[*/

        var backendSettings = new backendSettings('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendSettings.onBackendPageInit('edit')
        });

        /*]]>*/
	</script>


@endsection

