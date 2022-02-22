<div class="row col-12 mt-3">
    @if(count($voteItems) > 0)
        <div class="col-12 col-sm-6 col-md-6 p-0 m-0 pr-2">

            <h4>
                <center>Make your choice</center>
            </h4>

            <div class=" row p-2">
                <table class="table text-primary">
                    @foreach($voteItems as $nextVoteItem)
                        <tr>
                            <td>
                                <?php $voteItemImagePropsAttribute = $nextVoteItem->getVoteItemImagePropsAttribute(); ?>
                                @if(isset($voteItemImagePropsAttribute['image_url']) )
                                    <div class="row">
                                        <a class="a_link" href="{{ $voteItemImagePropsAttribute['image_url'] }}">
                                            <img class=" pull-left vote_item_selection_image" src="{{ $voteItemImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{
                                        $nextVoteItem->name }}">
                                        </a>
                                    </div>
                                @endif
                                <div class="row mb-4">
                                    <input class="ml-2 mt-2" type="radio" name="vote_item_radio" id="vote_item_radio_{{ $nextVoteItem->name }}"
                                           value="{{ $nextVoteItem->id }}">
                                    &nbsp;<label for="vote_item_radio_{{ $nextVoteItem->name }}">{{ $nextVoteItem->name }}</label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="row p-2">
                <a class="btn btn-primary a_link" onclick="javascript:frontendVote.makeVoteSelection();">Make selection !</a>
            </div>

            <div class="row">
                <div id="div_show_votes_results" class="m-3">
                    <button onclick="javascript:frontendVote.showVotesResults()" class="a_link small">Show results</button>
                </div>

                <div id="div_hide_votes_results" style="display:none;" class="m-3">
                    <button onclick="javascript:frontendVote.hideVotesResults()" class="a_link small">Hide results</button>
                    <div id="div_current_vote_results_in_stars"></div>
                </div>
            </div>

        </div>
    @else
        <div class="col-12 col-sm-6 col-md-6">
            <div class="alert alert-warning" role="alert">
                <p>Has no voting items !</p>
            </div>
        </div>
    @endif

    @if(count($quizQualityOptions) > 0)
        <div class="col-12 col-sm-6 col-md-6 p-0 m-0 pl-2">
            <h4>
                <center>Rate quiz</center>
            </h4>

            <div class="table-responsive">
                <table class="table text-primary">
                    @foreach($quizQualityOptions as $key=>$next_quiz_quality_option)
                        <tr>
                            <td>
                                <input class="" type="radio" name="quiz_quality_radio" id="quiz_quality_radio_{{ $next_quiz_quality_option }}" value="{{ $key }}">
                                <label class="col-form-label" for="quiz_quality_radio_{{ $next_quiz_quality_option }}">{{ $next_quiz_quality_option }}</label>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="row p-3">
                <a class="btn btn-primary a_link" onclick="javascript:frontendVote.MakeQuizQuality()">Rate !</a>
            </div>

            <div class="row">
                <div id="div_show_quiz_quality_results" class="m-3">
                    <button onclick="javascript:frontendVote.showQuizQualityResults()" class="a_link small">Show results</button>
                </div>

                <div id="div_hide_quiz_quality_results" style="display:none;" class="m-3">
                    <button onclick="javascript:frontendVote.hideQuizQualityResults()" class="a_link small">Hide results</button>
                    <div id="div_current_vote_quiz_quality_in_stars"></div>
                </div>
            </div>


        </div>
    @endif
</div>