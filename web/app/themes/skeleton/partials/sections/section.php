<?php
$block = 's-section';

$computed = \App\PageBuilder::computeSectionOptions($args);
$computed['class'] = prepend_class("section $block", $computed['class'] ?? '');

$modules = $args['modules'] ?? [];
?>

<div <?= atts_to_str($computed) ?>>
	<?php if (!empty($modules)) : ?>
		<?php get_template_part('partials/page-builder', null, ['modules' => $modules]); ?>
	<?php endif ?>
</div>
