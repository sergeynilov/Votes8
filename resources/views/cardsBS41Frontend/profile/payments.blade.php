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
                Profile : Payments
            </h4>

            <ul class="nav nav-pills mb-3 mt-5" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-user-payments-tab" data-toggle="pill" href="#profile-user-payments" role="tab"
                       aria-controls="profile-user-payments"
                       aria-selected="false">Made payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-payment-agreements-tab" data-toggle="pill" href="#profile-payment-agreements" role="tab"
                       aria-controls="profile-payment-agreements" aria-selected="true">
                        User's payment agreements
                    </a>
                </li>
            </ul>

            <div class="tab-content " id="pills-tabContent">
                <div class="tab-pane active" id="profile-user-payments" role="tabpanel" aria-labelledby="profile-user-payments-tab">
                    <div id="div_payments_content"></div>
                </div>

                <div class="tab-pane fade" id="profile-payment-agreements" role="tabpanel" aria-labelledby="profile-payment-agreements-tab">
                    <div id="div_load_payment_agreements"></div>
                </div>
            </div>


            <div class="form-row mb-3">
                <div class="row btn-group editor_btn_group ">
                    <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ URL::route('profile-view') }}'"
                            style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
                    </button>&nbsp;&nbsp;
                </div>
            </div>

        </section> <!-- class="card-body" -->


    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="div_payment_agreement_details_modal" aria-labelledby="payment-agreement-details-modal-label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payment-agreement-details-modal-label">Payment agreement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-2">
                    <div id="div_payment_agreement_details_modal_content"></div>
                </div>
                <div class="modal-footer p-1 pr-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.page-wrapper Page Content : profile edit   -->


@endsection


@section('scripts')

    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <script src="{{ asset('js/'.$frontend_template_name.'/profile.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendProfile = new frontendProfile('payments',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendProfile.onFrontendPageInit('payments')
        });

        /*]]>*/
    </script>


@endsection