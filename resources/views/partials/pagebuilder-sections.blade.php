{{--
Shorthand for rendering a page builder of type 'sections'

Usage:
@include('partials.page-builder-sections', $sections)

--}}

@include('partials.page-builder', ['layout' => $sections ?? [], 'type' => 'sections'])
