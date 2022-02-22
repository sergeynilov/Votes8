<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>
<div class="wrapper">

	@inject('viewFuncs', 'App\library\viewFuncs')

	@include( 'emails.app_header')

	<h4 class="email_title">
		Hello, {{ $to_user_first_name }} {{ $to_user_last_name }} !
	</h4>

	<p class="email_subtitle">You reset your password at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site successfully !</p>

	<p class="email_subtitle">Now your password is : {{ $generated_password }}</p>

	@include( 'emails.app_footer')
	@include( 'emails.emails_style')

</div>
</body>
</html>