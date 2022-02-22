@inject('viewFuncs', 'App\library\viewFuncs')
@if( !empty($chatParticipants) and count($chatParticipants) > 0 )
	<div class="chat-show-chat-members-block mt-0 mb-0 mt-3 bordered">

		<h3 class="text-center">Chat's participants @if($online_count>0) (
			<small><span id="span_dialog_show_chat_members_count">{{ $online_count }}</span> online</small>)
			@endif </h3>
		@foreach($chatParticipants as $nextChatParticipant)

            <?php $avatarData = ! empty($nextChatParticipant['avatarData']) ? $nextChatParticipant['avatarData'] : []; ?>

			<div class="card">
				<div class="card-body pt-0">

					<h5 class="card-title mb-0 pb-0">
						@if( $nextChatParticipant['is_online'] )
							&nbsp;<i class="fa fa-toggle-on"></i>
						@endif

						@if( !empty($avatarData['avatar_url']) )
							<div class="chat_incoming_msg_img">
								<a href="{!! route('public-profile-view', $nextChatParticipant['user_id'] ) !!}" target="_blank" class="a_link">
									<img src="{{$avatarData['avatar_url']}}" alt="{!! $nextChatParticipant['full_name'] !!}">
								</a>
							</div>
						@endif

						<a href="{{ route('public-profile-view', $nextChatParticipant['user_id'] ) }}" target="_blank">
							{!! $nextChatParticipant['username'] !!} ( {!! $nextChatParticipant['full_name'] !!} )
							@if($nextChatParticipant['you'])
								<strong>, You</strong>
							@endif
						</a>
					</h5>

					@if( !empty($nextChatParticipant['status_label']) )
						<small>
							@if($nextChatParticipant['status']=='M') <strong> @endif
								{{ $nextChatParticipant['status_label'] }}
								@if($nextChatParticipant['status']=='M') </strong> @endif
						</small>
					@endif


				</div>
			</div>

		@endforeach


	</div>
@else
	<div class="alert alert-warning small" role="alert">
		There are no members in the chat now
	</div>
@endif

<button onclick="javascript:frontendUserChat.attachChatMembers( {{ $chat_id }} ); return false; " class="a_link small btn btn-primary mb-3">
	<span class="btn-label"><i class="fa fa-users fa-submit-button"></i></span>
	&nbsp;Attach/remove user(s)
</button>

@if(count($activeUsers) > 0)

	<h3 class="text-center mp-4">You can attach/remove users from this chat</h3>
	@foreach($activeUsers as $nextActiveUser)
        <?php $avatarData = ! empty($nextActiveUser['avatarData']) ? $nextActiveUser['avatarData'] : []; ?>

		<div class="card">
			<div class="card-body pt-0">

				<h5 class="card-title mb-0 pb-0">
					@if( $nextActiveUser['is_online'] )
						&nbsp;<i class="fa fa-toggle-on"></i>
					@endif
					@if($nextActiveUser['user_in_chat'])
						&nbsp;<i class="fa fa-comments-o"></i>
					@endif


					@if( !empty($avatarData['avatar_url']) )
						<a href="{!! route('public-profile-view', $nextActiveUser['user_id'] ) !!}" target="_blank" class="a_link">
							<img src="{{$avatarData['avatar_url']}}" alt="{!! $nextActiveUser['full_name'] !!}">
						</a>
					@endif


					<a href="{{ route('public-profile-view', $nextActiveUser['user_id'] ) }}" target="_blank">
						{{$nextActiveUser['user_id']}} : {!! $nextActiveUser['username'] !!} ( {!! $nextActiveUser['full_name'] !!} )
						@if($nextActiveUser['you'])
							<strong>, You</strong>
						@endif
					</a>
				</h5>

				<div class="card-footer">
					@if(empty($nextActiveUser['user_in_chat']))
						<a href="#" onclick="javascript:frontendUserChat.attachUserToChat( '{{ $chat_id }}', '{{ $nextActiveUser['user_id'] }}', 'W',
								'{{$nextActiveUser['username']}}' )" class="a_link">
							<i class="fa fa-paperclip"></i>
						</a>
						<b>Attach with write access</b>&nbsp;&nbsp;&nbsp;

						<a href="#"
						   onclick="javascript:frontendUserChat.attachUserToChat( '{{ $chat_id }}', '{{ $nextActiveUser['user_id'] }}', 'R', '{{$nextActiveUser['username']}}' )"
						   class="a_link">
							<i class="fa fa-paperclip"></i>
						</a>
						<b>Attach with read access</b>&nbsp;&nbsp;&nbsp;
					@else
						<a href="#" onclick="javascript:frontendUserChat.clearUserInChat( '{{ $chat_id }}', '{{$nextActiveUser['user_id']}}', '{{$nextActiveUser['username']}}' )"
						   class="a_link">
							<i class="fa fa-remove"></i>
						</a>
						<b>Remove from this chat</b>&nbsp;&nbsp;&nbsp;
					@endif

				</div>

			</div>
		</div>

	@endforeach

@endif