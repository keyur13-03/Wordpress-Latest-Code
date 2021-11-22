	</div>
	<footer class="footer group">
		<button id="top" class="icon-angle-up"></button>
		<div class="inside">
			<nav class="bottom">
				<?php if ( have_rows('footer_menu', 'option') ) : ?>
					<?php while ( have_rows('footer_menu', 'option') ) : the_row(); ?>
						<?php $link = get_sub_field('menu_link'); ?>
						<?php if ($link) : ?>
							<?php
								// Translation home issue fix
								$link_url = get_the_permalink($link);
								if ($link_url === home_url('/') && is_french()) {
									$link_url .= 'fr/';
								}
							?>
							<a href="<?php echo $link_url; ?>"<?php if (get_sub_field('menu_open_in_new_tab')) : ?> target="_blank"<?php endif; ?>><?php
								if (__get_sub_field('menu_title')) {
									__the_sub_field('menu_title');
								} else {
									echo __get_the_title($link);
								}
							?></a><span class="icon-dot"></span>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>
			</nav>
			<div class="logos">
				<?php if ( have_rows('footer_logos', 'option') ): ?>
					<?php while ( have_rows('footer_logos', 'option') ) : the_row(); ?>
						<?php $image = get_sub_field('logo_image_' . (is_french() ? 'fr' : 'en')); ?>
						<a href="<?php __the_sub_field('logo_link'); ?>" target="_blank">
							<img src="<?php echo $image['url']; ?>" alt="<?php __the_sub_field('logo_title'); ?>" />
						</a>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
