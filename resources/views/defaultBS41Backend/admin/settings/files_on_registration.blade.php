@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($fileOnRegistrations) )
	<button type="button" class="btn btn-error btn-lg btn-block">Has no files on registration</button>
@else
	<div class="container">
		<h4 class="box-title p-3">Has {{ $file_on_registrations_count }} {{ \Illuminate\Support\Str::plural('file', $file_on_registrations_count) }}</h4>
	</div>

	<div class="row">

		@foreach ( $fileOnRegistrations as $nextFileOnRegistration )

			<div class="card p-2">

				@if( !empty($nextFileOnRegistration['is_image']) )

					<img class="card-img-top image_preview" src="{{ $nextFileOnRegistration['file_on_registration_url']  }}"
					     alt="{!! Purifier::clean($nextFileOnRegistration['file_on_registration']) !!}" style="width: {{
                $nextFileOnRegistration['image_preview_width'] }}px; height: auto;">
					<div class="card-body">
						<p class="card-text">
							<a class="`"
							   onclick="javascript:backendSettings.deleteFileOnRegistration( '{{ $nextFileOnRegistration['file_on_registration'] }}', true );">
								<i class="fa fa-close text-danger"></i>
							</a>
						</p>
					</div>
					<div class="card-footer">
						<p>{!! Purifier::clean($viewFuncs->nl2br2($nextFileOnRegistration['file_info'] )) !!}</p>
					</div>

				@else
					@if(  !empty($nextFileOnRegistration['extension_filename'])  )
						<img class="card-img-top image_preview" src="{{ $nextFileOnRegistration['extension_filename']  }}"	     alt="{!! Purifier::clean($nextFileOnRegistration['file_on_registration']) !!}" style="width: {{ $nextFileOnRegistration['image_preview_width'] }}px; height: auto;">
					@endif
					<div class="card-body">
						<p class="card-text">
							<a class="`"
							   onclick="javascript:backendSettings.deleteFileOnRegistration( '{{ $nextFileOnRegistration['file_on_registration'] }}', true );">
								<i class="fa fa-close text-danger"></i>
							</a>
						</p>
					</div>
					<div class="card-footer">
						<p>{!! Purifier::clean($viewFuncs->nl2br2($nextFileOnRegistration['file_info'] )) !!}</p>
					</div>

				@endif
			</div>

		@endforeach

	</div>

@endif
