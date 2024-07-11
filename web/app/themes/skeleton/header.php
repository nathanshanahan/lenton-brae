<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php partial('partials/tracking-head'); ?>
</head>

<?php $page_color_theme = get_field('page_color_theme', get_the_ID()) ?? 'light'; ?>

<body <?php body_class(); ?> data-color-theme="<?= $page_color_theme ?>">
	<?php partial('partials/svg-symbols'); ?>

	<?php partial('partials/tracking-body');
	?>

	<?php partial('partials/masthead'); ?>