@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')
    <!-- Page Content : msg -->

        <h1 class="text-center">
            @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
            <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))<small>{{ $site_subheading }}</small>@endif
        </h1>

        @include($frontend_template_name.'.layouts.logged_user')


        <div id="page-wrapper" class="card text-center m-4">

            <div class="card-title">
                <h4 class="alert alert-{{$type}}" role="alert">
                    {!! $text !!}
                </h4>
            </div>

            @if( !empty($action) and $action == 'show-login' )
                <div class="card-footer"><a class="a_link" href="{{ route('login') }}">Login</a></div>
            @endif

            @if( !empty($action) and $action == 'show-profile' )
                <div class="card-footer"><a class="a_link" href="{{ route('profile-view') }}">Profile</a></div>
            @endif

            @if( !empty($action) and $action == 'return' and !empty($http_referer) and !empty($http_referer) )
                <div class="card-footer"><a class="a_link" href="{{ $http_referer }}">Return</a></div>
            @endif

            <div class="card-footer"><a class="a_link" href="{{ route('home') }}">Home</a></div>

        </div>

@endsection