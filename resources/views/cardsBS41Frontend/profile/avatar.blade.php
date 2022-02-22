@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-1 mb-3">
        {{ Breadcrumbs::render('profile', 'Profile preview', 'Avatar') }}
    </div>

    <!-- Page Content : profile edit -->
    <div class="row bordered card" id="page-wrapper">

        <section class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
                Profile : Avatar
            </h4>

            <form method="POST" action="{{ URL::route('profile-edit-avatar-put') }}" accept-charset="UTF-8" id="form_profile_avatar_edit" enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}

                @include($frontend_template_name.'.layouts.page_header')


                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label">Avatar</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->file('avatar') !!}
                    </div>
                </div>


                @if( !empty($avatar_filename) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label"></label>
                        <div class="col-12 col-sm-8">
                            {{ $avatar_filename }} :<br>
                            <img alt="user" style="max-width: 32px; height: auto;" src="{{$viewFuncs->getLoggedUserImage() }}{{  "?dt=".time()  }}">
                        </div>
                    </div>
                @endif
                <div class="alert alert-warning small mb-5" role="alert">
                    Please, restrict size of avatar within {{$avatar_dimension_limits['max_width']}}*{{$avatar_dimension_limits['max_height']}} px.
                </div>

                <div class="form-row mb-3 mt-4">
                    <label class="col-12 col-sm-4 col-form-label">Full photo</label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->file('full_photo') !!}
                    </div>
                </div>


                @if( !empty($full_photo_filename) )
                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label"></label>
                        <div class="col-12 col-sm-8">
                            {{ $full_photo_filename }} :<br>
                            <img alt="user" src="{{$viewFuncs->getLoggedUserFullPhoto() }}{{  "?dt=".time()  }}">
                        </div>
                    </div>
                @endif
                <div class="alert alert-warning small" role="alert">
                    Please, restrict size of full photo within {{$full_photo_dimension_limits['max_width']}}*{{$full_photo_dimension_limits['max_height']}} px.
                </div>




                @if(!empty($account_register_avatar_text))
                    <div class="form-row mb-3">
                        <fieldset class="notes-block text-muted">
                            <legend class="notes-blocks">Notes</legend>
                            {!! $account_register_avatar_text !!}
                        </fieldset>
                    </div>
                @endif

                <div class="form-row mb-3 float-right">
                    <div class="row btn-group editor_btn_group ">
                        <button type="submit" class="btn btn-primary" style=""><span
                                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
                        </button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ URL::route('profile-view') }}'"
                                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>

        </section> <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Page Content : profile edit   -->


@endsection


@section('scripts')

    <script src="{{ asset('js/'.$frontend_template_name.'/profile.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendProfile = new frontendProfile('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendProfile.onFrontendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection                                                                                                       t
