<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@yield('meta_content')

	<title>@if(isset($site_name)){{ $site_name }}@endif</title>


	<!-- Styles -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="/favicon.ico"/>
	<link rel="alternate" type="application/atom+xml" title="News" href="/feed">

`
	<link href="{{ asset('css/'.$frontend_template_name.'/frontend.css') }}{{  "?dt=".time()  }}" rel="stylesheet" type="text/css">
    <?php  if ( ! empty($_REQUEST['is_debug']) and (int)$_REQUEST['is_debug'] == 9) { ?>
	<link href="{{ asset('css/debug.css') }}{{  "?dt=".time()  }}" rel="stylesheet" type="text/css">
    <?php  } ?>


	{{--iPhone portrait 320 x 480 --}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_xs_320.css') }}" media="only screen and (min-width: 320px) and (max-width: 479px)
"/>

	{{--iPhone landscape 480 x 320--}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_xs_480.css') }}"
	      media="only screen and (min-width: 480px)  and (max-width: 599px) "/>

	{{--Kindle portrait 600 x 1024--}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_xs_600.css') }}"
	      media="only screen and (min-width: 600px)  and (max-width: 767px) "/>

	{{--iPad portrait 768 x 1024--}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_sm.css') }}" media="only screen and (min-width: 768px)  and (max-width: 1023px) "/>

	{{--iPad landscape 1024 x 768--}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_md.css') }}" media="only screen and (min-width: 1024px) and (max-width: 1279px) "/>

	{{--Macbook 1280 x 800--}}
	<link rel="stylesheet" type="text/css" href="{{ url('css/'.$frontend_template_name.'/style_lg.css') }}" media="only screen and (min-width: 1280px)"/>
	<!-- Scripts -->



	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/popper.js')  }}"></script>

	<script src="{{ asset('/js/bootstrap.js') }}"></script>
	<script src="{{ asset('/js/jquery.bootstrap-growl.min.js') }}"></script>

	<script src="{{ asset('js/chosen.jquery.js') }}"></script>

	<script src="{{ asset('js/app/app_funcs.js') }}{{  "?dt=".time()  }}"></script>
	
	<script src="{{ asset('js/app.js') }}{{  "?dt=".time()  }}" defer></script>



</head>
<body>

<wrapper class="d-flex flex-column app-wrapper">

	<main class="flex-fill">
		@yield('content')
	</main>

	@include($frontend_template_name.'.layouts.footer')

</wrapper>


@section('scripts')


@endsection

@yield('scripts')


</body>

</html>