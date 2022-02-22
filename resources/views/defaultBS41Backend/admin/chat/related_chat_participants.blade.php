@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($usersList) == 0)
	<h4 class="card-subtitle pl-2 pb-2">Has no participants</h4>
@else
	<h4 class="card-subtitle pl-2 pb-2">The app has {{ count($usersList) }} chats, {{ $selected_chats_count }} attached for this user. </h4>

	<div class="table-responsive" style="max-height: 800px; overflow-y:scroll;">
		<table class="table table-bordered table-striped text-primary ">
			@foreach($usersList as $next_key => $nextUser)
				<tr>
					<td>
						{{--{{ $nextUser['id'] }}: :--}}
						{{ $nextUser['username'] }}, {{ $nextUser['email'] }}<br> (
						<small>
							{{ $viewFuncs->wrpGetUserStatusLabel($nextUser['status'] )}}, 
							{{-- {{ $nextUser['selected_user_status'] }}::--}}
							@if( !empty($nextUser['selected_user_status_label']) )
								{{ $nextUser['selected_user_status_label'] }}, 
							@endif
							{{ $nextUser['messages_count'] }}/{{ $nextUser['chat_messages_count'] }} messages
						</small>
						)
					</td>
					<td>

						@if(empty($nextUser['user_in_chat']))
							<a onclick="javascript:backendChat.attachChatParticipantToUser( '{{ $nextUser['id'] }}', '{{ $nextUser['username'] }}', 'M', 'manage' )" class="a_link">
								<i class="fa fa-paperclip a_link"></i>
							</a>
							Attach with manage access<br>

							<a onclick="javascript:backendChat.attachChatParticipantToUser( '{{ $nextUser['id'] }}', '{{ $nextUser['username'] }}', 'W', 'write' )" class="a_link"> <i class="fa fa-paperclip a_link"></i>
							</a>
							Attach with write access<br>

							<a onclick="javascript:backendChat.attachChatParticipantToUser( '{{ $nextUser['id'] }}', '{{ $nextUser['username'] }}', 'R', 'readonly' )" class="a_link">
								<i class="fa fa-paperclip"></i>
							</a>
							Attach with read access&nbsp;&nbsp;&nbsp;
						@else
							<a onclick="javascript:backendChat.clearChatParticipantOfUser( '{{ $nextUser['id'] }}', '{{ $nextUser['username'] }}' )" class="a_link">
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