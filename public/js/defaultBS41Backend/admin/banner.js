var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendBanner(page, paramsArray) {  // constructor of backend Banner's editor - set all params from server
// alert( "page::"+page+"  backendBanner paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    if (page == "edit") {
        this_id = paramsArray.id;
    }
    if (page == "list") {
        this.BannerLoad()
        $(".dataTables_filter").css("display","none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendBanner(Params) {  constructor of backend Banner's editor - set all params from server


backendBanner.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    if (page == "edit") {

    }
} // backendBanner.prototype.onBackendPageInit= function(page) {


backendBanner.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendBanner.prototype.BannerLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#banner_details_info_template').html();

    var ins= this
    oTable = $('#get-banner-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading banners..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-banner-dt-listing',

            data: function (d) {
                d.filter_text = $("#filter_text").val();
                d.filter_view_type = $("#filter_view_type").val();
                d.filter_active = $("#filter_active").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'text', name: 'text'},
            {data: 'active', name: 'active'},
            {data: 'view_type', name: 'view_type'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}
        ],

    "drawCallback": function (settings, b) {

            $(".dataTables_info").html( settings.json.data.length + " of " + settings.json.recordsFiltered +" banners")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }

        },

    }); // oTable = $('#get-banner-dt-listing-table').DataTable({

    $('#get-banner-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            uploadBannerDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

}

backendBanner.prototype.deleteBanner = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" banner ?', function () {
            var href = this_backend_home_url + "/admin/banners-destroy";
            $.ajax( {
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Banner was successfully deleted !", 'success')
                },
                error: function( error )
                {
                    console.log("error::")
                    console.log( error )

                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendBanner.prototype.deleteBanner = function ( id, name ) {


backendBanner.prototype.onSubmit = function () {
    var theForm = $("#form_banner_edit");
    theForm.submit();
}

////////// Banner Block End ///////////