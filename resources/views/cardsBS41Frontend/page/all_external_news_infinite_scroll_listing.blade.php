@inject('viewFuncs', 'App\library\viewFuncs')


@foreach($allExternalNews as $nextAllExternalNews)
	<article class="all-external-news-listing-append-block">

		<div class="card">
			<div class="card-body pt-2">

				<h5 class="card-title mb-0 pb-0">
{{--					::{{ $nextAllExternalNews['id'] }};--}}
					<a href="{{ route('news', $nextAllExternalNews['slug'] ) }}">
						{{ Purifier::clean($nextAllExternalNews['title']) }}
					</a>
					@if( $nextAllExternalNews['is_featured'] )
						<span class="float-right mt-0 pt-0 badge badge-pill badge-primary">Featured</span>
					@endif
				</h5>

				@if( !empty($nextAllExternalNews['content_shortly']) )
					<div class="card-footer mt-0 pt-0 mb-0 pb-0">
						<small>{!! Purifier::clean($nextAllExternalNews['content_shortly']) !!}</small>
					</div>
				@endif

				<div class="card-footer  mt-0 pt-0">
					<div class="row float-right mt-0 pt-0">
						Published at {{ $viewFuncs->getFormattedDateTime($nextAllExternalNews['created_at'], 'mysql', 'ago_format') }} by {{
	$nextAllExternalNews['source_type'] }}
					</div>
				</div>
			</div>
		</div>

	</article>
@endforeach