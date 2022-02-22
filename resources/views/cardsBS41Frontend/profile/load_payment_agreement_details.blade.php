@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($paymentAgreementDetails) )
    <h4 class="card-subtitle pl-2 pb-2">Payment agreement details are not provided. </h4>
@else
    <h4 class="card-subtitle pl-2 pb-2">Payment agreement details</h4>



    <fieldset class="notes-block text-muted">
        <legend class="notes-blocks">Agreement</legend>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_id">Agreement Id</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_id', $paymentAgreementDetails['agreement_id'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="state">State</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('state', $paymentAgreementDetails['state'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="description">Description</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('description', $paymentAgreementDetails['description'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="start_date">Start date</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('start_date', $viewFuncs->getFormattedDateTime($paymentAgreementDetails['start_date']), "form-control editable_field", [
                "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
    </fieldset>


    <fieldset class="notes-block text-muted">
        <legend class="notes-blocks">Payer Address</legend>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_recipient_name">Recipient name</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_recipient_name', $paymentAgreementDetails['payer_recipient_name'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_line1">Line 1</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_line1', $paymentAgreementDetails['payer_line1'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_city">City</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_city', $paymentAgreementDetails['payer_city'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_state">State</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_state', $paymentAgreementDetails['payer_state'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_postal_code">Postal code</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_postal_code', $paymentAgreementDetails['payer_postal_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="payer_country_code">Country code</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('payer_country_code', $paymentAgreementDetails['payer_country_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

    </fieldset>


    <fieldset class="notes-block text-muted">
        <legend class="notes-blocks">Shipping address</legend>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_recipient_name">Recipient name</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_recipient_name', $paymentAgreementDetails['shipping_address_recipient_name'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_line1">Line 1</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_line1', $paymentAgreementDetails['shipping_address_line1'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_city">City</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_city', $paymentAgreementDetails['shipping_address_city'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_state">State</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_state', $paymentAgreementDetails['shipping_address_state'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_postal_code">Postal code</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_postal_code', $paymentAgreementDetails['shipping_address_postal_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>
        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="shipping_address_country_code">Country code</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('shipping_address_country_code', $paymentAgreementDetails['shipping_address_country_code'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

    </fieldset>

    <fieldset class="notes-block text-muted">

        <legend class="notes-blocks">Payment plan</legend>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="plan_payment_definitions_type">Type</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('plan_payment_definitions_type', $paymentAgreementDetails['plan_payment_definitions_type'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="plan_payment_definitions_frequency">Frequency</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('plan_payment_definitions_frequency', $paymentAgreementDetails['plan_payment_definitions_frequency'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="plan_payment_definitions_amount_currency">Currency</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('plan_payment_definitions_amount_currency', $paymentAgreementDetails['plan_payment_definitions_amount_currency'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="plan_payment_definitions_amount_value">Value</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('plan_payment_definitions_amount_value', $paymentAgreementDetails['plan_payment_definitions_amount_value'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

    </fieldset>

    <fieldset class="notes-block text-muted">

        <legend class="notes-blocks">Balance</legend>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_outstanding_balance_currency">Balance currency</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_outstanding_balance_currency', $paymentAgreementDetails['agreement_details_outstanding_balance_currency'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_outstanding_balance_value">Balance value</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_outstanding_balance_value', $paymentAgreementDetails['agreement_details_outstanding_balance_value'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_cycles_remaining">Cycles remaining</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_cycles_remaining', $paymentAgreementDetails['agreement_details_cycles_remaining'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_cycles_completed">Cycles completed</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_cycles_completed', $paymentAgreementDetails['agreement_details_cycles_completed'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_next_billing_date">Billing date</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_next_billing_date', $viewFuncs->getFormattedDateTime($paymentAgreementDetails['agreement_details_next_billing_date']),
                "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_last_payment_date">Payment date</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_last_payment_date', $viewFuncs->getFormattedDateTime($paymentAgreementDetails['agreement_details_last_payment_date']), "form-control editable_field", [
                "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_last_payment_amount_currency">Amount currency</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_last_payment_amount_currency', $paymentAgreementDetails['agreement_details_last_payment_amount_currency'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_last_payment_amount_value">Amount value</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_last_payment_amount_value', $paymentAgreementDetails['agreement_details_last_payment_amount_value'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_final_payment_date">Payment date</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_final_payment_date', $viewFuncs->getFormattedDateTime($paymentAgreementDetails['agreement_details_final_payment_date']),
                "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>

        <div class="form-row mb-3">
            <label class="col-12 col-sm-4 col-form-label" for="agreement_details_failed_payment_count">Payment count</label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->text('agreement_details_failed_payment_count', $paymentAgreementDetails['agreement_details_failed_payment_count'], "form-control editable_field", [ "disabled"=>"disabled"  ] ) !!}
            </div>
        </div>


    </fieldset>

@endif