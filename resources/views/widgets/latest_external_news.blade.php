@inject('viewFuncs', 'App\library\viewFuncs')
@if( !empty($latestExternalNewsData) and count($latestExternalNewsData) > 0 )
	<div class="latest-news-block mt-0 mb-0 mt-3">

		<h3 class="text-center">Our Partners  News</h3>
		<ul class="news-line">
		@foreach($latestExternalNewsData as $nextExternalLatestNew)

			<div class="card m-0">
				<div class="card-body pt-0">

					<h5 class="card-title mb-0 pb-0">
						<a href="{{ $nextExternalLatestNew['source_url'] }}">
							{!! Purifier::clean($nextExternalLatestNew['title']) !!}
						</a>
						@if( $nextExternalLatestNew['is_featured'] )
							<span class="float-right mt-0 pt-0 badge badge-pill badge-primary">Featured</span>
						@endif
					</h5>

					@if( !empty($nextExternalLatestNew['content_shortly']) )
						<p class=" m-0 pt-0 ">
							<small>
								{!! Purifier::clean($nextExternalLatestNew['content_shortly']) !!}
							</small>
						</p>
					@endif

					<div class="card-footer  mt-0 pt-0">
						<div class="row float-right mt-0 pt-0">
							Published at {{ $viewFuncs->getFormattedDateTime($nextExternalLatestNew['created_at'], 'mysql', 'ago_format') }} by <b>&nbsp;{{
							$nextExternalLatestNew['source_type'] }}</b>
						</div>
					</div>

				</div>
			</div>

		@endforeach
		</ul>


		@if( $all_external_news_count > count($latestExternalNewsData) )
			<div class="card">
				<div class="card-body pt-0">
					<a href="{{ route('all-external-news' ) }}">
						Our Partners All News
					</a>
				</div>
			</div>
		@endif

	</div>
@else
	@if( !empty($config['show_no_items_label']) )
		<div class="alert alert-warning small" role="alert">
			There are no Our Partners news yet
		</div>
	@endif
@endif