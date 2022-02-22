@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($siteSubscriptionsList) == 0)
    <h4 class="card-subtitle pl-2 pb-2">Has no related site subscriptions</h4>
@else
    <h4 class="card-subtitle pl-2 pb-2">Has {{ count($siteSubscriptionsList) }} site subscriptions, {{ $selected_site_subscriptions_count }} selected for this user. </h4>

    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
        <table class="table table-bordered table-striped text-primary ">
            @foreach($siteSubscriptionsList as $next_key => $nextSiteSubscription)
                <tr>
                    <td>
                        {{ $nextSiteSubscription['name'] }} ( <small>{{ $viewFuncs->wrpGetSiteSubscriptionActiveLabel($nextSiteSubscription['active'] )}}</small> )
                    </td>
                    <td>

                        @if(!empty($nextSiteSubscription['selected']))
                            <a href="#" onclick="javascript:backendUser.clearSiteSubscriptionToUser({{$nextSiteSubscription['id']}},'{{$nextSiteSubscription['name']}}')">
                                <i class="fa fa-remove"></i>
                            </a>
                            <b>Selected</b>&nbsp;
                        @else
                            <a href="#" onclick="javascript:backendUser.attachSiteSubscriptionToUser({{$nextSiteSubscription['id']}},'{{$nextSiteSubscription['name']}}')">
                                <i class="fa fa-paperclip"></i>
                            </a>
                        @endif

                    </td>

                </tr>
            @endforeach

        </table>
    </div>

@endif          `