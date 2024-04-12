<?php

/**
 * This is a wrapper around get_template_part() that allows you to filter the arguments passed to the template.
 */
function partial(string $slug, string|null $name = null, array $args = []) {

	$filter_name = 'args:' . $slug;
	if ($name) {
		$filter_name .= '-' . $name;
	}

	$args = apply_filters($filter_name, $args, $slug, $name);

	get_template_part($slug, $name, $args);
}

/*
 * @param $id === integer|mixed. Attachment ID, object, or array
 * @param $size === string. Should be a valid image size
 * @param $atts === array. Attributes to add to img tag (including src)
 * @param $is_lazy === array. Flag if should be lazily loaded (defaults to true)
 *
 * @return string. Completed <img>
 */
function make_img( $img, $size = 'full', $atts = [], $is_lazy = true ) {
	$id = post_id($img);

	$defaults = [
		'class' => '',
	];

	$atts = wp_parse_args($atts, $defaults);

	$details = wp_get_attachment_image_src( $id, $size );
	if ( ! $details ) {
		return '';
	}

	$src = $details[0];
	$width = $details[1];
	$height = $details[2];
	$srcset = wp_get_attachment_image_srcset( $id, $size );

	if ($is_lazy) {
		$atts['data-src'] = $atts['data-src'] ?? $src;
		$atts['data-srcset'] = $atts['data-srcset'] ?? $srcset;
		$atts['class'] = append_class('lazyload', $atts['class']);

		// just in case this is set
		if (isset($atts['src'])) {
			unset($atts['src']);
		}
	}
	else {
		$atts['src'] = $atts['src'] ?? $src;
		$atts['srcset'] = $atts['srcset'] ?? $srcset;
	}

	$atts['width'] = $width;
	$atts['height'] = $height;
	if ($width >= $height) {
		$atts['class'] = append_class('is-landscape', $atts['class']);
	}
	else {
		$atts['class'] = append_class('is-portrait', $atts['class']);
	}

	// TODO: check if $img is an object/array and alt is provided, use that instead of
	// getting from post meta
	if (!isset($atts['alt'])) {
		$empty_alt = '';
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$atts['alt'] = !empty($alt) ? $alt : $empty_alt;
	}


	$element = make_elem('img',$atts);

	return $element;
}


/**
 * Outputs a HTML5 video element, with sources for various formats
 *
 * @param array<array> $formats [currently supports mp4 and webm]
 * @param array $poster
 * @param array $atts
 * @param bool $is_background
 * @return false|string
 */
function make_video($formats, $is_background = true, $atts = [], $poster = false, $is_lazy = false)
{
	// TODO: lazy loading implementation

	if (empty($formats)) {
		return false;
	}

	$default_atts = [
		'muted' => $is_background,
		'loop' => $is_background,
		'playsinline' => true,
		'autoplay' => $is_background,
		'controls' => !$is_background,
	];

	$atts = wp_parse_args($atts, $default_atts);

	if (empty($atts['poster']) && !empty($poster['url'])) {
		$atts['poster'] = $poster['url'];
	}

	$sources_html = '';
	if (!empty($formats['webm']['url'])) {
		$source = make_elem('source', [
			'src' => $formats['webm']['url'],
			'type' => 'video/webm; codecs="vp8,vp9"',
		]);

		$sources_html .= $source;
	}

	if (!empty($formats['mp4']['url'])) {
		$source = make_elem('source', [
			'src' => $formats['mp4']['url'],
			'type' => 'video/mp4',
		]);

		$sources_html .= $source;
	}


	$html = make_elem('video', $atts, $sources_html);

	return $html;
}
