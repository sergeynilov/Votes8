@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')
    {{ csrf_field() }}

    <!-- Page Content : user edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('users','transparent_on_white') !!}Edit user</h4>

            <form method="POST" action="{{ url('/admin/users/'.$user->id) }}" accept-charset="UTF-8" id="form_user_edit" enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}

            <ul class="nav nav-pills mb-3 " id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-user-details-tab" data-toggle="pill" href="#pills-user-details" role="tab" aria-controls="pills-user-details"
                       aria-selected="true">Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="pills-user-subscriptions-tab" data-toggle="pill" href="#pills-user-subscriptions" role="tab" aria-controls="pills-user-subscriptions"
                       aria-selected="false">Subscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-chats-tab" data-toggle="pill" href="#pills-chats" role="tab" aria-controls="pills-chats"
                       aria-selected="true">Chats
                    </a>
                </li>
            </ul>

            <div class="tab-content " id="pills-tabContent">
                <div class="tab-pane active" id="pills-user-details" role="tabpanel" aria-labelledby="pills-user-details-tab">
                @include($current_admin_template . '.admin.user.form')
                </div>

                <div class="tab-pane fade" id="pills-user-subscriptions" role="tabpanel" aria-labelledby="pills-user-subscriptions-tab">
                    <div id="div_related_site_subscriptions"></div>
                </div>

                <div class="tab-pane fade" id="pills-chats" role="tabpanel" aria-labelledby="pills-chats-tab">
                    <div id="div_related_chats"></div>
                </div>


            </div>

            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : user edit -->



@endsection


@section('scripts')

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor("notes_container", "notes", 460, 360);
    </script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/user.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#form_user_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendUser = new backendUser('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendUser.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>

@endsection

