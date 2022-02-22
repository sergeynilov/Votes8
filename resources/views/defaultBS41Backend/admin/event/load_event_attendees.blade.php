@inject('viewFuncs', 'App\library\viewFuncs')

<div style="display: flex;flex-direction: column; " class=" p-0 m-0">

    @if(count($eventAttendees) == 0)
        <div class="alert alert-warning small" role="alert">
            Has no attendees</h4>
        </div>
    @else
        <div style="justify-content: center; flex: 0 0 50px;">
             <h4 class="card-subtitle p-0 m-1" style=" border: 0px dotted yellow;">The event has {{ count($eventAttendees) }} attendee(s). </h4>
        </div>

        <div class="table-responsive" style=" border: 0px dotted green;  flex: 1 1;">
            <table class="table table-bordered table-striped text-primary ">
                @foreach($eventAttendees as $nextEventAttendee)
                    <tr>
                        <td>
                            @if(!empty($nextEventAttendee['attendee_user_id']) and !empty($nextEventAttendee['event_attendees_email']) )
                                {{ $viewFuncs->wrpConcatArray( [ $nextEventAttendee['event_attendees_email'], $nextEventAttendee['event_attendees_first_name'],  $nextEventAttendee['event_attendees_last_name'] ]) }}
                                &nbsp;<a target="_blank" href="/public-profile-view/{{ $nextEventAttendee['event_attendees_user_id'] }}">Public Profile</a>
                            @else
                                {{ $viewFuncs->wrpConcatArray( [ $nextEventAttendee['attendee_user_email'], $nextEventAttendee['attendee_user_display_name'] ]) }}
                            @endif

                        </td>
                        <td>
                            <a onclick="javascript:backendEvent.clearEventAttendees( '{{ $nextEventAttendee['id'] }}', '' )" style="" class="a_link">
                                <i class="fa fa-remove" title="Remove from this event"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>


        
    @endif



        <div class="row" style=" border: 0px dotted red; flex: 1 1;">
            <h4 class="card-subtitle p-0 pl-2 m-2" style=" border: 0px dotted yellow;">Add users from the app to this event.</h4>

            <div class="col-sm-12">
                {!! $viewFuncs->select('add_active_users', $activeUsers, '', "form-control editable_field", [ 'multiple'=>'multiple' ] ) !!}
            </div>

            <div class="row float-right m-2 p-0">
                <button type="button" class="btn btn-primary btn-default btn-sm"
                        onclick="javascript:backendEvent.addSelectedUsers();  return false; ">
                    {!! $viewFuncs->showAppIcon('event','selected_dashboard') !!}Add Selected User(s)
                </button>
            </div>

        </div>


        <div class="row p-1 m-3" style=" border: 0px dotted red; flex: 1 1; width: 100%;">
            <h4 class="card-subtitle p-0 pl-2 m-2" style=" border: 0px dotted yellow;">Add users which are not in the app to this event.</h4>

            <div class="form-row m-2 p-0">
                <label class="col-12 col-sm-4 col-form-label" for="new_attendee_user_email">Attendee user's email<span class="required"> * </span></label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('new_attendee_user_email', '', "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off" ] ) !!}
                </div>
            </div>

            <div class="form-row m-2 p-0">
                <label class="col-12 col-sm-4 col-form-label" for="new_attendee_user_display_name">Attendee display name<span class="required"> * </span></label>
                <div class="col-12 col-sm-8">
                    {!! $viewFuncs->text('new_attendee_user_display_name', '', "form-control editable_field", [ "maxlength"=>"100", "autocomplete"=>"off" ] ) !!}
                </div>
            </div>

            <div class="row m-0 p-0">
                <div class="btn-group editor_btn_group">
                    <button type="button" class="btn btn-primary btn-default btn-sm"
                            onclick="javascript:backendEvent.addExternalUser();  return false; ">
                        {!! $viewFuncs->showAppIcon('event','selected_dashboard') !!}Add New User
                    </button>
                </div>
            </div>

        </div>



</div>
