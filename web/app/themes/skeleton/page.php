<?php

get_header();
?>

<main class="site-main swup-page-loader">

	<?php if (have_posts()) : ?>

		<div data-pb-context="default">
			<?php partial('partials/page-builder', null, ['sections' => get_field('sections')]); ?>
		</div>

	<?php endif; ?>

</main>

<?php
get_sidebar();
get_footer();
