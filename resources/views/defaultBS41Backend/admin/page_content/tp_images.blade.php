<div class="row p-2" id="div_upload_image">

	<form action="upload" id="upload" enctype="multipart/form-data">
		<div class="form-row  p-2 mb-3">
			<div class="col-12 col-sm-6 p-0 mr-3">
				<label for="fileupload" class="col-form-label">Upload&nbsp;file:&nbsp;</label>
			</div>
			<div class="col-12 col-sm-6 p-0 ml-3 mb-1">
				<input id="fileupload" type="file" class="page_content_image_fileupload" name="files[]">
			</div>
		</div>
		<input type="hidden" id="page_content_id" name="page_content_id" value="{{ isset($pageContent->id) ? $pageContent->id : ''  }}">
		<input type="hidden" id="hidden_selected_image">
		{{ csrf_field() }}
	</form>

</div>


<div id="div_save_upload_image" class="row bordered p-3 ml-3" style="display: none">

	<div class="card" style="width: 100%;">

		<img class="" id="img_preview_image" alt="Preview" title="Preview" src="/images/spacer.png" width="1" height="1">
		<div class="card-body">
			<div class="row">
				<div id="img_preview_image_info" class="col-sm-12"></div>

				<div class="col-sm-12 p-0 m-0 alert alert-warning pl-5 pr-5" style="display: none" id="div_info_message" role="alert">
				</div>
				
				<div class="col-sm-6 p-0 m-0">
					<div class="form-row p-2">
						<label for="is_main_image" class="col-12 col-form-label">Is Main Image</label>
						<div class="col-12">
							{!! $viewFuncs->showStylingCheckbox('is_main_image', 1, false) !!}
						</div>
					</div>
				</div>

				<div class="col-sm-6 p-0 m-0 ">
					<div class="form-row p-2">
						<label for="is_video" class="col-12 col-form-label">Is video</label>
						<div class="col-12">
							{!! $viewFuncs->showStylingCheckbox('is_video', 1, false, '', ['onchange'=>"javascript:backendPageContent.is_videoOnChange(); "]  ) !!}
						</div>
					</div>
				</div>

			</div>


			<div class="row" id="div_video_block" style="display: none;">
				<div class="col-sm-6 p-0 m-0">
					<div class="form-row p-2">
						<label for="video_width" class="col-6 col-form-label">Video width</label>
						<div class="col-6">
							{!! $viewFuncs->text( 'video_width', '', "form-control editable_field", [ "maxlength"=>"4", "size"=>'4' ] ) !!}
						</div>
					</div>
				</div>

				<div class="col-sm-6 p-0 m-0">
					<div class="form-row p-2">
						<label for="video_height" class="col-6 col-form-label">Video height</label>
						<div class="col-6">
							{!! $viewFuncs->text( 'video_height', '', "form-control editable_field", [ "maxlength"=>"4", "size"=>'4' ] ) !!}
						</div>
					</div>
				</div>
			</div>

			<div id="div_video_block_warning" style="display: none;">
				<div class="alert alert-warning small mb-1 mt-1" role="alert">
					Please, enter width/height in pixels of uploaded video bor better view.
				</div>
			</div>

			<div class="row">
				<div class="form-row p-2">
					<label for="image_info" class="col-12 col-form-label">Info</label>
					<div class="col-12">
						{!! $viewFuncs->textarea('image_info', '', "form-control editable_field ", [ "rows"=>"3", "cols"=> 80, "placeholder"=>"Enter string description"  ] ) !!}
					</div>
				</div>
			</div>

			<div class="row">
				<div id="div_show_votes_results" class="m-3">
					<button onclick="javascript:backendPageContent.UploadImage()" class="a_link small btn btn-primary">
						<span class="btn-label"><i class="fa fa-save fa-submit-button"></i></span>
						&nbsp;Save
					</button>
				</div>

				<div id="div_hide_votes_results" class="m-3">
					<button onclick="javascript:backendPageContent.CancelUploadImage()" class="a_link small btn btn-inverse">
						<span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
					</button>
				</div>
			</div>

		</div>
	</div>

</div> <!-- div_save_upload_image -->

<div id="div-page-content-images" class="row p-2"></div>