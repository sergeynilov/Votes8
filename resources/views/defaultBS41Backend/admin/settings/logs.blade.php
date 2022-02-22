@inject('viewFuncs', 'App\library\viewFuncs')

<div class="form-row mb-3">

    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">laravel.log</legend>

        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-7 col-form-label" for="btn_laravel_log">View laravel log</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.viewLaravelLog() ; return false;" id="btn_laravel_log">
                            {!! $viewFuncs->showAppIcon('load','selected_dashboard', 'Load and view Laravel Log file') !!}Load
                        </button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.deleteLaravelLog() ; return false;" id="btn_laravel_log">
                            {!! $viewFuncs->showAppIcon('delete','selected_dashboard', 'Delete Laravel Log file') !!}Delete
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>
            <br>
            <div id="div_view_laravel_log" style="overflow-x: hidden; overflow-y: scroll; margin: 2px; max-height: 350px;">
            </div>

        </div>

    </fieldset>



    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">Paypal.log</legend>

        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-7 col-form-label" for="btn_paypal_log">View Paypal log</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.viewPaypalLog() ; return false;" id="btn_paypal_log">
                            {!! $viewFuncs->showAppIcon('load','selected_dashboard', 'Load and view Paypal Log file') !!}Load
                        </button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.deletePaypalLog() ; return false;" id="btn_paypal_log">
                            {!! $viewFuncs->showAppIcon('delete','selected_dashboard', 'Delete Paypal Log file') !!}Delete
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>
            <br>

            <div id="div_view_paypal_log" style="overflow-x: hidden; overflow-y: scroll; margin: 2px; max-height: 350px;"></div>

        </div>

    </fieldset>




    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">logging_deb.txt</legend>

        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-7 col-form-label" for="btn_logging_deb">View logging_deb.txt</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.viewLoggingDeb() ; return false;" id="btn_logging_deb">
                            {!! $viewFuncs->showAppIcon('load','selected_dashboard', 'Load and view logging_deb file') !!}Load
                        </button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.deleteLoggingDeb() ; return false;" id="btn_logging_deb">
                            {!! $viewFuncs->showAppIcon('delete','selected_dashboard', 'Delete logging_deb file') !!}Delete
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>
            <br>

            <div id="div_view_logging_deb" style="overflow-x: hidden; overflow-y: scroll; margin: 2px; max-height: 350px;"></div>

        </div>

    </fieldset>





{{--    sql-tracing-.txt--}}
    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">sql-tracing-.txt</legend>

        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-7 col-form-label" for="btn_sql_tracing_log">View sql tracing log</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.viewSqlTracingLog() ; return false;" id="btn_sql_tracing_log">
                            {!! $viewFuncs->showAppIcon('load','selected_dashboard', 'Load and view sql tracing log file') !!}Load
                        </button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary " onclick="javascript: backendSettings.deleteSqlTracingLog() ; return false;" id="btn_sql_tracing_log">
                            {!! $viewFuncs->showAppIcon('delete','selected_dashboard', 'Delete sql tracing log file') !!}Delete
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>
            <br>

{{--            <pre>--}}
                <div id="div_view_sql_tracing_log" style="overflow-x: hidden; overflow-y: scroll; margin: 2px; max-height: 350px;"></div>
{{--            </pre>--}}

        </div>

    </fieldset>


</div>


