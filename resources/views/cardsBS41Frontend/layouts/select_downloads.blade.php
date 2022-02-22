@inject('viewFuncs', 'App\library\viewFuncs')

@if(count($downloadsList) > 0 )
    <div class="container">

        <form method="POST" action="{{ url('/run_payment') }}" accept-charset="UTF-8" id="form_payment_execute" enctype="multipart/form-data">
            {!! csrf_field() !!}

            @foreach($downloadsList as $nextDownload)

                <div class="card mb-4">

                    <h4 class="card-title"><strong>{{ $nextDownload['title'] }}</strong></h4>
                    <div class="col-sm-12" style="display: flex; flex-direction: row;">
                        <div class="" style="display: flex; flex-direction: column;">

                            <div style="display: flex; align-content: center">
                                @if(!empty($nextDownload['extension_image_url']))
                                    <img class="pull-left single_vote_image_left_aligned" src="{{ $nextDownload['extension_image_url']  }}"
                                         alt="{!! Purifier::clean($nextDownload['title'])!!}" style="width: {{ $nextDownload['image_preview_width'] }}px; height: auto;">
                                @endif
                            </div>

                            <div style="display: flex; align-content: center">
                                <button type="button" class=" btn btn-secondary " onclick="javascript:document.location='{{ URL::route('file-download',[$nextDownload['id']]) }}'"
                                ><i class="fa fa-download"></i> &nbsp;Download
                                </button>
                            </div>
                            
                        </div>

                        <p class="card-text m-2" style="border-right: 1px dotted grey;">
                            {!! Purifier::clean($viewFuncs->nl2br2($nextDownload['description'] )) !!}
                        </p>
                    </div>

                    @if( !empty($nextDownload['price']) )
                         <div class="col-sm-12 mb-3" style="border-bottom: 1px dotted grey; display: flex;
                         flex-direction : row">

                        <div class="m-2" style="border: 0px dotted blue; flex : 1; display: flex; flex-direction : row">
                            <div class="m-2" style="display: flex; justify-content: center; align-items: center; flex-wrap: nowrap; ">
                                <input id="hidden_download_{{ $nextDownload['id'] }}_price" type="hidden" value="{{ $nextDownload['price'] }}">
                                {!! $viewFuncs->showStylingCheckbox( 'cbx_download_' . $nextDownload['id'], 1, false, $nextDownload['title'], ['onchange'=>"javascript:showPageContent.downloadOnChange
                                (this, ".$nextDownload['id']."); ", 'additive_class'=>'cbx_download' ]  ) !!}
                            </div>
                            <div class="m-2">
                                <b>{{ $viewFuncs->wrpFormattedCurrency( $nextDownload['price'] ) }}</b>
                            </div>
                        </div>

                        <div class="m-2 card-text" style="display: flex; flex : 2">
                            {!! Purifier::clean($viewFuncs->nl2br2($nextDownload['price_info'] )) !!}
                        </div>

                    </div>
                    @endif

                </div> <!-- <div class="card mb-4"> -->

            @endforeach

            <div class="alert alert-info" role="alert" id="div_downloads_select" style="display: none;">
            </div>

            <div class="alert alert-info" id="div_downloads_payment_description" style="display: none;">
                <div class="form-row mb-3">
                    <label class="col-12 col-sm-4 col-form-label"  for="payment_description">
                        Description <small>optional</small>
                    </label>
                    <div class="col-12 col-sm-8">
                        {!! $viewFuncs->textarea('payment_description', '', "form-control editable_field", ["rows"=>4] )
                         !!}
                    </div>
                </div>
            </div>


            <div class="alert alert-info" role="alert" id="div_payment_select" style="display: none; flex-direction: row">

                <div class="custom-control custom-radio custom-control-inline p-2 pl-5">
                    <input type="radio" class="custom-control-input" id="payment_type_paypal" name="payment_type" value="paypal">
                    <label class="custom-control-label" for="payment_type_paypal">
                        {!! $viewFuncs->showAppIcon('paypal', 'transparent_on_white', 'Select Paypal payment') !!} Paypal
                    </label>
                </div>
                <div class="custom-control custom-radio custom-control-inline p-2 pl-5">
                    {!! $viewFuncs->showAppIcon('stripe','selected_dashboard') !!}
                    <input type="radio" class="custom-control-input" id="payment_type_stripe" name="payment_type" value="stripe">
                    <label class="custom-control-label" for="payment_type_stripe">
                        {!! $viewFuncs->showAppIcon('stripe', 'transparent_on_white', 'Select stripe payment') !!} Stripe
                    </label>
                </div>

                <input id="hidden_selected_downloads" type="hidden" value="">

                <button type="button" class="btn btn-primary" onclick="javascript: showPageContent.onPaymentExecute() ; return false; " style=""><span
                            class="btn-label"><i class="fa fa-credit-card fa-submit-button"></i></span> &nbsp;Pay
                </button>&nbsp;&nbsp;
            </div>

        </form>

    </div>

@endif