@extends($frontend_template_name.'.layouts.frontend')

@section('meta_content')
    <h1 class="text-center">
        @if(isset($site_heading))<span> {{ $site_heading }}@endif</span>
    </h1>
    <meta name="description" content="{{ !empty($page_meta_description) ? $page_meta_description : '' }}" />
    <meta name="keywords" content="{{ !empty($page_meta_keywords_string) ? $page_meta_keywords_string : '' }}" />
@endsection


@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')

{{--    @if( 1 )--}}
{{--        {{ Widget::run('FrontendBanners', ['order_by_field_name'=> 'ordering', 'order_by_field_ordering'=>'desc']) }}--}}
{{--    @endif--}}

{{--    --}}
    <?php $voteImagePropsAttribute = $activeVote->getVoteImagePropsAttribute();?>

    @include($frontend_template_name.'.layouts.logged_user')
    <div class="row ml-1 mb-3">
        {{ Breadcrumbs::render('vote_by_slug', $activeVote) }}
    </div>

    <div class="row  ml-1 mr-1">
        <div class="col-sm-8 block-selection">

            <div class="row ml-1 mr-1">
                <h3 class="card-title ml-3">
                    @if(!empty($is_developer_comp))
                        id::{{ $activeVote->id }}
                    @endif
                    {{ $activeVote->name }}
                </h3>
            </div>
            <div class="row ml-1 mr-1">

                <div class="card">

                    <div class="col-sm-12" style="align-items:start">
                        @if(isset($voteImagePropsAttribute['image_url']) )
                            <a class="a_link" href="{{ $voteImagePropsAttribute['image_url'] }}">
                                <img class="pull-left single_vote_image_left_aligned" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{
                                    $activeVote->name }}">
                            </a>
                        @endif


                        <p class="card-text " style="align-items:start">
                            {!! Purifier::clean($activeVote->description) !!}
                        </p>

{{--                            $relatedTags::{!! $relatedTags   !!}--}}
<!--                            --><?php //echo '<pre>$relatedTags::'.print_r($relatedTags,true).'</pre>';   ?>
                        @if( count($relatedTags) > 0)
                            <div class="card-footer">
                                <div class="table-responsive">
                                    <table class="table text-primary " id="get-contact-us-dt-listing-table">
                                        <tr>
                                            @foreach($relatedTags as $relatedTag)
<!--                                                --><?php //echo '<pre>$relatedTag::'.print_r($relatedTag,true).'</pre>';   ?>
                                                <td>
                                                    <a href="{{ route('tag_by_slug', [$relatedTag['slug']] ) }}">
                                                        <span class="badge">{{ $relatedTag['name'] }}</span>
                                                    </a>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                <div class="card col-sm-12">
                    <div id="div_vote_items"></div>
                </div>


            </div>
        </div> {{--<div class="col-sm-8--}}

        @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => false,
    'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

    </div>{{--<div class="row">--}}

@endsection

@section('scripts')

    <script src="{{ asset('js/'.$frontend_template_name.'/vote.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendVote = new frontendVote('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendVote.onFrontendPageInit('view')
        });

        /*]]>*/
    </script>

@endsection

