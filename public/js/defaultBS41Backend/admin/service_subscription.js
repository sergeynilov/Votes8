// alert( "service_subscription.js::"+var_dump(-1) )

var this_backend_home_url
var this_id
var this_service_id
var this_csrf_token
var this_filter_type
var this_filter_value

function backendServiceSubscription(page, paramsArray) {  // constructor of backend ServiceSubscription's editor - set all params from server
// alert( "page::"+page+"  backendServiceSubscription paramsArray::"+var_dump(paramsArray) )
    console.log("paramsArray::")
    console.log( paramsArray )

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
        this_service_id = paramsArray.service_id;
        this.showPaypalPlanStatus();
        // if ( typeof this_id!= "undefined") {
        // }
    }
    if (page == "list") {
        this.ServiceSubscriptionLoad()
        $(".dataTables_filter").css("display","none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function(event){
            if(event.keyCode == 13){
                $('#btn_run_search').click();
            }
        });
    }
} // function backendServiceSubscription(Params) {  constructor of backend ServiceSubscription's editor - set all params from server


backendServiceSubscription.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    // alert( "backendServiceSubscription page::"+(page) )
    if (page == "edit") {

    }
} // backendServiceSubscription.prototype.onBackendPageInit= function(page) {


backendServiceSubscription.prototype.runSearch = function (oTable) {
    oTable.draw();
}


backendServiceSubscription.prototype.ServiceSubscriptionLoad = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#service_subscription_details_info_template').html();

    // alert( "ServiceSubscriptionLoad::"+var_dump(11) )
    var ins= this
    oTable = $('#get-service-subscriptions-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading service subscriptions..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-service-subscriptions-dt-listing',

            data: function (d) {
                d.filter_name = $("#filter_name").val();
                d.filter_price_period = $("#filter_price_period").val();
                d.filter_active = $("#filter_active").val();
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
            {data: 'name', name: 'name'},
            {data: 'active', name: 'active'},
            {data: 'price_period', name: 'price_period'},
            {data: 'price', name: 'price'},
            {data: 'service_id', name: 'service_id'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}
        ],

        "drawCallback": function (settings, b) {

            // alert( "settings.json::"+var_dump(settings.json) )
            $(".dataTables_info").html( settings.json.data.length + " of " + settings.json.recordsFiltered +" service subscription(s)")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }


        },

    }); // oTable = $('#get-service-subscriptions-dt-listing-table').DataTable({

    $('#get-service-subscriptions-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            uploadServiceSubscriptionDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

    function uploadServiceSubscriptionDetailsInfo(service_subscription_id) {
        var href= '/admin/get-service-subscription-details-info/' + service_subscription_id;
        $.ajax(
            {
                type: "GET",
                dataType: "json",
                url: url,
                success: function( response )
                {
                    $( "#div_service_subscription_details_info_" + service_subscription_id ).html( response.html );
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }

            } );

    } // function uploadServiceSubscriptionDetailsInfo(service_subscription_id) {

}


backendServiceSubscription.prototype.deleteServiceSubscription = function (id, name) {
    confirmMsg('Do you want to delete "' + name + '" service subscription with all related data ?', function () {
            var href = this_backend_home_url + "/admin/service-subscriptions/destroy";
            $.ajax( {
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Service Subscription was successfully deleted !", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.deleteServiceSubscription = function ( id, name ) {


backendServiceSubscription.prototype.onSubmit = function () {
    var theForm = $("#form_service_subscription_edit");
    theForm.submit();
}


backendServiceSubscription.prototype.acceptServiceSubscription = function (id, name) {
    confirmMsg('Do you want to accept service subscription of "' + name + '" ?', function () {
            var href = this_backend_home_url + "/admin/service-subscription-accept/"+id;
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

} // backendServiceSubscription.prototype.acceptServiceSubscription = function (id, name) {


backendServiceSubscription.prototype.showPaypalPlanStatus = function () {
    // alert( "showPaypalPlanStatus this_service_id::"+(this_service_id) )
    if ( this_service_id!= "" && this_service_id!= null) {
        $("#div_create_paypal_plan").css("display","none")
        $("#div_clear_paypal_plan").css("display","block")
    } else {
        $("#div_create_paypal_plan").css("display","block")
        $("#div_clear_paypal_plan").css("display","none")
    }

} // backendServiceSubscription.prototype.showPaypalPlanStatus = function ( id, name ) {

backendServiceSubscription.prototype.createPaypalPlan = function () {
    var name= $("#name").val()
    confirmMsg('Do you want to create new paypal plan based on this "' + name + '" service subscription and assign new paypal to this "' + name + '" service subscription?', function () {
        // Route::post('paypal_create_plan', 'PaymentController@paypal_create_plan')->name('paypal_create_plan');
        var id= $("#id").val()
        var price_period= $("#price_period").val()
        var price= $("#price").val()
        var description= $("#description").val()
            var href = this_backend_home_url + "/admin/paypal_create_plan";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"id": id, "name": name, "price_period": price_period, "price": price, "description": description, "_token": this_csrf_token},
                success: function( response )
                {
                    // $("#btn_run_search").click()
                    console.log("response::")
                    console.log( response )
                    console.log( response.service_id )
                    $("#service_id").val(response.service_id)
                    popupAlert("Plan creation at Paypal was successfull !", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.createPaypalPlan = function () {



backendServiceSubscription.prototype.loadPaypalPlan = function () {
            var href = this_backend_home_url + "/admin/load_paypal_plans";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"id": id, "name": name, "price_period": price_period, "price": price, "description": description, "_token": this_csrf_token},
                success: function( response )
                {
                    // $("#btn_run_search").click()
                    console.log("response::")
                    console.log( response )
                    console.log( response.$delete_plan_result )
                    $("#service_id").val(response.service_id)
                    popupAlert("Plan creation at Paypal was successfull !", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });


} // backendServiceSubscription.prototype.loadPaypalPlan = function (  ) {


backendServiceSubscription.prototype.clearPaypalPlan = function () {
    var name= $("#name").val()
    confirmMsg('Do you want to clear Paypal Plan from "' + name + '" service subscription ?', function () {
        var id= $("#id").val()
        var href = this_backend_home_url + "/admin/clear_paypal_plan";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function( response )
                {
                    // $("#btn_run_search").click()
                    console.log("response::")
                    console.log( response )
                    location.reload();
                    popupAlert("Paypal Plan was cleared successfully !", 'success')

                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.clearPaypalPlan = function ( id, name ) {


backendServiceSubscription.prototype.deletePaypalPlan = function (plan_id, plan_name) {
    confirmMsg('Do you want to delete "' + plan_name + '" paypal plan ?', function () {
            var href = this_backend_home_url + "/admin/paypal-plan/destroy";
            $.ajax( {
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"plan_id": plan_id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    location.reload();
                    popupAlert("Paypal plan was successfully deleted ! ", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.deletePaypalPlan = function ( plan_id, plan_name ) {


/* activatePaypalPlan( '{{$nextPaypalPlan->id}}','{{$nextPaypalPlan->name}}'); ">
                                        <i class="fa fa-toggle-on a_link"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="javascript:backendServiceSubscription.deactivatePaypalPlan( '{{$nextPaypalPlan->id}}','{{$nextPaypalPlan->name}}'); "> */
////////// ServiceSubscription Block End ///////////

backendServiceSubscription.prototype.activatePaypalPlan = function (plan_id, plan_name) {
    confirmMsg('Do you want to activate "' + plan_name + '" paypal plan ?', function () {
            var href = this_backend_home_url + "/admin/paypal-plan/activate";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"plan_id": plan_id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Paypal plan was successfully activated ! Refresh page to see changes.", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.activatePaypalPlan = function ( plan_id, plan_name ) {

backendServiceSubscription.prototype.deactivatePaypalPlan = function (plan_id, plan_name) {
    confirmMsg('Do you want to deactivate "' + plan_name + '" paypal plan ?', function () {
            var href = this_backend_home_url + "/admin/paypal-plan/deactivate";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"plan_id": plan_id, "_token": this_csrf_token},
                success: function( response )
                {
                    $("#btn_run_search").click()
                    popupAlert("Paypal plan was successfully deactivated ! Refresh page to see changes.", 'success')
                },
                error: function( error )
                {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendServiceSubscription.prototype.deactivatePaypalPlan = function ( plan_id, plan_name ) {
