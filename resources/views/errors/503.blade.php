<?php $frontend_template_name= 'cardsBS41Frontend';?>
@extends($frontend_template_name.'.layouts.frontend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <div id="page-wrapper" class="card text-center">

        <div class="card-title">
            <h4 class="alert alert-danger">

                @if( !empty($exception->getMessage()) )
                    {{ $exception->getMessage() }}.
                @else
                    The side is under development now.<br> Please, try later.
                @endif

                @if(!empty($exception->retryAfter))
                    <br>Please, try later ( in {{ $exception->retryAfter }} seconds ).
                @endif

            </h4>
        </div>

    </div>
@endsection
