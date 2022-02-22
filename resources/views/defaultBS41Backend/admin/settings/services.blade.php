@inject('viewFuncs', 'App\library\viewFuncs')

<div class="form-row mb-3">

    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">Site Mapping</legend>

        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">

                <div class="form-row mb-3">
                    <label class="col-12 col-sm-7 col-form-label" for="btn_generate_site_mapping">Generate Site Mapping</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary" onclick="javascript: backendSettings.generateSiteMapping() ; return false;" id="btn_generate_site_mapping">
                            <span class="btn-label">{!! $viewFuncs->showAppIcon('mapping','selected_dashboard') !!}</span> &nbsp;Generate
                        </button>&nbsp;&nbsp;
                    </div>
                </div>

            </form>
            <br>
            <div class="alert alert-info" role="alert" id="div_last_sitemapping_results">
                {{ $services_last_sitemapping_results }}
                @if( !empty($services_last_sitemapping_results_updated_at) )
                    <br> last generated at "{{ $services_last_sitemapping_results_updated_at }}"
                @endif
            </div>

        </div>

    </fieldset>

    <fieldset class="all-emails-copy-block text-muted">
        <legend class="all-emails-copy-blocks">Developer's mode</legend>

{{--        is_developer_comp:{{ $is_developer_comp }}--}}
        <div class="all-emails-copy-controls">
            <form role="form" autocomplete="off">


                <div class="form-row mb-3 mt-5" id="div_set_developers_mode_on" style="display: @if($is_developer_comp) none @endif">
                    <div class="col-12">
                        <h3>Developer's mode is Off</h3>
                    </div>

                    <label class="col-12 col-sm-7 col-form-label">Change Developer's mode</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary" onclick="javascript: backendSettings.setDevelopersModeOn() ; return false;" id="btn_set_developers_mode_on">
                            <span class="btn-label">{!! $viewFuncs->showAppIcon('bug','selected_dashboard', 'Development Mode 123') !!}</span> &nbsp;Set developer's mode On
                        </button>&nbsp;&nbsp;
                    </div>
                </div>


                <div class="form-row mb-3 mt-5" id="div_set_developers_mode_off" style="display: @if(!$is_developer_comp) none @endif">
                    <div class="col-12">
                        <h3>Developer's mode is On</h3>
                    </div>
                    <label class="col-12 col-sm-7 col-form-label">Change Developer's mode</label>
                    <div class="col-12 col-sm-5">
                        <button type="button" class="btn btn-primary" onclick="javascript: backendSettings.setDevelopersModeOff() ; return false;" id="btn_set_developers_mode_off">
                            <span class="btn-label">{!! $viewFuncs->showAppIcon('bug','selected_dashboard', 'Development Mode 456') !!}</span> &nbsp;Set developer's mode Off
                        </button>&nbsp;&nbsp;
                    </div>
                </div>


            </form>
            <br>

        </div>

    </fieldset>

</div>