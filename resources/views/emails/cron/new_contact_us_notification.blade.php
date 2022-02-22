@extends('beautymail::templates.widgets')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    @include('beautymail::templates.widgets.articleStart', ['color' => '#0000FF'])

    <h4 class="email_title">
        Hello, {{ $receiver_username }} !
    </h4>

    <p class="email_title">There are new Contact Us Messages at <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site.</p>

    <p class="email_subtitle">You can review/accept/delete them by links :</p>
    
    @foreach($newContactUsList as $nextNewContactUs)
        <p class="email_subtitle">
            <a href="{{ route('admin.contact_us_editor', $nextNewContactUs->id) }}" target="_blank" class="a_link"> By {{ $nextNewContactUs->author_name }} at {{
            $viewFuncs->getFormattedDateTime($nextNewContactUs->created_at) }}</a>
        </p>
    @endforeach

    <p class="email_subtitle mt-5">
        You have received this email as you are an admin of <a href="{{ $site_home_url }}" target="_blank" class="a_link">{{ $site_name }}</a> site.
    </p>

    @include('beautymail::templates.widgets.articleEnd')


    @include('beautymail::templates.widgets.newfeatureStart')

    <p class="email_footer">{!! $support_signature !!}</p>

    @include('beautymail::templates.widgets.newfeatureEnd')

@stop