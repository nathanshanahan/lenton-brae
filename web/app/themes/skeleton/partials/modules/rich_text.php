<?php
$block = 'm-rich-text';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$content = $args['content'];
?>

<div <?= atts_to_str($atts) ?> data-reveal="up">
	<?php if (!empty($content)) : ?>
		<?php partial('partials/user-html', '', [
			'content' => $content,
			'class' => "{$block}__content"
		]) ?>
	<?php endif ?>
</div>
