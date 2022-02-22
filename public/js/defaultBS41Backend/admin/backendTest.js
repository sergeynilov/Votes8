// alert( "backendTest.js::"+var_dump(-6) )

var this_backend_home_url
var this_csrf_token

function backendTest(page, paramsArray) {  // constructor of backendTest
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
} // function backendTest(Params) {  constructor


backendTest.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
/*
    this.showActivityLogRows(1)

    $('body').on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        // alert( "++url::"+var_dump(url) )
        //page_to_load::1?=5
        var page_to_load = getSplitted(url, 'get-activity-log-rows/1?=', 1)
        // alert( "page_to_load::"+var_dump(page_to_load) )
        if (!checkInteger(page_to_load)) page_to_load = 1
        backendTest.showActivityLogRows(page_to_load)
        return false;
    });
*/

}

backendTest.prototype.publishToProfile = function () {

            var href = this_backend_home_url + "/admin/publish-to-profile";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token},
                success: function (response) {

                    console.log("response::")
                    console.log( response )

                    alertMsg("publishToProfile was run successfully !!", 'Refreshing demo data', 'OK', 'fa fa-check')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

}

