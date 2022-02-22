var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendVoteCategory(page, paramsArray) {  // constructor of backend VoteCategory's editor - set all params from server
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if (this_filter_type == 'filter_active') {
        $("#filter_active").val(this_filter_value);
    }

    if (page == "edit") {
        this_id = paramsArray.id;
        this.getVoteCategoryMetaKeywords(this_id)
    }
    if (page == "list") {
        this.VoteCategoriesLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });

    }
} // function backendVoteCategory(Params) {  constructor of backend VoteCategory's editor - set all params from server


backendVoteCategory.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }
} // backendVoteCategory.prototype.onBackendPageInit= function(page) { 


backendVoteCategory.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendVoteCategory.prototype.VoteCategoriesLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#vote_category_details_info_template').html();

    var ins = this
    oTable = $('#get-vote-category-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading vote-categories..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-vote-categories-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_active = $("#filter_active").val();
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
            {data: 'active', name: 'active'},
            {data: 'in_subscriptions', name: 'in_subscriptions'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " vote" + " categories")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-vote-category-dt-listing-table').DataTable({

    $('#get-vote-category-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            uploadVoteCategoryDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadVoteCategoryDetailsInfo(vote_category_id) {
        var href = '/admin/get-vote-category-details-info/' + vote_category_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    $("#div_vote_category_details_info_" + vote_category_id).html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });

    } // function uploadVoteCategoryDetailsInfo(vote_category_id) {

}


backendVoteCategory.prototype.deleteVoteCategory = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" vote category with all related data ?', function () {
            var href = this_backend_home_url + "/admin/vote-categories/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Vote category was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendVoteCategory.prototype.deleteVoteCategory = function ( id, name ) {


backendVoteCategory.prototype.onSubmit = function () {
    var theForm = $("#form_vote_category_edit");
    theForm.submit();
}

////////// VoteCategory Block End ///////////


/// VOTE CATEGORIES META KEYWORD EDITOR BLOCK START

backendVoteCategory.prototype.getVoteCategoryMetaKeywords = function (vote_category_id) {
    if ( typeof vote_category_id == "undefined" ) return
    var href = '/admin/get-vote-category-meta-keywords/' + vote_category_id;
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

backendVoteCategory.prototype.attachMetaKeywordToVoteCategory = function () {
    var meta_keyword= $("#new_meta_keyword").val()
    if ( jQuery.trim(meta_keyword) == '') {
        popupAlert("Fill new meta keyword !", 'danger') // 'info', 'success'
        $("#new_meta_keyword").focus()
        return;
    }
    confirmMsg('Do you want to add "' + meta_keyword + '" meta keyword to this vote category?', function () {
            var href = this_backend_home_url + "/admin/votes/attach-vote-category-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("New meta keyword was successfully added!", 'success') // 'info', 'success'
                    backendVoteCategory.getVoteCategoryMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}

backendVoteCategory.prototype.clearMetaKeywordToVoteCategory = function (meta_keyword) {
    confirmMsg('Do you want to clear "' + meta_keyword + '" meta keyword from this vote category ?', function () {
            var href = this_backend_home_url + "/admin/votes/clear-vote-category-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("The meta keyword was successfully cleared!", 'success') // 'info', 'success'
                    backendVoteCategory.getVoteCategoryMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}

/// VOTE CATEGORIES META KEYWORD EDITOR BLOCK END

