var this_backend_home_url
var this_id
var this_csrf_token
var this_filter_type
var this_filter_value


function backendSiteSubscription(page, paramsArray) {  // constructor of backend SiteSubscription's editor - set all params from server
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
    }
    if (page == "list") {
        this.SiteSubscriptionsLoad()
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendSiteSubscription(Params) {  constructor of backend SiteSubscription's editor - set all params from server


backendSiteSubscription.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    // alert( "backendSiteSubscription  onBackendPageInit this_id::"+this_id )
    backendInit()

    if (page == "edit") {

    }
} // backendSiteSubscription.prototype.onBackendPageInit= function(page) {


backendSiteSubscription.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendSiteSubscription.prototype.SiteSubscriptionsLoad = function () {

    Mustache.tags = ["<%", "%>"];
    var template = $('#site_subscription_details_info_template').html();

    var ins = this
    oTable = $('#get-site-subscription-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading site subscriptions..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-site-subscriptions-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_active = $("#filter_active").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'active', name: 'active'},
            {data: 'vote_category_id', name: 'vote_category_id'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}

        ],

        "drawCallback": function (settings, b) {

            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " site subscriptions")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }

        },

    }); // oTable = $('#get-site-subscription-dt-listing-table').DataTable({

    $('#get-site-subscription-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            uploadSiteSubscriptionDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadSiteSubscriptionDetailsInfo(site_subscription_id) {
        var href = '/admin/get-site-subscription-details-info/' + site_subscription_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: href,
                success: function (response) {
                    $("#div_site_subscription_details_info_" + site_subscription_id).html(response.html);
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }

            });

    } // function uploadSiteSubscriptionDetailsInfo(site_subscription_id) {

}


backendSiteSubscription.prototype.deleteSiteSubscription = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" site subscription with all related data ?', function () {
            var href = this_backend_home_url + "/admin/site-subscriptions/destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Site subscription was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });
        }
    );

} // backendSiteSubscription.prototype.deleteSiteSubscription = function ( id, name ) {


backendSiteSubscription.prototype.onSubmit = function () {
    var theForm = $("#form_site_subscription_edit");
    theForm.submit();
}