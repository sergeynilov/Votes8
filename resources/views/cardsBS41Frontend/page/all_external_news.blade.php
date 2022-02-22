@extends($frontend_template_name.'.layouts.frontend')

@section('content')

	@inject('viewFuncs', 'App\library\viewFuncs')


	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br> {{ $all_external_news_count }} News
	</h1>


	@include($frontend_template_name.'.layouts.logged_user')

	<div class="row ml-2 mb-3">
		{{ Breadcrumbs::render('all-external-news', 'External News') }}
	</div>
	<div class="row ml-2 mb-3 small_title">
		<span id="span_external_news_loaded_count">{{ $external_news_loaded_count }}</span>&nbsp;of&nbsp;{{ $all_external_news_count }}&nbsp;External News loaded
	</div>


	<div class="row ml-1 mr-1">
		<div class="col-sm-8 ">

			<div class="row">

				<div id="infinite_scroll_container">

					@foreach($allExternalNews as $nextAllExternalNews)
						<article class="all-external-news-listing-append-block">

							<div class="card">
								<div class="card-body pt-2">

									<h5 class="card-title mb-0 pb-0">
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
				</div>

				<div class="all-external-news-listing-load-status-block">
					END OF CONTENT
					<div class="loader-ellips infinite-scroll-request">
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
						<span class="loader-ellips__dot"></span>
					</div>
					<p class="infinite-scroll-last">End of content</p>
					<p class="infinite-scroll-error">No more pages to load</p>
				</div>

			</div>
		</div>


		@include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

	</div>

@endsection



@section('scripts')

	<script src="{{ asset('js/'.$frontend_template_name.'/all_external_news.js') }}{{  "?dt=".time()  }}"></script>

	<script>
        /*<![CDATA[*/

        var frontendAllExternalNews = new frontendAllExternalNews('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendAllExternalNews.onFrontendPageInit('view')
        });

        /*]]>*/
	</script>

@endsection


