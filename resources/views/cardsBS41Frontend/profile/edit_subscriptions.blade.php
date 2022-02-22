@extends($frontend_template_name.'.layouts.frontend')

@section('content')

	@inject('viewFuncs', 'App\library\viewFuncs')

	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
			<small>{{ $site_subheading }}</small>@endif
	</h1>

	@include($frontend_template_name.'.layouts.logged_user')

	<div class="row ml-1 mb-3">
		{{ Breadcrumbs::render('profile', 'Profile preview', 'Edit subscriptions') }}
	</div>

	<!-- Page Content : edit subscription -->
	<div class="row bordered card" id="page-wrapper">

		<section class="card-body">
			<h4 class="card-title">
				<img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
				Profile : Edit subscriptions
			</h4>


			<a href="#" onclick="javascript:frontendProfile.getRelatedUsersSiteSubscriptions()">
				Refresh
			</a>


			@include($frontend_template_name.'.layouts.page_header')

			<div id="div_related_users_site_subscriptions"></div>



			@if(!empty($account_register_subscriptions_text))
				<div class="form-row mb-3">
					<fieldset class="blocks text-muted">
						<legend class="blocks">Notes</legend>
						{!! $account_register_subscriptions_text !!}
					</fieldset>
				</div>
			@endif

			<div class="form-row mb-3 float-right">
				<div class="row btn-group editor_btn_group ">
					<button type="submit" class="btn btn-primary" style=""><span
								class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
					</button>&nbsp;&nbsp;
					<button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ URL::route('profile-view') }}'"
					        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
					</button>&nbsp;&nbsp;
				</div>
			</div>

			{{--<button type="button" class="btn btn-inverse" onclick="javascript:frontendProfile.getUserMailChimpInfo()"--}}
			{{--style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Mailchimp info--}}
			{{--</button>&nbsp;&nbsp;--}}


		</section> <!-- class="card-body" -->


	</div>
	<!-- /.page-wrapper Page Content : edit subscription   -->


@endsection


@section('scripts')

	<script src="{{ asset('js/'.$frontend_template_name.'/profile.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var frontendProfile = new frontendProfile('edit_subscription',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendProfile.onFrontendPageInit('edit_subscription')
        });

        /*]]>*/
	</script>


@endsection