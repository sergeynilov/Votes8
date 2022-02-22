@inject('viewFuncs', 'App\library\viewFuncs')

@if($total_rows > 0)
<h4>
    {{ count($activityLogRows) }} of {{$total_rows}} activity log rows
</h4>
@endif

@if(count($activityLogRows) > 0)
    <div class="table-responsive">
        <table class="table table-bordered text-primary ">
            @foreach($activityLogRows as $nextActivityLog)
                <tr>

                    <td>
                        at {{ $nextActivityLog->log_name }}
                    </td>

                    <td>
                        at {!!  Purifier::clean($nextActivityLog->description)  !!}
                    </td>

                    <td>
                        {{ ucfirst(str_replace('_',' ', $nextActivityLog->causer_type )) }}
                    </td>

                    <td>
                        at {{ $viewFuncs->getFormattedDateTime($nextActivityLog->created_at) }}
                    </td>

                    <td>
                        at {!!  Purifier::clean($nextActivityLog->description)  !!}
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="row">
            {{ $activityLogRows->appends([])->links() }}
            &nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-primary" value="Refresh" onclick="javascript:backendDashboard.showActivityLogRows(1); return false;">
        </div>

    </div>
@else
    <div class="alert alert-warning" role="alert">
        <p>No activity log yet !</p>
    </div>
@endif


