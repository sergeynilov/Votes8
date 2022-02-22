@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($pageContentImages) )
    <button type="button" class="btn btn-error btn-lg btn-block">Has no images</button>
@else
    <div class="container">
        <h4 class="box-title pt-3">Has {{ $page_content_images_count }} {{ \Illuminate\Support\Str::plural('image/video', $page_content_images_count) }}</h4>
    </div>

    <div class="row m-2">

        <?php $odd = true ?>
        <?php $index0 = 0 ?>

        @foreach ( $pageContentImages as $nextPageContentImage )

            @if (!empty($nextPageContentImage->is_video != 1) )

                <?php $pageContentImageProps = $nextPageContentImage->getPageContentImageImagePropsAttribute();
                $main_text = $nextPageContentImage->is_main == 1 ? '<b>, Main</b>' : '';
                ?>

                <div class="card p-2">

                    <img class="card-img-top image_preview" src="{{ $pageContentImageProps['image_url']  }}" alt="{!! Purifier::clean($pageContentImageProps['info']) !!}" style="width: {{
                $pageContentImageProps['image_preview_width'] }}px; height: auto;">
                    <div class="card-body">
                        <p class="card-text">
                            <a class="`"
                               onclick="javascript:backendPageContent.deletePageContentImage( '{{ $nextPageContentImage['id'] }}', '{{ $pageContentImageProps['image'] }}', true );">
                                <i class="fa fa-close text-danger"></i>
                            </a>
                            {!! Purifier::clean($pageContentImageProps['file_info'] . $main_text) !!}
                        </p>
                    </div>
                    <div class="card-footer">
                        <p>{!! Purifier::clean($viewFuncs->nl2br2($pageContentImageProps['info'] )). ' <small><br>published at '. $viewFuncs->getCFFormattedDateTime($pageContentImageProps['created_at'])
                .'</small>' !!}</p>
                    </div>
                </div>
            @endif

        @endforeach


        @foreach ( $pageContentImages as $nextPageContentImage )
            <?php $pageContentImageProps = $nextPageContentImage->getPageContentImageImagePropsAttribute();
            $next_video_url = $pageContentImageProps['image_url'];
                        $video_width = $nextPageContentImage->video_width;
                        $video_height = $nextPageContentImage->video_height;
            ?>
            @if (!empty($nextPageContentImage->is_video == 1) )

                <div class="row" >

                    <div class="card" >
                        <div class="card-body">
                            <video id="video_page_content_{{ $nextPageContentImage->id }}" class="video-js video_page_content" controls preload="auto" poster="/images/{{$current_admin_template}}/video_poster.jpg"
                                   data-setup="{}" width="{{ $video_width }}" height="{{ $video_height }}">
                                <source src="{{ $next_video_url }}" type='video/mp4'>
                                <source src="{{ $next_video_url }}" type='video/webm'>
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                </p>
                            </video>
                            <a class="a_link"
                               onclick="javascript:backendPageContent.deletePageContentImage( '{{ $nextPageContentImage['id'] }}', '{{ $pageContentImageProps['image'] }}', false
                                       );">
                                <i class="fa fa-close text-danger"></i>
                            </a>
                        </div>

                        <div class="card-footer mb-5">
                            <p>{!! Purifier::clean($viewFuncs->nl2br2($pageContentImageProps['info'] )). ' <small><br>published at '. $viewFuncs->getCFFormattedDateTime($pageContentImageProps['created_at'])
                .'</small>' !!}</p>
                            <a href="{{ $pageContentImageProps['image_url'] }}" target="_blank">Upload</a></p>
                        </div>

                    </div>

                </div>

            @endif

        @endforeach


        @endif
    </div>
