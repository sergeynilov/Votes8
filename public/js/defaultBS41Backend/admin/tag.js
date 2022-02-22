// alert( "tag.js::"+var_dump(1) )
var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendTag(page, paramsArray) {  // constructor of backend Tag's editor - set all params from server
    // alert( "page::"+page+"  backendTag paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if (page == "edit") {
        this_id = paramsArray.id;
        this.getTagMetaKeywords(this_id)
    }
    if (page == "list") {
        this.TagsLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendTag(Params) {  constructor of backend Tag's editor - set all params from server


backendTag.prototype.onBackendPageInit = function (page) {  
    // alert( "backendTag  onBackendPageInit this_id::"+this_id )
    backendInit()

    if (page == "edit") {
        $('#pills-tab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    }
} // backendTag.prototype.onBackendPageInit= function(page) {


backendTag.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendTag.prototype.TagsLoad = function () {
    // alert("TagsLoad :" + (-10))

    Mustache.tags = ["<%", "%>"];
    var template = $('#tag_details_info_template').html();

    // console.log("public/js/tag.js::")


    var ins = this
    oTable = $('#get-tag-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading tags..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-tags-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
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
            {data: 'slug', name: 'slug'},
            {data: 'type', name: 'type'},
            {data: 'order_column', name: 'order_column'},
/* CREATE TABLE `tags` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` INT NULL,
	`slug` INT NULL,
	`type` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    	`order_column` INT(11) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,*/
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " tags")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                // $(".dataTables_info").parent().css("display", "none")
                // $(".dataTables_info").parent().parent().css("display", "none")
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }

        },

    }); // oTable = $('#get-tag-dt-listing-table').DataTable({

    var parent_form = this
    $('#get-tag-dt-listing-table tbody').on('click', 'td.details-control', function () {
        // alert("--get-tag-dt-listing-table::" + (-754))
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            parent_form.uploadTagDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });


}

backendTag.prototype.uploadTagDetailsInfo = function (tag_id) {
    var href = '/admin/get-tag-details-info/' + tag_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_tag_details_info_" + tag_id).html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // function uploadTagDetailsInfo(tag_id) {

backendTag.prototype.deleteTag = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" tag with all related data ?', function () {
            var href = this_backend_home_url + "/admin/tags/destroy";

            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Tag was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendTag.prototype.deleteTag = function ( id, name ) {

backendTag.prototype.statusOnChange = function () {
    var status = $("#status").val()
}

backendTag.prototype.onSubmit = function () {
    var theForm = $("#form_tag_edit");
    theForm.submit();
}

/// VOTE CATEGORIES META KEYWORD EDITOR BLOCK START

backendTag.prototype.getTagMetaKeywords = function (vote_category_id) {
    if ( typeof vote_category_id == "undefined" ) return
    var href = '/admin/get-tag-meta-keywords/' + vote_category_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_meta_keywords").html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // function uploadVoteDetailsInfo(vote_category_id) {

backendTag.prototype.attachMetaKeywordToTag = function () {
    var meta_keyword= $("#new_meta_keyword").val()
    if ( jQuery.trim(meta_keyword) == '') {
        popupAlert("Fill new meta keyword !", 'danger') // 'info', 'success'
        $("#new_meta_keyword").focus()
        return;
    }
    confirmMsg('Do you want to add "' + meta_keyword + '" meta keyword to this tag?', function () {
            var href = this_backend_home_url + "/admin/votes/attach-tag-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("New meta keyword was successfully added!", 'success') // 'info', 'success'
                    backendTag.getTagMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}

backendTag.prototype.clearMetaKeywordToTag = function (meta_keyword) {
    confirmMsg('Do you want to clear "' + meta_keyword + '" meta keyword from this tag ?', function () {
            var href = this_backend_home_url + "/admin/votes/clear-tag-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("The meta keyword was successfully cleared!", 'success') // 'info', 'success'
                    backendTag.getTagMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}
