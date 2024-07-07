<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php //partial('partials/tracking-head'); 
	?>
</head>

<body <?php body_class(); ?>>
	<?php partial('partials/svg-symbols'); ?>

	<?php partial('partials/tracking-body');
	?>

	<?php partial('partials/masthead'); ?>