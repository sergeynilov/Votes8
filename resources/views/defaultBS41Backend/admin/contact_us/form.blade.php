@include($current_admin_template.'.layouts.page_header')
@inject('viewFuncs', 'App\library\viewFuncs')

<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('id', isset($contactUs->id) ? $contactUs->id : '', "form-control", [ "readonly"=> "readonly" ]
        ) !!}
    </div>
</div>


<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="author_name">Author name</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('author_name', isset($contactUs->author_name) ? $contactUs->author_name : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
    </div>
</div>

<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="author_email">Author name</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('author_email', isset($contactUs->author_email) ? $contactUs->author_email : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
    </div>
</div>

<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="message">Message</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->textarea('message', isset($contactUs->message) ? $contactUs->message : '', "form-control", [ "readonly"=> "readonly", "rows"=> 10 ]
        ) !!}
    </div>
</div>


<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label">Accepted</label>
    <div class="col-12 col-sm-8">
        {{ $viewFuncs->wrpGetContactUsAcceptedLabel($contactUs->accepted)}}
        @if(!$contactUs->accepted)
            &nbsp;<button type="button" class=" btn btn-outline-primary" onclick="javascript: backendContactUs.acceptContactUs( '{{$contactUs->id}}',
                    '{{$contactUs->author_name}}' ) ; return false;
                ">Accept </button>
        @endif
    </div>
</div>


<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="accepted_at">Accepted at</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('accepted_at', isset($contactUs->accepted_at) ? $viewFuncs->getFormattedDateTime($contactUs->accepted_at) : '', "form-control", [ "readonly"=>
        "readonly" ] ) !!}
    </div>
</div>

<div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('created_at', isset($contactUs->created_at) ? $viewFuncs->getFormattedDateTime($contactUs->created_at) : '', "form-control", [ "readonly"=>
        "readonly" ] ) !!}
    </div>
</div>

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/contact-us') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
