// alert( "external_news_importing::"+var_dump(-3) )
var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendExternalNewsImporting(page, paramsArray) {  // constructor of backend ExternalNewsImporting's editor - set all params from server
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
    }
    if (page == "list") {
        this.ExternalNewsImportingsLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendExternalNewsImporting(Params) {  constructor of backend ExternalNewsImporting's editor - set all params from server


backendExternalNewsImporting.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }
} // backendExternalNewsImporting.prototype.onBackendPageInit= function(page) {


backendExternalNewsImporting.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendExternalNewsImporting.prototype.ExternalNewsImportingsLoad = function () {

    Mustache.tags = ["<%", "%>"];
    var template = $('#external_news_importing_details_info_template').html();

    var ins = this
    oTable = $('#get-external-news-importing-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading news-importing..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-external-news-importing-dt-listing',

            data: function (d) {
                d.filter_title = $("#filter_title").val();
                d.filter_status = $("#filter_status").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'url', name: 'url'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {

            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " external news importings")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }

        },

    }); // oTable = $('#get-external-news-importing-dt-listing-table').DataTable({

}


backendExternalNewsImporting.prototype.deleteExternalNewsImporting = function (id, title) {
    confirmMsg('Do you want to delete "' + title + '" external news importing ?', function () {
            var href = this_backend_home_url + "/admin/external-news-importing/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("External news importing was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );

} // backendExternalNewsImporting.prototype.deleteExternalNewsImporting = function ( id, title ) {


backendExternalNewsImporting.prototype.onSubmit = function () {
    var theForm = $("#form_external_news_importing_edit");
    theForm.submit();
}