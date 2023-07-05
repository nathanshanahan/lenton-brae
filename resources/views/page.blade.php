@extends('layouts.app')

@section('content')
    @while(have_posts()) @php(the_post())
        @include('partials.pagebuilder', ['type' => $pagebuilder_type])
    @endwhile
@endsection
