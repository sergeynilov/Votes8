var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendPageContent(page, paramsArray) {  // constructor of backend PageContent's editor - set all params from server
// alert( "page::"+page+"  backendPageContent paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if ( this_filter_type == 'filter_accepted' ) {
        $("#filter_accepted").val(this_filter_value);
    }

    if (page == "edit") {
        this_id = paramsArray.id;
        if ( typeof this_id!= "undefined") {
            this.getRelatedPageContentImages(this_id, false)
        }
    }
    if (page == "list") {
        this.PageContentLoad()
        $(".dataTables_filter").css("display","none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendPageContent(Params) {  constructor of backend PageContent's editor - set all params from server


backendPageContent.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    if (page == "edit") {

    }
} // backendPageContent.prototype.onBackendPageInit= function(page) {


backendPageContent.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendPageContent.prototype.PageContentLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#page_content_details_info_template').html();

/*                     {{ Form::select('filter_is_featured', $pageContentIsFeaturedValueArray, '', [ "id"=>"filter_is_featured", "class"=>"form-control editable_field select_input
                    " ] ) }}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {{ Form::select('filter_page_type', $pageContentPageTypeValueArray, '', [ "id"=>"filter_page_type", "class"=>"form-control editable_field select_input " ] ) }}
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    {{ Form::select('filter_is_homepage', $pageContentIsHomepageValueArray, '', [ "id"=>"filter_is_homepage", "class"=>"form-control editable_field
 */
    // alert( "PageContentLoad::"+var_dump(11) )
    var ins= this
    oTable = $('#get-page-content-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading pages..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-page-content-dt-listing',

            data: function (d) {
                d.filter_title = $("#filter_title").val();
                d.filter_is_featured = $("#filter_is_featured").val();
                d.filter_page_type = $("#filter_page_type").val();
                d.filter_published = $("#filter_published").val();
                d.filter_is_homepage = $("#filter_is_homepage").val();
            },
        }, // ajax: {

        columns: [
            // {
            //     "className": 'details-control',
            //     "orderable": false,
            //     "searchable": false,
            //     "data": null,
            //     "defaultContent": ''
            // },
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'creator_username', name: 'creator_username'},
            {data: 'is_featured', name: 'is_featured'},
            {data: 'published', name: 'published'},
            {data: 'page_type', name: 'page_type'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}
        ],

        "drawCallback": function (settings, b) {

            // alert( "settings.json::"+var_dump(settings.json) )
            $(".dataTables_info").html( settings.json.data.length + " of " + settings.json.recordsFiltered +" page content")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-page-content-dt-listing-table').DataTable({

    $('#get-page-content-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            uploadPageContentDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadPageContentDetailsInfo(page_content_id) {
        var href= '/admin/get-page-content-details-info/' + page_content_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: url,
                success: function( response )
                {
                    $( "#div_page_content_details_info_" + page_content_id ).html( response.html );
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }

            } );

    } // function uploadPageContentDetailsInfo(page_content_id) {

}


backendPageContent.prototype.deletePageContent = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" page content with all related data ?', function () {
            var href = this_backend_home_url + "/admin/page-contents/destroy";
            $.ajax( {
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Page content was successfully deleted !", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendPageContent.prototype.deletePageContent = function ( id, name ) {


backendPageContent.prototype.onSubmit = function () {
    var theForm = $("#form_page_content_edit");
    theForm.submit();
}


backendPageContent.prototype.acceptPageContent = function (id, title) {
    confirmMsg('Do you want to accept page content of "' + title + '" ?', function () {
            var href = this_backend_home_url + "/admin/page-content-accept/"+id;
            $.ajax( {
                type: "GET",
                dataType: "json",
                url: href,
                success: function( response )
                {
                    document.location.reload(true);
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }

            });

        }
    );

} // backendPageContent.prototype.acceptPageContent = function (id, title) {

////////// PageContent Block End ///////////



////////// Page Content Images Block Start ///////////


backendPageContent.prototype.is_videoOnChange = function () {
    var is_video= $("#is_video").is(':checked')
    if (is_video) {
        $("#div_video_block").css('display','block')
        $("#div_video_block_warning").css('display','block')
        $("#video_width").val('')
        $("#video_height").val('')
        $("#is_main_image").prop('checked', false);
    } else{
        $("#div_video_block").css('display','none')
        $("#div_video_block_warning").css('display','none')
    }
} // backendPageContent.prototype.is_videoOnChange = function () {

backendPageContent.prototype.deletePageContentImage = function (page_content_image_id, image_name, is_image) {
    confirmMsg("Do you want to delete '"+image_name+"' "+(is_image?"image":"video")+" ? ", function() {
            var href = this_backend_home_url+"/admin/delete-page-content-image"
            $.ajax({
                async :false,
                url: href,
                type: "DELETE",
                dataType: "json",
                data: { "page_content_image_id" : page_content_image_id, "image" : encodeURIComponent(image_name), "page_content_id" : this_id, "_token": this_csrf_token }
            }).done(function(result){
                // alert( "result::"+var_dump(result) )
                if (typeof result == "undefined") {
                    popupAlert("File was successfully deleted !", 'success')
                    backendPageContent.getRelatedPageContentImages( this_id, false )
                } else {
                    if ( typeof result.error_code  != "undefined") {
                        popupAlert(result.message, 'danger')
                    }
                }
            });
        }
    );

} // backendPageContent.prototype.deletePageContentImage = function (page_content_image_id, page_content_id, object_name, image_name) {


backendPageContent.prototype.getRelatedPageContentImages = function (page_content_id, get_only_images_count) {
    if ( !get_only_images_count ) {
        $('.page_content_image_fileupload').fileupload({
            url: this_backend_home_url+"/admin/upload-page-content-image-to-tmp-page-content",
            type: "POST",
            dataType: 'json',
            done: function (e, data) {
                // alert( "data.result.files.url::"+var_dump(data.result.files.url) )
                // alert( "page_content_image_fileupload data::"+var_dump(data) )
                $("#div_upload_image").css("display", "none");
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
                $("#is_main_image").prop('checked', false);
                $("#is_video").prop('checked', false);
                $("#image_info").val('');

                $("#div_video_block").css('display','none')
                $("#div_video_block_warning").css('display','none')
                $("#video_width").val('')
                $("#video_height").val('')

                $("#hidden_selected_image").val(data.result.files.name);
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
    // backendPageContent.switchImagesLoader(false)
    var href = this_backend_home_url+"/admin/get-related-page-content-images/" + page_content_id
    $.ajax({
        url: href,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            // alert( "result::"+var_dump(result) )
            // backendPageContent.switchImagesLoader(true)
            if (result.error_code == 0) {
                $('#div-page-content-images').html(result.html)

                var deviceParams= effectiveDeviceWidth()
                // alert( "deviceParams::"+var_dump(deviceParams) )

                $(".video_control").css("width", 900);
                if ( parseInt(deviceParams.width) <= 1024 ) {
                    $(".video_control").css("width", 768);
                }
                if ( parseInt(deviceParams.width) <= 768 ) {
                    $(".video_control").css("width", 600);
                }
                if ( parseInt(deviceParams.width) <= 600 ) {
                    $(".video_control").css("width", 500);
                }
                if ( parseInt(deviceParams.width) <= 480 ) {
                    $(".video_control").css("width", 400);
                }
                if ( parseInt(deviceParams.width) <= 320 ) {
                    $(".video_control").css("width", 240);
                }

                // backendPageContent.CancelUploadImage()
                if ( result.images_count > 0 ) {
                    $('#span_images_count').html('(' + result.images_count + ")")
                } else {
                    $('#span_images_count').html("")
                }
                // $('.image-link').magnificPopup({type:'image'});
            }
            if (result.error_code > 0) {
                alertMsg( result.message, 'Uploading error!', 'OK', 'fa fa-remove' )
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} //backendPageContent.prototype.getRelatedPageContentImages = function (get_only_images_count) {


backendPageContent.prototype.CancelUploadImage = function () {
    // alert( "CancelCancelUploadImage"+var_dump(-7) )
    $("#div_upload_image").css("display", "block");
    $("#div_save_upload_image").css("display", "none");
    $("#img_preview_image").attr( "src","" );
    $("#hidden_selected_image" ).val( "" );
    $("#is_main_image").prop('checked', false);
    $("#is_video").prop('checked', false);
    $("#video_width").val('')
    $("#video_height").val('')
} // backendPageContent.prototype.CancelUploadImage = function () {



backendPageContent.prototype.UploadImage = function () {
    var hidden_selected_image= $("#hidden_selected_image").val();
    var is_main_image= $("#is_main_image").is(':checked')
    var is_video= $("#is_video").is(':checked')
    var image_info= jQuery.trim( $("#image_info").val() )
    var video_width= jQuery.trim( $("#video_width").val() )
    var video_height= jQuery.trim( $("#video_height").val() )

    var dataArray= {
        "_token": this_csrf_token,
        "page_content_id" : this_id,
        "page_content_image" : hidden_selected_image,
        "is_main_image":( is_main_image ? "Y" : "N" ),
        "is_video" : ( is_video ? "Y" : "N" ),
        "video_width" : video_width,
        "video_height" : video_height,
        "info" : image_info
    }
    $.ajax({
        url: this_backend_home_url+'/admin/upload-image-to-page-content',
        type: 'POST',
        dataType: 'json',
        data: dataArray,
        success: function(result) {
            // alert( "result::"+var_dump(result) )
            if (result.error_code == 0) {
                popupAlert("New file was successfully uploaded !", 'success')
                backendPageContent.getRelatedPageContentImages( this_id, false )
                backendPageContent.CancelUploadImage()
            } else {
                popupAlert(result.message, 'danger')
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });
} // backendPageContent.prototype.UploadImage = function () {


////////// Page Content Images Block End ///////////
