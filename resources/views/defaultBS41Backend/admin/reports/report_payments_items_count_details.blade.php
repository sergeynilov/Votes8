@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($paymentItems) )
    <button type="button" class="btn btn-error btn-lg btn-block">Has no payments</button>
@else
    <div class="row m-2">

        <?php $odd = true ?>

        <div class="table-responsive" style="max-height: 100%; overflow-y:auto;">
            <table class="table text-primary ">
                @foreach ( $paymentItems as $nextPaymentItem )
                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_1" class="@if ( $odd ) grayed @else lightly-grayed @endif">
                        <td>
                            <a class="toggle_link" onclick="javascript:backendReports.showPaymentDetails({{ $nextPaymentItem['id'] }})"
                               id="a_payment_items_row_{{ $nextPaymentItem['id'] }}_show_block">
                                {!! $viewFuncs->showAppIcon('toggle_on','transparent_on_white', 'Show details') !!}
                            </a>
                            <a class="toggle_link" onclick="javascript:backendReports.hidePaymentDetails({{ $nextPaymentItem['id'] }})"
                               id="a_payment_items_row_{{ $nextPaymentItem['id'] }}_hide_block" style="display: none">
                                {!! $viewFuncs->showAppIcon('toggle_off','transparent_on_white', 'Hide details') !!}
                            </a>
                            {{ $nextPaymentItem['payment_username'] }}
                        </td>

                        <td>
                            payed {{ $nextPaymentItem['price'] }}&nbsp;*&nbsp;{{ $viewFuncs->getFormattedCurrency($nextPaymentItem['quantity']) }}
                            &nbsp;=&nbsp;<strong>{{$viewFuncs->getFormattedCurrency($nextPaymentItem['total']) }}</strong>
                        </td>

                        <td>
                            {{ $nextPaymentItem['payed_item_title'] }}
                        </td>

                        <td>
                            {{ $viewFuncs->getFormattedDateTime($nextPaymentItem['created_at']) }}</td>
                        </td>
                    </tr>
                    <tr id="payment_items_row_{{ $nextPaymentItem['id'] }}_2" style="display:none">
                        <td colspan="4">
                            {{ $viewFuncs->wrpConcatArray( [ $nextPaymentItem['payment_type_label'],
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
                        <td colspan="4" >
                            <p class="m-0 mb-4">
                            {{ $nextPaymentItem['payment_description'] }}
                            </p>
                        </td>
                    </tr>

                    <?php $odd = ! $odd; ?>

                @endforeach

            </table>

        </div>

    </div>
@endif