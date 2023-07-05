@php
$block = 'masthead';
@endphp
<header class="{{ $block }}">
    @php wp_nav_menu() @endphp
</header>
