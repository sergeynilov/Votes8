@inject('viewFuncs', 'App\library\viewFuncs')

@if( !empty($metaKeywords) and count($metaKeywords) == 0 )
    <h4 class="card-subtitle pl-2 pb-2">Has no meta keywords</h4>
    <div class="form-row p-2">
        <label for="new_meta_keyword" class="col-3 col-form-label">New</label>
        <div class="col-6">
            {!! $viewFuncs->text( 'new_meta_keyword', '', "form-control editable_field", [ "maxlength"=>"255" ] ) !!}
        </div>
        <div class="col-3">
            <button type="button" onclick="javascript:backendVote.attachMetaKeywordToVote()" class="btn btn-primary">
                &nbsp;Add
            </button>&nbsp;&nbsp;
        </div>
    </div>
@else
    <h4 class="card-subtitle pl-2 pb-2">Has {{ count($metaKeywords) }} meta keywords. </h4>

    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
        <table class="table table-bordered table-striped text-primary ">
            @foreach($metaKeywords as $next_meta_keyword)
                <tr>
                    <td style="width: 95%">
                        {{ $next_meta_keyword }}
                    </td>
                    <td style="width: 5%">
                        <a href="#" onclick="javascript:backendVote.clearMetaKeywordToVote('{{$next_meta_keyword}}')">
                            <i class="fa fa-remove"></i>
                        </a>

                    </td>

                </tr>
            @endforeach

        </table>
    </div>

    <div class="form-row p-2">
        <label for="new_meta_keyword" class="col-3 col-form-label">New</label>
        <div class="col-6">
            {!! $viewFuncs->text( 'new_meta_keyword', '', "form-control editable_field", [ "maxlength"=>"255" ] ) !!}
        </div>
        <div class="col-3">
            <button type="button" onclick="javascript:backendVote.attachMetaKeywordToVote()" class="btn btn-primary">
                &nbsp;Add
            </button>&nbsp;&nbsp;
        </div>
    </div>

@endif

<div class="form-row p-2 pt-5">
    <label for="vote_meta_description" class="col-12 col-form-label">Meta description
        <small>( if empty, then "site name" : "vote title" would be used)</small>
    </label>
    <div class="col-9">
        {!! $viewFuncs->text( 'vote_meta_description', $vote->meta_description, "form-control editable_field", [ "maxlength"=>"255" ] ) !!}
    </div>
    <div class="col-3">
        <button type="button" onclick="javascript:backendVote.updateMetaDescriptionToVote()" class="btn btn-primary">
            &nbsp;Update
        </button>&nbsp;&nbsp;
    </div>
</div>

