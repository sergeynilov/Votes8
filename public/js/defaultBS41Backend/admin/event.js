main.jsvar this_backend_home_url
var this_backend_per_page
var this_eventsList
var this_id
var this_eventTypeValueArray
var this_eventTypeColorValueArray
var this_eventTypeGoogleCalendarColorValueArray
var this_event
var this_csrf_token
var this_event_name
var this_start_date
var this_end_date
var this_moment_time_label_format
var this_start_date_formatted

function backendEvent(page, paramsArray) {  // constructor of backend Event's editor - set all params from server
// alert( "page::"+page+"  backendEvent paramsArray::"+var_dump(paramsArray) )
//     console.log("page::" + page + "  backendEvent paramsArray::")
//     console.log(paramsArray)

    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_moment_time_label_format = paramsArray.moment_time_label_format;
    this_start_date_formatted = paramsArray.start_date_formatted;
    this_eventsList = [];
    this_csrf_token = paramsArray.csrf_token;

    if (page == "list") {
        this_event_name = paramsArray.event_name;
        this_start_date = paramsArray.start_date;
        this_end_date = paramsArray.end_date;

        $('input[name="filter_start_date_end_date_picker"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: false,
            timePickerIncrement: 10,
            startDate: dbDateTimeToDateTimePicker(this_start_date_formatted),
            // endDate: end_date_formatted,
            locale: {
                format: 'DD MMMM, YYYY hh:mm a',
                cancelLabel: 'Clear'
            }
        });

        $('#filter_start_date_end_date_picker').on('apply.daterangepicker', function (ev, picker) {
            $("#filter_start_date").val(picker.startDate.format('YYYY-MM-DD hh:mm:ss'));
            $("#filter_end_date").val(picker.endDate.format('YYYY-MM-DD hh:mm:ss'));
        });

        $('#filter_start_date_end_date_picker').on('cancel.daterangepicker', function (ev, picker) {
            //do something, like clearing an input
            $('#filter_start_date_end_date_picker').val('');
        });

        // let filter_report_type = $("#filter_report_type").val()
        // if ( filter_report_type == 'L' ) {
        //     debugger
        //     $("#div_events_calendar_wrapper").css("display","none")
        //     $("#table_events_listing").css("display","block")
        //
        //     this.evenstLoadWithDataTables()
        //
        // } else {
        $("#div_events_calendar_wrapper").css("display", "block")
        $("#table_events_listing").css("display", "none")
        // }
        $(".dataTables_filter").css("display", "none")
        $(".dataTables_info").css("display", "none")
        $(document).keypress(function (event) {
            if (event.keyCode == 13) {
                $('#btn_run_search').click();
            }
        });
        this.runSearch()

    }   // if (page == "list") {


    if (page == "edit") {

        this_id = paramsArray.id;
        this_eventTypeValueArray = paramsArray.eventTypeValueArray;
        this_eventTypeColorValueArray = paramsArray.eventTypeColorValueArray;
        this_eventTypeGoogleCalendarColorValueArray = paramsArray.eventTypeGoogleCalendarColorValueArray;
        this_event = paramsArray.event;
        // console.log("EDIT  this_event::")
        // console.log(this_event)
        //
        // console.log("typeof this_event::")
        // console.log( typeof this_event )

        if ( typeof this_event != "undefined" ) {
            var start_date_formatted = dbDateTimeToDateTimePicker(this_event.start_date);
        }
        if ( typeof this_event != "undefined" ) {
            var end_date_formatted = dbDateTimeToDateTimePicker(this_event.end_date);
        }

        $('input[name="start_date_end_date_picker"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: false,
            timePickerIncrement: 10,
            startDate: start_date_formatted,
            endDate: end_date_formatted,
            locale: {
                format: 'DD MMMM, YYYY hh:mm a'
                //format: 'DD MMMM, YYYY H:mm' // valid 24 hours format
            }
        });

        $('#start_date_end_date_picker').on('apply.daterangepicker', function (ev, picker) {
            $("#start_date").val(picker.startDate.format('YYYY-MM-DD hh:mm:ss'));
            $("#end_date").val(picker.endDate.format('YYYY-MM-DD hh:mm:ss'));
        });

        // this.loadEventAttendees()
        this.onTypeChange()

    } // if (page == "edit") {
} // function backendEvent(Params) {  constructor of backend Event's editor - set all params from server


backendEvent.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    if (page == "edit") {
        // $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    }
    // alert( "END onBackendPageInit::"+var_dump(-77) )
} // backendEvent.prototype.onBackendPageInit= function(page) {


backendEvent.prototype.runSearch = function (oTable) {
    // let filter_report_type = $("#filter_report_type").val()
    // alert( "filter_report_type::"+(filter_report_type) )
    // if (filter_report_type == 'L') {
    //     $("#div_events_calendar_wrapper").css("display","none")
    //     $("#table_events_listing").css("display","block")
    //     console.log("typeof oTable::")
    //     console.log( typeof oTable )
    //
    //     if ( typeof oTable != "undefined" ) {
    //         // alert( "::"+(-99) )
    //         oTable.draw();
    //     }
    // } else {
    $("#div_events_calendar_wrapper").css("display", "block")
    $("#table_events_listing").css("display", "none")
    this.evenstLoadWithFullCalendar()
    // }


}


backendEvent.prototype.evenstLoadWithFullCalendar = function () {
    // alert("evenstLoadWithFullCalendar::" + (-65))
    var dataArray = {
        "_token": this_csrf_token,
        "filter_event_name": $("#filter_event_name").val(),
        "filter_start_date": $("#filter_start_date").val(),
        "filter_end_date": $("#filter_end_date").val(),
        "filter_type": $("#filter_type").val(),
        "filter_status": $("#filter_status").val()
    }

    var href = this_backend_home_url + "/admin/get_events_fc_listing";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: dataArray,
        success: function (response) {
            if (response.error_code == 0) {
                initFullCalendar(response.events, response.calendar_events_default_date);
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}


/// EVENT ATTENDEES BLOCK START
backendEvent.prototype.loadEventAttendees = function () {
    // alert("loadEventAttendees::" + (-65))

    var href = this_backend_home_url + "/admin/load_event_attendees/" + this_id;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: href,
        success: function (response) {
            if (response.error_code == 0) {
                $("#div_event_attendees_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });
                $('#div_event_attendees_content').html(response.html)
            }
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendEvent.prototype.loadEventAttendees = function () {


backendEvent.prototype.clearEventAttendees = function (event_attendee_id, user_email) {
    confirmMsg('Do you want to exclude this user from event ?', function () {
            var href = this_backend_home_url + "/admin/clear_event_attendee";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"event_id": this_id, "event_attendee_id": event_attendee_id, "_token": this_csrf_token},
                success: function (response) {
                    // $("#btn_run_search").click()
                    popupAlert("Event attendee was successfully cleared !", 'success')
                    backendEvent.loadEventAttendees()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendEvent.prototype.clearEventAttendees = function ( id, name ) {

backendEvent.prototype.addSelectedUsers = function () {
    var activeUsersList = $("#add_active_users").val()

    confirmMsg('Do you want to add ' + activeUsersList.length + ' selected users to this event ?', function () {
            var href = this_backend_home_url + "/admin/add_active_users_to_event";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"event_id": this_id, "activeUsersList": activeUsersList, "_token": this_csrf_token},
                success: function (response) {
                    popupAlert("Selected users were successfully added !", 'success')
                    backendEvent.loadEventAttendees()
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendEvent.prototype.addSelectedUsers = function () {

backendEvent.prototype.addExternalUser = function () {
    var new_attendee_user_email = $("#new_attendee_user_email").val()
    if (jQuery.trim(new_attendee_user_email) == '') {
        popupAlert("Fill new attendee user's email !", 'danger') // 'info', 'success'
        $("#new_attendee_user_email").focus()
        return;
    }

    if (!checkEmail(new_attendee_user_email)) {
        popupAlert("New attendee user's email has invalid format!", 'danger') // 'info', 'success'
        $("#new_attendee_user_email").focus()
        return;
    }

    var new_attendee_user_display_name = $("#new_attendee_user_display_name").val()
    if (jQuery.trim(new_attendee_user_display_name) == '') {
        popupAlert("Fill new attendee user's display name !", 'danger') // 'info', 'success'
        $("#new_attendee_user_display_name").focus()
        return;
    }

    var href = this_backend_home_url + "/admin/add_external_user_event";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {
            "event_id": this_id,
            "new_attendee_user_email": new_attendee_user_email,
            "new_attendee_user_display_name": new_attendee_user_display_name,
            "_token": this_csrf_token
        },
        success: function (response) {
            popupAlert("External user was successfully added !", 'success')
            backendEvent.loadEventAttendees()
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // backendEvent.prototype.addExternalUser = function () {

/// EVENT ATTENDEES BLOCK END


backendEvent.prototype.evenstLoadWithDataTables = function () {
    Mustache.tags = ["<%", "%>"];
    var template = $('#event_details_info_template').html();

    alert("template::" + (template))
    var ins = this
    oTable = $('#get-event-dt-listing-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "processing": "Loading events..."
        },
        "lengthChange": false,
        "pageLength": this_backend_per_page,
        ajax: {
            url: this_backend_home_url + '/admin/get-event-dt-listing',
            data: function (d) {
                d.filter_event_name = $("#filter_event_name").val();
                d.filter_start_date = $("#filter_start_date").val();
                d.filter_end_date = $("#filter_end_date").val();
                d.filter_type = $("#filter_type").val();
                d.filter_is_public = $("#filter_is_public").val();
                d.filter_status = $("#filter_status").val();
            },
        }, // ajax: {

        columns: [
            {data: 'id', name: 'id'},
            {data: 'event_name', name: 'event_name'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'assigned_to_google_calendar', name: 'assigned_to_google_calendar', orderable: false, searchable: false},
            {data: 'type', name: 'type'},
            {data: 'is_public', name: 'is_public'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action_delete', name: 'action_delete', orderable: false, searchable: false}
        ],

        "drawCallback": function (settings, b) {

            $(".dataTables_info").html(settings.json.data.length + " of " + settings.json.recordsFiltered + " events")
            $(".dataTables_info").css("display", "inline")
            if (settings.json.recordsTotal <= this_backend_per_page) { // we need to hide pagination block
                $(".dataTables_paginate").css("display", "none")
            } else {  // we need to show pagination block
                $(".dataTables_paginate").css("display", "block")
            }

        },

    }); // oTable = $('#get-event-dt-listing-table').DataTable({

    $('#get-event-dt-listing-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            uploadEventDetailsInfo(row.data().id);
            row.child(Mustache.render(template, row.data())).show();
            tr.addClass('shown');
        }
    });

}

backendEvent.prototype.deleteEvent = function (id, event_name) {
    confirmMsg('Do you want to delete "' + event_name + '" event ?', function () {
            var href = this_backend_home_url + "/admin/events-destroy";
            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": id, "_token": this_csrf_token},
                success: function (response) {
                    $("#btn_run_search").click()
                    popupAlert("Event was successfully deleted !", 'success')
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendEvent.prototype.deleteEvent = function ( id, name ) {


backendEvent.prototype.onSubmit = function () {
    var theForm = $("#form_event_edit");
    theForm.submit();
}

backendEvent.prototype.onEventDelete = function () {
    let event_name = $("#event_name").val()
    confirmMsg('Do you want to delete "' + event_name + '" event ?', function () {

            var href = this_backend_home_url + "/admin/events";

            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: href,
                data: {"id": this_id, "_token": this_csrf_token},
                success: function (response) {
                    popupAlert("Event was successfully deleted !", 'success')
                    document.location = '/admin/events'
                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // backendEvent.prototype.onEventDelete = function () {


////////// Event Block End ///////////

////////// calendarAction  Block START ///////////

backendEvent.prototype.calendarActionUpdate = function () {
    let event_name = $("#event_name").val()
    confirmMsg('Do you want to update "' + event_name + '" event in Google calendar ?', function () {
            let new_event_description = $('#description_container').val()

            let dataArray = {
                "new_event_title": event_name,
                "new_event_description": new_event_description,
                "new_event_start_date": $("#start_date").val(),
                "new_event_end_date": $("#start_date").val(),
                "_token": this_csrf_token
            }
            $("#form_action").val("calendarActionUpdate");
            var theForm = $("#form_event_edit");
            theForm.submit();
        }
    );

} // backendEvent.prototype.calendarActionUpdate = function () {


backendEvent.prototype.calendarActionInsert = function () {
    let event_name = $("#event_name").val()
    confirmMsg('Do you want to insert "' + event_name + '" event into Google calendar ?', function () {
            let new_event_description = $('#description_container').val()

            let dataArray = {
                "new_event_title": event_name,
                "new_event_description": new_event_description,
                "new_event_start_date": $("#start_date").val(),
                "new_event_end_date": $("#start_date").val(),
                "_token": this_csrf_token
            }
            $("#form_action").val("CalendarActionInsert");
            var theForm = $("#form_event_edit");
            theForm.submit();

        }
    );

} // backendEvent.prototype.calendarActionInsert = function () {


backendEvent.prototype.calendarActionDelete = function () {
    let event_name = $("#event_name").val()
    confirmMsg('Do you want to delete "' + event_name + '" event from Google calendar ?', function () {
            let new_event_description = $('#description_container').val()

            let dataArray = {
                "new_event_title": event_name,
                "new_event_description": new_event_description,
                "new_event_start_date": $("#start_date").val(),
                "new_event_end_date": $("#start_date").val(),
                "_token": this_csrf_token
            }
            $("#form_action").val("calendarActionDelete");
            var theForm = $("#form_event_edit");
            theForm.submit();

        }
    );

} // backendEvent.prototype.calendarActionDelete = function () {

// '{{ $nexEventItem['start_date'] }}', '{{ $nexEventItem['description'] }}', '{{ $nexEventItem['location'] }}'
backendEvent.prototype.importSelectedCalendarEventsIntoDb = function () {
    // let event_name = $("#event_name").val()
    var checked_calendar_events_selection = ''
    var checked_calendar_events_selection_count = 0
    $('input.cbx_calendar_event_selection:checked').each(function () {
        checked_calendar_events_selection = checked_calendar_events_selection + $(this).val() + ','
        checked_calendar_events_selection_count++
    });

    // alert( "checked_calendar_events_selection::"+(checked_calendar_events_selection) + "  checked_calendar_events_selection_count::"+(checked_calendar_events_selection_count) )

    if (checked_calendar_events_selection_count == 0) {
        popupAlert("Select calendar events !", 'danger')
        return;
    }

    confirmMsg('Do you want to import "' + checked_calendar_events_selection_count + '" event(s) from Google Calendar into the system ?', function () {


            $("#form_action_items").val(checked_calendar_events_selection);
            $("#form_action").val("import_calendar_event_into_db");
            var theForm = $("#form_synchronize_google_events_edit");
            // alert( "theForm::"+var_dump(theForm) )
            theForm.submit();

        }
    );

} // backendEvent.prototype.importSelectedCalendarEventsIntoDb = function () {

backendEvent.prototype.onTypeChange = function () {
    let type = $("#type").val();

    if (type != "" /* && typeof this_eventTypeColorValueArray[type] != "undefined" */) {
        $("#btn_type_color").css("display", "none");
        $("#i_type_color").css("display", "none");
        $("#btn_type_color").css("color", this_eventTypeColorValueArray[type]);
        $("#btn_type_color").css("background-color", this_eventTypeColorValueArray[type]);

        $("#i_type_color").css("color", this_eventTypeColorValueArray[type]);
        $("#i_type_color").css("background-color", this_eventTypeColorValueArray[type]);
        $("#i_type_color").css("display", "inline");
        $("#btn_type_color").css("display", "inline");
    } else {
        $("#btn_type_color").css("display", "none");
        $("#i_type_color").css("display", "none");
        $("#btn_type_color").css("color", "#A9A9A9");
        $("#btn_type_color").css("background-color", "#A9A9A9");

        $("#i_type_color").css("color", "#A9A9A9");
        $("#i_type_color").css("background-color", "#A9A9A9");
        $("#i_type_color").css("display", "inline");
        $("#btn_type_color").css("display", "inline");
    }

} // backendEvent.prototype.onTypeChange = function () {


function initFullCalendar(eventsList, calendar_events_default_date) {
    if (typeof window.calendarEventsObject != "undefined") { // clear existing instance
        window.calendarEventsObject.destroy();
    }

    var calendarEl = document.getElementById('events_calendar');

    var effective_device_width = effectiveDeviceWidth('width') //TODO
    // alert( "initFullCalendar effective_device_width::"+(effective_device_width) +"  calendar_events_default_date::"+calendar_events_default_date )

    var current_date = moment(calendar_events_default_date).format('YYYY-MM-DD')
    var today = moment();

    window.calendarEventsObject = new FullCalendar.Calendar(calendarEl, { // FullCalendar Init
        plugins: ['dayGrid', 'timeGrid'],
        defaultView: 'dayGridMonth',

        views: {
            dayGridMonth: {
                buttonText: 'Month'
            },
            timeGridWeek: {
                buttonText: 'Week'
            }
        },


        axisFormat: "H:mm A",
        timeFormat: "H:mm A",
        // firstDay: today,

        header: {
            left: 'dayGridMonth,timeGridWeek',
            center: 'title',
            right: 'today prev,next '
        },

        eventRender: function (eventInfo) {


            let different_day_label = ''
            if (!eventInfo.event.extendedProps.is_same_day) {
                different_day_label = moment(eventInfo.event.end).format('Do MMMM YYYY') + ' '
                // console.log("different_day_label::")
                // console.log( different_day_label )
            }

            // console.log("eventInfo.event.url::")
            // console.log( typeof eventInfo.event.url )
            // console.log( eventInfo.event.url )

            var external_google_calendar_button = ""
            if (eventInfo.event.url != "" && typeof eventInfo.event.url != "undefined") {
                // alert( eventInfo.event.url + "  eventInfo.event..url ::"+( typeof eventInfo.event.url )  )
                external_google_calendar_button = '<i class=\'fa fa-external-link\' style=\'color:#eaeaea;\' title=\'Has event at Google Calendar\'></i>&nbsp;';
                // 'external-link'=> 'fa fa-external-link',

                // different_day_label= moment(eventInfo.event.end).format('Do MMMM YYYY')+' '
                // console.log("different_day_label::")
                // console.log( different_day_label )
            }

            $(eventInfo.el).tooltip({
                title: eventInfo.event.extendedProps.event_id + ':<b>' + eventInfo.event.title + "</b> at " +
                    moment(eventInfo.event.start).format(this_moment_time_label_format) + ' - ' + different_day_label +
                    moment(eventInfo.event.end).format(this_moment_time_label_format) +
                    "<br><i class=\"fa fa-users\"></i>&nbsp;<b>" + eventInfo.event.extendedProps.attendees_count + "&nbsp;attendee(s)</b>" +
                    " <br><small>" + eventInfo.event.extendedProps.description + "</small>",
                html: true,
            });

            eventInfo.el.querySelector('.fc-content').innerHTML = "<i class='fa fa-edit'" +
                " onclick=\"javascript:backendEvent.editCalendarEvent(event," + eventInfo.event.extendedProps.event_id + ");\" style='color:#eaeaea;'></i>&nbsp;" +
                external_google_calendar_button +
                eventInfo.el.querySelector('.fc-content').innerHTML;
        }, // eventRender: function (eventInfo) {


        dayRender: function (date) {
            // console.log("dayRender date::")
            // console.log( date )
            // console.log("dayRender date.view::")
            // console.log( date.view )
            //
            // console.log("dayRender date.view.activeStart::")
            // console.log( date.view.activeStart )
            //

            var ntoday = new Date().getTime();
            var eventEnd = moment(date.date).valueOf();
            var eventStart = moment(date.date).valueOf();
            // var eventEnd = moment( date.view.activeEnd ).valueOf();
            // var eventStart = moment( date.view.activeStart ).valueOf();
            // console.log("-ntoday::")
            // console.log( ntoday )
            // console.log("-0 eventStart::")
            // console.log( eventStart )
            //
            // console.log("-1 eventEnd::")
            // console.log( eventEnd )

            if (!eventEnd) {
                if (eventStart < ntoday) {

                    // console.log("-1 eventStart::")
                    // console.log( eventStart )
                    //
                    date.el.classList.add('past-event');

                }
            } else {
                if (eventEnd < ntoday) {

                    // console.log("-2 eventStart::")
                    // console.log( eventStart )
                    date.el.classList.add('past-event');

                }
            }

            // date.css("background-color", "red");
        },

        select: function (start, end, allDay) {
            var check = $.fullCalendar.formatDate(start, 'yyyy-MM-dd');
            var today = $.fullCalendar.formatDate(new Date(), 'yyyy-MM-dd');
            if (check < today) {
                // console.log("-1 select check::")
                // console.log(check)

                // Previous Day. show message if you want otherwise do nothing.
                // So it will be unselectable
            } else {
                // console.log("-2 select check::")
                // console.log(check)
                // Its a right date
                // Do something
            }
        },

        events: eventsList,
        defaultDate: current_date,
        // axisFormat: 'HH:mm',
        // timeFormat: 'H:mm',
        // slotLabelFormat:"HH:mm",


        showNonCurrentDates: false,
        displayEventTime: true,
        eventLimit: true, // allow "more" link when too many events

        editable: true,
        allDaySlot: true,
        selectable: true,
        selectHelper: true,
        selectOverlap: false,
        fixedWeekCount: false,
        disableDragging: true,

        aspectRatio: 0.4,
        // width: 1200,
        height: 900,
        // width: effective_device_width,

        eventClick: function (clickObj) {
            // console.log("eventClick  clickObj.el::")
            // console.log( clickObj.el )

            // alert( "eventClick clickObj.el.href::"+var_dump(clickObj.el.href) )
            if (clickObj.el.href != "") {
                // alert( "::"+var_dump(-4) )
                let el_href = clickObj.el.href

                // console.log("eventClick  clickObj.el.href::")
                // console.log(clickObj.el.href)


                clickObj.el.href = ""
                window.open(el_href, "_blank");
                // clickObj.event.preventDefault();
                // alert( "::"+var_dump(-41) )
                // e.preventDefault();
                return false;
            }
            return false;
        },
    });  // window.calendarEventsObject = new FullCalendar.Calendar(calendarEl, { // FullCalendar Init
    //    'calendar_events_default_date' => '2019-08-22',


    // $('#events_calendar').FullCalendar('gotoDate', current_date);

    window.calendarEventsObject.render(
        {
            backgroundColor: 'green',
            textColor: 'yellow',
        }
    );

    jQuery('.eo-fullcalendar').on('click', '.fc-event', function (e) {
        e.preventDefault();
        window.open(jQuery(this).attr('href'), '_blank');
    });

    /*
        $('.fc-prev-button').click(function(){
            alert('prev is clicked, do something');
        });

        $('.fc-next-button').click(function(){
            alert('nextis clicked, do something');
        });
    */


    // alert("AFTER calendar.render()::" + var_dump(-88))
}   // function initFullCalendar() {

backendEvent.prototype.editCalendarEvent = function (e, event_id) {
    // e.bubbles= false;
    e.cancelBubble = true;
    // console.log("editCalendarEvent e::")
    // console.log( e )
    // console.log("editCalendarEvent event_id::")
    // console.log( event_id )

    // alert( "editCalendarEvent  event_id::"+var_dump(event_id) )

    //            ->editColumn('action', '<a href="/admin/events/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
    document.location = '/admin/events/' + event_id + '/edit'
    e.preventDefault();
    return false;
}


// onclick="javascript:backendEvent.ImportIntoDb({!! $nexEventItem['calendar_event_id'] !!}); return false; ">


////////// calendarAction  Block END ///////////
