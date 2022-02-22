// alert( "video.js::"+var_dump(-5) )

// alert( "video.js::"+var_dump(1) )
var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendVideo(page, paramsArray) {  // constructor of backend Video's editor - set all params from server
    // alert( "page::"+page+ "  paramsArray.id::"+paramsArray.id+"  backendVideo paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;
    this_filter_is_homepage = paramsArray.filter_is_homepage;

    if (this_filter_type == 'filter_status') {
        $("#filter_status").val(this_filter_value);
    }
    if (this_filter_type == 'filter_is_homepage') {
        $("#filter_is_homepage").val(this_filter_is_homepage);
    }

    if (page == "edit") {
        this_id = paramsArray.id;
        this.getVideoRelatedTags(this_id)
    }
    if (page == "list") {
        this.VideosLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendVideo(Params) {  constructor of backend Video's editor - set all params from server


backendVideo.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    // alert( "backendVideo  onBackendPageInit this_id::"+this_id )
    backendInit()

    if (page == "edit") {
        $('#pills-tab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    }
} // backendVideo.prototype.onBackendPageInit= function(page) {  


backendVideo.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendVideo.prototype.VideosLoad = function () {
    // alert("VideosLoad :" + (-10))

    Mustache.tags = ["<%", "%>"];
    var template = $('#video_details_info_template').html();

    var ins = this
    oTable = $('#get-video-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading videos..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get_videos_dt_listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_status = $("#filter_status").val();
                d.filter_is_quiz = $("#filter_is_quiz").val();
                d.filter_is_homepage = $("#filter_is_homepage").val();
                d.filter_video_category_id = $("#filter_video_category_id").val();
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
            {data: 'is_quiz', name: 'is_quiz'},
            {data: 'video_category_name', name: 'video_category_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " videos")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }
        },

    }); // oTable = $('#get-video-dt-listing-table').DataTable({

    var parent_form = this
    $('#get-video-dt-listing-table tbody').on('click', 'td.details-control', function () {
        // alert("--get-video-dt-listing-table::" + (-754))
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            parent_form.uploadVideoDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });


}

backendVideo.prototype.uploadVideoDetailsInfo = function (video_id) {
    var href = '/admin/get_video_details_info/' + video_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_video_details_info_" + video_id).html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // function uploadVideoDetailsInfo(video_id) {

backendVideo.prototype.deleteVideo = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" video with all related data ?', function () {
            var href = this_backend_home_url + "/admin/video/destroy";

            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Video was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendVideo.prototype.deleteVideo = function ( id, name ) {

backendVideo.prototype.statusOnChange = function () {
    var status = $("#status").val()
}

backendVideo.prototype.onSubmit = function () {
    var theForm = $("#form_video_edit");
    theForm.submit();
}


backendVideo.prototype.deleteVideoItem = function (video_item_id, video_id, name) {
    confirmMsg('Do you want to delete "' + name + '" Video item ?', function () {
            var href = this_backend_home_url + "/admin/video_items/destroy/" + video_item_id;
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"video_item_id": video_item_id, "_token": this_csrf_token},
                success: function (response) {
                    // $("#btn_run_search").click()
                    // alert("success ::" + var_dump(response))
                    document.location = '/admin/video/edit/' + video_id
                    // $( "#div_video_details_info_" + video_id ).html( response.html );
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
}


backendVideo.prototype.getVideoRelatedTags = function (video_id) {
    if ( typeof video_id == "undefined" ) return
    var href = '/admin/get_video_related_tags/' + video_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_related_tags").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });

} // function uploadVideoDetailsInfo(video_id) {

backendVideo.prototype.attachTagToVideo = function (tag_id, tag_name) {
    confirmMsg('Do you want to attach "' + tag_name + '" tag to this video ?', function () {
            var href = this_backend_home_url + "/admin/videos/attach-related-tag/" + this_id + "/" + tag_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendVideo.getVideoRelatedTags(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
} // backendVideo.prototype.attachTagToVideo = function (tag_id, tag_name) {

backendVideo.prototype.clearTagToVideo = function (tag_id, tag_name) {
    confirmMsg('Do you want to clear "' + tag_name + '" tag from this video ?', function () {
            var href = this_backend_home_url + "/admin/videos/clear-related-tag/" + this_id + "/" + tag_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendVideo.getVideoRelatedTags(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
} // backendVideo.prototype.clearTagToVideo = function (tag_id, tag_name) {

