@extends($frontend_template_name.'.layouts.frontend')
@inject('viewFuncs', 'App\library\viewFuncs')

@section('content')
	<div class="row justify-content-center">

		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Login') }}</div>

				<div class="card-body">
					<form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
						{{--@csrf--}}
						{{ csrf_field() }}

						<div class="form-row m-4">
							<label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

							<div class="col-md-6">

								@include($frontend_template_name.'.layouts.page_header')


								@if ( !empty($show_demo_admin_on_login) and !empty($demo_admin_email) )
									<small>In this <b>demo</b> you can login into the system under <b>demo creditials</b> : {{ $demo_admin_email }} / {{ $demo_admin_password }}
									</small>
									<input id="email" type="email" class="form-control" name="email" value="{{ $demo_admin_email }}" required autofocus>
								@else
									<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"
									       autofocus>
								@endif


								@if ($errors->has('email'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-row m-4">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

							<div class="col-md-6">
								@if ( !empty($show_demo_admin_on_login) and !empty($demo_admin_password) )
									<input id="password" type="password" class="form-control" name="password" required value="{{ $demo_admin_password }}">
								@else
									<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
								@endif

								@if ($errors->has('password'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									{!! $viewFuncs->showStylingCheckbox('remember', 1, false, '', []  ) !!}
									<label class="form-check-label" for="remember">
										{{ __('Remember Me') }}
									</label>
								</div>
							</div>
						</div>

						<div class="form-row m-4">
							<div class="col-sm-12 col-md-6 offset-md-3" style="display: flex; justify-content: center;flex-direction: row">
								<button type="submit" class="btn btn-primary">
									{{ __('Login') }}
								</button>
								<a class="btn btn-link" href="{{ route( 'forgot-password' ) }}">
									{{ __('Forgot Your Password?') }}
								</a>
							</div>

							<div class="col-sm-12 col-md-6 offset-md-3" style="display: flex; justify-content: center;flex-direction: row">

								<a class="btn btn-link" href="{{ route('account-register-details') }}">
									{{ __('Register') }}
								</a>

							</div>
						</div>



						@if( ( !empty($allow_facebook_authorization) and $allow_facebook_authorization == 'Y' ) OR ( !empty($allow_google_authorization) and
						$allow_google_authorization == 'Y' ) OR ( !empty($allow_github_authorization) and $allow_github_authorization == 'Y' ) OR ( !empty($allow_linkedin_authorization) and $allow_linkedin_authorization == 'Y' ) OR ( !empty($allow_twitter_authorization) and $allow_twitter_authorization == 'Y' ) OR ( !empty($allow_instagram_authorization) and $allow_instagram_authorization == 'Y' ) )
						<div class="form-row mb-3">
							<fieldset class="notes-block text-muted">
								<legend class="notes-blocks">Login using...</legend>

								@if( !empty($allow_facebook_authorization) and $allow_facebook_authorization == 'Y' )
									<a href="{{url('auth/facebook')}}" class="btn btn-primary a_link" style="width: 200px;" >
										<i class="fa fa-facebook fa-submit-button"></i>&nbsp;Facebook
									</a>
									<hr>
								@endif

								@if( !empty($allow_google_authorization) and $allow_google_authorization == 'Y' )
									<a href="{{url('auth/google')}}" class="btn btn-primary a_link" style="width: 200px;">
										<i class="fa fa-google fa-submit-button"></i>&nbsp;Google
									</a>
									<hr>
								@endif

								@if( !empty($allow_github_authorization) and $allow_github_authorization == 'Y' )
									<a href="{{url('auth/github')}}" class="btn btn-primary a_link" style="width: 200px;">
										<i class="fa fa-github fa-submit-button"></i>&nbsp;Github
									</a>
									<hr>
								@endif

								@if( !empty($allow_linkedin_authorization) and $allow_linkedin_authorization == 'Y' )
									<a href="{{url('auth/linkedin')}}" class="btn btn-primary a_link" style="width: 200px;">
										<i class="fa fa-linkedin fa-submit-button"></i>&nbsp;Linkedin
{{--										<small>(Temporary disabled - return later)</small>--}}
									</a>
									<hr>
								@endif

								@if( !empty($allow_twitter_authorization) and $allow_twitter_authorization == 'Y' )
									<a href="{{url('auth/twitter')}}" class="btn btn-primary a_link" style="width: 200px;">
										<i class="fa fa-twitter fa-submit-button"></i>&nbsp;Twitter
									</a>
									<hr>
								@endif

								@if( !empty($allow_instagram_authorization) and $allow_instagram_authorization == 'Y' )
									<a href="{{url('auth/instagram')}}" class="btn btn-primary a_link" style="width: 200px;">
										<i class="fa fa-instagram fa-submit-button"></i>&nbsp;Instagram ??
									</a>
									<hr>
								@endif

							</fieldset>
						</div>
						@endif


					</form>
				</div>
			</div>
		</div>

	</div>
@endsection
