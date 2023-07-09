@extends('layouts.section')

@section('section-content')

<div class="{{ $block }}">
	<h2>Example section</h2>

	<div class="content">
		@include('partials.pagebuilder-modules', $modules)
	</div>
</div>

@overwrite


{{-- For computed classes and atts see the view composer and \App\Bone\PageBuilder::computeSectionOptions --}}
@section('section-classes'){!! $section_computed_classes ?? '' !!}@overwrite
@section('section-attributes'){!! $section_computed_attributes ?? '' !!}@overwrite
