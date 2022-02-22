var this_frontend_home_url
var this_backend_home_url
var this_csrf_token

function backendCalendar(page, paramsArray) {  // constructor of backend User's editor - set all params from server
    // alert( "paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_frontend_home_url = paramsArray.frontend_home_url;
    // alert( "this_frontend_home_url::"+this_frontend_home_url )
    this_csrf_token = paramsArray.csrf_token;

    // alert("/admin/calendar.js page::" + (page))
    if (page == "view") {
        this.showCalendarAddEvent()
        // this.getRelatedFileOnRegistrations(false)
    }
} // function backendCalendar(Params) {  constructor of backend User's editor - set all params from server


////////// CALENDAR BLOCK START ///////////
backendCalendar.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()

    if (page == "edit") {

    }

} // backendCalendar.prototype.onBackendPageInit= function(page) {


// backendCalendar.prototype.onSubmit = function (tab_name) {
//     var theForm = $("#form_settings_" + tab_name + "_edit");
//     // alert( "\"#tab_name_to_submit_\"+tab_name::"+("#tab_name_to_submit_"+tab_name) +"  tab_name::"+tab_name )
//     $("#tab_name_to_submit_" + tab_name).val(tab_name)
//     theForm.submit();
//     // alert( "theForm::"+var_dump(theForm) )
// }

backendCalendar.prototype.showCalendarAddEvent = function () {
    $("#div_show_calendar_add_event_modal").modal({
        "backdrop": "static",
        "keyboard": true,
        "show": true
    });
    // $('#span_vote_payments_report_details_content_title').html(downloaded_item_title)

} // backendCalendar.prototype.runReportPayments = function () {

backendCalendar.prototype.saveCalendarAddEvent = function (user_id) {
    var href = this_backend_home_url + "/admin/calendar_add_event";
    // alert( "this_csrf_token::"+var_dump(this_csrf_token) )

    var theForm = $("#form_save_calendar_add_event");
    // alert( "theForm::"+var_dump(theForm) )
    theForm.submit();


    return;
    $.ajax( {
        type: "POST",
        crossDomain: true,
        dataType: "jsonp",
        url: href,
        data: { "title": $('#new_event_title').val(),   "description": $('#new_event_description').val(),   "start_date": $('#new_event_start_date').val(),   "end_date": $('#new_event_end_date').val(),     "_token": this_csrf_token},
        success: function( response )
        {
            console.log("response::")
            console.log( response )
            // $("#service_id").val(response.service_id)
            popupAlert("New event was added successfully !", 'success')
        },
        error: function( error )
        {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendCalendar.prototype.saveCalendarAddEvent

//            $("#div_show_calendar_add_event_modal").modal('hide');

////////// CALENDAR BLOCK END ///////////