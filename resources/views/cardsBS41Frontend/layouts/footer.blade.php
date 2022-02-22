

@inject('viewFuncs', 'App\library\viewFuncs')



<div class="modal fade" tabindex="-1" role="dialog" id="div_frontend_search_modal"  aria-labelledby="frontend-search-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="frontend-search-modal-label">Search <span id="span_vote_by_days_report_details_content_title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >

                <div class="row m-1">
                    <label class="col-12 col-form-label" for="input_search">Enter text : </label>
                    <div class="col-12">
                        <input id="input_search" value="" size="255" class="form-control editable_field">
                    </div>
                </div>
                <div id="div_most_used_search_results"></div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="javascript:runSearchDialog(); return;"
                        ><span class="btn-label"><i class="fa fa-save"></i></span> &nbsp;Search
                </button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<footer >
    <div class="row menu-items-container">

        <div class="col">
            <a class="menu-item" href="{{ url('about') }}">
                <span class="icon"></span><span>About</span>
            </a>
        </div>


        @if(!empty($is_developer_comp))
            <div class="col">
                <a class="menu-item" href="{{ url('/laravel-websockets') }}">
                    <span class="icon"></span><span>Websockets</span>
                </a>
            </div>
        @endif

        
        <div class="col">
            <a class="menu-item" href="{{ url('security-privacy') }}">
                <span class="icon"></span><span>Security</span>
            </a>
        </div>

        <div class="col">
            <a class="menu-item" href="{{ url('terms-of-service') }}">
                <span class="icon"></span><span>Terms</span>
            </a>
        </div>

        <div class="col">
            <a class="menu-item" href="{{ route('page-contact-us') }}" >
                <span class="icon"></span><span>Contacts us</span>
            </a>
        </div>

        <div class="col">
            <a class="menu-item" href="{{ route('events-timeline') }}" >
                <span class="icon"></span><span>Events</span>
            </a>
        </div>

    </div>

    
    @if(!empty($copyright_text))
    <div class="row menu-items-container">
        <div class="col menu-item">
            <span style="display: none">
               {!! $viewFuncs->showRunningUnderSymbol() !!}/
               {!! $viewFuncs->showAppVersion() !!}
            </span>
            {!! $copyright_text !!}
        </div>
    </div>
    @endif

    
</footer>