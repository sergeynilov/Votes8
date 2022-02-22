@extends($frontend_template_name.'.layouts.frontend')

@section('content')
	@inject('viewFuncs', 'App\library\viewFuncs')

	{{--$viewParamsArray['tagDetail'] = $tagDetail;--}}
	{{--$viewParamsArray['tagDetailImageProps'] = $tagDetailImageProps;--}}

	<h1 class="text-center">
		@if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
		<br>Tagged : {{ $tag_name }}
		<small>( {{ count($tagRelatedVotes) }} {{ \Illuminate\Support\Str::plural('vote', count($tagRelatedVotes)) }} )</small>
	</h1>


	@include($frontend_template_name.'.layouts.logged_user')

	<div class="row ml-1 mb-2">
		{{ Breadcrumbs::render('tag_by_slug', $tag_name) }}
	</div>

	<div class="row ml-1 mr-1">
		<div class="col-sm-8 ">

			<div class="row block-selection">

				<div class="card">

					<div class="col-12">
						@if(isset($tagDetailImageProps['image_url']) )
							<a class="a_link" href="{{ $tagDetailImageProps['image_url'] }}">
								<img class=" pull-left tag_image_left_aligned" src="{{ $tagDetailImageProps['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $tag_name }}">
							</a>
						@endif


						@if(!empty($tagDetail->description))
							<div>
								<p class="card-text m-1">
									{!! Purifier::clean($tagDetail->description) !!}
								</p>
							</div>
						@endif

					</div>
				</div>

                <?php $odd = true ?>
				@foreach($tagRelatedVotes as $nextActiveVote)

					<div class="col-md-6 mt-4 vote-item-wrapper">
						<div class="card">

                        <span class="w-80 mx-auto px-4 py-1 rounded-bottom category-link primary border-info text-white">
                            @if(!empty($nextActiveVote->vote_category_name) )
		                        <a href="{{ route('votes-by-category', $nextActiveVote->vote_category_slug ) }}"><strong>{{ $nextActiveVote->vote_category_name
                            }} </strong></a>
	                        @endif
                        </span>

							<div class="img-preview-wrapper">
                                <?php $voteImagePropsAttribute = $nextActiveVote->getVoteImagePropsAttribute();?>
								@if(isset($voteImagePropsAttribute['image_url']))
									<a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
										<img class="image_in_3_columns_list" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}"
										     alt="{{ $nextActiveVote->name }}">
									</a>
								@endif
							</div>

							<div class="card-body pt-0">
								<h5 class="card-title">
									<a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
										{{ $nextActiveVote->name }}
									</a>
								</h5>


								<p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextActiveVote->description,100))  !!}</p>
								<a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
									Go to Quiz
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

		</div> {{--<div class="col-sm-8--}}

		@include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
	'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )


	</div>{{--<div class="row">--}}


@endsection
