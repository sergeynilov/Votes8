@inject('viewFuncs', 'App\library\viewFuncs')
{{--<div class="modal fade" tabindex="-1" role="dialog" id="div_show_todo_page_modal"  aria-labelledby="show-todo-page-modal-label" aria-hidden="true">--}}
    {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content" id="div_show_todo_page_header">--}}
            {{--<div class="modal-header">--}}
                {{--<h5 class="modal-title" id="show-todo-page-modal-label">To Do Editor</h5>--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                    {{--<span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
            {{--</div>--}}
            {{--<div class="modal-body m-0 p-0" style="width: 100%;">--}}
                {{--<div id="div_show_todo_page_content"></div>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-primary" onclick="javascript:backendFooter.saveTodoDialog( '{{ $csrf_token }}' ); return;"--}}
                {{--><span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save--}}
                {{--</button>--}}
                {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}



<div class="modal fade" tabindex="-1" role="dialog" id="div_system_info_modal"  aria-labelledby="system-info-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="div_system_info" style="display: none">
            <div class="modal-header">
                <h5 class="modal-title" id="system-info-modal-label">System info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="div_info_content"></div>
                bootstrap4 plugins:&nbsp;<b><span id="span_bootstrap4_plugins"></span></b><br>
                bootstrap4 version:&nbsp;<b><span id="span_bootstrap4_enabled"></span></b><br>
                jquery version:&nbsp;<b><span id="span_jquery_version"></span></b><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="row menu-items-container">
        {{--no-gutters--}}

        <div class="col">
            <a class="menu-item" href="{{ url('home') }}">
                <span class="icon"></span><span>Home</span>
            </a>
        </div>

{{--        <div class="col">--}}
{{--            <a class="menu-item" href="{{ url('logs') }}">--}}
{{--                <span class="icon"></span><span>Logs</span>--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <div class="col">--}}
{{--            <a class="menu-item" href="/dump.html">--}}
{{--                <span class="icon"></span><span>Dump</span>--}}
{{--            </a>--}}
{{--        </div>--}}


        @if(!empty($is_developer_comp))
        <div class="col">
            <a class="menu-item" href="{{ url('/laravel-websockets') }}">
                <span class="icon"></span><span>Websockets</span>
            </a>
        </div>
        @endif


        <div class="col">
            <a class="menu-item" href="{{ url('about') }}">
                <span class="icon"></span><span>About</span>
            </a>
        </div>

        <div class="col">
            {{--var href = '/admin/show-todo-page';--}}
            {{--<a class="menu-item" onclick="javascript:backendFooter.showTodoPage()" href="#">--}}
            <a class="menu-item" href="{{ url('/admin/todo-container-page') }}">
                <span class="icon"></span><span>To Do</span>
            </a>
        </div>

        <div class="col">
            <a class="menu-item" onclick="javascript:backendFooter.showSystemInformation()" href="#">
                <span class="icon"></span><span>System</span>
            </a>
        </div>

    </div>

    @if(!empty($copyright_text))
        <div class="row menu-items-container">
            <div class="col menu-item">
                {!! $copyright_text !!}
            </div>
        </div>
    @endif

</footer>
{{--backend_todo_tasks_popup::{{ $backend_todo_tasks_popup }}--}}
<script src="{{ asset('/js/'.$current_admin_template.'/admin/footer.js') }}{{  "?dt=".time()  }}"></script>

<script>
    /*<![CDATA[*/


    var backendFooter = new backendFooter(
            <?php echo json_encode( $viewFuncs->getFooterParams() ) ?>
    );
    jQuery(document).ready(function ($) {
        if ( '{{$backend_todo_tasks_popup}}' == "Y" ) {
            backendFooter.showLoggedUserInfo()
        }
    });

    /*]]>*/
</script>
