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

	<p class="email_title">Your account at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site was activated successfully !</p>

	<p class="email_subtitle">
		Now, you can login under your credentials :<br>
		User's email : {{ $to_user_email }} and password you entered during registration.
	</p>
	<hr>
	`    <p class="email_subtitle">
		Thank you that you are with us !
	</p>


	@include( 'emails.app_footer')
	@include( 'emails.emails_style')

</div>
</body>
</html>