<?php get_header(); ?>

		<section class="content clear">
			<article class="article spacer group full">
				<h1><?php __the_field('not_found', 'option'); ?></h1>
				<p><?php __the_field('not_found_description', 'option'); ?></p>
				<?php get_search_form(); ?>
			</article>
		</section>

<?php get_footer(); ?>
