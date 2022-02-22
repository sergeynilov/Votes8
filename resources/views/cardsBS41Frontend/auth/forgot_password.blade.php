@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">{{ __('Forgot password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.reset', ['token'=>$csrf_token]) }}" aria-label="{{ __('Forgot password') }}">
                        @csrf

                        <div class="form-row m-4">
                            <label for="email" class="col-sm-4 col-form-label text-md-right" >{{ __('Enter your e-mail') }}</label>

                            <div class="col-sm-6">

                                @include($frontend_template_name.'.layouts.page_header')

                                <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-row m-4 m-4">
                            <div class="col-sm-12 ml-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection