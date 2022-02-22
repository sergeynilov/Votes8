@inject('viewFuncs', 'App\library\viewFuncs')
<h4>
    All votes {{ count($detailedVoteItemUsersResults) }} : {{ $correct_count }} correct, {{ $not_correct_count }} incorrect
</h4>
@if(count($detailedVoteItemUsersResults) > 0)
    <div class="table-responsive">
        <table class="table table-bordered text-primary ">
            @foreach($detailedVoteItemUsersResults as $nextDetailedVoteItemUsersResult)
                <tr>
                    <td>
                        <a href="{{ url('/admin/users/' . $nextDetailedVoteItemUsersResult->user_id ) }}/edit" target="_blank">
                            {{ $nextDetailedVoteItemUsersResult->first_name }} {{ $nextDetailedVoteItemUsersResult->last_name }} ( {{ $nextDetailedVoteItemUsersResult->username }}
                            )
                        </a>
                    </td>
                    <td>
                        {{ $viewFuncs->wrpGetVoteItemUsersResultIsCorrectLabel($nextDetailedVoteItemUsersResult->is_correct) }}
                    </td>
                    <td>
                        at {{ $viewFuncs->getFormattedDateTime($nextDetailedVoteItemUsersResult->voted_at) }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@else
    <div class="alert alert-warning" role="alert">
        <p>Nobody voted !</p>
    </div>
@endif


