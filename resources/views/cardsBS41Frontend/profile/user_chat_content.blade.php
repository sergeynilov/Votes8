<div class="card">
	@inject('viewFuncs', 'App\library\viewFuncs')

	<div class="mb-3 col-12">
		id::{{ $loggedUser['id']}}::{{ $loggedUser['username'] }} ( {{ $loggedUser['full_name'] }} ), {{ $loggedUser['email'] }}<br>
		{!! $viewFuncs->wrpGetUserStatusLabel($loggedUser['status']) !!} as {{ $viewFuncs->getLoggedUserDisplayAccessGroupsName() }}
		Activated at {!! $viewFuncs->getFormattedDateTime($loggedUser['activated_at']) !!}
	</div>


	<div class="form-row mb-3">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">
					<a class="btn btn-primary collapsed" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
						<span class="btn-label small m-0 p-0"><i class="fa fa-caret-square-o-down"></i></span>
					</a>&nbsp;
					{{ $currentChat->name }}
				</h4>
				<div class="m-0 mb-4 p-0 collapse multi-collapse" id="multiCollapseExample1">
					<p class="card-text text-small p-1 m-1">{!! Purifier::clean($currentChat->description) !!}</p>

				@if(count($relatedChatMessageDocuments) > 0)
						<h4 class="card-subtitle">
							Has {{ count($relatedChatMessageDocuments) }} {{ \Illuminate\Support\Str::plural('file', count($relatedChatMessageDocuments)) }} uploaded :
						</h4>
						@foreach($relatedChatMessageDocuments as $nextRelatedChatMessageDocument)
							<p class="card-text p-0 m-0 p-0">
								<a href="{{ asset($nextRelatedChatMessageDocument['chat_message_document_url']) }}" class="a_link" target="_blank">
									{!! Purifier::clean	($nextRelatedChatMessageDocument['filename'])!!} :
								</a>
								<span class="card-text text-small p-1 m-1">{!! Purifier::clean($nextRelatedChatMessageDocument['info']) !!}</span>
							</p>
						@endforeach
				@endif
			</div>

				<p class="card-text pull-left">
					<button type="button" class="btn btn-default  btn-sm"
					        onclick="javascript:frontendUserChat.showChatMembers({!! $currentChat->id !!}); return false; ">
						<span class="fa fa-users" aria-hidden="true"></span> <span id="span_show_chat_members_count"></span>&nbsp;Member(s) online
					</button>
				</p>
				<p class="card-text pull-right">Created at {!! $viewFuncs->getFormattedDateTime($currentChat->created_at) !!}</p>
			</div>
		</div>
	</div>


	<div class="ml-2 mr-2" id="div_type_whisper_alert_wrapper" style="display: none;">
		<div class="alert alert-info small" role="alert" id="div_type_whisper_alert">
			You can only read messages in this chat
		</div>
	</div>
	@if($chatParticipant->status == "R")
		<div class="chat_type_message_textarea ml-2 mr-2">
			<div class="alert alert-warning small" role="alert">
				You can only read messages in this chat
			</div>
		</div>
	@else
		<div class="chat_type_message_textarea ml-2 mr-2">


			<fieldset class="notes-block text-muted">
				<legend class="notes-blocks">Send message/Upload file:</legend>

				{{--onkeydown="javascript:frontendUserChat.vote_chat_new_messageOnKeyDown()--}}
				<input type="text" id="vote_chat_new_message" placeholder="Type a message" onkeydown="javascript:frontendUserChat.vote_chat_new_messageOnKeyDown()">
				{{--<textarea placeholder="Type a message" id="vote_chat_new_message" rows="3" cols="100"></textarea>--}}
				<button class=" btn btn-outline-primary" onclick="javascript:frontendUserChat.newChatMessageSent('{!! $currentChat->id !!}'); return false; ">
					<i class="fa fa-paper-plane-o" aria-hidden="true"></i>
					Send
				</button>

				<span id="span-temp-uploading-chat-message-documents-count"></span>
				<div id="div-temp-uploading-chat-message-documents"></div>
				<form action="upload" id="upload_chat_message_document" enctype="multipart/form-data">
					<div class="form-row  p-2 mb-3">
						<div class="col-12 col-sm-6 p-0 mr-3">
							<label for="fileupload" class="col-form-label">Upload&nbsp;file:&nbsp;</label>
						</div>
						<div class="col-12 col-sm-6 p-0 ml-3 mb-1">
							::<input id="fileupload" type="file" class="chat_message_document_fileupload" name="files[]">
						</div>
					</div>
					<input type="hidden" id="hidden_selected_chat_message_document" name="hidden_selected_chat_message_document">
					{{ csrf_field() }}
				</form>




				<div id="div_save_upload_image" class="row bordered p-3 ml-3" style="display: none">

					<div class="card" style="width: 100%;">

						<img class="" id="img_preview_image" alt="Preview" title="Preview" src="/images/spacer.png" width="1" height="1">
						<div class="card-body">
							<div class="row">
								<div id="img_preview_image_info" class="col-sm-12"></div>
								<div class="col-sm-12 p-0 m-0 alert alert-warning pl-5 pr-5" style="display: none" id="div_info_message" role="alert">
								</div>
							</div>

							<div class="row">
								<div id="div_show_votes_results" class="m-3">
									<button onclick="javascript:frontendUserChat.UploadChatMessageDocument()" class="a_link small btn btn-primary">
										<span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span>
										&nbsp;Save
									</button>
								</div>

								<div id="div_hide_votes_results" class="m-3">
									<button onclick="javascript:frontendUserChat.CancelUploadChatMessageDocument()" class="a_link small btn btn-inverse">
										<span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
									</button>
								</div>
							</div>

						</div>
					</div>

				</div> <!-- div_save_upload_image -->


			</fieldset>

		</div>
	@endif

	<div class="form-row mb-3">

		<div class="chat_messages_block">
			<div class="chat_messages_history">

				@foreach($chatMessages as $nextChatMessage)

                    <?php $avatarData = ! empty($nextChatMessage['avatarData']) ? $nextChatMessage['avatarData'] : []; ?>
					@if( $loggedUser->id != $nextChatMessage['user_id'])
						<div class="chat_incoming_msg pb-3">
							<div class="received_msg">
								<div class="received_withd_msg">

                                    <span class="chat_user_full_name">

                                        @if( !empty($avatarData['avatar_url']) )
                                            <div class="chat_incoming_msg_img">
                                                <a href="{!! route('public-profile-view', $nextChatMessage['user_id'] ) !!}" target="_blank" class="a_link">
                                                    <img src="{{$avatarData['avatar_url']}}" alt="{!! $nextChatMessage['user_full_name'] !!}">
                                                </a>
                                            </div>
                                        @endif
                                        {{ $nextChatMessage['user_id'] }}:<a href="{!! route('public-profile-view', $nextChatMessage['user_id'] ) !!}" target="_blank" class="a_link">
                                            <strong>{!! $nextChatMessage['user_full_name'] !!} </strong>
                                            </a>
	                                        @if( $nextChatMessage['chat_message_documents_count'] > 0 )
		                                        Has {{ $nextChatMessage['chat_message_documents_count'] }} {{ \Illuminate\Support\Str::plural('file', $nextChatMessage['chat_message_documents_count']) }} attached
	                                        @endif
                                    </span>

									<p>{{$nextChatMessage['id']}}:{!! Purifier::clean($viewFuncs->nl2br2($nextChatMessage['text'])) !!}</p>
									<span class="chat_time_date">
                                        {!! $viewFuncs->getFormattedDateTime($nextChatMessage['created_at']) !!}
                                    </span>
								</div>
							</div>

						</div>
					@endif

					@if( $loggedUser->id == $nextChatMessage['user_id'])
						<div class="outgoing_msg pb-3">
							<div class="chat_sent_msg">
								<p>{{$nextChatMessage['id']}}:{!! Purifier::clean($nextChatMessage['text']) !!}</p>
								<span class="chat_time_date">
                                    <strong>{{ $nextChatMessage['user_id'] }}:You</strong> at
									{!! $viewFuncs->getFormattedDateTime($nextChatMessage['created_at']) !!}
									@if( $nextChatMessage['chat_message_documents_count'] > 0 )
										<strong>Has {{ $nextChatMessage['chat_message_documents_count'] }} {{ \Illuminate\Support\Str::plural('file', $nextChatMessage['chat_message_documents_count'])
										 }} attached</strong>
									@endif
                                </span>
							</div>
						</div>
					@endif

				@endforeach

			</div>
		</div>

	</div>

</div>
