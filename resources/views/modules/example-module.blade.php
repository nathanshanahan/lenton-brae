@extends('layouts.module')

@section('module-content')

<div class="{{ $block }}">
<h2>Example module</h2>

@if (!empty($content))
	{!! $content !!}
@endif
</div>

@overwrite


{{-- For computed classes and atts see the view composer and \App\Bone\PageBuilder::computeModuleOptions --}}
@section('module-classes'){!! $module_computed_classes ?? '' !!}@overwrite
@section('module-attributes'){!! $module_computed_attributes ?? '' !!}@overwrite
