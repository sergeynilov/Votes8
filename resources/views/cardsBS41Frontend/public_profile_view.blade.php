@extends($frontend_template_name.'.layouts.frontend')

@section('content')

	@inject('viewFuncs', 'App\library\viewFuncs')

	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
			<small>{{ $site_subheading }}</small>@endif
	</h1>

	@include($frontend_template_name.'.layouts.logged_user')

	<div class="row m-3">
		{{ Breadcrumbs::render('public_profile', 'Public profile') }}
	</div>


	<!-- Page Content : public_profile preview -->

	<div class="row bordered card m-3" id="page-wrapper">
		<h4 class="card-title ml-3 mr-3">
			<img class="card-img-top" src="{{ $medium_slogan_img_url }}{{  "?dt=".time()  }}" alt="Site slogan" style="width: 80px;height:auto">
			Public profile : {{ $publicUserProfile['username'] }}
		</h4>

		@if( !empty($fullPhotoData['full_photo_url']) and !empty($fullPhotoData['full_photo']) )
			<img class="card-img-top" src="{{ $fullPhotoData['full_photo_url'] }}{{  "?dt=".time()  }}" alt="{{ $publicUserProfile['username'] }}" style="width: 256px;height:auto">
		@endif


		<div class="card-body">
			<h5 class="card-title">
				<strong>{{ $publicUserProfile['full_name'] }}</strong>
				@if (!empty($publicUserProfile['sex']))
					, {{ $viewFuncs->wrpGetUserSexLabel( $publicUserProfile['sex'] ) }}
				@endif
			</h5>
			<table>

				<tr>
					<td class="pull-right">
						ID :
					</td>
					<td>
						{{ $publicUserProfile['id'] }}
					</td>
				</tr>

				<tr>
					<td class="pull-right">
						Email :
					</td>
					<td>
						{{ $publicUserProfile['email'] }}
					</td>
				</tr>

				@if(!empty($publicUserProfile['provider_name']))
					<tr>
						<td class="pull-right">
							Provider name/Provider id :
						</td>
						<td>
							{{ $viewFuncs->wrpCapitalize($publicUserProfile['provider_name']) }} / {{ $publicUserProfile['provider_id'] }}
						</td>
					</tr>
				@endif

				<tr>
					<td class="pull-right">
						Status :
					</td>
					<td>
						{{ $viewFuncs->wrpGetUserStatusLabel($publicUserProfile['status']) }}
					</td>
				</tr>

				<tr>
					<td class="pull-right">
						Verified :
					</td>
					<td>
						{{ $viewFuncs->wrpGetUserVerifiedLabel($publicUserProfile['verified']) }}
					</td>
				</tr>


				<tr>
					<td class="pull-right">
						Access groups :
					</td>
					<td>
						{{ $viewFuncs->getUserDisplayAccessGroupsName($publicUserProfile['id']) }}
					</td>
				</tr>


				@if(!empty($publicUserProfile['phone']))
				<tr>
					<td class="pull-right">
						Phone :
					</td>
					<td>
						{{ $publicUserProfile['phone'] }}
					</td>
				</tr>
				@endif

				@if(!empty($publicUserProfile['activated_at']))
				<tr>
					<td class="pull-right">
						Activated at :
					</td>
					<td>
						{!! $viewFuncs->getFormattedDateTime($publicUserProfile['activated_at']) !!}

					</td>
				</tr>
				@endif

				@if(!empty($publicUserProfile['website']))
				<tr>
					<td class="pull-right">
						Website :
					</td>
					<td>
						<a href="{{ $viewFuncs->addHttpPrefix($publicUserProfile['website']) }}" target="_blank" class="a_link" >{{ $publicUserProfile['website'] }}</a>
					</td>
				</tr>
				@endif

			</table>
			<p class="card-text mt-3 mt-3">{!! Purifier::clean($viewFuncs->nl2br2($publicUserProfile['notes'])) !!}</p>
		</div>


		@if( !empty($profileUserSiteSubscriptions) and count($profileUserSiteSubscriptions) > 0)
			<h4 class="card-title m-3 mt-4">Subscribed to news :</h4>
			<ul class="list-group list-group-flush">
				@foreach($profileUserSiteSubscriptions as $nextSelectedSubscription)
					<li class="list-group-item">{{ $nextSelectedSubscription->site_subscription_name }}</li>
				@endforeach
			</ul>
		@endif

		@if( !empty($openedTodos) and count($openedTodos) > 0)
			<h4 class="card-title m-3 mt-4">Opened Todo:</h4>
			<ul class="list-group list-group-flush">
				@foreach($openedTodos as $nextOpenedTodo)
					<li class="list-group-item">{{ $nextOpenedTodo['priority_label'] }} : {{ $nextOpenedTodo['text'] }}</li>
				@endforeach
			</ul>
		@endif

		@if(!empty($is_developer_comp))
		@if( !empty($publicUserChatParticipants) and count($publicUserChatParticipants) > 0)
			<h4 class="card-title m-3 mt-4">Participant of chats:</h4>
			<ul class="list-group list-group-flush">
				@foreach($publicUserChatParticipants as $nextPublicUserChatParticipant)
					<li class="list-group-item">{{ $nextPublicUserChatParticipant->chat_name }} <small>( {{ $viewFuncs->wrpGetChatStatusLabel($nextPublicUserChatParticipant->chat_status) }} )</small> TODO START CHAT</li>
				@endforeach
			</ul>
		@endif
		@endif

		@if(!empty($is_developer_comp))
			<div class="card-body">
				<a href="#" class="card-link">TO CHAT TODO</a>
				<a href="#" class="card-link">Another link</a>
			</div>
		@endif
	</div>

	<!-- /.page-wrapper Page Content : public_profile preview -->

@endsection


@section('scripts')

	<script src="{{ asset('js/'.$frontend_template_name.'/public_profile.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/


        var publicProfile = new publicProfile('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            publicProfile.onFrontendPageInit('view')
        });


        /*]]>*/
	</script>


@endsection
