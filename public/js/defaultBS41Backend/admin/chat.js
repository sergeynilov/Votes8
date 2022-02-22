// alert( "chat.js::"+var_dump(-66) )

var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendChat(page, paramsArray) {  // constructor of backend Chat's editor - set all params from server
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
        this.getChatRelatedChatParticipants(this_id)
    }
    if (page == "list") {
        this.ChatsLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendChat(Params) {  constructor of backend Chat's editor - set all params from server


backendChat.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }
} // backendChat.prototype.onBackendPageInit= function(page) { 


backendChat.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendChat.prototype.ChatsLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#chat_details_info_template').html();

    var ins = this
    oTable = $('#get-chat-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading chats..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-chats-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_status = $("#filter_status").val();
            },
        }, // ajax: {

        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": ''
            },
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'creator_id', name: 'creator_id'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " chats")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-chat-dt-listing-table').DataTable({

    $('#get-chat-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            uploadChatDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadChatDetailsInfo(chat_id) {
        var href = '/admin/get-chat-details-info/' + chat_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    $("#div_chat_details_info_" + chat_id).html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });

    } // function uploadChatDetailsInfo(chat_id) {

}


backendChat.prototype.deleteChat = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" chat with all related data ?', function () {
            var href = this_backend_home_url + "/admin/chats/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Chat was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendChat.prototype.deleteChat = function ( id, name ) {


backendChat.prototype.onSubmit = function () {
    var theForm = $("#form_chat_edit");
    theForm.submit();
}

////////// Chat Block End ///////////


////////// Chat Participants Block Start ///////////

backendChat.prototype.getChatRelatedChatParticipants = function (chat_id) {
    // alert( "getChatRelatedChatParticipants  chat_id::"+var_dump(chat_id) )
    
    if ( typeof chat_id == "undefined" ) return
    var href = '/admin/get-chat-related-chat-participants/' + chat_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_related_chat_participants").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });

} // function getChatRelatedChatParticipants(chat_id) {

backendChat.prototype.attachChatParticipantToUser = function (user_id, user_username, to_status, to_status_label) {
    confirmMsg('Do you want to attach "' + user_username + '" user to this chat with "'+to_status_label+'" access ?', function () {
            var href = this_backend_home_url + "/admin/users/attach-chat-participant-to-user";
            $.ajax( {
                type      : "POST",
                dataType  : "json",
                url       : href,
                data      : { "user_id": user_id, "chat_id": this_id, "status" : to_status, "_token": this_csrf_token },
                success: function( response )
                {

                    backendChat.getChatRelatedChatParticipants(this_id)
                    popupAlert("Selected user was attached to the current chat successfully !", 'success')
                },
                error: function( error )
                {
                    popupAlert(error.responseJSON.message, 'danger')
                }
            });

        }
    );
} // backendChat.prototype.attachChatParticipantToUser = function (user_id, user_username) {

backendChat.prototype.clearChatParticipantOfUser = function (user_id, user_username) {
    confirmMsg('Do you want to remove user "' + user_username + '"  from this chat ?', function () {

            var href = this_backend_home_url + "/admin/users/clear-related-chat";
            $.ajax( {
                type      : "POST",
                dataType  : "json",
                url       : href,
                data      : { "user_id": user_id, "chat_id": this_id, "_token": this_csrf_token },
                success: function( response )
                {

                    backendChat.getChatRelatedChatParticipants(this_id)
                    popupAlert("Selected user was cleared from the current chat successfully !", 'success')
                },
                error: function( error )
                {
                    popupAlert(error.responseJSON.message, 'danger')
                }
            });

        }
    );
} // backendChat.prototype.clearChatParticipantOfUser = function (chat_id, user_username) {

////////// Chat Participants Block End ///////////
