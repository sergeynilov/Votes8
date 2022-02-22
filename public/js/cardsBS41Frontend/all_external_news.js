var this_frontend_home_url
var this_infinite_scroll_rows_per_scroll_step
var this_csrf_token


function frontendAllExternalNews(page, paramsArray) {  // constructor of frontend AllExternalNews's editor - set all params from server
    // alert( "paramsArray page::"+var_dump(paramsArray) )
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_infinite_scroll_rows_per_scroll_step = paramsArray.infinite_scroll_rows_per_scroll_step;
    this_csrf_token = paramsArray.csrf_token;

    if (page == "view") {
        this.InitInfiniteScroll()
    }
} // function frontendAllExternalNews(Params) {  constructor of frontend AllExternalNews's editor - set all params from server


frontendAllExternalNews.prototype.InitInfiniteScroll = function () {



    return;
    var infScroll = new InfiniteScroll('#infinite_scroll_container', {
            path: function () {
                console.log(" path this.loadCount::")
                console.log( this.loadCount )

                console.log("this.loadCount * this_infinite_scroll_rows_per_scroll_step::")
                console.log( this.loadCount * this_infinite_scroll_rows_per_scroll_step )

                if ( this.loadCount > 0 ) {
                    $("#span_external_news_loaded_count").html(this.loadCount * this_infinite_scroll_rows_per_scroll_step)
                }
                return '/get-all-external-news-listing/' + ( ( this.loadCount + 1 ) * this_infinite_scroll_rows_per_scroll_step );
            },
            append: '.all-external-news-listing-append-block',
            status: '.all-external-news-listing-load-status-block',
        }
        , function (data) {
        }
    )

}


frontendAllExternalNews.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()
} // frontendAllExternalNews.prototype.onFrontendPageInit= function(page) {



