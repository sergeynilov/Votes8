var this_frontend_home_url
var this_backend_home_url
var this_csrf_token

function backendSettings(page, paramsArray) {  // constructor of backend User's editor - set all params from server
    // alert( "paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_frontend_home_url = paramsArray.frontend_home_url;
    // alert( "this_frontend_home_url::"+this_frontend_home_url )
    this_csrf_token = paramsArray.csrf_token;

    // alert( "backendSettings page::"+(page) )
    if (page == "edit") {
        this.showQuizQualityOptionsListing()
        this.getRelatedFileOnRegistrations(false)

        /////////////////
        $(document).on('click', '.all-emails-copy-btn-add', function (e) {
            e.preventDefault();

            var controlForm = $('.all-emails-copy-controls form:first'),
                currentEntry = $(this).parents('.all-emails-copy-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
            controlForm.find('.all-emails-copy-entry:not(:last) .all-emails-copy-btn-add')
                .removeClass('all-emails-copy-btn-add').addClass('btn-remove')
                .removeClass('all-emails-copy-btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function (e) {
            $(this).parents('.all-emails-copy-entry:first').remove();

            e.preventDefault();
            return false;
        });

        $(document).on('click', '.cron-tasks-receivers-btn-add', function (e) {
            e.preventDefault();

            var controlForm = $('.cron-tasks-receivers-controls form:first'),
                currentEntry = $(this).parents('.cron-tasks-receivers-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
            controlForm.find('.cron-tasks-receivers-entry:not(:last) .cron-tasks-receivers-btn-add')
                .removeClass('cron-tasks-receivers-btn-add').addClass('btn-remove')
                .removeClass('cron-tasks-receivers-btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function (e) {
            $(this).parents('.cron-tasks-receivers-entry:first').remove();

            e.preventDefault();
            return false;
        });

    }
} // function backendSettings(Params) {  constructor of backend User's editor - set all params from server


////////// SETTINGS BLOCK START ///////////
backendSettings.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }
} // backendSettings.prototype.onBackendPageInit= function(page) {


backendSettings.prototype.onSubmit = function (tab_name) {
    var theForm = $("#form_settings_" + tab_name + "_edit");
    // alert( "\"#tab_name_to_submit_\"+tab_name::"+("#tab_name_to_submit_"+tab_name) +"  tab_name::"+tab_name )
    $("#tab_name_to_submit_" + tab_name).val(tab_name)
    theForm.submit();
    // alert( "theForm::"+var_dump(theForm) )
}

////////// SETTINGS BLOCK END ///////////


// SETTINGS QUIZ QUALITY OPTIONS BLOCK BEGIN
backendSettings.prototype.showQuizQualityOptionsListing = function () {
    var href = this_backend_home_url + "/admin/get-settings-show-quiz-quality-options-listing";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            // console.log("response::")
            // console.log( response )

            $("#div_settings_quiz_quality_options").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // showQuizQualityOptionsListing

backendSettings.prototype.deleteQuizQualityOption = function (quiz_quality_id, quiz_quality_label) {
    confirmMsg('Do you want to delete "' + quiz_quality_label + '" quiz quality option ?', function () {
            var href = this_backend_home_url + "/admin/settings-show-quiz-quality-option-destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"quiz_quality_id": quiz_quality_id, "_token": this_csrf_token},
                success: function (response) {
                    backendSettings.showQuizQualityOptionsListing()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendSettings.prototype.deleteQuizQualityOption = function ( quiz_quality_id, quiz_quality_label ) {

backendSettings.prototype.addQuizQualityOption = function () {
    var add_new_quiz_quality_id = jQuery.trim($("#add_new_quiz_quality_id").val())
    var add_new_quiz_quality_label = jQuery.trim($("#add_new_quiz_quality_label").val())

    if (add_new_quiz_quality_id == "") {
        popupAlert("Enter quiz quality id !", 'danger')
        $("#add_new_quiz_quality_id").focus()
        return;
    }

    if (add_new_quiz_quality_label == "") {
        popupAlert("Enter quiz quality label !", 'danger')
        $("#add_new_quiz_quality_label").focus()
        return;
    }

    var href = this_backend_home_url + "/admin/add-settings-show-quiz-quality";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"add_new_quiz_quality_id": add_new_quiz_quality_id, "add_new_quiz_quality_label": add_new_quiz_quality_label, "_token": this_csrf_token},
        success: function (response) {
            backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendSettings.prototype.addQuizQualityOption = function (  ) {

// SETTINGS QUIZ QUALITY BLOCK END


////////// Files On Registration Block Start ///////////


backendSettings.prototype.deleteFileOnRegistration = function (file_on_registration) {
    confirmMsg("Do you want to delete '" + file_on_registration + "' file ? ", function () {
            var href = this_backend_home_url + "/admin/delete-settings-file-on-registration"
            $.ajax({
                async: false,
                url: href,
                type: "DELETE",
                dataType: "json",
                data: {"file_on_registration": file_on_registration, "image": encodeURIComponent(file_on_registration), "_token": this_csrf_token}
            }).done(function (result) {
                // alert( "result::"+var_dump(result) )
                if (typeof result == "undefined") {
                    popupAlert("File was successfully deleted !", 'success')
                    backendSettings.getRelatedFileOnRegistrations(false)
                } else {
                    if (typeof result.error_code != "undefined") {
                        popupAlert(result.message, 'danger')
                    }
                }
            });
        }
    );

} // backendSettings.prototype.deleteFileOnRegistration = function (file_on_registration, object_name, image_name) {


backendSettings.prototype.getRelatedFileOnRegistrations = function (get_only_file_on_registrations_count) {
    if (!get_only_file_on_registrations_count) {
        // alert( "getRelatedFileOnRegistrations::"+var_dump() )

        // $('.page_content_image_fileupload').fileupload({
        //     url: this_backend_home_url+"/admin/upload-page-content-image-to-tmp-page-content",
        //     type: "POST",
        //     dataType: 'json',
        //     done: function (e, data) {

        $('.file_on_registration_fileupload').fileupload({
            url: this_backend_home_url + "/admin/upload-settings-file-on-registration-to-tmp",
            type: "POST",
            // data: { "_token": this_csrf_token},
            dataType: 'json',
            done: function (e, data) {
                // alert( "data.result.files.url::"+var_dump(data.result.files.url) )
                // alert( "file_on_registration_fileupload data::"+var_dump(data) )
                $("#div_files_on_registration_upload_image").css("display", "none");
                $("#div_save_upload_image").css("display", "block");

                var info_message = data.result.files.info_message
                // alert( "info_message::"+var_dump(info_message) )
                if (info_message != "") {
                    $("#div_info_message").css("display", "block");
                    $("#div_info_message").html(info_message);
                } else {
                    $("#div_info_message").css("display", "none");
                    $("#div_info_message").html("");
                }
                if (parseInt(data.result.files.FilenameInfo.width) && parseInt(data.result.files.FilenameInfo.height)) {
                    $("#img_preview_image").attr("src", data.result.files.url);
                    $("#img_preview_image").attr("width", data.result.files.FilenameInfo.width + "px");
                    $("#img_preview_image").attr("height", data.result.files.FilenameInfo.height + "px");
                } else {
                    $("#img_preview_image").attr("src", "/images/spacer.png");
                }

                // alert( "data.result.files.file_info::"+var_dump(data.result.files.file_info) )
                var info = data.result.files.file_info
                $("#img_preview_image_info").html(info);
                $("#image_info").val('');

                $("#hidden_selected_file_on_registration").val(data.result.files.name);
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    }
    var href = this_backend_home_url + "/admin/get-settings-related-file-on-registrations"
    $.ajax({
        url: href,
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            if (result.error_code == 0) {
                // alert( "result.html::"+var_dump(result.html) )
                $('#div-file-on-registrations').html(result.html)
                if (result.images_count > 0) {
                    $('#span_images_count').html('(' + result.images_count + ")")
                } else {
                    $('#span_images_count').html("")
                }
                // $('.image-link').magnificPopup({type:'image'});
            }
            if (result.error_code > 0) {
                alertMsg(result.message, 'Uploading error!', 'OK', 'fa fa-remove')
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} //backendSettings.prototype.getRelatedFileOnRegistrations = function (get_only_file_on_registrations_count) {


backendSettings.prototype.CancelUploadFileOnRegistration = function () {
    $("#div_files_on_registration_upload_image").css("display", "block");
    $("#div_save_upload_image").css("display", "none");
    $("#img_preview_image").attr("src", "");
    $("#hidden_selected_file_on_registration").val("");
} // backendSettings.prototype.CancelUploadFileOnRegistration = function () {


backendSettings.prototype.UploadFileOnRegistration = function () {
    var hidden_selected_file_on_registration = $("#hidden_selected_file_on_registration").val();
    var dataArray = {
        "_token": this_csrf_token,
        "hidden_selected_file_on_registration": hidden_selected_file_on_registration,
    }
    $.ajax({
        url: this_backend_home_url + '/admin/upload-settings-image-to-file-on-registration',
        type: 'POST',
        dataType: 'json',
        data: dataArray,
        success: function (result) {
            // console.log("result::")
            // console.log( result )
            //
            // alert( "result::"+var_dump(result) )
            if (result.error_code == 0) {
                popupAlert("New file on registration was successfully uploaded !", 'success')
                backendSettings.getRelatedFileOnRegistrations(false)
                backendSettings.CancelUploadFileOnRegistration()
            } else {
                popupAlert(result.message, 'danger')
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });
} // backendSettings.prototype.UploadFileOnRegistration = function () {


backendSettings.prototype.setDevelopersModeOn = function () {
    confirmMsg('Do you want to set developer\'s mode on ?', function () {
            var href = this_backend_home_url + "/admin/set-developers-mode-on";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token},
                success: function (response) {
                    $("#div_set_developers_mode_off").css("display", "block")
                    $("#div_set_developers_mode_on").css("display", "none")
                    popupAlert("Developers Mode was set On !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendSettings.prototype.setDevelopersModeOn = function ( ) {


backendSettings.prototype.setDevelopersModeOff = function () {
    confirmMsg('Do you want to set developer\'s mode off ?', function () {
            var href = this_backend_home_url + "/admin/set-developers-mode-off";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token},
                success: function (response) {
                    $("#div_set_developers_mode_on").css("display", "block")
                    $("#div_set_developers_mode_off").css("display", "none")
                    popupAlert("Developers Mode was set Off !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendSettings.prototype.setDevelopersModeOff = function ( ) {


backendSettings.prototype.generateSiteMapping = function () {
    // alert( "this_frontend_home_url::"+(this_frontend_home_url) )
    confirmMsg('Do you want to generate site mapping ?', function () {
            var href = this_frontend_home_url + "/sitemapping";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    console.log("+++==response::")
                    console.log(response)
                    console.log("+++==response.message::")
                    console.log(response.message)

                    $("#div_last_sitemapping_results").html(response.message)
                    popupAlert("Site mapping was successfully generated !", 'success')
                    // backendSettings.showQuizQualityOptionsListing()
                },
                error: function (error) {
                    console.log("error::")
                    console.log(error)

                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendSettings.prototype.generateSiteMapping = function ( ) {


////////// Files On Registration Block End ///////////


////////// LOGS BLOCK START ///////////
backendSettings.prototype.viewLaravelLog = function () {
    var href = this_backend_home_url + "/admin/view_laravel_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_laravel_log").html(response.laravel_log_content)
            popupAlert("View laravel log was successfully read !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendSettings.prototype.viewLaravelLog = function ( ) {

backendSettings.prototype.deleteLaravelLog = function () {
    var href = this_backend_home_url + "/admin/delete_laravel_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_laravel_log").html("")
            popupAlert("View laravel log was successfully deleted !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendSettings.prototype.deleteLaravelLog = function ( ) {



backendSettings.prototype.viewPaypalLog = function () {
    var href = this_backend_home_url + "/admin/view_paypal_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_paypal_log").html(response.paypal_log_content)
            popupAlert("View paypal log was successfully read !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendSettings.prototype.viewPaypalLog = function ( ) {

backendSettings.prototype.deletePaypalLog = function () {
    var href = this_backend_home_url + "/admin/delete_paypal_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_paypal_log").html("")
            popupAlert("View paypal log was successfully deleted !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendSettings.prototype.deletePaypalLog = function ( ) {




////////////////        logging_deb.txt
backendSettings.prototype.viewLoggingDeb = function () {
    var href = this_backend_home_url + "/admin/view_logging_deb";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_logging_deb").html(response.logging_deb_content)
            popupAlert("View logging_deb log was successfully read !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });
} // backendSettings.prototype.viewLoggingDeb = function ( ) {

backendSettings.prototype.deleteLoggingDeb = function () {
    var href = this_backend_home_url + "/admin/delete_logging_deb";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_logging_deb").html("")
            popupAlert("View logging_deb log was successfully deleted !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });
} // backendSettings.prototype.deleteLoggingDeb = function ( ) {

////////////

backendSettings.prototype.viewSqlTracingLog = function () {
    var href = this_backend_home_url + "/admin/view_sql_tracing_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_sql_tracing_log").html(response.sql_tracing_log_content)
            popupAlert("View sql tracing log was successfully read !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendSettings.prototype.viewSqlTracingLog = function ( ) {

backendSettings.prototype.deleteSqlTracingLog = function () {
    var href = this_backend_home_url + "/admin/delete_sql_tracing_log";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            console.log("+++==response::")
            console.log(response)
            $("#div_view_sql_tracing_log").html("")
            popupAlert("View sql tracing log was successfully deleted !", 'success')
            // backendSettings.showQuizQualityOptionsListing()
        },
        error: function (error) {
            console.log("error::")
            console.log(error)
            popupErrorMessage(error.responseJSON.message)
        }
    });


} // backendSettings.prototype.deleteSqlTracingLog = function ( ) {

////////// LOGS BLOCK END ///////////

