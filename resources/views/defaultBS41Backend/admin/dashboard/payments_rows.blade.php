@inject('viewFuncs', 'App\library\viewFuncs')

<div class="row">
    <label class="col-6 col-form-label pb-0 mb-0  mt-3" for="filter_user_id"> Select filter(s):
    </label>
    <div class="col-6 p-2 m-0">
        <input type="button" class="btn btn-primary" value="Refresh" onclick="javascript:backendDashboard.showPaymentsRows(1); return false;">
    </div>

    <div class="col-6 p-2 m-0">
        {!! $viewFuncs->select('filter_download_id', $downloadsValueArray, $filter_download_id, "form-control editable_field", [] ) !!}
    </div>
    <div class="col-6 p-2 m-0">
        {!! $viewFuncs->select('filter_user_id', $usersSelectionArray, $filter_user_id, "form-control editable_field", [] ) !!}
    </div>
</div>

@if( count($paymentItems) == 0)
    <button type="button" class="btn btn-error btn-lg btn-block">Has no payments</button>
@else
    <div class="row m-2">

        <?php $odd = true ?>
        @if($total_rows > 0)
            <h4>
                {{ count($paymentItems) }} of {{$total_rows}} payment items
            </h4>
        @endif

        <div class="table-responsive" style="max-height: 100%; overflow-y:auto;">
            <table class="table text-primary ">
                @foreach ( $paymentItems as $nextPaymentItem )
                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_1" class="@if ( $odd ) grayed @else lightly-grayed @endif">
                        <td>
                            <a class="toggle_link" onclick="javascript:backendDashboard.showPaymentDetails({{ $nextPaymentItem['id'] }})"
                               id="a_payment_items_row_{{ $nextPaymentItem['id'] }}_show_block">
                                {!! $viewFuncs->showAppIcon('toggle_on','transparent_on_white', 'Show details') !!}
                            </a>
                            <a class="toggle_link" onclick="javascript:backendDashboard.hidePaymentDetails({{ $nextPaymentItem['id'] }})"
                               id="a_payment_items_row_{{ $nextPaymentItem['id'] }}_hide_block" style="display: none">
                                {!! $viewFuncs->showAppIcon('toggle_off','transparent_on_white', 'Hide details') !!}
                            </a>
                            <strong>{{ $nextPaymentItem['payment_username'] }}</strong> payed for <strong>{{ $nextPaymentItem['payed_item_title'] }}</strong>
                        </td>

                        <td>
                            {{ $viewFuncs->getFormattedCurrency($nextPaymentItem['price']) }}&nbsp;*&nbsp;{{ $nextPaymentItem['quantity'] }}
                            &nbsp;=&nbsp;<strong>{{$viewFuncs->getFormattedCurrency( $nextPaymentItem['price'] * $nextPaymentItem['quantity'] ) }}</strong>
                        </td>

                        {{--                        <td>--}}
                        {{--                            <strong>{{ $nextPaymentItem['payed_item_title'] }}</strong>--}}
                        {{--                        </td>--}}

                        <td>
                            {{ $viewFuncs->getFormattedDateTime($nextPaymentItem['created_at']) }}</td>
                        </td>
                    </tr>
                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_2" style="display:none">
                        <td colspan="4">
                            {{ $viewFuncs->wrpConcatArray( [ $viewFuncs->wrpGetPaymentTypeLabel($nextPaymentItem['payment_type']),
                            '№ '.$nextPaymentItem['invoice_number'],
                            ( !empty($nextPaymentItem['tax']) ? 'tax:'.$viewFuncs->getFormattedCurrency($nextPaymentItem['tax']) : '' ),
                            ( !empty($nextPaymentItem['shipping']) ? 'shipping:'.$viewFuncs->getFormattedCurrency($nextPaymentItem['shipping']) : '') ], ', ' ) }}
                        </td>
                    </tr>

                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_3" style="display:none">
                        <td colspan="4">
                            {{ $viewFuncs->wrpConcatArray( [ $nextPaymentItem['payer_email'], $nextPaymentItem['payer_first_name'], $nextPaymentItem['payer_first_name'],
                            $nextPaymentItem['payer_shipping_address'] ], ', ' ) }}
                        </td>
                    </tr>

                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_4" class="mb-4" style="display:none">
                        <td colspan="4">
                            <p class="m-0 mb-4">
                                {{ $nextPaymentItem['payment_description'] }}
                            </p>
                        </td>
                    </tr>

                    <?php $odd = ! $odd; ?>

                @endforeach

            </table>

            <div class="row">
                {{ $paymentItems->appends([])->links() }}
                {{--                &nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-primary" value="Refresh" onclick="javascript:backendDashboard.showPaymentsRows(1); return false;">--}}
            </div>

        </div>

    </div>
@endif