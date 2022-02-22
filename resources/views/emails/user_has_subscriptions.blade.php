@extends('beautymail::templates.widgets')

@section('content')

    @include('beautymail::templates.widgets.articleStart', ['color' => '#0000FF'])

    <h4 class="email_title">
        Hello, {!! $to_user_name !!} !
    </h4>

    <p class="email_title">Listing of your subscriptions at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site was refreshed !</p>
    <p class="email_title">Now you are subscribed at categories :
        @foreach($usersSiteSubscriptionsList as $nextUsersSiteSubscription)
            {!! $nextUsersSiteSubscription['name'] !!}<br>
        @endforeach
    </p>

    @include('beautymail::templates.widgets.articleEnd')


    @include('beautymail::templates.widgets.newfeatureStart')

    <p class="email_footer">{!! $support_signature !!}</p>

    @include('beautymail::templates.widgets.newfeatureEnd')

@stop