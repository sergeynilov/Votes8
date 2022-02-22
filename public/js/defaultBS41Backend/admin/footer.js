var this_backend_home_url
var this_csrf_token
var this_empty_img_url

function backendFooter(paramsArray) {  // constructor of backend Vote item's editor - set all params from server
    this_backend_home_url = paramsArray.backend_home_url;
    this_empty_img_url = paramsArray.empty_img_url;
    this_csrf_token = paramsArray.csrf_token;
    // alert( "this_csrf_token::"+this_csrf_token + "this_empty_img_url::"+this_empty_img_url +"  this_backend_home_url::"+this_backend_home_url)
} // function backendFooter(Params) {  constructor of backend Vote's editor - set all params from server


backendFooter.prototype.showLoggedUserInfo = function () {
    var href = '/admin/show-logged-user-info';
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function (response) {
                let info_text = ''
                let opened_todo_count = response.opened_todo_count

                // logged_user_id
                if (opened_todo_count > 0) {
                    info_text = info_text + '<a target="_blank" href="'+response.site_home_url+'/public-profile-view/'+response.logged_user_id+'">You</a> have <b>' + opened_todo_count + "</b>" +
                    " <i>opened todo items</i>."
                    // info_text = info_text + "You have <b>" + opened_todo_count + "</b> <i>opened todo items</i>."
                    //Route::get('public-profile-view/{user_id}', 'HomeController@public_profile_view')->name('public-profile-view');

                }
                if (info_text != "") {
                    popupAlert(info_text, 'info')
                }

            },
            error: function (error) {
                popupErrorMessage(error.responseJSON.message)
            }

        });

}


backendFooter.prototype.showSystemInformation = function () {
    var href = this_backend_home_url + '/admin/get-system-info';
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function (response) {
                console.log("response::")
                console.log(response)

                var dialog_info_text = response.system_info

                var jquery_version = $.fn.jquery;

                if (typeof $.ui != "undefined") {
                    var jquery_ui_version = $.ui.version;
                }

                $("#div_system_info_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
                $('#div_info_content').html(dialog_info_text)
                $('#div_system_info').css("display", "block")

                var deviceParams = effectiveDeviceWidth()
                if (typeof deviceParams.width != "undefined" && typeof deviceParams.height != "undefined") {
                    // $("#span_device_width").html( deviceParams.width + "*" + deviceParams.height )
                    $("#span_device_width_footer").html(deviceParams.width + "*" + deviceParams.height)
                }

                var bootstrap4_enabled = (typeof $().emulateTransitionEnd == 'function');
                if (bootstrap4_enabled) {
                    $("#span_bootstrap4_enabled").html("Bootstrap 4 is enabled" + '. Version : ' + $.fn.tooltip.Constructor.VERSION + ". ");
                    $("#span_bootstrap4_plugins").html(getBootstrapPlugins());
                } else {
                    $("#span_bootstrap4_enabled").html("Bootstrap 4 is not enabled");
                }

                $("#span_jquery_version").html($.fn.jquery);
                if (typeof $.ui != "undefined") {
                    $("#span_jquery_ui_version").html($.ui.version);
                } else {
                    $("#div_jquery_ui_version").css("display", "none")
                }


            },
            error: function (error) {
                popupErrorMessage(error.responseJSON.message)
            }

        });

}


function backendInit() {
    var href = this_backend_home_url + "/logged-user";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"_token": this_csrf_token},
        success: function (response) {
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
            document.location = '/logout'
        }
    });

    setTimeout("close_div_action_text_alert()", 5000);

    $('.navbar-light .dmenu').hover(function () {   // https://bootsnipp.com/snippets/yN681
        $(this).find('.sm-menu').first().stop(true, true).slideDown(150);
    }, function () {
        $(this).find('.sm-menu').first().stop(true, true).slideUp(105)
    });

    $(".chosen_select_box").chosen({
        disable_search_threshold: 10,
        no_results_text: "Nothing found!",
        allow_single_deselect: true,
    });
    // }


    // alert( "AFTER chosen_select_box::"+(-88) )
    return;
    // $('.selectpicker').selectpicker();
    $('[data-toggle="tooltip"]').tooltip()
    $('.image-link').magnificPopup({type: 'image'});
    alert("AFTER tooltip::")


} // function backendInit() {
