<?php

/**
 * Handles media rendering for images, videos, and oEmbed.
 * Accepts main arg $media and additional args for HTML attributes on the wrapper <div>.
 *
 * @param array $args Contains 'media' and other arbitrary attributes.
 */

$media = $args['media'] ?? '';
if (empty($media)) {
	return;
}

// Use the display value from $args if available, otherwise fallback to $media
$display = $args['display'] ?? $media['display'] ?? 'natural';
$type = $media['type'] ?? 'image';
$media_class = 'media-container media-container--' . $display . ' media-container--' . $type;
$extra_args = array_diff_key($args, ['media' => '', 'display' => '']);

// Append or set class attribute
$extra_args['class'] = isset($extra_args['class'])
	? append_class($media_class, $extra_args['class'])
	: $media_class;

// Calculate aspect ratio and add to extra_args if display is natural
if ($display === 'natural') {
	if ($type === 'image' && !empty($media['image'])) {
		$image = $media['image'];
		$aspect_ratio = $image['width'] . '/' . $image['height'];
		$style = "--media-aspect-ratio: $aspect_ratio;";
		$extra_args['style'] = isset($extra_args['style']) ? $extra_args['style'] . ' ' . $style : $style;
	} elseif ($type === 'video' && (!empty($media['video']['mp4']) || !empty($media['video']['webm']))) {
		$width = $media['video']['mp4']['width'] ?? $media['video']['webm']['width'] ?? 1;
		$height = $media['video']['mp4']['height'] ?? $media['video']['webm']['height'] ?? 1;
		$aspect_ratio = $height . '/' . $width;
		$style = "--media-aspect-ratio: $aspect_ratio;";
		$extra_args['style'] = isset($extra_args['style']) ? $extra_args['style'] . ' ' . $style : $style;
	}
}
?>

<div <?= atts_to_str($extra_args) ?>>

	<?php if ($type == 'image') :
		if (empty($media['image'])) {
			return;
		}

		// Render image with srcset and sizes attributes
		$image = $media['image'];
		$sizes = $image['sizes'];
	?>
		<img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>" srcset="
            <?= $sizes['thumbnail'] ?> <?= $sizes['thumbnail-width'] ?>w, 
            <?= $sizes['medium'] ?> <?= $sizes['medium-width'] ?>w, 
            <?= $sizes['large'] ?> <?= $sizes['large-width'] ?>w
        " sizes="(max-width: 150px) 150px, 
            (max-width: 300px) 300px, 
            708px" width="<?= $image['width'] ?>" height="<?= $image['height'] ?>">

	<?php elseif ($type == 'video') :
		if (empty($media['video']['mp4']) && empty($media['video']['webm'])) {
			return;
		}

		// Render video element with sources and options
		$webm_video = $media['video']['webm'];
		$mp4_video = $media['video']['mp4'];
		$video_options = $media['video_options'];
	?>
		<video <?= $video_options['autoplay'] == '1' ? 'data-autoplay="true" autoplay playsinline muted ' : '' ?> <?= $video_options['loop'] == '1' ? 'loop ' : '' ?> <?= $video_options['controls'] == '1' ? 'controls ' : '' ?> <?= $video_options['lazy_load'] ? 'loading="lazy"' : '' ?> width="<?= $webm_video['width'] ?? $mp4_video['width'] ?? ''; ?>" height="<?= $webm_video['height'] ?? $mp4_video['height'] ?? ''; ?>">
			<?php if (!empty($webm_video['url'])) : ?>
				<source src="<?= $webm_video['url'] ?>" type="<?= $webm_video['mime_type'] ?>">
			<?php endif; ?>
			<?php if (!empty($mp4_video['url'])) : ?>
				<source src="<?= $mp4_video['url'] ?>" type="<?= $mp4_video['mime_type'] ?>">
			<?php endif; ?>
			Your browser does not support the video tag.
		</video>

	<?php elseif ($type == 'oembed') :
		if (empty($media['oembed'])) {
			return;
		}

		// Render oEmbed iframe with modified URL parameters and attributes
		$video_options = $media['video_options'];
		$iframe = $media['oembed'];

		// Extract and modify src URL with parameters
		preg_match('/src="(.+?)"/', $iframe, $matches);
		$src = $matches[1];
		$params = array(
			'controls' => $video_options['controls'] == '1' ? 1 : 0,
			'autoplay' => $video_options['autoplay'] == '1' ? 1 : 0,
			'muted' => $video_options['autoplay'] == '1' ? 1 : 0,
			'playsinline' => 1,
			'loop' => $video_options['loop'] == '1' ? 1 : 0,
		);
		$new_src = add_query_arg($params, $src);
		$iframe = str_replace($src, $new_src, $iframe);

		// Add iframe attributes
		$attributes = 'frameborder="0"';
		if ($video_options['lazy_load'] ?? false) {
			$attributes .= ' loading="lazy"';
		}

		if ($video_options['autoplay'] == '1') {
			$attributes .= ' data-autoplay="true"';
		}

		$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

		echo $iframe;

	endif; ?>
</div>