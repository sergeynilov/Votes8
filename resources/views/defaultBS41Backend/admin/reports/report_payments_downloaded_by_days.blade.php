@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($paymentItems) )
    <button type="button" class="btn btn-error btn-lg btn-block">Has no payments</button>
@else
    <div class="row m-2">

<!--        --><?php //echo '<pre>$paymentItems::'.print_r($paymentItems,true).'</pre>';   ?>
{{--            [formatted_created_at] => 2019-07-21--}}
{{--            [quantity_count] => 14--}}

        <div class="table-responsive" style="max-height: 900px; overflow-y:auto;">
            <table class="table table-bordered table-striped text-primary ">
                @foreach ( $paymentItems as $nextPaymentItem )
                    <tr>
                        <td>
                            {{ $nextPaymentItem['item_id'] }}<a href="#" onclick="javascript:backendReports.openPaymentsByDaysReportDetails('{{ $nextPaymentItem['formatted_created_at'] }}',
                                    '{{ $nextPaymentItem['quantity_count'] }}', '{{ $filter_voted_at_from }}', '{{
                            $filter_voted_at_till  }}', '{{ $filterSelectedUsers }}')">Details</a>::{{
                            $nextPaymentItem['formatted_created_at'] }}
                        </td>
                        <td>
                            {{ $nextPaymentItem['quantity_count'] }}
                        </td>
                    </tr>
                @endforeach

{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <a href="#" onclick="javascript:backendReports.openItemsCountReportDetails('{{ $nextPaymentItem['payment_item_id'] }}',--}}
{{--                                    '{{ $nextPaymentItem['item_title'] }}', '{{ $filter_voted_at_from }}', '{{--}}
{{--                            $filter_voted_at_till  }}', '{{ $filterSelectedUsers }}')">--}}
{{--                                {{ $nextPaymentItem['item_title'] }}&nbsp;{!! $viewFuncs->showAppIcon('details','transparent_on_white', 'Details on selected position') !!}</a>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            {{ $nextPaymentItem['quantity_count'] }}--}}
{{--                        </td>--}}
{{--                    </tr>--}}

{{--                --}}
            </table>
        </div>

    </div>
@endif