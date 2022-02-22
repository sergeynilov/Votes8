@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($paymentAgreements) == 0)
    <h4 class="card-subtitle pl-2 pb-2">You have no payment agreements yet. </h4>
@else
    <h4 class="card-subtitle pl-2 pb-2">You have {{ count($paymentAgreements) }} payment agreement(s)</h4>

    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
        <table class="table table-bordered table-striped text-primary ">
            <thead>
                <tr>
                    <th>Payment type</th>
                    <th>Payment agreement Id</th>
                    <th>Start Date</th>
                </tr>
            </thead>
            <tbody>

            @foreach($paymentAgreements as $next_key => $nextPaymentAgreement)
                <tr>

                    <td>
                        {{ $nextPaymentAgreement['id'] }}::{{ $nextPaymentAgreement['name'] }}<br>
                        {{ $viewFuncs->wrpGetPaymentTypeLabel( $nextPaymentAgreement['payment_type'] ) }}
                    </td>

                    <td>
                        <a class="toggle_link" onclick="javascript:frontendProfile.showPaymentAgreementDetails( '{{ $nextPaymentAgreement['id'] }}',
                                '{{$nextPaymentAgreement['payment_agreement_id']}}'); "
                           id="a_payment_agreements_row_{{ $nextPaymentAgreement['id'] }}_show_block">
                            {!! $viewFuncs->showAppIcon('toggle_on','transparent_on_white', 'Show details') !!}
                        </a>

                        {{ $nextPaymentAgreement['payment_agreement_id'] }}
                    </td>
                    <td>
                        {{ $viewFuncs->getFormattedDateTime($nextPaymentAgreement['start_date']) }}
                    </td>

                </tr>
            @endforeach
            </tbody>

        </table>
        <input type="button" class="btn btn-primary btn-sm" value="Refresh"
               onclick="javascript:frontendProfile.loadPaymentAgreements(); return false;">

    </div>

@endif