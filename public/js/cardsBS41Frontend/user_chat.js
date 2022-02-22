// alert( "frontendUserChat::"+(-11) )
var this_frontend_home_url
var this_csrf_token
var this_votes_chat_server_url
var this_activeUsers
var this_chat_id
var this_logged_user_username
var this_last_whisper_time



function frontendUserChat(page, paramsArray) {  // constructor of frontend userChat's editor - set all params from server
// alert( "page::"+page+"  frontendUserChat paramsArray::"+var_dump(paramsArray) )
//     popupAlert( " frontendUserChat !", 'info')

    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    this_votes_chat_server_url = paramsArray.votes_chat_server_url;
    this_logged_user_id = paramsArray.logged_user_id;
    this_chat_id = paramsArray.chat_id;
    this_logged_user_username = paramsArray.logged_user_username;
    this_last_whisper_time= false;

    this.loadUserChatContent()
    // this.getRelatedChatMessageDocuments(false)

    // div_user_chat_container
} // function frontendUserChat(Params) {  constructor of frontend userChat's editor - set all params from server


frontendUserChat.prototype.loadUserChatContent = function () {

    var href = this_frontend_home_url + "/profile/load-user-chat-content/" + this_chat_id;
    // alert( "loadUserChatContent href::"+var_dump(href) )
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            // console.log("response::")
            // console.log( response )

            $('#div_user_chat_container').html(response.html)
            frontendUserChat.initChatMessageDocuments(false)
            // frontendUserChat.getTempUploadingChatMessageDocuments()
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}

frontendUserChat.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    // alert( "public/js/cardsBS41Frontend/user_chat.js page::"+(page) )
    frontendInit()

    // frontendUserChat.showChatMembers(1) // TO COMMENT
    /**
     * Echo exposes an expressive API for subscribing to channels and listening
     * for events that are broadcast by Laravel. Echo and event broadcasting
     * allows your team to easily build robust real-time web applications.
     */

    // $( "#vote_chat_new_message" ).keyup(function() {
    //     // alert( "Handler for .keyup() called.  this_logged_user_username::"+var_dump(this_logged_user_username) );
    //     Echo.join('make_votes_chat')
    //         .whisper('typing', this_logged_user_username);
    //
    // });

    // console.log("onFrontendPageInit check -1 Echo::")
    // console.log( Echo )





    Echo.join('make_votes_chat')
        .listen('VoteChatMessageSentEvent', (event) => {
            // alert( "listen.VoteChatMessageSentEvent event -9::"+var_dump(event) )
            popupAlert( event.author_name + " sent a message !", 'info', 2000)
            console.log("VoteChatMessageSentEvent  event::")
            console.log( event )

        })
        .listenForWhisper('typing', (username) => {
            // alert( "listenForWhisper typing::"+var_dump(username) )
            // console.log("typing  response::")
            // console.log( response )
            $("#div_type_whisper_alert").html( username+" typing..." );
            $("#div_type_whisper_alert_wrapper").css( "display", "block" );
            // this_last_whisper_time= ( new Date() ).getTime()
            clearTimeout(this_last_whisper_time)
            this_last_whisper_time= setTimeout( () => {
                $("#div_type_whisper_alert_wrapper").css( "display", "none" );
                $("#div_type_whisper_alert").html( "" );
            }, 3000);
        })
        .here((activeUsers) => {
            // WORK OK alert( ".here::"+var_dump(-7) )
            console.log("here  activeUsers::")
            console.log( activeUsers )
            this_activeUsers= activeUsers
            $("#span_show_chat_members_count").html(activeUsers.length)
        })
        .joining((user) => {
            // WORK OK alert( ".joining::"+var_dump(-5) )
            console.log("joining -5 user::")
            console.log(user.id + ' = ' + user.username);
            popupAlert( user.username + " joined this chat !", 'info')
            let span_show_chat_members_count= parseInt($("#span_show_chat_members_count").html()) + 1
            $("#span_show_chat_members_count").html(span_show_chat_members_count)
            $("#span_dialog_show_chat_members_count").html(span_show_chat_members_count)
        })
        .leaving((user) => {
            // WORK OK alert( ".leaving::"+var_dump(-6) )
            console.log("leaving -6 user::")
            console.log(user.id + ' = ' + user.username);
            popupAlert( user.username + " left this chat !", 'info')
            let span_show_chat_members_count= parseInt($("#span_show_chat_members_count").html()) - 1
            if ( span_show_chat_members_count > 0 ) {
                $("#span_show_chat_members_count").html(span_show_chat_members_count)
                $("#span_dialog_show_chat_members_count").html(span_show_chat_members_count)
            }
        })
    ;


    // alert( "AFTER Echo.join::"+(-11) )
    if (page == "view") {

    }

} // frontendUserChat.prototype.onFrontendPageInit= function(page) { 


frontendUserChat.prototype.vote_chat_new_messageOnKeyDown= function() {
    // alert( "vote_chat_new_messageOnKeyDown  event::"+var_dump(event) )
    Echo.join('make_votes_chat')
        .whisper('typing', this_logged_user_username);
}


frontendUserChat.prototype.newChatMessageSent= function(chat_id) {
    var new_message= jQuery.trim( $('#vote_chat_new_message').val() )
    // alert( "newChatMessageSent  chat_id::"+(chat_id) + "  new_message::"+new_message)
    if ( new_message != "" ) {
        // alert( "newChatMessageSent this_frontend_home_url::"+var_dump(this_frontend_home_url) )
        var href = this_frontend_home_url + "/profile/chat-message-sent";
        $.ajax( {
            type      : "POST",
            dataType  : "json",
            url       : href,
            data      : { "chat_id": chat_id, "text": new_message, "is_top" : 1, "_token": this_csrf_token },
            success: function( response )
            {
                $('#vote_chat_new_message').val("")

                popupAlert("Your message was sent !", 'success')
            },
            error: function( error )
            {
                popupAlert(error.responseJSON.message, 'danger')
            }
        });

    }
    
}

frontendUserChat.prototype.showChatMembers= function(chat_id) {
    // popupAlert("WWWWWW", 'danger') // 'info', 'success'
    var activeUserIds= ''

    this_activeUsers.forEach(function (nextActiveUser) {
        console.log("nextActiveUser::")
        console.log( nextActiveUser )
        activeUserIds= activeUserIds+nextActiveUser.id+','
        // monthsXCoordItems.push(data.formatted_created_at);
        // voteValuesCorrect.push(data.count);
    });

    var href= '/profile/show-chat-members/'+chat_id+'/'+activeUserIds;
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function( response )
            {
                console.log("response::")
                console.log( response )

                $("#span_dialog_show_chat_members_count").html($("#span_show_chat_members_count").html())

                $("#div_user_chat_members_content").html(response.html)
                $("#div_user_chat_members_modal" ).modal(  {
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                }  );
            },
            error: function( error )
            {
                popupErrorMessage(error.responseJSON.message)
            }

        } );

} // frontendUserChat.prototype.showChatMembers= function(chat_id) {


// <button onclick="javascript:frontendUserChat.attachChatMembers( {{ $chat_id }} ); return false; " class="a_link small btn btn-primary">
// frontendUserChat.prototype.showChatMembers= function(chat_id) {

frontendUserChat.prototype.attachChatMembers= function(chat_id) {
    alert( "attachChatMembers  chat_id::"+(chat_id) )
}

frontendUserChat.prototype.attachUserToChat= function(chat_id, user_id, to_status, username) {
    var href= '/profile/attach-user-to-chat/'+chat_id+'/'+user_id+'/'+to_status;
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function( response )
            {
                frontendUserChat.showChatMembers( chat_id )
                popupAlert( username + " was successfully added to this chat !", 'success' )
            },
            error: function( error )
            {
                popupErrorMessage(error.responseJSON.message)
            }

        } );

}

frontendUserChat.prototype.clearUserInChat= function(chat_id, user_id, username) {
    var href= '/profile/clear-user-in-chat/'+chat_id+'/'+user_id;
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function( response )
            {
                frontendUserChat.showChatMembers(chat_id)
                popupAlert( username + " was successfully removed from this chat !", 'success' )
            },
            error: function( error )
            {
                popupErrorMessage(error.responseJSON.message)
            }

        } );
}

////////// userChat Block End ///////////


frontendUserChat.prototype.initChatMessageDocuments = function (get_only_chat_message_documents_count) {
    if ( !get_only_chat_message_documents_count ) {
        // alert( "initChatMessageDocuments::"+var_dump(-999) )

        // $('.page_content_image_fileupload').fileupload({
        //     url: this_frontend_home_url+"/admin/upload-page-content-image-to-tmp-page-content",
        //     type: "POST",
        //     dataType: 'json',
        //     done: function (e, data) {
        $('.chat_message_document_fileupload').fileupload({
            url: this_frontend_home_url+"/profile/upload-chat-message-document-to-tmp/"+this_chat_id,
            type: "POST",
            // data: { "_token": this_csrf_token},
            dataType: 'json',
            done: function (e, data) {
                // alert( "data.result.files.url::"+var_dump(data.result.files.url) )
                alert( "chat_message_document_fileupload data::"+var_dump(data) )
                
                $("#div_files_chat_message_document_upload_image").css("display", "none");
                $("#div_save_upload_image").css("display", "block");

                var info_message= data.result.files.info_message
                // alert( "info_message::"+var_dump(info_message) )
                if ( info_message!= "" ) {
                    $("#div_info_message").css("display", "block");
                    $("#div_info_message").html(info_message);
                }
                else {
                    $("#div_info_message").css("display", "none");
                    $("#div_info_message").html("");
                }
                if ( parseInt(data.result.files.FilenameInfo.width) && parseInt(data.result.files.FilenameInfo.height)) {
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

                $("#hidden_selected_chat_message_document").val(data.result.files.name);
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

} //frontendUserChat.prototype.initChatMessageDocuments = function (get_only_chat_message_documents_count) {


frontendUserChat.prototype.CancelUploadChatMessageDocument = function () {
    $("#div_files_chat_message_document_upload_image").css("display", "block");
    $("#div_save_upload_image").css("display", "none");
    $("#img_preview_image").attr( "src","" );
    $("#hidden_selected_chat_message_document" ).val( "" );
} // frontendUserChat.prototype.CancelUploadChatMessageDocument = function () {




frontendUserChat.prototype.UploadChatMessageDocument = function () {
    var hidden_selected_chat_message_document= $("#hidden_selected_chat_message_document").val();
    var dataArray= {
        "_token": this_csrf_token,
        "hidden_selected_chat_message_document" : hidden_selected_chat_message_document,
    }
    $.ajax({
        url: this_frontend_home_url+'/profile/upload-image-to-chat-message-document/'+this_chat_id,
        type: 'POST',
        dataType: 'json',
        data: dataArray,
        success: function(result) {
            // console.log("result::")
            // console.log( result )
            //
            // alert( "result::"+var_dump(result) )
            if (result.error_code == 0) {
                popupAlert("New chat message document was successfully uploaded !", 'success')
                frontendUserChat.getTempUploadingChatMessageDocuments( false )
                frontendUserChat.CancelUploadChatMessageDocument()
            } else {
                popupAlert(result.message, 'danger')
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });
} // frontendUserChat.prototype.UploadChatMessageDocument = function () {



frontendUserChat.prototype.getTempUploadingChatMessageDocuments = function () {
    var href = this_backend_home_url+"/profile/get-temp-uploading-chat-message-documents/"+this_chat_id
    alert( "getTempUploadingChatMessageDocuments  href::"+var_dump(href) )
    $.ajax({
        url: href,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.error_code == 0) {
                // alert( "result.html::"+var_dump(result.html) )
                $('#div-temp-uploading-chat-message-documents').html(result.html)
                frontendUserChat.getTempUploadingChatMessageDocuments()

                if ( result.images_count > 0 ) {
                    console.log("result::")
                    console.log( result )

                    $('#span-temp-uploading-chat-message-documents-count').html('(' + result.chat_message_documents_count + ")")
                } else {
                    $('#temp-uploading-chat-message-documents-count').html("")
                }
                // $('.image-link').magnificPopup({type:'image'});
            }
            if (result.error_code > 0) {
                popupErrorMessage(error.responseJSON.message)
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} //frontendUserChat.prototype.getTempUploadingChatMessageDocuments = function () {


