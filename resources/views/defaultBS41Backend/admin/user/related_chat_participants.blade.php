@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($chatsList) == 0)
	<h4 class="card-subtitle pl-2 pb-2">Has no related chats</h4>
@else
	<h4 class="card-subtitle pl-2 pb-2">The app has {{ count($chatsList) }} chats, {{ $selected_chats_count }} attached for this user. </h4>

	<div class="table-responsive" style="max-height: 800px; overflow-y:scroll;">
		<table class="table table-bordered table-striped text-primary ">
			@foreach($chatsList as $next_key => $nextChat)
				<tr>
					<td>
						{{--{{ $nextChat['id'] }}:--}}
						{{ $nextChat['name'] }} (
						<small>
							{{ $viewFuncs->wrpGetChatStatusLabel($nextChat['status'] )}},
							{{--{{ $nextChat['selected_chat_access_status'] }}::--}}
							@if( !empty($nextChat['selected_chat_access_status_label']) )
								{{ $nextChat['selected_chat_access_status_label'] }},
							@endif
							{!! $nextChat['messages_count'] !!}/{!! $nextChat['user_messages_count'] !!} messages
						</small>
						)
					</td>
					<td>

					@if(empty($nextChat['user_in_chat']))
							<a onclick="javascript:backendUser.attachChatParticipantToUser( '{{ $nextChat['id'] }}', '{{ $nextChat['name'] }}', 'M', 'manage' )">
								<i class="fa fa-paperclip"></i>
							</a>
							Attach with manage access<br>

							<a onclick="javascript:backendUser.attachChatParticipantToUser( '{{ $nextChat['id'] }}', '{{ $nextChat['name'] }}', 'W', 'write' )">
								<i class="fa fa-paperclip"></i>
							</a>
							Attach with write access<br>

							<a onclick="javascript:backendUser.attachChatParticipantToUser( '{{ $nextChat['id'] }}', '{{ $nextChat['name'] }}', 'R', 'readonly' )">
								<i class="fa fa-paperclip"></i>
							</a>
							Attach with read access&nbsp;&nbsp;&nbsp;
						@else
							<a onclick="javascript:backendUser.clearChatParticipantOfUser( '{{ $nextChat['id'] }}', '{{ $nextChat['name'] }}' )">
								<i class="fa fa-remove"></i>
							</a>
							Remove from this chat&nbsp;&nbsp;&nbsp;
						@endif

					</td>

				</tr>
			@endforeach

		</table>
	</div>

@endif          `