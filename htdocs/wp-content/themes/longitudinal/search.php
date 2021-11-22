<?php get_header(); ?>

		<section class="content clear">
			<article class="article spacer group full">
				<?php if ( have_posts() ) : ?>
					<h1><?php __the_field('search_results', 'option'); ?> <?php echo get_search_query(); ?></h1>
					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
						<h3><a href="<?php the_permalink(); ?>"><?php __the_title(); ?></a></h3>
						<p><?php __the_excerpt(); ?></p>
					<?php endwhile; ?>
				<?php else : ?>
					<h1><?php __the_field('nothing_found', 'option'); ?></h1>
					<p><?php __the_field('nothing_found_description', 'option'); ?></p>
				<?php endif; ?>
				<?php get_search_form(); ?>
			</article>
		</section>

<?php get_footer(); ?>
