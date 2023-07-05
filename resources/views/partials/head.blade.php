<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('utils.styles')

    {{-- TODO

    {!! App::googleAnalytics() !!}
	{!! App::googleTagManagerHead() !!}
	{!! App::facebookPixelHead() !!}

    --}}

    @php wp_head() @endphp
</head>
