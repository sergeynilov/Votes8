// alert( "vote.js::"+var_dump(1) )
var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendVote(page, paramsArray) {  // constructor of backend Vote's editor - set all params from server
    // alert( "page::"+page+ "  paramsArray.id::"+paramsArray.id+"  backendVote paramsArray::"+var_dump(paramsArray) )
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
        this.getVoteRelatedTags(this_id)
        this.getVoteMetaKeywords(this_id)
    }
    if (page == "list") {
        this.VotesLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendVote(Params) {  constructor of backend Vote's editor - set all params from server


backendVote.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    // alert( "backendVote  onBackendPageInit this_id::"+this_id )
    backendInit()

    if (page == "edit") {
        $('#pills-tab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    }
} // backendVote.prototype.onBackendPageInit= function(page) {  


backendVote.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendVote.prototype.VotesLoad = function () {
    // alert("VotesLoad :" + (-10))

    Mustache.tags = ["<%", "%>"];
    var template = $('#vote_details_info_template').html();

    var ins = this
    oTable = $('#get-vote-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading votes..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-votes-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_status = $("#filter_status").val();
                d.filter_is_quiz = $("#filter_is_quiz").val();
                d.filter_is_homepage = $("#filter_is_homepage").val();
                d.filter_vote_category_id = $("#filter_vote_category_id").val();
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
            {data: 'vote_category_name', name: 'vote_category_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {
            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " votes")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }
        },

    }); // oTable = $('#get-vote-dt-listing-table').DataTable({

    var parent_form = this
    $('#get-vote-dt-listing-table tbody').on('click', 'td.details-control', function () {
        // alert("--get-vote-dt-listing-table::" + (-754))
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            parent_form.uploadVoteDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });


}

backendVote.prototype.uploadVoteDetailsInfo = function (vote_id) {
    var href = '/admin/get-vote-details-info/' + vote_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            $("#div_vote_details_info_" + vote_id).html(response.html);
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }

    });
} // function uploadVoteDetailsInfo(vote_id) {

backendVote.prototype.deleteVote = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" vote with all related data ?', function () {
            var href = this_backend_home_url + "/admin/votes/destroy";

            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Vote was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendVote.prototype.deleteVote = function ( id, name ) {

backendVote.prototype.statusOnChange = function () {
    var status = $("#status").val()
}

backendVote.prototype.onSubmit = function () {
    var theForm = $("#form_vote_edit");
    theForm.submit();
}


backendVote.prototype.deleteVoteItem = function (vote_item_id, vote_id, name) {
    confirmMsg('Do you want to delete "' + name + '" Vote item ?', function () {
            var href = this_backend_home_url + "/admin/vote-item/" + vote_item_id+"/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"vote_item_id": vote_item_id, "_token": this_csrf_token},
                success: function (response) {
                    document.location = '/admin/votes/' + vote_id + '/edit'
                    popupAlert("Vote item was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
}


backendVote.prototype.getVoteRelatedTags = function (vote_id) {
    if ( typeof vote_id == "undefined" ) return
    var href = '/admin/get-vote-related-tags/' + vote_id;
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

} // function uploadVoteDetailsInfo(vote_id) {

backendVote.prototype.attachTagToVote = function (tag_id, tag_name) {
    confirmMsg('Do you want to attach "' + tag_name + '" tag to this vote ?', function () {
            var href = this_backend_home_url + "/admin/votes/attach-related-tag/" + this_id + "/" + tag_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendVote.getVoteRelatedTags(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });
        }
    );
} // backendVote.prototype.attachTagToVote = function (tag_id, tag_name) {

backendVote.prototype.clearTagToVote = function (tag_id, tag_name) {
    confirmMsg('Do you want to clear "' + tag_name + '" tag from this vote ?', function () {
        var href = this_backend_home_url + "/admin/votes/clear-related-tag/" + this_id + "/" + tag_id;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    backendVote.getVoteRelatedTags(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
} // backendVote.prototype.clearTagToVote = function (tag_id, tag_name) {


////////////

backendVote.prototype.getVoteMetaKeywords = function (vote_id) {
    if ( typeof vote_id == "undefined" ) return
    var href = '/admin/get-vote-meta-keywords/' + vote_id;
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

} // function uploadVoteDetailsInfo(vote_id) {

backendVote.prototype.attachMetaKeywordToVote = function () {
    var meta_keyword= $("#new_meta_keyword").val()
    if ( jQuery.trim(meta_keyword) == '') {
        popupAlert("Fill new meta keyword !", 'danger') // 'info', 'success'
        $("#new_meta_keyword").focus()
        return;
    }
    confirmMsg('Do you want to add "' + meta_keyword + '" meta keyword to this vote ?', function () {
            var href = this_backend_home_url + "/admin/votes/attach-vote-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("New meta keyword was successfully added!", 'success') // 'info', 'success'
                    backendVote.getVoteMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}

backendVote.prototype.clearMetaKeywordToVote = function (meta_keyword) {
    confirmMsg('Do you want to clear "' + meta_keyword + '" meta keyword from this vote ?', function () {
        var href = this_backend_home_url + "/admin/votes/clear-vote-meta-keyword/" + this_id + "/" + meta_keyword;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("The meta keyword was successfully cleared!", 'success') // 'info', 'success'
                    backendVote.getVoteMetaKeywords(this_id)
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
}


backendVote.prototype.updateMetaDescriptionToVote = function () {
    var vote_meta_description= $("#vote_meta_description").val()
    confirmMsg('Do you want to update meta description of this vote ?', function () {
        if ( vote_meta_description == '' ) vote_meta_description= '-'
            var href = this_backend_home_url + "/admin/votes/update-vote-meta-description/" + this_id + "/" + vote_meta_description;
            $.ajax({
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    popupAlert("The meta description was successfully updated!", 'success') // 'info', 'success'
                    $("#vote_meta_description").focus()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );
} // backendVote.prototype.updateMetaDescriptionToVote = function () {
