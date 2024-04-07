<?php
/**
 * Accepts the main arg $content, and arbitrary args to be added as html attributes
 * on the wrapper <div>
 *
 *
 * @param string 	content
 * @param *			*
 *
 */

$content = $args['content'] ?? '';
if (empty($content)) {
	return;
}

$content_class = 'user-html';
$extra_args = array_diff_key($args, ['content' => '']);

$extra_args['class'] = isset($extra_args['class'])
	? append_class($content_class, $extra_args['class'])
	: $content_class;
?>

<div <?= atts_to_str($extra_args) ?>>
	<?= $content ?>
</div>
