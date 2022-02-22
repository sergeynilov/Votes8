@inject('viewFuncs', 'App\library\viewFuncs')
<?php $new_votes_count= $viewFuncs->getNewVotesCount();?>
<?php $new_contact_us_count= $viewFuncs->getNewContactUsCount();?>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="dropdown-item @if($current_controller_name == 'DashboardController') active @endif" href="{{ route('admin.dashboard') }}">

        {!! $viewFuncs->showAppIcon('dashboard','selected_dashboard') !!}Dashboard
        @if(!empty($is_developer_comp))
            {!! $viewFuncs->showAppIcon('bug', 'warning', 'Development Mode 012') !!}
        @endif

        @if(!empty($is_running_under_docker) and !empty($is_developer_comp) )
            &nbsp;<i class="fa fa-cubes" style="color: blue;" >DOCKER</i>
        @endif

    </a>


    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

            @guest
                <li><a href="{{ route('login') }}">&nbsp;&nbsp;Login</a></li>
                <li><a href="{{ route('account-register-details') }}">&nbsp;&nbsp;Register</a></li>
                @else


                    <li class="nav-item dropdown dmenu">
                        <a class="nav-link @if($current_controller_name == 'VotesController' or $current_controller_name == 'VoteCategoriesController') dropdown-toggle-active @else
                                dropdown-toggle @endif" href="#" id="navbardrop_votes" data-toggle="dropdown">Votes</a>


                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_votes">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VotesFilter', ['filter'=>'filter_status', 'A']) }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}Active votes
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VotesFilter', ['filter'=>'filter_status', 'N']) }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}New votes ( Drafts )
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VotesFilter', ['filter'=>'filter_is_homepage', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}Homepage votes
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VotesFilter', ['filter'=>'filter_is_all', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}All votes
                                </a>
                            </li>


                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.votes.create') }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}New vote
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VoteCategoriesFilter', ['filter'=>'filter_active', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('category','transparent_on_white') !!}Active vote categories
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VoteCategoriesFilter', ['filter'=>'filter_is_all', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('category','transparent_on_white') !!}All vote categories
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/vote-categories/create') }}">
                                    {!! $viewFuncs->showAppIcon('category','transparent_on_white') !!}New vote category
                                </a>
                            </li>


                        </ul>
                    </li>



                    <li class="nav-item dropdown dmenu ">
                        <a class="nav-link @if($current_controller_name == 'SiteSubscriptionsController') dropdown-toggle-active @else dropdown-toggle @endif" href="#"
                           id="navbardrop_site_ubscriptions" data-toggle="dropdown">Subscriptions</a>

                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_site_ubscriptions">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.SubscriptionsFilter', ['filter'=>'filter_active', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('subscription','transparent_on_white') !!}Active site subscriptions
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin.SubscriptionsFilter', ['filter'=>'filter_is_all', 1]) }}">
                                    {!! $viewFuncs->showAppIcon('subscription','transparent_on_white') !!}All site subscriptions
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/site-subscriptions/create') }}">
                                    {!! $viewFuncs->showAppIcon('subscription','transparent_on_white') !!}New site subscription
                                </a>
                            </li>




                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/paypal_plans') }}">
                                    {!! $viewFuncs->showAppIcon('paypal','transparent_on_white') !!}Paypal plans
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/service-subscriptions') }}">
                                    {!! $viewFuncs->showAppIcon('service','transparent_on_white') !!}Service subscriptions(in paypal)
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/service-subscriptions/create') }}">
                                    {!! $viewFuncs->showAppIcon('service','transparent_on_white') !!}New Service subscription(in paypal)
                                </a>
                            </li>


                        </ul>
                    </li>




                        <li class="nav-item dropdown dmenu ">
                            <a class="nav-link @if($current_controller_name == 'UsersController') dropdown-toggle-active @else dropdown-toggle @endif" href="#"
                               id="navbardrop_users"
                               data-toggle="dropdown">Users</a>

                            <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_users">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.UsersFilter', ['filter'=>'filter_status', 'A']) }}">
                                        {!! $viewFuncs->showAppIcon('users','transparent_on_white') !!}Active users
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.UsersFilter', ['filter'=>'filter_status', 'N']) }}">
                                        {!! $viewFuncs->showAppIcon('users','transparent_on_white') !!}New users
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.UsersFilter', ['filter'=>'filter_is_all', 1]) }}">
                                        {!! $viewFuncs->showAppIcon('users','transparent_on_white') !!}All users
                                    </a>
                                </li>


                                @if(!empty($is_developer_comp))
                                <li role="separator" class="divider"></li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.ChatsFilter', ['filter'=>'filter_status', 'A']) }}">
                                        {!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}New chats
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.ChatsFilter', ['filter'=>'filter_is_all', 1]) }}">
                                        {!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}All chats
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ url('admin/chats/create') }}">
                                        {!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}New chat
                                    </a>
                                </li>
                                @endif


                            </ul>
                        </li>




                    <li class="nav-item dropdown dmenu">
                        <a class="nav-link @if($current_controller_name == 'ContactUsController' or $current_controller_name == 'TagsController') dropdown-toggle-active
@else dropdown-toggle @endif" href="#"
                           id="navbardrop_content" data-toggle="dropdown">Content </a>

                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_content">
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/contact-us') }}">
                                    {!! $viewFuncs->showAppIcon('contact-us','transparent_on_white') !!}Contacts
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/tags') }}">
                                    {!! $viewFuncs->showAppIcon('tag','transparent_on_white') !!}Tags
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/tags/create') }}">
                                    {!! $viewFuncs->showAppIcon('tag','transparent_on_white') !!}New tag
                                </a>
                            </li>




                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/banners') }}">
                                    {!! $viewFuncs->showAppIcon('banner','transparent_on_white') !!}Banners
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/banners/create') }}">
                                    {!! $viewFuncs->showAppIcon('banner','transparent_on_white') !!}New banner
                                </a>
                            </li>




                            <li role="separator" class="divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/page-contents') }}">
                                    {!! $viewFuncs->showAppIcon('page-content','transparent_on_white') !!}Pages
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/page-contents/create') }}">
                                    {!! $viewFuncs->showAppIcon('page-content','transparent_on_white') !!}New page
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/external-news-importing') }}">
                                    {!! $viewFuncs->showAppIcon('external-link','transparent_on_white') !!}External news importing
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/external-news-importing/create') }}">
                                    {!! $viewFuncs->showAppIcon('external-link','transparent_on_white') !!}New external news importing
                                </a>
                            </li>


                            <li role="separator" class="divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/events') }}">
                                    {!! $viewFuncs->showAppIcon('event','transparent_on_white') !!}Events
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/events/create') }}">
                                    {!! $viewFuncs->showAppIcon('event','transparent_on_white') !!}New event
                                </a>
                            </li>


                        </ul>
                    </li>



                    <li class="nav-item dropdown dmenu">
                        <a class="nav-link @if($current_controller_name == 'ReportsController') dropdown-toggle-active @else dropdown-toggle @endif" href="#"
                           id="navbardrop_reports"
                           data-toggle="dropdown">Reports</a>

                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_reports">

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/report-votes-by-days') }}">
                                    {!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Votes by days
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/report-votes-by-vote-names') }}">
                                    {!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Votes by vote names
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/report-quizzes-rating') }}">
                                    {!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Quizzes rating
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ url('admin/report-compare-correct-votes') }}">
                                    {!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Compare correct votes
                                </a>
                            </li>


                            @if( !empty($is_developer_comp) )
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('admin/report-search-results') }}">
                                        {!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Most frequent search results
                                    </a>
                                </li>
                            @endif

                            @if( !empty($is_developer_comp) )
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('admin/report-payments') }}">
                                        {!! $viewFuncs->showAppIcon('payment','transparent_on_white') !!}Payments
                                    </a>
                                </li>
                            @endif

                        </ul>

                    </li>


                    @if( $new_votes_count > 0 or $new_contact_us_count > 0)
                    <li class="nav-item dropdown dmenu">
                        
                        <a class="nav-link dropdown-toggle" href="#"
                           id="navbardrop_notifications"
                           data-toggle="dropdown"><span class="fa fa-info" style="color: red;"></span></a>

                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_notifications">

                            @if($new_votes_count > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.VotesFilter', ['filter'=>'filter_status', 'N']) }}">
                                    {!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}{{ $new_votes_count }} New votes ( Drafts )
                                </a>
                            </li>
                            @endif

                            @if($new_contact_us_count > 0)
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/contact-us/filter/accepted/0') }}">
                                    {!! $viewFuncs->showAppIcon('contact-us','transparent_on_white') !!}{{ $new_contact_us_count }} new contact us messages
                                </a>
                            </li>
                            @endif

                        </ul>

                    </li>
                    @endif


                    <li class="nav-item dropdown dmenu">
                        <a class="nav-link  @if($current_controller_name == 'ProfileController') dropdown-toggle-active @else dropdown-toggle @endif" href="#"
                           id="navbardrop_logged_user" data-toggle="dropdown"><img alt="user" style="max-width: 32px; height: auto;"
                                                                                   src="{{$viewFuncs->getLoggedUserImage() }}{{  "?dt=".time()  }}"></a>
                        <ul class="dropdown-menu dropdown-menu-right sm-menu" aria-labelledby="navbardrop_logged_user">
                            <li>
                                <a class="dropdown-item" href="{{ url('/profile/view') }}">
                                    Profile
                                    @if(!empty($is_developer_comp))
                                        &nbsp;{{ $viewFuncs->getLoggedUserDisplayName() }}&nbsp;,
                                        <br>{{ $viewFuncs->getLoggedUserEmail() }}&nbsp;,
                                        <br>{{ $viewFuncs->getLoggedUserDisplayAccessGroupsName() }}
                                    @endif
                                </a>
                            </li>


                            @if(!empty($is_developer_comp))
                            <li>
                                <a class="dropdown-item" href="{{ url('/profile/user-chat-container') }}">
                                    {!! $viewFuncs->showAppIcon('chat','transparent_on_white') !!}Chat
                                </a>
                            </li>
                            @endif


                            <li role="separator" class="divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/settings') }}">
                                    {!! $viewFuncs->showAppIcon('settings','transparent_on_white') !!}Settings
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>


                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {!! $viewFuncs->showAppIcon('logout','transparent_on_white') !!}{{ __('Logout') }}
                                </a>
                            </li>

                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </li>
                    @endguest

        </ul>

    </div>
</nav>
