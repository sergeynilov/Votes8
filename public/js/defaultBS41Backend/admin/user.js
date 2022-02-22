var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendUser(page, paramsArray) {  // constructor of backend User's editor - set all params from server
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if (this_filter_type == 'filter_status') {
        $("#filter_status").val(this_filter_value);
    }

    if (page == "edit") {
        this_id = paramsArray.id;
        this.getUserRelatedSiteSubscriptions(this_id)
        this.getUserRelatedChats(this_id)

    }
    if (page == "list") {
        this.UsersLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendUser(Params) {  constructor of backend User's editor - set all params from server


backendUser.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }
} // backendUser.prototype.onBackendPageInit= function(page) {


backendUser.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendUser.prototype.UsersLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#user_details_info_template').html();

    var ins = this
    oTable = $('#get-user-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading users..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-users-dt-listing',

            data: function (d) {
                d.filter_username = $("#filter_username").val();
                d.filter_status = $("#filter_status").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'username', name: 'username'},
            {data: 'status', name: 'status'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " users")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-user-dt-listing-table').DataTable({

    $('#get-user-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            uploadUserDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadUserDetailsInfo(user_id) {
        var href = '/admin/get_user_details_info/' + user_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    $("#div_user_details_info_" + user_id).html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });

    } // function uploadUserDetailsInfo(user_id) {

}


backendUser.prototype.deleteUser = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" user with all related data ?', function () {
            var href = this_backend_home_url + "/admin/users/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("User was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendUser.prototype.deleteUser = function ( id, name ) {


backendUser.prototype.onSubmit = function () {
    var theForm = $("#form_user_edit");
    theForm.submit();
}

backendUser.prototype.generatePassword = function (user_id) {
    confirmMsg('Do you want to generate new password for this account ? It would be sent to the owner of this account.', function () {
            var href = this_backend_home_url + "/admin/user/generate-password/" + user_id;
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

} // backendUser.prototype.generatePassword

backendUser.prototype.editUserAccess = function (user_id) {
    var href= this_backend_home_url + '/admin/get-user-access-groups-info/'+user_id;

    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function( response )
            {
                $("#div_user_access_results").html(response.html)
                $("#div_user_access_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
            },
            error: function( error )
            {
                popupErrorMessage(error.responseJSON.message)
            }
        }
    );

} // backendUser.prototype.editUserAccess=

backendUser.prototype.updateUserAccess = function (user_id) {
    var href = this_backend_home_url + "/admin/update-user-access";
    var selectUserAccess= [];
    $('select.update_user_access').each(function () {
        var userAccessItem = {}
        userAccessItem['id'] = this.id;
        userAccessItem['selected'] = $(this).val();
        selectUserAccess[selectUserAccess.length]= userAccessItem
    });

    $.ajax( {
        type: "POST",
        dataType: "json",
        url: href,
        data: {"user_id": user_id, "selectedUserAccess": selectUserAccess, "_token": this_csrf_token},
        success: function( response )
        {
            popupAlert("Your modifications were applied !", 'success') // 'info', 'success'
            $("#div_user_access_modal").modal('hide');

        },
        error: function( error )
        {
            popupAlert(error.responseJSON.message, 'danger') // 'info', 'success'
        }
    });


} // backendUser.prototype.updateUserAccess=

////////// User Block End ///////////



////////// Site Subscriptions Block Start ///////////

backendUser.prototype.getUserRelatedSiteSubscriptions = function (user_id) {
    if ( typeof user_id == "undefined" ) return
    var href = '/admin/get-user-related-site-subscriptions/' + user_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_related_site_subscriptions").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });

}

backendUser.prototype.attachSiteSubscriptionToUser = function (site_subscription_id, site_subscription_name) {
    confirmMsg('Do you want to attach "' + site_subscription_name + '" site subscription to this user ?', function () {
            var href = this_backend_home_url + "/admin/users/attach-related-site-subscription/" + this_id + "/" + site_subscription_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendUser.getUserRelatedSiteSubscriptions(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
} // backendUser.prototype.attachSiteSubscriptionToUser = function (site_subscription_id, site_subscription_name) {

backendUser.prototype.clearSiteSubscriptionToUser = function (site_subscription_id, site_subscription_name) {
    confirmMsg('Do you want to clear "' + site_subscription_name + '" site subscription from this user ?', function () {
            var href = this_backend_home_url + "/admin/users/clear-related-site-subscription/" + this_id + "/" + site_subscription_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendUser.getUserRelatedSiteSubscriptions(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
} // backendUser.prototype.clearSiteSubscriptionToUser = function (site_subscription_id, site_subscription_name) {

////////// Site Subscriptions Block End ///////////





////////// Users Block Start ///////////

backendUser.prototype.getUserRelatedChats = function (user_id) {
    if ( typeof user_id == "undefined" ) return
    var href = '/admin/get-user-related-chats/' + user_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_related_chats").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });

} 

backendUser.prototype.attachChatParticipantToUser = function (chat_id, chat_name, to_status, to_status_label) {
    confirmMsg('Do you want to attach "' + chat_name + '" chat to this user with "'+to_status_label+'" access ?', function () {
        var href = this_backend_home_url + "/admin/users/attach-chat-participant-to-user";
        $.ajax( {
            type      : "POST",
            dataType  : "json",
            url       : href,
            data      : { "chat_id": chat_id, "user_id": this_id, "status" : to_status, "_token": this_csrf_token },
            success: function( response )
            {

                backendUser.getUserRelatedChats(this_id)
                popupAlert("Selected chat was attached to the current user successfully !", 'success')
            },
            error: function( error )
            {
                popupAlert(error.responseJSON.message, 'danger')
            }
        });

        }
    );
} // backendUser.prototype.attachChatParticipantToUser = function (chat_id, chat_name) {

backendUser.prototype.clearChatParticipantOfUser = function (chat_id, chat_name) {
    confirmMsg('Do you want to clear chat "' + chat_name + '" from this user ?', function () {

        var href = this_backend_home_url + "/admin/users/clear-related-chat";
        $.ajax( {
            type      : "POST",
            dataType  : "json",
            url       : href,
            data      : { "chat_id": chat_id, "user_id": this_id, "_token": this_csrf_token },
            success: function( response )
            {

                backendUser.getUserRelatedChats(this_id)
                popupAlert("Selected chat was cleared from the current user successfully !", 'success')
            },
            error: function( error )
            {
                popupAlert(error.responseJSON.message, 'danger')
            }
        });

        }
    );
} // backendUser.prototype.clearChatParticipantOfUser = function (chat_id, chat_name) {

////////// Users Block End ///////////
