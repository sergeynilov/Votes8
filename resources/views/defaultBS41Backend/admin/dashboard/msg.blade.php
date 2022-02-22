@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')
    <!-- Page Content : Dashboard msg -->
    <div id="page-wrapper" class="card text-center">

        <div class="card-title">
            <h4 class="alert alert-{{$type}}">
                <p>{!! Purifier::clean($text) !!}</p>
            </h4>
        </div>

        @if( !empty($action) and $action == 'show-login' )
            <div class="card-footer"><a class="a_link" href="{{ route('login') }}">Login</a></div>
        @endif

        @if( !empty($action) and $action == 'return' and !empty($http_referer) and !empty($http_referer) )
            <div class="card-footer"><a class="a_link" href="{{ $http_referer }}">Return</a></div>
        @endif

        <div class="card-footer"><a class="a_link" href="{{ route('admin.dashboard') }}">Dashboard</a></div>

    </div>
@endsection
