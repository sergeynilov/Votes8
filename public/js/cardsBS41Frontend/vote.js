var this_frontend_home_url
var this_vote_id
var this_csrf_token
var this_filter_type
var this_filter_value


function frontendVote(page, paramsArray) {  // constructor of frontend Vote's editor - set all params from server
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if (page == "view") {
        this_vote_id = paramsArray.vote_id;
        this.LoadVoteItems()
    }
} // function frontendVote(Params) {  constructor of frontend Vote's editor - set all params from server


frontendVote.prototype.LoadVoteItems = function () {
    var href = this_frontend_home_url + "/load-vote-items/"+this_vote_id;
    
    $.ajax( {
        type: "GET",
        dataType: "json",
        url: href,
        success: function( response )
        {
            $('#div_vote_items').html(response.html)
        },
        error: function( error )
        {
            popupErrorMessage(error.responseJSON.message)
        }
    });
}


frontendVote.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()

    // if (page == "view") {
    // }
} // frontendVote.prototype.onFrontendPageInit= function(page) {


/////////   VOTES BLOCK START
frontendVote.prototype.makeVoteSelection = function () {
    var vote_item_radio= $('input[name=vote_item_radio]:checked').val()
    if ( parseInt(vote_item_radio) <=  0 || typeof vote_item_radio == "undefined") {
        popupAlert("Select option for voting !", 'danger') // 'info', 'success'
        return;
    }

    var href = this_frontend_home_url + "/make-vote-selection";
    $.ajax( {
        type: "POST",
        dataType: "json",
        url: href,
        data: {"vote_id": this_vote_id, "vote_item_id": vote_item_radio, "_token": this_csrf_token},
        success: function( response )
        {
            $('input[name=vote_item_radio]:checked').prop('checked', false);
            frontendVote.showVotesResults()
            popupAlert("Thank you for voting ! Your vote was added !", 'success') // 'info', 'success'

        },
        error: function( error )
        {
            $('input[name=vote_item_radio]:checked').prop('checked', false);
            popupAlert(error.responseJSON.message, 'danger') // 'info', 'success'
        }
    });
} // frontendVote.prototype.makeVoteSelection = function () {

frontendVote.prototype.showVotesResults = function () {
    $("#div_show_votes_results").css("display","none");
    $("#div_hide_votes_results").css("display","block");
    var href = this_frontend_home_url + "/get-vote-results-in-stars/"+this_vote_id;
    $.ajax( {
        type: "GET",
        dataType: "json",
        url: href,
        success: function( response )
        {
            $("#div_current_vote_results_in_stars").html(response.html);
        },
        error: function( error )
        {
            $('input[name=vote_item_radio]:checked').prop('checked', false);
            popupAlert(error.responseJSON.message, 'danger') // 'info', 'success'
        }
    });

} // frontendVote.prototype.showVotesResults = function () {

frontendVote.prototype.hideVotesResults = function () {
    $("#div_show_votes_results").css("display","block");
    $("#div_hide_votes_results").css("display","none");
} // frontendVote.prototype.hideVotesResults = function () {

/////////   VOTES BLOCK END


/////////   QUIZ QUALITY BLOCK START
frontendVote.prototype.MakeQuizQuality = function () {
    var quiz_quality_radio= $('input[name=quiz_quality_radio]:checked').val()
    if ( parseInt(quiz_quality_radio) <=  0 || typeof quiz_quality_radio == "undefined") {
        popupAlert("Select option for rating !", 'danger') // 'danger', 'success'
        return;
    }

    var href = this_frontend_home_url + "/make-quiz-quality";
    $.ajax( {
        type: "POST",
        dataType: "json",
        url: href,
        data: {"quiz_quality_id": quiz_quality_radio, "vote_id": this_vote_id, "_token": this_csrf_token},
        success: function( response )
        {
            $('input[name=quiz_quality_radio]:checked').prop('checked', false);
            frontendVote.showQuizQualityResults()
            popupAlert("Thank you for rating ! Your rate was added!", 'success')
        },
        error: function( error )
        {
            $('input[name=quiz_quality_radio]:checked').prop('checked', false);
            popupAlert(error.responseJSON.message, 'danger') // 'info', 'success'
        }
    });
} // frontendVote.prototype.MakeQuizQuality = function () {

frontendVote.prototype.showQuizQualityResults = function () {
    $("#div_show_quiz_quality_results").css("display","none");
    $("#div_hide_quiz_quality_results").css("display","block");
    var href = this_frontend_home_url + "/get-vote-quiz-quality-in-stars/"+this_vote_id;
    $.ajax( {
        type: "GET",
        dataType: "json",
        url: href,
        success: function( response )
        {
            $("#div_current_vote_quiz_quality_in_stars").html(response.html);
        },
        error: function( error )
        {
            $('input[name=vote_item_radio]:checked').prop('checked', false);
            popupAlert(error.responseJSON.message, 'danger') // 'info', 'success'
        }
    });

} // frontendVote.prototype.showQuizQualityResults = function () {

frontendVote.prototype.hideQuizQualityResults = function () {
    $("#div_show_quiz_quality_results").css("display","block");
    $("#div_hide_quiz_quality_results").css("display","none");
} // frontendVote.prototype.hideQuizQualityResults = function () {

/////////   QUIZ QUALITY BLOCK END
