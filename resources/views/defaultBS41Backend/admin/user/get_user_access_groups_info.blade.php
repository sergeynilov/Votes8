@inject('viewFuncs', 'App\library\viewFuncs')
@if(count($groups) > 0)

    @foreach($groups as $nextGroup)
        <div class="form-row mb-1 pb-1">
            <label class="col-12 col-sm-4 col-form-label" for="checked_user_access_{{ $nextGroup['id'] }}">
                {{ $nextGroup['name'] }}<br>
            </label>
            <div class="col-12 col-sm-8">
                {!! $viewFuncs->select('checked_user_access_'.$nextGroup['id'], $userGroupSelectedValueArray, !empty($nextGroup['is_checked']) ? 1 : 0, "form-control
                editable_field update_user_access chosen_select_box", [] ) !!}
            </div>
        </div>
        <div class="form-row mb-5 mt-0 pt-0">
            <small>{{ $nextGroup['description'] }}</small>
        </div>
    @endforeach


@endif