var this_backend_home_url
var this_csrf_token
var this_filter_type
var this_filter_value
var this_voteItemUCodesList = []
var this_voteItemUsersResultsCorrect = []
var this_voteItemUsersResultsNoneCorrect = [];

var this_monthsXCoordItems = [];
var this_voteValuesCorrect = [];
var this_voteValuesNoneCorrect = [];

var this_filter_start_date = ''
var this_filter_end_date = ''
var this_column_width = 10
var this_chartBackgroundColors = []


function backendReports(page, paramsArray) {  // constructor of backend Report's form
    // alert( "page::"+page+"  backendReports paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    this_filter_start_date = paramsArray.reports_demo_period_start;
    this_filter_end_date = paramsArray.reports_demo_period_end;
    this_chartBackgroundColors = paramsArray.chartBackgroundColors;
} // function backendReports(Params) {  constructor of backend Report's form


backendReports.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    // alert( "onBackendPageInit  page::"+var_dump(page) )
    backendInit()
    if (page == "votes_by_days") {
        this.runVotesReportByDays()
    }
    if (page == "votes_by_vote_names") {
        this.runVotesReportByVoteNames()
    }
    if (page == "quizzes_rating") {
        this.runReportQuizzesRating()
    }
    if (page == "compare_correct_votes") {
        this.runReportCompareCorrectVotes()
    }
    if (page == "search_results") {
        this.runReportSearchResults()
    }

    if (page == "payments") {
        this.runReportPayments()
    }

    var start_date_formatted = dbDateToDatePicker(this_filter_start_date);
    var end_date_formatted = dbDateToDatePicker(this_filter_end_date);

    if (page == "payments") {   // filter_payments_at_from_till
        $('input[name="filter_payments_at_from_till"]').daterangepicker({
            startDate: start_date_formatted,
            endDate: end_date_formatted,
            locale: {
                format: 'DD MMMM, YYYY'
            }
        });

        $('#filter_payments_at_from_till').on('apply.daterangepicker', function (ev, picker) {
            this_filter_start_date = picker.startDate.format('YYYY-MM-DD');
            this_filter_end_date = picker.endDate.format('YYYY-MM-DD');
            // console.log(picker.startDate.format('YYYY-MM-DD'));
            // console.log(picker.endDate.format('YYYY-MM-DD'));
        });
    } else {
        $('input[name="filter_voted_at_from_till"]').daterangepicker({
            startDate: start_date_formatted,
            endDate: end_date_formatted,
            locale: {
                format: 'DD MMMM, YYYY'
            }
        });

        $('#filter_voted_at_from_till').on('apply.daterangepicker', function (ev, picker) {
            this_filter_start_date = picker.startDate.format('YYYY-MM-DD');
            this_filter_end_date = picker.endDate.format('YYYY-MM-DD');
            // console.log(picker.startDate.format('YYYY-MM-DD'));
            // console.log(picker.endDate.format('YYYY-MM-DD'));
        });

    }
    
    // this.openItemsCountReportDetails('1', 'item_titleIIII', '2018-08-01', '2019-11-22 23:59:59', '')

} // backendReports.prototype.onBackendPageInit= function(page) { 


// VOTES BY DAYS BLOCK BEGIN

backendReports.prototype.clearrunVotesReportByDaysParameters = function () {
    $('#filter_user_id').val("");
    $('#filter_vote_category_id').val("");
    $('#filter_vote_id').val("");
}

backendReports.prototype.runVotesReportByDays = function () {
    var href = '/admin/report-votes-by-days-retrieve';
    var monthsXCoordItems = new Array();
    var voteValuesCorrect = new Array();
    var voteValuesNoneCorrect = new Array();

    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var selectedVotes = $('#filter_vote_id').val();

    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
        "selectedVotes": selectedVotes
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,

        success: function (response) {

            this.this_voteItemUsersResultsCorrect = response.voteItemUsersResultsCorrect
            this.this_voteItemUsersResultsNoneCorrect = response.voteItemUsersResultsNoneCorrect
            // alert( "this.this_voteItemUsersResultsCorrect::"+var_dump(this.this_voteItemUsersResultsCorrect) )

            if ( this.this_voteItemUsersResultsCorrect.length == 0 && this.this_voteItemUsersResultsNoneCorrect.length == 0 ) {
                popupAlert("No data found. Change search criteria !", 'danger')
            }

            response.voteItemUsersResultsCorrect.forEach(function (data) {
                monthsXCoordItems.push(data.formatted_created_at);
                voteValuesCorrect.push(data.count);
            });



            response.voteItemUsersResultsNoneCorrect.forEach(function (data) {
                voteValuesNoneCorrect.push(data.count);
            });

            backendReports.this_monthsXCoordItems= monthsXCoordItems;
            backendReports.this_voteValuesCorrect= voteValuesCorrect;
            backendReports.this_voteValuesNoneCorrect= voteValuesNoneCorrect;



            var lineCanvas = document.getElementById("canvasVotesByDays");
            var lineCanvasContext = lineCanvas.getContext('2d');
            $("#div_canvasVotesByDays").css("display", "block")

            var numberWithCommas = function (x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };
            var self = this;

            if (window.chartLineObject != undefined) { // clear existing instance
                window.chartLineObject.destroy();
            }
            // console.log("monthsXCoordItems::")
            // console.log(monthsXCoordItems)
            //
            //
            // console.log("voteValuesCorrect::")
            // console.log(voteValuesCorrect)
            //
            // console.log("voteValuesNoneCorrect::")
            // console.log(voteValuesNoneCorrect)

            window.chartLineObject = new Chart(lineCanvasContext, {
                type: 'line',
                data: {
                    labels: monthsXCoordItems,
                    datasets: [
                        {
                            label: 'Correct Votes',
                            data: voteValuesCorrect,
                            borderWidth: 1,           // The stroke width of the line in pixels.

                            backgroundColor: formatColor('#05b932'), //rgba(0, 0, 0, 0.1), // The fill color of the line.
                            borderColor: formatColor('#05b932'),// rgba(255, 0, 0, 0.1) // The color of the line border.
                            hoverBackgroundColor: formatColor('#05b932'),   // The fill colour of the lines when hovered.
                            hoverBorderColor: formatColor('#05b932'),        //    The stroke colour of the lines when hovered.
                            hoverBorderWidth: 1                             //    The stroke width of the lines when hovered.
                        },
                        {
                            label: 'Incorrect Votes',
                            data: voteValuesNoneCorrect,
                            borderWidth: 1,           // The stroke width of the line in pixels.

                            backgroundColor: formatColor('#b1a19a'), //rgba(0, 0, 0, 0.1), // The fill color of the line.
                            borderColor: formatColor('#b1a19a'),// rgba(255, 0, 0, 0.1) // The color of the line border.
                            hoverBackgroundColor: formatColor('#b1a19a'),   // The fill colour of the lines when hovered.
                            hoverBorderColor: formatColor('#b1a19a'),        //    The stroke colour of the lines when hovered.
                            hoverBorderWidth: 1                             //    The stroke width of the lines when hovered.
                        }
                    ]
                },

                options: { // options of Report By Vote Days ( 'line' report )
                    animation: {
                        duration: 10,
                    },

                    tooltips: { // tooltip text of Report By Vote Days ( 'line' report )
                        mode: 'label',
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
                            }
                        }
                    }, // tooltips: { // tooltip text of Report By Vote Days ( 'line' report )

                    scales: { // options for x and y scales of 'line' report
                        xAxes: [{
                            stacked: true,    // Stacked line charts can be used to show how one data series i
                            gridLines: {display: true},
                        }],
                        yAxes: [{
                            stacked: true,  // Stacked line charts can be used to show how one data series i
                            ticks: {
                                callback: function (value) {  // on Y scale show only integer without decimals
                                    if (Math.floor(value) === value) {
                                        return value;
                                    }  // return numberWithCommas(value);
                                },
                            },
                        }],
                    }, // scales: { // options for x and y scales of 'line' report
                    legend: {display: true}
                } // options: { // options of Report By Vote Days ( 'line' report )

            }); // window.chartLineObject = new Chart(lineCanvasContext, {

            lineCanvas.onclick = function (e) {
                var firstPoint = window.chartLineObject.getElementsAtEvent(e);
                if (typeof firstPoint[0] == "undefined") {
                    popupAlert("Select one of visible dots to get detailed results !", 'danger')
                    return;
                }
                if (firstPoint) {
                    var first_point_index = firstPoint[0]._index
                    if (typeof window.chartLineObject.data.labels[first_point_index] == "undefined") {
                        popupAlert("Bad point !", 'danger')
                        return;
                    }

                    var selected_day = window.chartLineObject.data.labels[first_point_index];
                    // alert( "  selected_day::"+(selected_day) )
                    backendReports.showVoteNamesReportDetailsByDays(selected_day)
                    return;
                }
            } // window.chartLineObject.onclick = function(e) {

        }, //success: function (response) {
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });


} // backendReports.prototype.runVotesReportByDays = function () {
// VOTES BY DAYS BLOCK END


// VOTES BY VOTE NAMES BLOCK START

backendReports.prototype.clearVotesReportByVoteNamesParameters = function () {
    $('#filter_user_id').val("");
    $('#filter_vote_category_id').val("");
    $('#filter_vote_id').val("");
}


backendReports.prototype.runVotesReportByVoteNames = function () {
    var href = '/admin/report-votes-by-vote-names-retrieve';
    var voteNamesXCoordItems = new Array();
    var voteNamelabels       = new Array();

    var voteValuesCorrect    = new Array();
    var voteValuesNoneCorrect = new Array();
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var selectedVotes = $('#filter_vote_id').val();

    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
        "selectedVotes": selectedVotes
    }

    $.ajax({
        url: href,
        type: "POST",
        dataType: "json",
        data: dataArray,
    }).done(function (response) {
        if (response.error_code == 0) {
            // console.log("response::")
            // console.log(response)

            this.this_voteItemUCodesList = response.voteItemUCodesList;
            voteNamesXCoordItems = response.voteNamesXCoordItems;
            voteNamelabels = response.voteNamelabels;
            voteValuesCorrect = response.voteValuesCorrect;
            voteValuesNoneCorrect = response.voteValuesNoneCorrect;

            if ( voteValuesCorrect.length == 0 && voteValuesNoneCorrect.length == 0 ) {
                popupAlert("No data found. Change search criteria !", 'danger')
            }

            // console.log("voteValuesCorrect::")
            // console.log( voteValuesCorrect )
            // console.log("voteValuesNoneCorrect::")
            // console.log( voteValuesNoneCorrect )

            var barCanvas = document.getElementById("canvasVoteNames");
            $("#div_canvasVoteNames").css("display", "block")

            var barCanvasContext = barCanvas.getContext('2d');

            var numberWithCommas = function (x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };
            var self = this;
            if (window.chartObject != undefined) { // clear existing instance
                window.chartObject.destroy();
            }
            window.chartObject = new Chart(barCanvasContext, {  // stacked bar report https://jsfiddle.net/sdfx/hwx9awgn/

                type: 'bar',    // http://www.chartjs.org/docs/latest/charts/bar.html
                data: {
                    labels: voteNamesXCoordItems,
                    datasets: [

                        {
                            label: 'Correct Votes',
                            data: voteValuesCorrect,
                            borderWidth: 1,           // The stroke width of the bar in pixels.

                            backgroundColor: formatColor('#05b932'), //rgba(0, 0, 0, 0.1), // The fill color of the bar. See Colors
                            borderColor: formatColor('#05b932'),// rgba(255, 0, 0, 0.1) // The color of the bar border.

                            hoverBackgroundColor: formatColor('#05b932'),   // The fill colour of the bars when hovered.
                            hoverBorderColor: formatColor('#05b932'),        //    The stroke colour of the bars when hovered.
                            hoverBorderWidth: 1                             //    The stroke width of the bars when hovered.
                        },

                        {
                            label: 'Incorrect Votes',
                            data: voteValuesNoneCorrect,
                            borderWidth: 1,           // The stroke width of the bar in pixels.

                            backgroundColor: formatColor('#b1a19a'), //rgba(0, 0, 0, 0.1), // The fill color of the bar. See Colors
                            borderColor: formatColor('#b1a19a'),// rgba(255, 0, 0, 0.1) // The color of the bar border.
                            hoverBackgroundColor: formatColor('#b1a19a'),   // The fill colour of the bars when hovered.
                            hoverBorderColor: formatColor('#b1a19a'),        //    The stroke colour of the bars when hovered.
                            hoverBorderWidth: 1                             //    The stroke width of the bars when hovered.
                        },

                    ]
                },

                options: { // options of Report By Vote Names
                    animation: {
                        duration: 10,
                    },

                    tooltips: { // tooltip text of Report By Vote Days ( 'bar' report )
                        mode: 'label',
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
                            }
                        }
                    }, // tooltips: { // tooltip text of Report By Vote Days ( 'bar' report )

                    scales: { // options for x and y scales
                        xAxes: [{
                            stacked: true,    // Stacked bar charts can be used to show how one data series i
                            gridLines: {
                                display: true,
                                // offsetGridLines: true,
                            },
                            // barThickness: 70,

                        }],
                        yAxes: [{
                            stacked: true,  // Stacked bar charts can be used to show how one data series i
                            ticks: {
                                callback: function (value) { // on Y scale show only integer without decimals
                                    if (Math.floor(value) === value) {
                                        return value;
                                    }
                                },  // callback: function(value) { return numberWithCommas(value); },
                            },
                        }],
                    }, // scales: { // options for x and y scales
                    legend: {display: true}
                }, // options: { // options of Report By Vote Names


                plugins: [{
                    beforeInit: function (chart) {
                        chart.data.labels.forEach(function (value, index, array) {
                            var a = [];
                            a.push(value.slice(0, this_column_width));
                            var i = 1;
                            while (value.length > (i * this_column_width)) {
                                a.push(value.slice(i * this_column_width, (i + 1) * this_column_width));
                                i++;
                            }
                            array[index] = a;
                        })
                    }
                }]

            }); // window.chartObject = new Chart(barCanvasContext, {  // stacked bar report https://jsfiddle.net/sdfx/hwx9awgn/

            barCanvas.onclick = function (e) { // https://stackoverflow.com/questions/45980436/chart-js-link-to-other-page-when-click-on-specific-section-in-chart
                var slice = window.chartObject.getElementAtEvent(e);

                if (!slice.length) return; // return if not clicked on slice
                var label = slice[0]._model.label;
                if (label.length > 1) { // that is an array - we need to convert it to string
                    var label_text = ''
                    for (var key in label) {
                        if (label.hasOwnProperty(key)) {
                            label_text = label_text + label[key]
                        }
                    }
                    label = label_text
                }
                // console.log("+++self.this_voteItemUCodesList::")
                // console.log( self.this_voteItemUCodesList )
                self.this_voteItemUCodesList.forEach(function (data) {
                    if (label == data.vote_name) {
                        backendReports.showVoteNamesReportDetailsByVoteId(data.id, data.vote_name)
                        return;
                    }
                });
            } // barCanvas.onclick = function(e) {
        }

        if (response.error_code > 0) {
            alertMsg(response.message, 'Reports error!', 'OK', 'fa fa-file-chart-pie')
        }
    });


} // backendReports.prototype.runVotesReportByVoteNames = function () {

backendReports.prototype.showVoteNamesReportDetailsByVoteId = function (vote_id, vote_name) {
    var href = '/admin/report-votes-by-vote-names-retrieve-by-vote-id';
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var dataArray = {
        "_token": this_csrf_token,
        "filter_vote_id": vote_id,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {
                $("#div_vote_names_report_details_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
                $('#span_vote_names_report_details_content_title').html(vote_name)
                $('#div_vote_names_report_details_content').html(response.html)
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendReports.prototype.showVoteNamesReportDetailsByVoteId = function ( vote_id, vote_name ) {


backendReports.prototype.showVoteNamesReportDetailsByDays = function (selected_day, vote_name) {
    var href = '/admin/report-votes-by-vote-names-retrieve-by-selected-day';
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var dataArray = {
        "_token": this_csrf_token,
        "filter_selected_day": selected_day,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            // alert( "response::"+var_dump(response) )
            if (response.error_code == 0) {
                $("#div_vote_by_days_report_details_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
                $('#span_vote_by_days_report_details_content_title').html(response.selected_day_formatted)
                $('#div_vote_by_days_report_details_content').html(response.html)
                $('#div_vote_by_days_report_details_correct_count').html(response.correct_count)
                $('#div_vote_by_days_report_details_not_correct_count').html(response.not_correct_count)
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendReports.prototype.showVoteNamesReportDetailsByDays = function (selected_day) {
// VOTES BY VOTE NAMES BLOCK END


// QUIZZES RATING BLOCK START

backendReports.prototype.clearReportQuizzesRatingParameters = function () {
    $('#filter_user_id').val("");
    $('#filter_vote_category_id').val("");
    $('#filter_vote_id').val("");
}
backendReports.prototype.runReportQuizzesRating = function () {
    var href = '/admin/report-quizzes-rating-retrieve';
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var selectedVotes = $('#filter_vote_id').val();

    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
        "selectedVotes": selectedVotes
    }
    // alert( "runReportQuizzesRating +++dataArray::"+var_dump(dataArray) )

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {

                if ( response.quizQualityResultsRatingData.length == 0 ) {
                    popupAlert("No data found. Change search criteria !", 'danger')
                }

                var labelsArray = [];
                var valuesArray = [];
                response.quizQualityResultsRatingData.forEach(function (data) {
                    labelsArray.push(data.vote_name);
                    valuesArray.push(data.quiz_quality_avg);
                });

                $("#div_canvasQuizzesRating").css("display", "block")

                var barQuizzesRatingOptions = {
                    legend: {display: false},
                    title: {
                        display: true,
                        text: 'Quizzes rating'
                    }
                };

                var quizzesRatingGraphArea = document.getElementById("canvasQuizzesRating").getContext("2d");
                if (window.chartQuizzesRatingObject != undefined) { // clear existing instance
                    window.chartQuizzesRatingObject.destroy();
                }
                window.chartQuizzesRatingObject = new Chart(quizzesRatingGraphArea, {
                    type: 'horizontalBar',
                    data: {
                        labels: labelsArray,
                        datasets: [
                            {
                                label: "Average rating mark",
                                backgroundColor: this_chartBackgroundColors,
                                data: valuesArray
                            }
                        ]
                    },
                    options: barQuizzesRatingOptions
                });
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendReports.prototype.runReportQuizzesRating = function () {
// QUIZZES RATING BLOCK END


// COMPARE CORRECT VOTES BLOCK START
backendReports.prototype.clearReportCompareCorrectVotesParameters = function () {
    $('#filter_user_id').val("");
    $('#filter_vote_category_id').val("");
    $('#filter_vote_id').val("");
}
backendReports.prototype.runReportCompareCorrectVotes = function () {
    var href = '/admin/report-compare-correct-votes-retrieve';
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var selectedVotes = $('#filter_vote_id').val();

    // alert( "runReportCompareCorrectVotes href::"+var_dump(href) )
    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
        "selectedVotes": selectedVotes
    }
    alert( "runVotesReportByDays +++dataArray::"+var_dump(dataArray) )

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {

                // alert( "runReportCompareCorrectVotes response::"+var_dump(response) )
                if ( response.voteItemUsersResultCorrectPercents.length == 0 ) {
                    popupAlert("No data found. Change search criteria !", 'danger')
                }

                var labelsArray = [];
                var valuesArray = [];
                response.voteItemUsersResultCorrectPercents.forEach(function (data) {
                    labelsArray.push(data.vote_name);
                    valuesArray.push(data.percent);
                });

                $("#div_canvasCompareCorrectVotes").css("display", "block")

                if (window.chartCompareCorrectObject != undefined) { // clear existing instance
                    window.chartCompareCorrectObject.destroy();
                }
                window.chartCompareCorrectObject = new Chart(document.getElementById("canvasCompareCorrectVotes"), {
                    type: 'pie',
                    data: {
                        labels: labelsArray,
                        datasets: [{
                            label: "Correct votes ( in percent )",
                            backgroundColor: this_chartBackgroundColors,
                            // data: [2478,5267,734,784,433]
                            data: valuesArray
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Correct votes in percent'
                        }
                    }
                });

            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendReports.prototype.runReportCompareCorrectVotes = function () {
// COMPARE CORRECT VOTES BLOCK END




// SEARCH RESULTS BLOCK START

backendReports.prototype.clearReportSearchResultsParameters = function () {
    $('#filter_user_id').val("");
    $('#filter_vote_category_id').val("");
    $('#filter_vote_id').val("");
}
backendReports.prototype.runReportSearchResults = function () {
    var href = '/admin/report-search-results-retrieve';
    var selectedUsers = $('#filter_user_id').val();
    var selectedVoteCategories = $('#filter_vote_category_id').val();
    var selectedVotes = $('#filter_vote_id').val();

    // alert( "runReportSearchResults href::"+var_dump(href) )
    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "selectedVoteCategories": selectedVoteCategories,
        "selectedVotes": selectedVotes
    }
    // alert( "runVotesReportByDays +++dataArray::"+var_dump(dataArray) )

    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {

                // alert( "runReportSearchResults response::"+var_dump(response) )



                searchTextXCoordItems      = response.searchTextXCoordItems;
                searchResultslabels        = response.searchResultslabels;
                searchResultFoundArray     = response.searchResultFoundArray;
                searchResultNotFoundArray  = response.searchResultNotFoundArray;
                // console.log("searchResultFoundArray::")
                // console.log( searchResultFoundArray )
                //
                // console.log("searchResultNotFoundArray::")
                // console.log( searchResultNotFoundArray )

                var barCanvas = document.getElementById("SearchResults");
                $("#div_SearchResults").css("display", "block")

                var barCanvasContext = barCanvas.getContext('2d');

                var numberWithCommas = function (x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                };
                var self = this;
                if (window.chartObject != undefined) { // clear existing instance
                    window.chartObject.destroy();
                }
                window.chartObject = new Chart(barCanvasContext, {  // stacked bar report https://jsfiddle.net/sdfx/hwx9awgn/

                    type: 'bar',    // http://www.chartjs.org/docs/latest/charts/bar.html
                    data: {
                        labels: searchTextXCoordItems,
                        datasets: [

                            {
                                label: 'Found requests',
                                data: searchResultFoundArray,
                                borderWidth: 1,           // The stroke width of the bar in pixels.

                                backgroundColor: formatColor('#05b932'), //rgba(0, 0, 0, 0.1), // The fill color of the bar. See Colors
                                borderColor: formatColor('#05b932'),// rgba(255, 0, 0, 0.1) // The color of the bar border.

                                hoverBackgroundColor: formatColor('#05b932'),   // The fill colour of the bars when hovered.
                                hoverBorderColor: formatColor('#05b932'),        //    The stroke colour of the bars when hovered.
                                hoverBorderWidth: 1                             //    The stroke width of the bars when hovered.
                            },

                            {
                                label: 'Not found requests',
                                data: searchResultNotFoundArray,
                                borderWidth: 1,           // The stroke width of the bar in pixels.

                                backgroundColor: formatColor('#b1a19a'), //rgba(0, 0, 0, 0.1), // The fill color of the bar. See Colors
                                borderColor: formatColor('#b1a19a'),// rgba(255, 0, 0, 0.1) // The color of the bar border.
                                hoverBackgroundColor: formatColor('#b1a19a'),   // The fill colour of the bars when hovered.
                                hoverBorderColor: formatColor('#b1a19a'),        //    The stroke colour of the bars when hovered.
                                hoverBorderWidth: 1                             //    The stroke width of the bars when hovered.
                            },

                        ]
                    },

                    options: { // options of Report By Vote Names
                        animation: {
                            duration: 10,
                        },

                        tooltips: { // tooltip text of Report By Vote Days ( 'bar' report )
                            mode: 'label',
                            callbacks: {
                                label: function (tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
                                }
                            }
                        }, // tooltips: { // tooltip text of Report By Vote Days ( 'bar' report )

                        scales: { // options for x and y scales
                            xAxes: [{
                                stacked: true,    // Stacked bar charts can be used to show how one data series i
                                gridLines: {
                                    display: true,
                                    // offsetGridLines: true,
                                },
                                // barThickness: 70,

                            }],
                            yAxes: [{
                                stacked: true,  // Stacked bar charts can be used to show how one data series i
                                ticks: {
                                    callback: function (value) { // on Y scale show only integer without decimals
                                        if (Math.floor(value) === value) {
                                            return value;
                                        }
                                    },  // callback: function(value) { return numberWithCommas(value); },
                                },
                            }],
                        }, // scales: { // options for x and y scales
                        legend: {display: true}
                    }, // options: { // options of Report By Vote Names


                    plugins: [{
                        beforeInit: function (chart) {
                            chart.data.labels.forEach(function (value, index, array) {
                                var a = [];
                                a.push(value.slice(0, this_column_width));
                                var i = 1;
                                while (value.length > (i * this_column_width)) {
                                    a.push(value.slice(i * this_column_width, (i + 1) * this_column_width));
                                    i++;
                                }
                                array[index] = a;
                            })
                        }
                    }]

                }); // window.chartObject = new Chart(barCanvasContext, {  // stacked bar report https://jsfiddle.net/sdfx/hwx9awgn/







            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendReports.prototype.runReportSearchResults = function () {
// SEARCH RESULTS BLOCK END

//  RESULTS EXPORT BLOCK START
//                                 <input type="submit" class="btn btn-primary"  value="Export to excel" onclick="javascript:backendReports.showExportToExcel('votes_by_days'); return false;"
backendReports.prototype.showExportToExcel = function (report_name) {
    // alert( "showExportToExcel report_name::"+var_dump(report_name) )
    $("#div_save_export_to_excel").css("display","block")
}

backendReports.prototype.saveExportToExcel = function (report_name) {
    // alert( "saveExportToExcel report_name::"+var_dump(report_name) )
    console.log("this.this_monthsXCoordItems::")
    console.log( this.this_monthsXCoordItems )

    console.log("this.this_voteValuesCorrect::")
    console.log( this.this_voteValuesCorrect )
    //
    // var this_monthsXCoordItems = [];
    // var this_voteValuesCorrect = [];
    // var this_voteValuesNoneCorrect = [];

    $("#report_name").val(report_name)
    $("#monthsXCoordItems").val(this.this_monthsXCoordItems)
    $("#voteValuesCorrect").val(this.this_voteValuesCorrect)
    $("#voteValuesNoneCorrect").val(this.this_voteValuesNoneCorrect)
    // $("#voteItemUsersResultsNoneCorrect").val(voteItemUsersResultsNoneCorrect)
/*
    var href = '/admin/report-save-to-excel';

    var dataArray = {
        "_token": this_csrf_token,
        "report_name": report_name,
        "voteItemUsersResultsCorrect": this.this_voteItemUsersResultsCorrect,
        "voteItemUsersResultsNoneCorrect": this.this_voteItemUsersResultsNoneCorrect
    }
*/

// alert( "-1::"+var_dump(  $("#monthsXCoordItems").val()  ) )
//           return;
    var theForm = $("#form_report_save_to_excel");
    theForm.submit();

    return;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {
                alert( "OK response::"+var_dump(response) )
/*
                $("#div_vote_names_report_details_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
                $('#span_vote_names_report_details_content_title').html(vote_name)
                $('#div_vote_names_report_details_content').html(response.html)
*/
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

    // $("#div_save_export_to_excel").css("display","block")
}

//  RESULTS EXPORT BLOCK END


backendReports.prototype.clearrunReportPaymentsParameters = function () {
    $('#filter_user_id').val("");
    // $('#filter_vote_category_id').val("");
    // $('#filter_vote_id').val("");
}

backendReports.prototype.runReportPayments = function () {
    var href = '/admin/report_payments_retrieve';
    var selectedUsers = $('#filter_user_id').val();
    var filter_report_type = $('#filter_report_type').val();
    // var selectedVoteCategories = $('#filter_vote_category_id').val();
    // var selectedVotes = $('#filter_vote_id').val();
    var dataArray = {
        "_token": this_csrf_token,
        "filter_voted_at_from": this_filter_start_date,
        "filter_voted_at_till": this_filter_end_date,
        "selectedUsers": selectedUsers,
        "filter_report_type": filter_report_type,
    }

    // alert( "dataArray::"+var_dump(dataArray) )
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,

        success: function (response) {
            console.log("runReportPayments response::")
            console.log( response )

            // resources/views/defaultBS41Backend/admin/reports/report_payments_downloaded_by_quantity_count.blade.php
            $("#div_report_payments_downloaded_by_quantity_count").html(response.html);
            $("#div_report_payments_downloaded_by_quantity_count_container").css( "display", "block" );


        }, //success: function (response) {
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });


} // backendReports.prototype.runReportPayments = function () {



// openItemsCountReportDetails REPORT BLOCK BEGIN
backendReports.prototype.openItemsCountReportDetails = function ( downloaded_item_id, downloaded_item_title, filter_voted_at_from, filter_voted_at_till, filterSelectedUsers ) {
    // alert( "downloaded_item_id::"+var_dump(downloaded_item_id) )
    var href = '/admin/report_open_items_count_details';
    var dataArray = {
        "_token": this_csrf_token,
        "downloaded_item_id": downloaded_item_id,
        "filter_voted_at_from": filter_voted_at_from,
        "filter_voted_at_till": filter_voted_at_till,
        "selectedUsers": filterSelectedUsers,
    }
    // alert( "dataArray::"+var_dump(dataArray) )
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,

        success: function (response) {
            $("#div_report_open_items_count_details_modal").modal({
                "backdrop": "static",
                "keyboard": true,
                "show": true
            });
             $('#span_vote_payments_report_details_content_title').html(downloaded_item_title)
            $('#div_report_open_items_count_details_content').html(response.html)

        }, //success: function (response) {
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // backendReports.prototype.runReportPayments = function () {

backendReports.prototype.showPaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").hide()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").show()
    $("#payment_items_row_"+payment_detail_id+"_2").show()
    $("#payment_items_row_"+payment_detail_id+"_3").show()
    $("#payment_items_row_"+payment_detail_id+"_4").show()
}


backendReports.prototype.hidePaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").show()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").hide()
    $("#payment_items_row_"+payment_detail_id+"_2").hide()
    $("#payment_items_row_"+payment_detail_id+"_3").hide()
    $("#payment_items_row_"+payment_detail_id+"_4").hide()
}

// openItemsCountReportDetails REPORT BLOCK BEGIN




// openPaymentsByDaysReportDetails REPORT BLOCK BEGIN
/*                             {{ $nextPaymentItem['item_id'] }}<a href="#" onclick="javascript:openPaymentsByDaysReportDetails('{{ $nextPaymentItem['formatted_created_at'] }}',
                                    '{{ $nextPaymentItem['quantity_count'] }}', '{{ $filter_voted_at_from }}', '{{
                            $filter_voted_at_till  }}', '{{ $filterSelectedUsers }}')">Details</a>::{{
                            $nextPaymentItem['formatted_created_at'] }}
 */
backendReports.prototype.openPaymentsByDaysReportDetails = function ( formatted_created_at, quantity_count, filter_voted_at_from, filter_voted_at_till, filterSelectedUsers ) {
    alert( "openPaymentsByDaysReportDetails formatted_created_at::"+var_dump(formatted_created_at) )
    var href = '/admin/report_payments_by_days_count_details';
    var dataArray = {
        "_token": this_csrf_token,
        "formatted_created_at": formatted_created_at,
        "filter_voted_at_from": filter_voted_at_from,
        "filter_voted_at_till": filter_voted_at_till,
        "selectedUsers": filterSelectedUsers,
    }
    alert( "dataArray::"+var_dump(dataArray) )
    // return;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,

        success: function (response) {
            $("#div_report_payments_by_days_count_details_modal").modal({
                "backdrop": "static",
                "keyboard": true,
                "show": true
            });
             $('#span_vote_payments_report_details_content_title').html(quantity_count)
            $('#div_report_payments_by_days_count_details_content').html(response.html)

        }, //success: function (response) {
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // backendReports.prototype.runReportPayments = function () {

backendReports.prototype.showPaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").hide()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").show()
    $("#payment_items_row_"+payment_detail_id+"_2").show()
    $("#payment_items_row_"+payment_detail_id+"_3").show()
    $("#payment_items_row_"+payment_detail_id+"_4").show()
}


backendReports.prototype.hidePaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").show()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").hide()
    $("#payment_items_row_"+payment_detail_id+"_2").hide()
    $("#payment_items_row_"+payment_detail_id+"_3").hide()
    $("#payment_items_row_"+payment_detail_id+"_4").hide()
}

// openPaymentsByDaysReportDetails REPORT BLOCK BEGIN

