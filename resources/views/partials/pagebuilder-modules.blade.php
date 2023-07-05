{{--
Shorthand for rendering a page builder of type 'modules'

Usage:
@include('partials.page-builder-modules', $modules)

--}}

@include('partials.pagebuilder', ['layout' => $modules ?? [], 'type' => 'modules'])
