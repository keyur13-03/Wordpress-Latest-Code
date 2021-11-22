<?php get_header(); ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php $banner = get_field('banner_image', 240); ?>
			<section class="banner spacer" style="background-image: url(<?php echo $banner['url']; ?>);"></section>
			
			<section class="content clear">
				<article class="team article spacer group full">
					<?php $image = get_field('team_photo'); ?>
					<div class="image" style="background-image: url(<?php echo $image['sizes']['large'] ?>);"></div>
					<div class="inside">
						<h1><?php __the_title(); ?></h1>
						<?php __the_content(); ?>
						<p><a class="button" href="<?php echo get_permalink(240); ?>"><?php __the_field('back_to_team', 'option'); ?></a></p>
					</div>
				</article>
			</section>

		<?php endwhile; ?>

<?php get_footer(); ?>
