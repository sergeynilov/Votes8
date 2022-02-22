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
		Hello, {!! $to_user_name !!} !
	</h4>

	<p class="email_title">Thank you !</p>
	<p class="email_title">You contacted us at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site successfully !</p>

	<p class="email_subtitle">The administration of our site would review it soon and get in touch with you !</p>

	<p class="email_content">Your message was : </p>
	<div class="p-2 email_content" >
		{!! Purifier::clean($viewFuncs->nl2br2($message_text)) !!}
	</div>

	@if($user_was_registered)
		<hr>
		<p class="email_subtitle">
			As you were not registered at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site before,
			you were registered by your name/email you provided and you can activate your
			account by <a href="{{ $site_home_url }}/account/activation/{{ $verification_token  }}">Activation url</a>
		</p>
		<hr>
		<p class="email_subtitle">
			Thank you that you are with us !
		</p>
	@endif


	@include( 'emails.app_footer')
	@include( 'emails.emails_style')
</div>
</body>
</html>