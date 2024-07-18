<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php partial('partials/tracking-head'); ?>

	<style>
		[data-color-theme="textured"] {
			--background-image: url('../assets/background-texture-8a90bd8d.webp');
		}
	</style>

	<script type="text/javascript">
		<?php $site_url = get_site_url(); ?>

		var load_more_params = {
			"ajaxurl": "<?= $site_url ?>\/wp-admin\/admin-ajax.php",
			"posts_per_page": 9
		};
	</script>
</head>

<?php $page_color_theme = 'color-theme--' . get_field('page_color_theme', get_the_ID()) ?? 'light'; ?>



<body <?php body_class($page_color_theme); ?>>
	<?php partial('partials/svg-symbols'); ?>

	<?php partial('partials/tracking-body');
	?>

	<?php partial('partials/masthead'); ?>