@if (!empty($layout) && is_array($layout))
	@foreach ($layout as $item)
		@include($type . '.' . $item    ['acf_fc_layout'], $item)
	@endforeach
@endif
