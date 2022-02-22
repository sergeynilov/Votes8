@if($votes->count() == 0)
    <p class="card-subtitle">Has no related votes</p>
@else
    <p class="card-subtitle">Has {{ $votes->count() }} related {{ \Illuminate\Support\Str::plural('vote', $votes->count()) }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-primary">
            @foreach($votes as $nextVote)
                <tr>

                    <td>
                        {{ $nextVote->name }}
                        <small>with {{ $nextVote->voteItems()->count() }} items</small>
                        '<a href="{{ route('admin.votes.edit', $nextVote->id ) }}"><i class=" fa fa-edit"></i></a>

                    </td>

                </tr>

            @endforeach
        </table>
    </div>

@endif
