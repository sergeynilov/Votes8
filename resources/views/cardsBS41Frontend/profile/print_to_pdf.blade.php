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
        {{ Breadcrumbs::render('profile', 'Profile preview') }}
    </div>

    <form method="POST" action="{{ url('/profile/print-to-pdf') }}" accept-charset="UTF-8" id="form_print_to_pdf_options_dialog" name="form_print_to_pdf_options_dialog"
          enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" id="background_color" name="background_color" value="{{$background_color}}">
        <input type="hidden" id="title_font_name" name="title_font_name" value="{{$title_font_name}}">
        <input type="hidden" id="title_font_size" name="title_font_size" value="{{$title_font_size}}">
        <input type="hidden" id="title_font_color" name="title_font_color" value="{{$title_font_color}}">

        <input type="hidden" id="subtitle_font_name" name="subtitle_font_name" value="{{$subtitle_font_name}}">
        <input type="hidden" id="subtitle_font_size" name="subtitle_font_size" value="{{$subtitle_font_size}}">
        <input type="hidden" id="subtitle_font_color" name="subtitle_font_color" value="{{$subtitle_font_color}}">

        <input type="hidden" id="notes_font_name" name="notes_font_name" value="{{$notes_font_name}}">
        <input type="hidden" id="notes_font_size" name="notes_font_size" value="{{$notes_font_size}}">
        <input type="hidden" id="notes_font_color" name="notes_font_color" value="{{$notes_font_color}}">
    </form>


    <form method="POST" action="{{ url('/profile/generate-pdf-by-content') }}" accept-charset="UTF-8" id="form_print_to_pdf_content" name="form_print_to_pdf_content"
          enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="form-row m-3">
            <div class="col-12 col-sm-2">
                <button type="button" class="btn btn-primary" onclick="javascript:frontendPrintToPdf.openPrintToPdfOptions()">
                    <span class="btn-label"><i class="fa fa-filter"></i></span> &nbsp;Options
                </button>
            </div>
            <div class="col-12 col-sm-4">
                {!! $viewFuncs->select('option_output_file_format', $outputFileFormatsList,'', "form-control editable_field chosen_select_box", [] ) !!}
            </div>
            <div class="col-12 col-sm-4">
                {!! $viewFuncs->text('option_output_filename', 'profile', "form-control editable_field", [ "maxlength"=>"50", "autocomplete"=>"off", 'placeholder'=> 'Enter name of generated file' ] ) !!}
            </div>
            <div class="col-12 col-sm-2">
                <button type="button" class="btn btn-primary" onclick="javascript:frontendPrintToPdf.generatePrintToPdfOptions()">
                    <span class="btn-label"><i class="fa fa-plus-square-o"></i></span> &nbsp;Generate
                </button>
            </div>
        </div>

        <input type="hidden" id="pdf_content" name="pdf_content" value="">

    </form>



    <div class="card" style="width: 100%; background-color: {{ $background_color }} !important; border:0px solid green;" id="div_profile_content">

        <div class="col-sm-12">

            {{--            class="pull-left profile_image_left_aligned"--}}
            @if( !empty($fullPhotoData['full_photo_url']) and !empty($fullPhotoData['full_photo']) )
                <img class="left_aligned_profile_image" src=" {{ asset($fullPhotoData['full_photo_url']) }}{{  "?dt=".time()  }}" alt="Full photo"
                     height="{{ $fullPhotoData['file_height'] }}" width="{{ $fullPhotoData['file_width'] }}">
            @endif


            <div class="card-body" style="background-color: {{ $background_color }} !important;">
                <h4 class="card-title"
                    style="font-family: '{{ $title_font_name }}'; font-size: {{ $title_font_size }} !important; color: {{ $title_font_color }} !important;">
                    {!! $userProfile['username'] !!} ({!! $userProfile['full_name'] !!}), {!! $userProfile['email'] !!}
                    @if(!empty($userProfile['sex']))
                        , {!! $viewFuncs->wrpGetUserSexLabel($userProfile['sex']) !!}
                    @endif
                </h4>


{{--                <input type="hidden" id="notes_font_name" name="notes_font_name" value="{{$notes_font_name}}">--}}
{{--                <input type="hidden" id="notes_font_size" name="notes_font_size" value="{{$notes_font_size}}">--}}
{{--                <input type="hidden" id="notes_font_color" name="notes_font_color" value="{{$notes_font_color}}">--}}

            @if(!empty($userProfile['notes']))
                    <p class="card-text"
                       style="font-family: '{{ $notes_font_name }}'; font-size: {{ $notes_font_size }} !important; color:{{ $notes_font_color }} !important; background-color: {{ $background_color }} !important;">
                        {!! Purifier::clean( $viewFuncs->nl2br2($userProfile['notes']) ) !!}
                    </p>
                @endif

                <p class="card-text"
                   style="font-family: '{{ $content_font_name }}'; font-size: {{ $content_font_size }} !important; color:{{ $content_font_color }} !important; background-color:
                   {{ $background_color }} !important; margin-left: 15px;">
                    {!! $viewFuncs->wrpGetUserStatusLabel($userProfile['status']) !!} as {!! $viewFuncs->getLoggedUserDisplayAccessGroupsName() !!}.&nbsp;
                    Activated at {!! $viewFuncs->getFormattedDateTime($userProfile['activated_at']) !!}
                </p>

                @if(count($profileUsersSiteSubscriptions) > 0)
                    <h5 class="card-subtitle "
                        style="font-family: '{{ $subtitle_font_name }}'; font-size: {{ $subtitle_font_size }} !important; color: {{ $subtitle_font_color }} !important;
                                background-color: {{ $background_color }} !important; margin: 15px;">
                        Has subscriptions</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($profileUsersSiteSubscriptions as $nextSelectedSubscription)
                            <li class="list-group-item"
                                style="font-family: '{{ $content_font_name }}'; font-size: {{ $content_font_size }} !important; color:{{ $content_font_color }} !important; ">
                                {{ $nextSelectedSubscription->site_subscription_name }}
                                @if( !empty($nextSelectedSubscription->vote_category_name) )
                                    (
                                    <small>{{ $nextSelectedSubscription->vote_category_name }} )</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif


            </div>

            @if( !empty($attachedImages) )
                @foreach($attachedImages as $nextAttachedImage)
                    <img class="left_aligned_profile_image" src=" {{ asset($nextAttachedImage['attached_image_url']) }}">
                    <div style="margin-bottom: 30px; font-family: '{{ $content_font_name }}'; font-size: {{ $content_font_size }} !important; color:{{ $content_font_color }} !important; ">
                        {!! $nextAttachedImage['text'] !!}
                    </div>
                @endforeach
            @endif

        </div> <!-- {{--			<div class="col-sm-12">--}} -->

        <style>
            .left_aligned_profile_image {
                float: left;
                border: 1px dotted gray;
                padding: 2px;
                margin: 5px;
                max-width: 380px;
                height: auto;
            }

        </style>
    </div>



    <div class="modal fade" tabindex="-1" role="dialog" id="div_print_to_pdf_options_modal" aria-labelledby="div_print_to_pdf_options-modal-label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="div_print_to_pdf_options-modal-label">Select options for generating pdf file</h5>
                    {{--<h5 class="modal-title">&nbsp;&nbsp;:--}}
                    {{--&nbsp;&nbsp;<small>--}}
                    {{--<span id="div_print_to_pdf_options_correct_count"></span> correct votes,--}}
                    {{--<span id="div_print_to_pdf_options_not_correct_count"></span> not correct votes--}}
                    {{--</small>--}}
                    {{--</h5>--}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="width: 100%">
                    <div id="div_print_to_pdf_options_results" style="width: 100%;"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="javascript:frontendPrintToPdf.selectedPrintToPdfOptions(); return;"
                    ><span class="btn-label"><i class="fa fa-save"></i></span> &nbsp;Select
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- /.page-wrapper Page Content : print to pdf -->

@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-colorpicker.css') }}"/>
    <script src="{{ asset('js/bootstrap-colorpicker.min.js')  }}"></script>
    {{--file:///_wwwroot/lar/Votes/node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js--}}

    <script src="{{ asset('js/'.$frontend_template_name.'/print_to_pdf.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendPrintToPdf = new frontendPrintToPdf('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendPrintToPdf.onFrontendPageInit('edit')
        });

        /*]]>*/
    </script>
@endsection


