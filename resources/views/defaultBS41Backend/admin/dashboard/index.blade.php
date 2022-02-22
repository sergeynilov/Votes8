@extends($current_admin_template.'.layouts.backend')

@section('content')


	@inject('viewFuncs', 'App\library\viewFuncs')

	<!-- Page Content : dashboard index -->
	<div id="page-wrapper" class="card">

		@include($current_admin_template.'.layouts.page_header')

		<section class="card-body content_block_admin_dashboard_wrapper ">

			<h4 class="card-title ">{!! $viewFuncs->showAppIcon('dashboard','transparent_on_white') !!}Dashboard</h4>

			{{--<div class="row">--}}
			{{--<div class="col-12 col-sm-6">--}}

			{{--<div class="card border-success mb-3" style="width: 100%;">--}}

			{{--<div class="card-header bg-transparent border-success"><i class="fa fa-envelope-square"></i>&nbsp;Email testing</div>--}}
			{{--<div class="card-body text-success">--}}
			{{--<h5 class="card-title">Testing "User was activated" email</h5>--}}
			{{--<p class="card-text">--}}
			{{--Testing "User was activated" email based on "Testing "resources/views/emails/user_was_activated.blade.php" template,--}}
			{{--personal data of logged user.--}}
			{{--</p>--}}
			{{--</div>--}}
			{{--<input type="button" class="btn btn-primary" value="Run test" onclick="javascript:backendDashboard.emailTestUserWasRegistered(); return false;"--}}
			{{--id="btn_run_search">--}}
			{{--<div class="card-footer bg-transparent border-success">--}}
			{{--<small>Samples emails would be saved into "storage/email-previews" subdirectory and could be opened by--}}
			{{--clicking on popup message in left top corner.--}}
			{{--</small>--}}
			{{--</div>--}}

			{{--</div>--}}

			{{--</div>--}}


			{{--<div class="row">--}}
			{{--<div class="col-12 col-sm-6">--}}

			{{--<div class="card border-success mb-3" style="width: 100%;">--}}

			{{--<div class="card-header bg-transparent border-success"><i class="fa fa-envelope-square"></i>&nbsp;Email testing</div>--}}
			{{--<div class="card-body text-success">--}}
			{{--<h5 class="card-title">Testing "User was registered" email</h5>--}}
			{{--<p class="card-text">--}}
			{{--Testing "User was registered" email based on "Testing "resources/views/emails/user_was_registered.blade.php" template,--}}
			{{--personal data of logged user and pseudo password.--}}
			{{--</p>--}}
			{{--</div>--}}
			{{--<input type="button" class="btn btn-primary" value="Run test" onclick="javascript:backendDashboard.emailTestUserWasRegistered(); return false;"--}}
			{{--id="btn_run_search">--}}
			{{--<div class="card-footer bg-transparent border-success">--}}
			{{--<small>Samples emails would be saved into "storage/email-previews" subdirectory and could be opened by--}}
			{{--clicking on popup message in left top corner.--}}
			{{--</small>--}}
			{{--</div>--}}

			{{--</div>--}}

			{{--</div>--}}
			{{--<div class="col-12 col-sm-6">--}}

			{{--<div class="card border-success mb-3" style="width: 100%;">--}}

			{{--<div class="card-header bg-transparent border-success"><i class="fa fa-envelope-square"></i>&nbsp;Email testing</div>--}}
			{{--<div class="card-body text-success">--}}
			{{--<h5 class="card-title">Testing "User has subscriptions" email</h5>--}}
			{{--<p class="card-text">--}}
			{{--Testing "User has subscriptions" email based on "Testing "resources/views/emails/user_has_subscriptions.blade.php" template and--}}
			{{--subscriptions and personal data of logged user.--}}
			{{--</p>--}}
			{{--</div>--}}
			{{--<input type="button" class="btn btn-primary" value="Run test" onclick="javascript:backendDashboard.emailTestUserHasSubscriptions(); return false;"--}}
			{{--id="btn_run_search">--}}
			{{--<div class="card-footer bg-transparent border-success">--}}
			{{--<small>Samples emails would be saved into "storage/email-previews" subdirectory and could be opened by--}}
			{{--clicking on popup message in left top corner.--}}
			{{--</small>--}}
			{{--</div>--}}

			{{--</div>--}}
			{{--</div>--}}

			{{--</div>--}}


			<div class="row">
				@if(count($newVotes) > 0)
					<div class="col-12 col-sm-6">
						<div class="card border-success mb-3" style="width: 100%;">

							<div class="card-header bg-transparent border-success"><i class="fa fa-location-arrow"></i>&nbsp;Needs for reaction</div>
							<div class="card-body text-success">
								<h5 class="card-title">New Votes(Drafts)</h5>
								@foreach($newVotes as $newVote)
									<p class="card-text">
										<a href="{{ route('admin.votes.edit', $newVote->id ) }}">{{ $newVote->name }}</a>
									</p>
								@endforeach
							</div>

						</div>
					</div>
				@endif

				@if(count($newContactUs) > 0)
					<div class="col-12 col-sm-6">
						<div class="card border-success mb-3" style="width: 100%;">

							<div class="card-header bg-transparent border-success"><i class="fa fa-location-arrow"></i>&nbsp;Needs for reaction</div>
							<div class="card-body text-success">
								<h5 class="card-title">New Contact us</h5>
								@foreach($newContactUs as $newContact)
									<p class="card-text">
										<a href="{{ url("/admin/contact-us/". $newContact->id) }}/edit">
											by {{ $newContact->author_name }} / {{
                                            $newContact->author_email }}
										</a> on {{ $viewFuncs->getFormattedDateTime($newContact->created_at) }}
									</p>
								@endforeach
							</div>

						</div>
					</div>
				@endif

				@if(count($newVotes) > 0)
					<div class="col-12 col-sm-6">
						<div class="card border-success mb-3" style="width: 100%;">

							<div class="card-header bg-transparent border-success"><i class="fa fa-taxi"></i>&nbsp;Service</div>
							<div class="card-body text-success">
								<h5 class="card-title">Refresh demo data</h5>
								<input type="button" class="btn btn-primary" value="Refresh" onclick="javascript:backendDashboard.refreshDemoData(); return false;">
								<div class="card-footer bg-transparent border-success">
									<small>Refresh Demo Data you could see Demo Data provided by the developer. Please waite, it can take some time.
									</small>
								</div>
							</div>

						</div>
					</div>
				@endif


				@if(!empty($is_developer_comp))
					{{--<div class="card" style="width: 18rem;">--}}
					{{--<img class="card-img-top" src=".../100px180/?text=Image cap" alt="Card image cap">--}}
					{{--<div class="card-body">--}}
					{{--<h5 class="card-title">Card title</h5>--}}
					{{--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
					{{--</div>--}}
					{{--<ul class="list-group list-group-flush">--}}
					{{--<li class="list-group-item">Cras justo odio</li>--}}
					{{--<li class="list-group-item">Dapibus ac facilisis in</li>--}}
					{{--<li class="list-group-item">Vestibulum at eros</li>--}}
					{{--</ul>--}}
					{{--<div class="card-body">--}}
					{{--<a href="#" class="card-link">Card link</a>--}}
					{{--<a href="#" class="card-link">Another link</a>--}}
					{{--</div>--}}
					{{--</div>--}}
				@endif
			</div>


			<div class="row">
				<div class="col-sm-12">
					<div class="card border-success mb-3" style="width: 100%;">
						<div class="card-header bg-transparent border-success">{!! $viewFuncs->showAppIcon('payment', 'transparent_on_white') !!}&nbsp;Payments</div>
						<div class="card-body text-success">
							<div id="div_payments_content"></div>
						</div>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-sm-12">
					<div class="card border-success mb-3" style="width: 100%;">
						<div class="card-header bg-transparent border-success"><i class="fa fa-archive"></i>&nbsp;Activity log</div>
						<div class="card-body text-success">
							<div id="div_activity_log_content"></div>
							<input type="button" class="btn btn-primary" value="Clear" onclick="javascript:backendDashboard.clearActivityLogRows(); return false;">
						</div>
					</div>
				</div>
			</div>


			@if(!empty($is_developer_comp))
			<div class="col-12 col-sm-12">
				<div class="card border-success mb-3" style="width: 100%;">

					<div class="card-header bg-transparent border-success"><i class="fa fa-external-link-square "></i>&nbsp;External links</div>
					<div class="card-body text-success">
						<a href="https://fontawesome.com/v4.7.0/icons/" class="a_link">https://fontawesome.com/v4.7.0/icons/</a>
						<br><a href="chrome://settings/?search=cach" class="a_link">chrome://settings/?search=cach</a>
						<br><a href="https://getbootstrap.com/docs/4.1/components/card/" class="a_link">
							Bootstrap 4.1 Card
						</a>
						<br><a href="http://bootstrap-4.ru/docs/4.1/migration/" class="a_link">
							bootstrap-41.ru Migration
						</a>

						<br><a href="http://localhost/phpmyadmin" class="a_link">
							Local phpmyadmin
						</a>
						<br>
						<br><a href="http://138.68.107.4/phpmyadmin" class="a_link">
							Server's Phpmyadmin
						</a>
						<br><a href="http://localhost/adminer/index.php" class="a_link">
							adminer
						</a>
						<br><a href="http://138.68.107.4/adminer.php" class="a_link">
							Server's adminer
						</a>
						<br><a href=" http://localhost/info.php" class="a_link">
							info
						</a>


					</div>
				</div>
			</div>
			@endif

			{{--<div class="row">--}}
			{{--<div class="col-12 col-sm-6">--}}
			{{--21--}}
			{{--</div>--}}
			{{--<div class="col-12 col-sm-6">--}}
			{{--12 <button class="btn btn-secondary"><i class="fa fa-user"></i> User Icon</button>--}}

			{{--</div>--}}
			{{--</div>--}}


			{{--<div class="card border-success mb-3" style="max-width: 18rem;">--}}
			{{--<div class="card-header bg-transparent border-success">Header</div>--}}
			{{--<div class="card-body text-success">--}}
			{{--<h5 class="card-title">Success card title</h5>--}}
			{{--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
			{{--</div>--}}
			{{--<div class="card-footer bg-transparent border-success">Footer</div>--}}
			{{--</div>--}}


		</section>  <!-- class="card-body" -->

	</div>
	<!-- /.page-wrapper  Page Content : dashboard index -->



@endsection


@section('scripts')


	<script src="{{ asset('js/'.$current_admin_template.'/admin/dashboard.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var oTable
        var backendDashboard = new backendDashboard('index',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendDashboard.onBackendPageInit('index')
        });

        /*]]>*/
	</script>


@endsection

