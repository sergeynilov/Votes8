@include($current_admin_template.'.layouts.page_header')

@if(!empty($current_google_calendar))
    <h4 class="text-center p-3">All events can be synchronized to "{{ $current_google_calendar }}" Google Calendar.</h4>
@endif

<input type="hidden" id="form_action" name="form_action" value="">
<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($event->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($event->id) ? $event->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif



<div class="form-row mb-3 {{ in_array('event_name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="event_name">Event name<span class="required"> * </span></label>

    <div class="col-12 col-sm-8" style="display: flex; flex-direction: row">
        <div class="" style="flex:1 0 ">
            {!! $viewFuncs->text('event_name', isset($event->event_name) ? $event->event_name : '',  "form-control
            editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
        </div>

        @if(isset($event->id))
        <div class="m-1 p-0 pl-2" style="flex:0 0 30px; ">
            <button type="button" class="btn btn-primary btn-default btn-sm"
                    onclick="javascript:backendEvent.loadEventAttendees();  return false; ">
                {!! $viewFuncs->showAppIcon('event','selected_dashboard', "Event Attendees") !!}
            </button>
        </div>
        @endif

    </div>
</div>

@if(isset($event->id))
    <div class="form-row mb-3 {{ in_array('slug', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="slug">Slug</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('slug', isset($event->slug) ? $event->slug : '', 'form-control', [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif



<div class="form-row mb-3 {{ in_array('end_date', $errorFieldsArray) ? 'validation_error' : '' }}">
    <input type="hidden" id="start_date" name="start_date" value="{{ isset($event->start_date) ? $event->start_date : '' }}">
    <input type="hidden" id="end_date" name="end_date" value="{{ isset($event->end_date) ? $event->end_date : '' }}">



    <label class="col-12 col-sm-4 col-form-label" for="start_date_end_date_picker">
        Start date / End date
        <span class="required"> * </span>
    </label>
    <div class="col-12 col-sm-8" style="padding-bottom: 30px;">
        {!! $viewFuncs->text('start_date_end_date_picker', '', "form-control", [ "readonly"=>"readonly" ] ) !!}
        <p class="m-2">
            <small>
                You can pick one date and then the second date(it must be bigger the first date). After that select time. The range will be selected by clicking on
                “Apply” button.
            </small>
        </p>
    </div>

</div>



<div class="form-row mb-3 {{ in_array('type', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_type">Type<span class="required"> * </span></label>

    <div class="col-12 col-sm-8" style="display: flex; flex-direction: row">

        <div class="" style="flex:1 0 ">
            {!! $viewFuncs->select('chosen_type', $eventTypeValueArray, isset($event->type) ? $event->type : '', "form-control editable_field chosen_select_box",
            ['onchange'=>"javascript:chosenSelectionOnChange('type'); backendEvent.onTypeChange();",'data-placeholder'=>" -Select Type- "] ) !!}
            <input type="text" id="type" name="type" value="{{ isset($event->type) ? $event->type : ''}}" style="visibility: hidden; width: 1px; height: 1px">
        </div>
         <div class="m-1 p-0 pl-2" style="flex:0 0 30px; ">
             <button type="button" class="btn btn-sm" id="btn_type_color" >
                 {!! $viewFuncs->showAppIcon('color','selected_dashboard', "Event type color", "i_type_color") !!}
             </button>
         </div>
    </div>

</div>




<div class="form-row mb-3 {{ in_array('is_public', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_public">Is Public<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_public', $eventIsPublicValueArray, isset($event->is_public) ? $event->is_public : '', "form-control editable_field
        chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('is_public'); ",'data-placeholder'=>" -Select Is Public- "] ) !!}
        <input type="text" id="is_public" name="is_public" value="{{ isset($event->is_public) ? $event->is_public : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('published', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_published">Published<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_published', $eventPublishedValueArray, isset($event->published) ? $event->published : '', "form-control editable_field
        chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('published'); ",'data-placeholder'=>" -Select Is Published- "] ) !!}
        <input type="text" id="published" name="published" value="{{ isset($event->published) ? $event->published : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>




<div class="form-row mb-3 {{ in_array('location', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="location">Location</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('location', isset($event->location) ? $event->location : '', "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


{{--$table->enum('type', ['O', 'D', 'C', 'S', 'R'])->comment( 'O=>Office, D=> Out of doors, C=> City, S => Concert/Stadium, R => Restaurant/Cafe');--}}
{{--$table->string('location', 100)->nullable();--}}


<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="description_container">Description</label>
    <div class="col-12 col-sm-12 col-md-8">
        {!! $viewFuncs->textarea('description_container', isset($event->description) ? $event->description : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('description', isset($event->description) ? $event->description : '', "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
    </div>

</div>


@if(isset($event->status_label))
    <div class="form-row mb-3 {{ in_array('status_label', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">Status</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('status_label', isset($event->status_label) ? $event->status_label : '', "form-control", [ "readonly"=> "readonly" ] ) !!}

        </div>


        <label class="col-12 col-sm-4 col-form-label">Calendar actions</label>
{{--        private static $eventStatusValueArray = Array( 'N' => 'New', 'M' => 'Modified', 'U' => 'Unmodified' );--}}


        <div class="col-12 col-sm-8 mt-3">

            @if($event->status != 'N')
            <div class="col-12 mb-3">
                <div class="alert alert-info" role="alert" id="div_alert_calendar_action_update">
                    <button type="button" class="btn btn-primary btn-lg mr-2" onclick="javascript:backendEvent.calendarActionUpdate(); return;">&nbsp;Update </button>
                    Content of this event would be updated to <strong>Google "{{ $current_google_calendar }}" Calendar</strong>
                </div>
            </div>
            @endif

            @if($event->status == 'N')
            <div class="col-12 mb-3">
                <div class="alert alert-info" role="alert" id="div_alert_calendar_action_insert">
                    <button type="button" class="btn btn-primary btn-lg mr-2" onclick="javascript:backendEvent.calendarActionInsert(); return;">&nbsp;Insert </button>
                    Content of this event would be Insert to <strong>Google "{{ $current_google_calendar }}" Calendar</strong>
                </div>
            </div>
            @endif

            @if($event->status != 'N')
            <div class="col-12 mb-3">
                <div class="alert alert-info" role="alert" id="div_alert_calendar_action_delete">
                    <button type="button" class="btn btn-primary btn-lg mr-2" onclick="javascript:backendEvent.calendarActionDelete(); return;">&nbsp;Delete </button>
                    Content of this event would be Deleted from <strong>Google "{{ $current_google_calendar }}" Calendar</strong>
                </div>
            </div>
            @endif

        </div>


    </div>
@endif

@if(isset($event->calendar_event_id))
    <div class="form-row mb-3 {{ in_array('calendar_event_id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">Calendar event id</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('calendar_event_id', isset($event->calendar_event_id) ? $event->calendar_event_id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

@if(isset($event->calendar_event_html_Link))
    <div class="form-row mb-3 {{ in_array('calendar_event_html_Link', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label">Calendar event html Link</label>
        <div class="col-12 col-sm-8" style="display: flex; flex-direction: row">
            <div style="flex : 1">
                {!! $viewFuncs->text('calendar_event_html_Link', isset($event->calendar_event_html_Link) ? $event->calendar_event_html_Link : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
            </div>
            <div style="flex : 0 0 20px" class="p-2">
                <a href="{{ $event->calendar_event_html_Link }}" class="a_link" target="_blank">{!! $viewFuncs->showAppIcon('external-link','transparent_on_white') !!}</a>
            </div>
        </div>
    </div>
@endif


@if(isset($event->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($event->created_at) ? $viewFuncs->getFormattedDateTime($event->created_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($event->updated_at) ? $viewFuncs->getFormattedDateTime($event->updated_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendEvent.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ url('/admin/events') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;

        <button type="button" class=" btn btn-dark  " onclick="javascript:backendEvent.onEventDelete()"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa fa-trash"></i></span> &nbsp;Delete
        </button>&nbsp;&nbsp;

    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="div_event_attendees_modal"  aria-labelledby="event-attendees-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="event_attendees-modal-label">Event attendees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body " >
                <div id="div_event_attendees_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
