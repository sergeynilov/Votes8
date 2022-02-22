<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>
<div class="wrapper">

	@include( 'emails.app_header')

	@inject('viewFuncs', 'App\library\viewFuncs')
	<h4 class="email_title">
		Hello, {!! $to_user_first_name !!} {!! $to_user_last_name !!} !
	</h4>

	
	<p class="email_title">You were registered at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site successfully !</p>


	<p class="email_subtitle">You need to activate your account by <a href="{{ $site_home_url }}/account/activation/{{ $verification_token  }}">Activation url</a></p>

	<p class="email_subtitle">to login under your credentials :<br>
		User's email : {!! $to_user_email !!}<br>
		Password : {!! $to_user_password !!}
	</p>

	@if(!empty($usersSiteSubscriptionsList))
		<hr>
		<p class="email_title">During registration you subscribed at news :
			@foreach($usersSiteSubscriptionsList as $nextUsersSiteSubscription)
				{!! $nextUsersSiteSubscription['name'] !!}<br>
			@endforeach
		</p>
	@endif


	@include( 'emails.app_footer')
	@include( 'emails.emails_style')

</div>
</body>
</html>