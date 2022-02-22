@inject('viewFuncs', 'App\library\viewFuncs')

@if( empty($paymentItems) )
    <button type="button" class="btn btn-error btn-lg btn-block">Has no payments</button>
@else
    <div class="row m-2">

{{--        $filter_voted_at_from:{{ $filter_voted_at_from }}<br>--}}
{{--        $filter_voted_at_till:{{ $filter_voted_at_till }}<br>--}}
{{--        $filterSelectedUsers:{{ $filterSelectedUsers }}<br>--}}
{{--        $filter_report_type:{{ $filter_report_type }}<br>--}}

{{--        <div class="table-responsive" style="max-height: 900px; overflow-y:auto;">--}}
        <div class="table-responsive" style="max-height: 100%; overflow-y:auto;">
            <table class="table table-bordered table-striped text-primary ">
                @foreach ( $paymentItems as $nextPaymentItem )
                    
                    <tr>
                        <td>
                            <a href="#" onclick="javascript:backendReports.openItemsCountReportDetails('{{ $nextPaymentItem['payment_item_id'] }}',
                                    '{{ $nextPaymentItem['item_title'] }}', '{{ $filter_voted_at_from }}', '{{
                            $filter_voted_at_till  }}', '{{ $filterSelectedUsers }}')">
                            {{ $nextPaymentItem['item_title'] }}&nbsp;{!! $viewFuncs->showAppIcon('details','transparent_on_white', 'Details on selected position') !!}</a>
                        </td>
                        <td>
                            {{ $nextPaymentItem['quantity_count'] }}
                        </td>
                    </tr>

                @endforeach
            </table>
        </div>

    </div>
@endif