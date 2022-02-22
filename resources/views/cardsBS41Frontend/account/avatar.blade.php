@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : account avatar -->
    <div class="row bordered card" id="page-wrapper">

        <div class="card-body">

            <h1 class="text-center">
                @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
            </h1>

            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Registration : Avatar & Full photo
            </h4>

            <div class="row ml-1 mb-3">
                {{ Breadcrumbs::render('account-register', 'Registration : Avatar') }}
            </div>

            <form method="POST" action="{{ URL::route('account-register-avatar-post') }}" accept-charset="UTF-8" id="form_account_avatar_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}

                @include($frontend_template_name.'.account.register_steps', ['current_step'=> 2])

                @include($frontend_template_name.'.layouts.page_header')


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-3 col-form-label" for="avatar">Avatar</label>
                    <div class="col-12 col-sm-9">
                        {!! $viewFuncs->file('avatar') !!}
                    </div>
                </div>

                <div class="form-row mb-5">
                    <div class="alert alert-warning small" role="alert">
                        Please, restrict size of avatar within {{$avatar_dimension_limits['max_width']}}*{{$avatar_dimension_limits['max_height']}} px.
                    </div>
                </div>


                @if( !empty($avatar_filename) and !empty($avatar_filename_url) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label">Preview of uploaded avatar</label>
                        <div class="col-12 col-sm-8">
                            {{ $avatar_filename }} :<br>
                            <img src="{{$avatar_filename_url}}{{  "?dt=".time()  }}">
                            {{--$avatar_filename_url::{{ $avatar_filename_url  }}--}}
                        </div>
                    </div>
                @endif


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-3 col-form-label">Full photo</label>
                    <div class="col-12 col-sm-9">
                        {!! $viewFuncs->file('full_photo') !!}
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="alert alert-warning small" role="alert">
                        Please, restrict size of full_photo within {{$full_photo_dimension_limits['max_width']}}*{{$full_photo_dimension_limits['max_height']}} px.
                    </div>
                </div>


                @if( !empty($full_photo_filename) and !empty($full_photo_filename_url) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label">Preview of uploaded full photo</label>
                        <div class="col-12 col-sm-8">
                            {{ $full_photo_filename }} :<br>
                            <img src="{{$full_photo_filename_url}}{{  "?dt=".time()  }}">
                        </div>
                    </div>
                @endif


                @if(!empty($account_register_avatar_text))
                    <div class="form-row mb-3">
                        <fieldset class="notes-block text-muted">
                            <legend class="notes-blocks">Notes</legend>
                            {!! $account_register_avatar_text !!}
                        </fieldset>
                    </div>
                @endif


                <div class="row">
                    <div class="col-8 col-sm-8">
                        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ URL::route('account-register-cancel') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Cancel
                        </button>&nbsp;&nbsp;
                        <button type="button" class=" btn btn-secondary  " onclick="javascript:document.location='{{ URL::route('account-register-details') }}'"
                                style=""><span class="btn-label"></span> &nbsp;Prior
                        </button>&nbsp;&nbsp;
                    </div>
                    <div class="col-4 col-sm-4">
                        <div class="btn-group float-right mt-2" role="group">
                            <button type="submit" class="btn btn-primary float-right"><span class="btn-label"></span> &nbsp;Next
                            </button>
                        </div>
                    </div>
                </div>


            </form>

        </div> <!-- class="card-body" -->

    </div>

    <!-- /.page-wrapper Page Content : account avatar -->


@endsection


@section('scripts')

    <script src="{{ asset('js/cardsBS41Frontend/register.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendRegister = new frontendRegister('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendRegister.onFrontendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection