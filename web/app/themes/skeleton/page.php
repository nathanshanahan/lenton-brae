<?php

get_header();
?>

	<main class="site-main">

		<?php if ( have_posts() ) : ?>

			<h1><?= get_the_title() ?></h1>

			<div data-pb-context="default">
				<?php partial('partials/page-builder', null, ['sections' => get_field('sections')]); ?>
			</div>

		<?php endif; ?>

	</main>

<?php
get_sidebar();
get_footer();
