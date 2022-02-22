@include($current_admin_template.'.layouts.page_header')

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($serviceSubscription->id))
    <div class="form-row mb-3 {{ in_array('id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="id">ID</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('id', isset($serviceSubscription->id) ? $serviceSubscription->id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="form-row mb-3 {{ in_array('name', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="name">Name<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('name', isset($serviceSubscription->name) ? $serviceSubscription->name : '', "form-control editable_field", [ "maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('active', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_active">Active<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_active', $serviceSubscriptionActiveValueArray, isset($serviceSubscription->active) ? $serviceSubscription->active : '', "form-control
        editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('active'); ",'data-placeholder'=>" -Select Active- "]
         ) !!}
        <input type="text" id="active" name="active" value="{{ isset($serviceSubscription->active) ? $serviceSubscription->active : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


{{--'activate'=> "fa fa-toggle-on",--}}
{{--'deactivate'=> "fa fa-toggle-off",--}}
{{--'check'=> "fa fa-check"--}}

<?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>
@if(isset($serviceSubscription->id))
    <div class="form-row mb-3 {{ in_array('service_id', $errorFieldsArray) ? 'validation_error' : '' }}">
        <label class="col-12 col-sm-4 col-form-label" for="service_id">Service id</label>
        <div class="col-12 col-sm-8">

{{--            service_id--}}
            {!! $viewFuncs->text('service_id', isset($serviceSubscription->service_id) ? $serviceSubscription->service_id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}

{{--            {{ isset($serviceSubscription->service_id) ? $serviceSubscription->service_id : '' }}--}}

            <div id="div_create_paypal_plan">
                <button type="button" class=" btn btn-outline-primary" onclick="javascript: backendServiceSubscription.createPaypalPlan() ; return false; ">&nbsp;{!! $viewFuncs->showAppIcon('activate','transparent_on_white') !!}&nbsp;Create Paypal Plan</button>
                <div class="alert alert-info" role="alert" id="div_alert_create_paypal_plan">
                    You can create new paypal plan based on this service subscription and assign new paypal to this service subscription.
                </div>
                <div class="alert alert-info" role="alert" id="div_alert_select_existing_paypal_plan">
                    <strong>OR</strong><br>
                    You can select existing paypal plan and assign it to this service subscription.
                    <button type="button" class=" btn btn-outline-primary btn-xs" onclick="javascript: backendServiceSubscription.loadPaypalPlan() ; return false; ">&nbsp;{!!
                    $viewFuncs->showAppIcon('activate','transparent_on_white') !!}&nbsp;Load Paypal Plans</button>
                </div>

            </div>

            <div id="div_clear_paypal_plan">
                <button type="button" class=" btn btn-outline-primary" onclick="javascript: backendServiceSubscription.clearPaypalPlan() ; return false; ">&nbsp;{!!
                $viewFuncs->showAppIcon('deactivate','transparent_on_white') !!}&nbsp;Clear Paypal Plan</button>
                <div class="alert alert-info" role="alert" id="div_clear_alert_paypal_plan">
                    You can clear paypal from this service subscription, but paypal will not be removed.
                </div>
            </div>

            <div >
                <a class="dropdown-item" href="{{ url('admin/paypal_plans') }}" target="_blank">
                    {!! $viewFuncs->showAppIcon('paypal','transparent_on_white') !!}Paypal plans
                </a>
            </div>


            {{--            {!! $viewFuncs->text('service_id', isset($serviceSubscription->service_id) ? $serviceSubscription->service_id : '', "form-control", [ "readonly"=> "readonly" ] ) !!}--}}
        </div>
    </div>

    @else

    <div class="form-row mb-3">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_price_period"></label>
        <div class="col-12 col-sm-8">
            <div class="alert alert-info" role="alert" id="div_alert_select_existing_paypal_plan">
                Saving new service subscription, you can create Paypal Billing Plan and assign it to this service subscription.
            </div>
        </div>
    </div>

@endif
{{--<div class="form-row mb-3">--}}
{{--    <label class="col-12 col-sm-4 col-form-label">Access</label>--}}
{{--    <div class="col-12 col-sm-8">--}}
{{--        {{ $viewFuncs->getUserDisplayAccessGroupsName($user->id) }}&nbsp;--}}
{{--        <button type="button" class=" btn btn-outline-primary" onclick="javascript: backendUser.editUserAccess({{ $user->id }}) ; return false; ">&nbsp;<i class="fa fa-group"></i>&nbsp;--}}
{{--            User's Access</button>--}}
{{--    </div>--}}
{{--</div>--}}




<div class="form-row mb-3 {{ in_array('price_period', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="chosen_price_period">Price Period<span class="required"> * </span></label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->select('chosen_price_period', $serviceSubscriptionPricePeriodValueArray, isset($serviceSubscription->price_period) ? $serviceSubscription->price_period : '', "form-control
        editable_field chosen_select_box", ['onchange'=>"javascript:chosenSelectionOnChange('price_period'); ",'data-placeholder'=>" -Select Price Period- "]
         ) !!}
        <input type="text" id="price_period" name="price_period" value="{{ isset($serviceSubscription->price_period) ? $serviceSubscription->price_period : ''}}" style="visibility: hidden; width: 1px; height: 1px">
    </div>
</div>


<div class="form-row mb-3 {{ in_array('price', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="price">Price</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('price', isset($serviceSubscription->price) ? $serviceSubscription->price : '', "form-control", [ "placeholder"=>"Enter integer value.", "autocomplete"=>"off" ] ) !!}
    </div>
</div>


<div class="form-row mb-3 {{ in_array('description', $errorFieldsArray) ? 'validation_error' : '' }}">
    <label class="col-12 col-sm-4 col-form-label" for="description">Description</label>
    <div class="col-12 col-sm-8">
        {!! $viewFuncs->text('description', isset($serviceSubscription->description) ? $serviceSubscription->description : '', "form-control editable_field", [
        "maxlength"=>"120", "autocomplete"=>"off" ]
        ) !!}
    </div>
</div>


@if(isset($serviceSubscription->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="created_at">Created at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('created_at', isset($serviceSubscription->created_at) ? $viewFuncs->getFormattedDateTime($serviceSubscription->created_at) : '', "form-control", [
            "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


@if(isset($serviceSubscription->id))
    <div class="form-row mb-3">
        <label class="col-12 col-sm-4 col-form-label" for="updated_at">Updated at</label>
        <div class="col-12 col-sm-8">
            {!! $viewFuncs->text('updated_at', isset($serviceSubscription->updated_at) ? $viewFuncs->getFormattedDateTime($serviceSubscription->updated_at) : '', "form-control", [
            "readonly"=> "readonly" ] ) !!}
        </div>
    </div>
@endif


<div class="row float-right" style="padding: 0; margin: 0;">
    <div class="row btn-group editor_btn_group">
        <button type="button" class="btn btn-primary" onclick="javascript: backendServiceSubscription.onSubmit() ; return false; " style=""><span
                    class="btn-label"><i class="fa fa-save fa-submit-button"></i></span> &nbsp;Save
        </button>&nbsp;&nbsp;
        <button type="button" class=" btn btn-inverse  " onclick="javascript:document.location='{{ url('/admin/service-subscriptions') }}'"
                style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Cancel
        </button>&nbsp;&nbsp;
    </div>
</div>
