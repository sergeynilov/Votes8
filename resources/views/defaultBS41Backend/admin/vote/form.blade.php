@include($current_admin_template.'.layouts.page_header')
@inject('viewFuncs', 'App\library\viewFuncs')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($vote->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" id="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($vote->id) ? $vote->id : '', 'form-control', [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($vote->name) ? $vote->name : '', 'form-control editable_field', [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


@if(isset($vote->id))
    <div class="form-row mb-3 {{ in_array('slug', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="slug">Slug</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('slug', isset($vote->slug) ? $vote->slug : '', 'form-control', [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('vote_category_id', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_vote_category_id">Vote category<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_vote_category_id', $voteCategoriesSelectionArray, isset($vote->vote_category_id) ? $vote->vote_category_id : '', "form-control
        editable_field chosen_select_boxTT", ['onchange'=>"javascript:chosenSelectionOnChange('vote_category_id'); ",'data-placeholder'=>" -Select Vote Category- "] ) !!}
        <input type="text" id="vote_category_id" name="vote_category_id" value="{{ isset($vote->vote_category_id) ? $vote->vote_category_id : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('is_quiz', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_quiz">Is Quiz<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_quiz', $voteIsQuizValueArray, isset($vote->is_quiz) ? $vote->is_quiz : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('is_quiz'); ",'data-placeholder'=>" -Select Is Quiz- "] ) !!}
        <input type="text" id="is_quiz" name="is_quiz" value="{{ isset($vote->is_quiz) ? $vote->is_quiz : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('is_homepage', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_is_homepage">Is Homepage<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_is_homepage', $voteIsHomepageValueArray, isset($vote->is_homepage) ? $vote->is_homepage : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('is_homepage'); ",'data-placeholder'=>" -Select Is Homepage- "] ) !!}
        <input type="text" id="is_homepage" name="is_homepage" value="{{ isset($vote->is_homepage) ? $vote->is_homepage : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>



<div class="form-row mb-3 {{ in_array('status', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_status">Status<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_status', $voteStatusValueArray, isset($vote->status) ? $vote->status : '', "form-control editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('status'); ",'data-placeholder'=>" -Select Status- "] ) !!}
        <input type="text" id="status" name="status" value="{{ isset($vote->status) ? $vote->status : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-12 col-md-4 col-form-label" for="description_container">Description</label>
    <div class="col-12 col-sm-12 col-md-8">
        {!! $viewFuncs->textarea('description_container', isset($vote->description) ? $vote->description : '', "form-control editable_field ", [   "rows"=>"5", "cols"=> 120,  "autocomplete"=>"off"  ] ) !!}
        {!! $viewFuncs->textarea('description', isset($vote->description) ? $vote->description : '', "form-control editable_field ", [ "rows"=>"1", "cols"=> 120, "autocomplete"=>"off", "style"=>"visibility: hidden;" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('image', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label">Image</label>
    <div class="col-12 col-sm-8">

        @if(isset($vote->id))
        <?php $voteImagePropsAttribute = $vote->getVoteImagePropsAttribute();?>
        @if(isset($voteImagePropsAttribute['image_url']) )
            <a class="a_link" target="_blank" href="{{ $voteImagePropsAttribute['image_url'] }}">
                <img class="image_preview" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $vote->name }}">
            </a><br>
            {!! Purifier::clean($voteImagePropsAttribute['file_info']) !!}
        @endif
        @endif

            <div style="padding-top: 30px; padding-bottom: 30px;">
                <input type="file" id="image" name="image">
            </div>
    </div>
</div>




<div class="form-row mb-3 {{ in_array('ordering', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="ordering">Ordering</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('ordering', isset($vote->ordering) ? $vote->ordering : '', "form-control", [ "placeholder"=>"Enter integer value.", "autocomplete"=>"off" ] ) !!}
    </div>
</div>

@if(isset($vote->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($vote->created_at) ? $viewFuncs->getFormattedDateTime($vote->created_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>

    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($vote->updated_at) ? $viewFuncs->getFormattedDateTime($vote->updated_at) : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif

<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendVote.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/votes') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
