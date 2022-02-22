@inject('viewFuncs', 'App\library\viewFuncs')


@can('view', auth()->user())

	<div class="row small_title ml-1 mb-2 mt-2">
		<div class="mb-2 col-12 col-sm-6">
			Welcome, <a href="{{ route('profile-view') }}">{{ $viewFuncs->getLoggedUserDisplayName() }}</a>&nbsp;

			@if($viewFuncs->checkLoggedUserHasImage())
				<img alt="user" style="max-height: 24px; width: auto;" src="{{ $viewFuncs->getLoggedUserImage() }}{{  "?dt=".time()  }}">
			@endif

			&nbsp;<a href="{{ route('logout') }}">Logout</a>
		</div>

		<div class="mb-2 col-12 col-sm-6">
			@if( $viewFuncs->loggedUserHasAdminAccess() )
				&nbsp;&nbsp;&nbsp;<a href="{{ route('admin.dashboard') }}"><span class="fa fa-lock">&nbsp;</span><span style="text-decoration: underline">Backend</span></a>
			@endif

			@if( $viewFuncs->loggedUserHasSubscription() )
				&nbsp;{!! $viewFuncs->showLoggedUserSubscription() !!}&nbsp;
			@endif

			{!! $viewFuncs->showAppVersion() !!}

			@if(!empty($is_developer_comp))
				{!! $viewFuncs->showAppIcon('bug', 'warning', 'Development Mode 987') !!}
			@endif

			@if(!empty($is_developer_comp))
				<span class="fa fa-search-plus  float-right mr-2">&nbsp;<span onclick="javascript:showSearchDialog(false);">Search</span></span>
			@endif
			@if(!empty($is_running_under_docker) and !empty($is_developer_comp) )
				{!! $viewFuncs->showRunningUnderSymbol() !!}
			@endif
		</div>
	</div>

@else
	<div class=" small_title ml-1 mb-2 mt-2">
		You are not logged&nbsp;&nbsp;
		<a href="{{ route('login') }}">Login</a>&nbsp;
		<a href="{{ route('account-register-details') }}">Register</a>

		@if(!empty($is_developer_comp))
			<span class="fa fa-search-plus float-right mr-2">&nbsp;<span onclick="javascript:showSearchDialog(false);">Search</span></span>
		@endif
		&nbsp;&nbsp;{!! $viewFuncs->showAppVersion() !!}
		@if(!empty($is_running_under_docker) and !empty($is_developer_comp) )
			{!! $viewFuncs->showRunningUnderSymbol() !!}
		@endif

	</div>

@endcan