@include($current_admin_template.'.layouts.page_header')

<input type="hidden" id="vote_id" name="vote_id" value="{{ $parent_vote_id }}">
<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($voteItem->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($voteItem->id) ? $voteItem->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($voteItem->name) ? $voteItem->name : '', "form-control editable_field ", [ "maxlength"=>"255", "autocomplete"=>"off"  ] ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('is_correct', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_correct">Is Correct<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_correct', $voteIsCorrectValueArray, isset($voteItem->is_correct) ? $voteItem->is_correct : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('is_correct'); ",'data-placeholder'=>" -Select Is Correct- "]
        ) !!}
        <input type="text" id="is_correct" name="is_correct" value="{{ isset($voteItem->is_correct) ? $voteItem->is_correct : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>

<div class="form-row mb-3 {{ in_array('ordering', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="ordering">Ordering</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('ordering', isset($voteItem->ordering) ? $voteItem->ordering : '', "form-control", [ "placeholder"=>"Enter integer value.",
        "autocomplete"=>"off"  ] ) !!}
    </div>
</div>

<div class="form-row mb-3 {{ in_array('image', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Image</label>
    <div class="col-12 col-sm-8">

        @if(isset($voteItem->id))
            <?php $voteItemImagePropsAttribute = $voteItem->getVoteItemImagePropsAttribute(); ?>
            @if(isset($voteItemImagePropsAttribute['image_url']) )
                <a class="a_link" target="_blank" href="{{ $voteItemImagePropsAttribute['image_url'] }}">
                    <img class="image_preview" src="{{ $voteItemImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $voteItem->name }}">
                </a><br>
                {!! Purifier::clean($voteItemImagePropsAttribute['file_info']) !!}
            @endif
        @endif

        <div style="padding-top: 30px; padding-bottom: 30px;">
            <input type="file" id="image" name="image">
        </div>
    </div>
</div>

@if(isset($voteItem->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($voteItem->created_at) ? $viewFuncs->getFormattedDateTime($voteItem->created_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($voteItem->updated_at) ? $viewFuncs->getFormattedDateTime($voteItem->updated_at) : '', "form-control", [ "readonly"=>
            "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendVoteItem.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ route('admin.votes.edit', $parent_vote_id ) }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;

    </div>
</div>