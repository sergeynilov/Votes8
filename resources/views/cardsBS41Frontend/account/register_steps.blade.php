<div class="form-row col-12 bs-wizard p-1 m-1" style="border-bottom:0; width: 100%;">
    {{--$current_step::{{ $current_step }}--}}
    <div class="col-4 bs-wizard-step  @if($current_step > 1) complete @endif @if($current_step == 1) active @endif ">
        <div class="text-center bs-wizard-stepnum pl-3 pr-3">Your Details</div>
        <div class="progress" ><div class="progress-bar"></div></div>
        <span class="bs-wizard-dot"></span>
    </div>

    <div class="col-4 bs-wizard-step @if($current_step > 2) complete @endif @if($current_step == 2) active @endif">
        <div class="text-center bs-wizard-stepnum pl-3 pr-3">Your Avatar</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <span class="bs-wizard-dot"></span>
    </div>

    <div class="col-4 bs-wizard-step @if($current_step > 3) complete @endif @if($current_step == 3) active @endif">
        <div class="text-center bs-wizard-stepnum pl-3 pr-3">
{{--            {{ URL::route('account-register-subscriptions') }}--}}
            Subscriptions
            {{--/account/register/subscriptions  account-register-subscriptions-post--}}
        </div>
        <div class="progress"><div class="progress-bar"></div></div>
        <span class="bs-wizard-dot"></span>
        <span class="bs-wizard-dot"></span>
    </div>

    <div class="col-4 bs-wizard-step @if($current_step > 4) complete @endif @if($current_step == 4) active @endif"><!-- complete -->
        <div class="text-center bs-wizard-stepnum pl-3 pr-3">Confirm Data</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <span class="bs-wizard-dot"></span>
        <span class="bs-wizard-dot"></span>
    </div>
</div>
{{--https://bootsnipp.com/snippets/gjm35--}}