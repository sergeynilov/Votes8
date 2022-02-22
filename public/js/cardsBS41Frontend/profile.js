// alert( "profile.js::"+var_dump(1) )
var this_frontend_home_url
var this_csrf_token
var this_start_date_formatted


function frontendProfile(page, paramsArray) {  // constructor of frontend Profile's editor - set all params from server
// alert( "page::"+page+"  frontendProfile paramsArray::"+var_dump(paramsArray) )
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    this_start_date_formatted = paramsArray.start_date_formatted;

} // function frontendProfile(Params) {  constructor of frontend Profile's editor - set all params from server


frontendProfile.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    // alert( "frontendProfile  onFrontendPageInit page::" + page)
    frontendInit()
    if (page == "edit_subscription") {
        this.getRelatedUsersSiteSubscriptions()
    }
    if (page == "payments") {
        this.loadPaymentAgreements()
        this.showPaymentsRows(1)
    }

    $('body').on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        // alert( "++url::"+(url) )

        var page_to_load = getSplitted(url, 'get-profile-payment-items-rows', 1)
        // alert( "+ 2 page_to_load::"+var_dump(page_to_load) )
        if ( page_to_load != '' ) {

            if (!checkInteger(page_to_load)) {
                var page_to_load = getSplitted(page_to_load, '=', 1)
            }

            if (!checkInteger(page_to_load)) page_to_load = 1
            frontendProfile.showPaymentsRows(page_to_load)
            return;
        }

        return false;
    }); // $('body').on('click', '.pagination a', function (e) {


} // frontendProfile.prototype.onFrontendPageInit= function(page) {


frontendProfile.prototype.generatePassword = function () {

    confirmMsg('Do you want to generate new password for your account ? It would be sent to you by email.', function () {
            var href = this_frontend_home_url + "/profile/generate-password";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token},
                success: function (response) {
                    popupAlert("Password generated and sent to owner of the profile successfully !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );


} // frontendProfile.prototype.generatePassword

frontendProfile.prototype.printToPdf = function () {  // all vars/objects init

    /*     Route::get('print-to-pdf', array(
        'as'      => 'profile-print-to-pdf',
        'uses'    => 'ProfileController@print_to_pdf'
    ));
 */
    // confirmMsg('Do you want to generate new password for your account ? It would be sent to you by email.', function () {
    document.location = this_frontend_home_url + "/profile/print-to-pdf";
    return;
    var href = this_frontend_home_url + "/profile/print-to-pdf";
    $.ajax({
        type: "GET",
        // type: "POST",
        dataType: "json",
        url: href,
        // data: {"_token": this_csrf_token},
        success: function (response) {
            popupAlert("Your profile was printed to pdf file successfully !", 'success')
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

    //     }
    // );


} // printToPdf.prototype.printToPdf

frontendProfile.prototype.getUserMailChimpInfo = function () {  // all vars/objects init
    var href = this_frontend_home_url + "/profile/get-user-mail-chimp-info";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("response::")
            console.log(response)

            // popupAlert("Your profile was printed to pdf file successfully !", 'success')
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

    //     }
    // );


} // getUserMailChimpInfo

////////// Profile Block End ///////////
frontendProfile.prototype.getRelatedUsersSiteSubscriptions = function () {
    var href = this_frontend_home_url + '/profile/get-related-users-site-subscriptions';
    // alert( "::"+var_dump(href) )
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_related_users_site_subscriptions").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });

} // function uploadVoteDetailsInfo() {


frontendProfile.prototype.subscribeUsersSiteSubscription = function (site_subscription_id, site_subscription_name) {
    confirmMsg('Do you want to subscribe to "' + site_subscription_name + '" news ?', function () {
            var href = this_frontend_home_url + "/profile/subscribe-users-site-subscription/" + site_subscription_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    frontendProfile.getRelatedUsersSiteSubscriptions()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
} // frontendProfile.prototype.attachTagToVote = function (site_subscription_id, site_subscription_name) {

frontendProfile.prototype.unsubscribeUsersSiteSubscription = function (site_subscription_id, site_subscription_name) {
    confirmMsg('Do you want to unsubscribe from "' + site_subscription_name + '" news ?', function () {
            var href = this_frontend_home_url + "/profile/unsubscribe-users-site-subscription/" + site_subscription_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    frontendProfile.getRelatedUsersSiteSubscriptions()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
} // frontendProfile.prototype.clearTagToVote = function (site_subscription_id, site_subscription_name) {


////////// PROFILE PAYMENT AGREEMENT DETAILS BLOCK START ///////////
frontendProfile.prototype.loadPaymentAgreements = function () {
            var href = this_frontend_home_url + "/profile/load_payment_agreements";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    $("#div_load_payment_agreements").html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
} // frontendProfile.prototype.loadPaymentAgreements = function () {

//                         <a class="toggle_link" onclick="javascript:frontendProfile.showPaymentAgreementDetails( '{{ $nextPaymentAgreement['id'] }}',
//                                 '{{$nextPaymentAgreement['payment_agreement_id']}}'); "
frontendProfile.prototype.showPaymentAgreementDetails = function (id,payment_agreement_id) {
            var href = this_frontend_home_url + "/profile/load_payment_agreement_details/"+payment_agreement_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {

                    
                    $("#div_payment_agreement_details_modal").modal({
                        "backdrop": "static",
                        "keyboard": true,
                        "show": true
                    });
                    // $('#span_vote_names_report_details_content_title').html(vote_name)
                    $('#div_payment_agreement_details_modal_content').html(response.html)


                    // $("#div_load_payment_agreements").html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
} // frontendProfile.prototype.showPaymentAgreementDetails = function () {

////////// PROFILE PAYMENT AGREEMENT DETAILS BLOCK END ///////////



////////// PROFILE PAYMENTS BLOCK START ///////////
frontendProfile.prototype.showPaymentsRows = function (page) {
    var filter_download_id = $('#filter_download_id').val()
    var href               = this_frontend_home_url + "/profile/get-profile-payment-items-rows";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"_token": this_csrf_token, "filter_download_id": filter_download_id, "page": page},
        success: function (response) {
            $('#div_payments_content').html(response.html)
            $('input[name="filter_start_date_end_date_picker"]').daterangepicker({
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 10,
                startDate: dbDateTimeToDateTimePicker(this_start_date_formatted),
                // endDate: end_date_formatted,
                locale: {
                    format: 'DD MMMM, YYYY hh:mm a',
                    cancelLabel: 'Clear'
                }
            });

            $('#filter_start_date_end_date_picker').on('apply.daterangepicker', function (ev, picker) {
                $("#filter_start_date").val(picker.startDate.format('YYYY-MM-DD hh:mm:ss'));
                $("#filter_end_date").val(picker.endDate.format('YYYY-MM-DD hh:mm:ss'));
            });

            $('#filter_start_date_end_date_picker').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                $('#filter_start_date_end_date_picker').val('');
            });

        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}


frontendProfile.prototype.showPaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").hide()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").show()

    $("#payment_items_row_"+payment_detail_id+"_2").show()
    $("#payment_items_row_"+payment_detail_id+"_3").show()
    $("#payment_items_row_"+payment_detail_id+"_4").show()
}

frontendProfile.prototype.hidePaymentDetails = function (payment_detail_id) {
    $("#a_payment_items_row_"+payment_detail_id+"_show_block").show()
    $("#a_payment_items_row_"+payment_detail_id+"_hide_block").hide()

    $("#payment_items_row_"+payment_detail_id+"_2").hide()
    $("#payment_items_row_"+payment_detail_id+"_3").hide()
    $("#payment_items_row_"+payment_detail_id+"_4").hide()
}

////////// PROFILE PAYMENTS BLOCK END ///////////
