var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendContactUs(page, paramsArray) {  // constructor of backend ContactUs's editor - set all params from server
// alert( "page::"+page+"  backendContactUs paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    this_filter_type = paramsArray.filter_type;
    this_filter_value = paramsArray.filter_value;

    // alert( "this_filter_type::"+(this_filter_type) + "  this_filter_value::"+this_filter_value )
    if ( this_filter_type == 'filter_accepted' ) {
        $("#filter_accepted").val(this_filter_value);
        // $('.chosen_filter_accepted').trigger("chosen:updated");
        $('#filter_accepted').trigger("chosen:updated");
    }

    // alert( "$(\"#filter_accepted\").val::"+(  $("#filter_accepted").val() ) )
    if (page == "edit") {
        this_id = paramsArray.id;
    }
    if (page == "list") {
        this.ContactUsLoad()
        $(".dataTables_filter").css("display","none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendContactUs(Params) {  constructor of backend ContactUs's editor - set all params from server


backendContactUs.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    if (page == "edit") {

    }
} // backendContactUs.prototype.onBackendPageInit= function(page) { 


backendContactUs.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendContactUs.prototype.ContactUsLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#contact_us_details_info_template').html();

    var ins= this
    oTable = $('#get-contact-us-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading contacts..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-contact-us-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_accepted = $("#filter_accepted").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'author_name', name: 'author_name'},
            {data: 'author_email', name: 'author_name'},
            {data: 'message', name: 'message'},
            {data: 'accepted', name: 'accepted'},
            {data: 'accepted_at', name: 'accepted_at'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}
        ],

        "drawCallback": function (settings, b) {

            $(".dataTables_info").html( settings.json.data.length + " of " + settings.json.recordsFiltered +" contact us")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-contact-us-dt-listing-table').DataTable({

    $('#get-contact-us-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            uploadContactUsDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadContactUsDetailsInfo(contact_us_id) {
        var href= '/admin/get-contact-us-details-info/' + contact_us_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: url,
                success: function( response )
                {
                    $( "#div_contact_us_details_info_" + contact_us_id ).html( response.html );
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }

            } );

    } // function uploadContactUsDetailsInfo(contact_us_id) {

}


backendContactUs.prototype.deleteContactUs = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" contact us with all related data ?', function () {
            var href = this_backend_home_url + "/admin/contact-us/destroy";
            $.ajax( {
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Contact us was successfully deleted !", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        
        }
    );

} // backendContactUs.prototype.deleteContactUs = function ( id, name ) {


backendContactUs.prototype.acceptContactUs = function (id, author_name) {
    confirmMsg('Do you want to accept contact us of "' + author_name + '" ?', function () {
            var href = this_backend_home_url + "/admin/contact-us-accept/"+id;
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

} // backendContactUs.prototype.deleteVoteCategory = function ( id, name ) {

////////// ContactUs Block End ///////////
