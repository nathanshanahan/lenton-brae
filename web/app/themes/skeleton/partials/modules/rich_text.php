<?php
$block = 'm-rich-text';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$content = $args['content'];
?>

<div <?= atts_to_str($atts) ?>>
	<?php if (!empty($content)) : ?>
		<?php get_template_part('partials/user-html', '', [
			'content' => $content,
			'class' => "{$block}__content"
		]) ?>
	<?php endif ?>
</div>
