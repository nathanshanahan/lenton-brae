@php
$block = 'masthead';
@endphp
<header class="{{ $block }}">
	@if (has_nav_menu('primary_navigation'))
    	@php wp_nav_menu() @endphp
	@endif
</header>
