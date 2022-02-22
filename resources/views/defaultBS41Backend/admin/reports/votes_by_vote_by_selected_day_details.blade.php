@inject('viewFuncs', 'App\library\viewFuncs')
<section>
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="accordion " id="accordionExample">

                <?php $row_number= true; ?>
                @foreach($detailedVoteItemUsersResults as $nextDetailedVoteItemUsersResult)
                <div class="card">
                    <div class="card-header" id="heading_{{ $nextDetailedVoteItemUsersResult['vote_id'] }}">
                        <h5 class="mb-2 mt-0" style=" overflow-wrap: break-word">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse_{{
                            $nextDetailedVoteItemUsersResult['vote_id'] }}" aria-expanded="{{  $row_number }}"
                                    aria-controls="collapse_{{ $nextDetailedVoteItemUsersResult['vote_id'] }}" style=" overflow-wrap: break-word">
                                <i class="fa fa-ad"></i><i class="fa fa-level-down mr-3"></i>

                                <span style=" overflow-wrap: break-word">
                                    {{ $nextDetailedVoteItemUsersResult['vote_name'] }}
                                    <small>{{ count($nextDetailedVoteItemUsersResult['subResults']) }} {{ \Illuminate\Support\Str::plural('vote', count($nextDetailedVoteItemUsersResult['subResults']) ) }}</small>
                                </span>

                            </button>
                        </h5>
                    </div>

                    <div id="collapse_{{ $nextDetailedVoteItemUsersResult['vote_id'] }}" class="collapse fade @if($row_number) show @endif" aria-labelledby="heading_{{
                    $nextDetailedVoteItemUsersResult['vote_id'] }}" data-parent="#accordionExample">
                        <div class="card-body">

                            <table class="table table-bordered text-primary ">
                            @foreach($nextDetailedVoteItemUsersResult['subResults'] as $nextSubResultRow)
                                    <tr>
                                        <td>
                                            <a href="{{ url('/admin/users/' . $nextSubResultRow['user_id'] ) }}/edit" target="_blank">
                                                 {{ $nextSubResultRow['username'] }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $viewFuncs->wrpGetVoteItemUsersResultIsCorrectLabel($nextSubResultRow['is_correct']) }}
                                        </td>
                                        <td>
                                            at {{ $viewFuncs->getFormattedDateTime($nextSubResultRow['voted_at']) }}
                                        </td>
                                    </tr>

                            @endforeach
                            </table>


                        </div>
                    </div>
                </div>
                <?php $row_number= false; ?>
                @endforeach


            </div>
        </div>
    </div>
</section>
