@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($siteSubscriptionsList) == 0)
	<h4 class="card-subtitle pl-2 pb-2">You are not subscribed to any news subscriptions yet. </h4>
@else
	<h4 class="card-subtitle pl-2 pb-2">System has {{ count($siteSubscriptionsList) }} news subscriptions, you subscribed to {{ $subscribed_site_subscriptions_count }}</h4>

	<div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
		<table class="table table-bordered table-striped text-primary ">
<!--			--><?php //echo '<pre>$siteSubscriptionsList::'.print_r($siteSubscriptionsList,true).'</pre>';   ?>
			@foreach($siteSubscriptionsList as $next_key => $nextSiteSubscription)
				<tr>
					<td>
						{{ $nextSiteSubscription['id'] }}::{{ $nextSiteSubscription['name'] }}
					</td>
					<td>

						@if(!empty($nextSiteSubscription['is_checked']))
							<a href="#" onclick="javascript:frontendProfile.unsubscribeUsersSiteSubscription({{$nextSiteSubscription['id']}},'{{$nextSiteSubscription['name']}}')">
								<i class="fa fa-remove"></i>
							</a>
							<b>Subscribed</b>&nbsp;
						@else
							<a href="#" onclick="javascript:frontendProfile.subscribeUsersSiteSubscription({{$nextSiteSubscription['id']}},'{{$nextSiteSubscription['name']}}')">
								<i class="fa fa-paperclip"></i>
							</a>
							<b>Not Subscribed</b>&nbsp;
						@endif

					</td>

				</tr>
			@endforeach

		</table>
	</div>

@endif