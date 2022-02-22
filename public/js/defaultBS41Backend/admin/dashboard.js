var this_backend_home_url
var this_csrf_token


function backendDashboard(page, paramsArray) {  // constructor of backendDashboard
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
} // function backendDashboard(Params) {  constructor


backendDashboard.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    this.showPaymentsRows(1)
    this.showActivityLogRows(1)

    $('body').on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        // alert( "++url::"+(url) )
        //page_to_load::1?=5


        
        var page_to_load = getSplitted(url, 'get-activity-log-rows/', 1)
        // alert( "-1 page_to_load::"+var_dump(page_to_load) )
        if ( page_to_load != '' ) {
            if (!checkInteger(page_to_load)) {
                var page_to_load = getSplitted(page_to_load, '=', 1)
            }

            if (!checkInteger(page_to_load)) page_to_load = 1
            backendDashboard.showActivityLogRows(page_to_load)
            return ;
        }


        ///get-payment-items-rows
        var page_to_load = getSplitted(url, 'get-payment-items-rows/', 1)
        // alert( "+ 2 page_to_load::"+var_dump(page_to_load) )
        if ( page_to_load != '' ) {

            if (!checkInteger(page_to_load)) {
                var page_to_load = getSplitted(page_to_load, '=', 1)
            }

            if (!checkInteger(page_to_load)) page_to_load = 1
            backendDashboard.showPaymentsRows(page_to_load)
            return;
        }

        return false;
    }); // $('body').on('click', '.pagination a', function (e) {

}



backendDashboard.prototype.refreshDemoData = function () {
    confirmMsg('Do you want to refresh demo data of this application?', function () {

            var href = this_backend_home_url + "/admin/refresh-demo-data";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token},
                success: function (response) {
                    alertMsg("Refreshing demo data was run successfully !!", 'Refreshing demo data', 'OK', 'fa fa-check')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} 


backendDashboard.prototype.showActivityLogRows = function (page) {
    var href = this_backend_home_url + "/admin/get-activity-log-rows/" + page;
    // alert( "showActivityLogRows href::"+(href) )
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $('#div_activity_log_content').html(response.html)
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}

backendDashboard.prototype.clearActivityLogRows = function (page) {
    confirmMsg('Do you want to clear activity logs?', function () {

            var href = this_backend_home_url + "/admin/clear-activity-log-rows";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("Activity logs successfully cleared !", 'success') // 'info', 'success'
                    backendDashboard.showActivityLogRows(1)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );
}

backendDashboard.prototype.showPaymentsRows = function (page) {
    var filter_download_id = $('#filter_download_id').val()
    var filter_user_id     = $('#filter_user_id').val()
    var href               = this_backend_home_url + "/admin/get-payment-items-rows";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"_token": this_csrf_token, "filter_download_id": filter_download_id, "filter_user_id": filter_user_id, "page": page},
        success: function (response) {
            $('#div_payments_content').html(response.html)
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });
}

backendDashboard.prototype.showPaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").hide()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").show()

    $("#payment_items_row_"+payment_detail_id+"_2").show()
    $("#payment_items_row_"+payment_detail_id+"_3").show()
    $("#payment_items_row_"+payment_detail_id+"_4").show()
}

backendDashboard.prototype.hidePaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").show()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").hide()

    $("#payment_items_row_"+payment_detail_id+"_2").hide()
    $("#payment_items_row_"+payment_detail_id+"_3").hide()
    $("#payment_items_row_"+payment_detail_id+"_4").hide()
}
