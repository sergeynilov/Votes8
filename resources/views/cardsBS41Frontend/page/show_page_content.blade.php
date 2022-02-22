@extends($frontend_template_name.'.layouts.frontend')

@section('content')

	@inject('viewFuncs', 'App\library\viewFuncs')
    <?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br> {{ $pageContent->title }}
	</h1>


	@include($frontend_template_name.'.layouts.logged_user')

	<div class="row ml-1 mb-2">
		{{ Breadcrumbs::render('page-content-by-slug', $pageContent->title) }}
	</div>


	<div class="row ml-1 mr-1">
		<div class="col-sm-8">

			<div class="row">

				<div class="card">

					<div class="col-12">
						@if(isset($pageContentImageProps['image_url']) )
							<a class="a_link" href="{{ $pageContentImageProps['image_url'] }}">
								<img class=" pull-left tag_image_left_aligned" src="{{ $pageContentImageProps['image_url'] }}{{  "?dt=".time()  }}"
								     alt="{{ Purifier::clean($pageContent->title) }}">
							</a>
						@endif


						@if(!empty($pageContent->content))
							<div class="card-text m-1">
								{!! Purifier::clean($pageContent->content) !!}
							</div>
						@endif

					</div>
					@if($pageContent->page_type == 'N')
						<div class="card-footer  mt-0 pt-0">
							<div class="row float-right mt-0 pt-0">
								Published at {{ $viewFuncs->getFormattedDateTime($pageContent->created_at, 'mysql', 'ago_format') }} by {{ $pageContent->creator_username }}
							</div>
						</div>
					@endif


				</div>

				@if($page_slug == 'contact-us')
					@include($frontend_template_name.'.page.contact_us_form', ['medium_slogan_img_url'=> $medium_slogan_img_url])
				@endif

				@if(count($otherPageContents) > 0)
                    <?php $odd = true ?>
					<div class="row">
						<h3 class="text-center">Other news</h3>
						@foreach($otherPageContents as $nextOtherPageContent)


							<div class="col-md-6 mt-4 news-item-wrapper">
								<div class="card">

									<div class="news-preview-wrapper">
                                        <?php $pageContentImagePropsAttribute = $nextOtherPageContent->getPageContentImagePropsAttribute();?>
										@if(isset($pageContentImagePropsAttribute['image_url']))
											<a href="{{ route('news', $nextOtherPageContent->slug ) }}">
												<img class="image_in_3_columns_list" src="{{ $pageContentImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}"
												     alt="{{ Purifier::clean($nextOtherPageContent->title) }}">
											</a>
										@endif
									</div>

									<div class="card-body pt-0">
										<h5 class="card-title">
											<a href="{{ route('news', $nextOtherPageContent->slug ) }}">
												{!! Purifier::clean($nextOtherPageContent->title) !!}
											</a>
										</h5>

										@if( !empty($nextOtherPageContent->content_shortly) )
											<p class="card-text">{!! Purifier::clean($nextOtherPageContent->content_shortly) !!}</p>
										@else
											<p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextOtherPageContent->content,255))  !!}</p>
										@endif
										<a href="{{ route('news', $nextOtherPageContent->slug ) }}">
											Details
										</a>
									</div>
								</div>
							</div>

							@if($odd)
								<div class="clearfix "></div>
							@endif

                            <?php $odd = ! $odd; ?>

						@endforeach
					</div>
				@endif


			</div>

			@if(count($pageContentVideos) > 0)
				<div class="row">
					@foreach($pageContentVideos as $nextPageContentVideo)
                        <?php $pageContentVidepProps = $nextPageContentVideo->getPageContentImageImagePropsAttribute();
                        $next_video_url = $pageContentVidepProps['image_url'];
                        ?>

						@if(!empty($next_video_url))
							<div class="row m-1">

								<input type="hidden" id="video_page_content_width_{{ $nextPageContentVideo->id }}" value="{{ $nextPageContentVideo->video_width }}"><br>
								<input type="hidden" id="video_page_content_height_{{ $nextPageContentVideo->id }}" value="{{ $nextPageContentVideo->video_height }}">

								<video id="video_page_content_{{ $nextPageContentVideo->id }}" class="video-js video_page_content" controls preload="auto"
								       poster="/images/{{$frontend_template_name}}/video_poster.jpg"
								       data-setup="{}">
									<source src="{{ $next_video_url }}" type='video/mp4'>
									<source src="{{ $next_video_url }}" type='video/webm'>
									<p class="vjs-no-js">
										To view this video please enable JavaScript, and consider upgrading to a web browser that
										<a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
									</p>
								</video>

								@if(!empty($nextPageContentVideo['info']))
									<div class="card-text m-1 mt-0 mb-4 pl-2">
										{!! $nextPageContentVideo['info'] !!}
									</div>
								@endif

							</div>
						@endif


					@endforeach
				</div>
			@endif


			@if(count($pageContentImages) > 0)
				<div class="row mt-5">
					<div class="card mb-4">
						@foreach($pageContentImages as $nextPageContentImage)
                            <?php $pageContentImageProps = $nextPageContentImage->getPageContentImageImagePropsAttribute(); ?>

							@if(isset($pageContentImageProps['image_url']) )
								<div class="card-title m-1 mb-0">
									<img class=" pull-left " src="{{ $pageContentImageProps['image_url'] }}{{  "?dt=".time()  }}"
									     alt="{{ Purifier::clean($pageContent->title) }}">
								</div>
							@endif
							@if(!empty($pageContentImageProps['info']))
								<div class="card-text m-1 mt-0 mb-4">
									{!! $pageContentImageProps['info'] !!}
								</div>
							@endif


						@endforeach
					</div>

				</div>
			@endif


		</div> {{--<div class="col-sm-8--}}




		@include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
	'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )


	</div>{{--<div class="row">--}}

	<div class="row">
		@include($frontend_template_name.'.layouts.select_downloads',['downloadsList'=>  ( !empty($downloadsList) ? $downloadsList : [] )  ])
	</div>


@endsection

@section('scripts')

	<script src="{{ asset('js/'.$frontend_template_name.'/show_page_content.js') }}{{  "?dt=".time()  }}"></script>

	<script src="{{ asset('js/video.js') }}"></script>
	<link href="{{ asset('css/video-js.css') }}" rel="stylesheet" type="text/css">


	<script>
        /*<![CDATA[*/

        var showPageContent = new showPageContent('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            showPageContent.onFrontendPageInit('view')
        });

        /*]]>*/
	</script>

@endsection