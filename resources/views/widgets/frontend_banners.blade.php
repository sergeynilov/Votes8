@if( !empty($activeBanners) and count($activeBanners) > 0 )
    <div class="active-banners-block mt-5">

        @foreach($activeBanners as $nextActiveBanners)
                <a href="{{ $nextActiveBanners['url'] }}" class="a_link" target="_blank">
                    <img src="/show_banner_image/{{ $nextActiveBanners['id'] }}/{{ urlencode($nextActiveBanners['text']) }}/{{ urlencode($nextActiveBanners['logo']) }}/{{ urlencode
                ($nextActiveBanners['short_descr'])}}/{{$nextActiveBanners['view_type'] }}{{  "?dt=".time()  }}" class="banner_image">
                </a>
{{--            ::{{$nextActiveBanners['view_type'] }}--}}
{{--            <hr>--}}
{{--            <hr>--}}
{{--            <hr>--}}
{{--            <hr>--}}
        @endforeach

    </div>
@endif
