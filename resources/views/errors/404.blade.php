<?php $frontend_template_name = 'cardsBS41Frontend'; ?>


@extends($frontend_template_name.'.layouts.frontend')

@section('content')
        @inject('viewFuncs', 'App\library\viewFuncs')

        <div id="page-wrapper" class="card text-center m-4">

            <div class="card-title">
                <h4 class="alert alert-danger">
                    You entered invalid url !
                </h4>
            </div>

            <div class="card-footer">
                <a class="a_link" href="{{ route('home') }}">Home</a>
            </div>

        </div>

@endsection