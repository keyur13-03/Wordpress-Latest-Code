<?php get_header(); ?>

			<div class="content">
				<section class="home-banner">
					<?php $banner = get_field('home_banner', 2); ?>
					<div class="background" style="background-image: url(<?php echo $banner['url']; ?>);"></div>
					<div class="title group spacer">
						<h1 class="home"><?php __the_field('home_banner_title', 2); ?></h1>
						<p><?php __the_field('home_banner_description', 2); ?></p>
					</div>
				</section>
					<section id="covid19" class="covid group spacer">
					<div >
						<?php __the_field('covid_message', 2); ?>
					</div>
				</section>
				<section class="welcome group spacer">
					<div class="left">
						<?php __the_field('home_content', 2); ?>
						<p><a class="button" href="<?php the_field('home_main_link', 2); ?>"><?php __the_field('home_main_link_title', 2); ?></a></p>
					</div>
					<div class="right group">
						<?php if ( have_rows('home_logos', 2) ) : ?>
							<?php while ( have_rows('home_logos', 2) ) : the_row(); ?>
								<?php $logo = get_sub_field('logo_image_' . (is_french() ? 'fr' : 'en')); ?>
								<img src="<?php echo $logo['url']; ?>" alt="<?php __the_sub_field('logo_title'); ?>" />
							<?php endwhile; ?>
						<?php endif; ?>
						<p><a class="button" href="<?php the_field('home_main_link', 2); ?>"><?php __the_field('home_main_link_title', 2); ?></a></p>
					</div>
				</section>
				<?php
				/*
					<section class="videos mobile-group spacer">
						<div class="info tablet-group">
							<h3><?php __the_field('home_videos_title', 2); ?></h3>
							<p><?php __the_field('home_videos_description', 2); ?></p>
						</div>
						<?php if ( have_rows('home_videos', 2) ) : $i = 0; ?>
							<div class="video-cont">
								<div id="videos" class="inside">
									<?php while ( have_rows('home_videos', 2) ) : the_row(); $i++; ?>
										<div class="video<?php if ($i > 1) : ?> hidden<?php endif; ?>" data-order="<?php echo $i; ?>">
											<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/<?php __the_sub_field('video_youtube_id'); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
							<div class="controls tablet-group">
								<button id="prev" class="prev icon-angle-left"></button><button id="next" class="next icon-angle-right"></button>
							</div>
						<?php endif; ?>
					</section>
					*/
				?>
				<section class="videos mobile-group spacer">
						<div class="info tablet-group">
							<h3><?php __the_field('home_videos_title', 2); ?></h3>
							<p><?php __the_field('home_videos_description', 2); ?></p>
						</div>
						<?php if ( have_rows('home_videos', 2) ) : $i = 0; ?>
							<div class="video-cont">
								<div id="videos" class="inside">
									<?php while ( have_rows('home_videos', 2) ) : the_row(); $i++; ?>


										<div class="video<?php if ($i > 1) : ?> hidden<?php endif; ?>" data-order="<?php echo $i; ?>">

											<video class="iframe" width="520" height="315" controls="true"   poster="<?php __the_sub_field('video_poster_link'); ?>"><source src="<?php __the_sub_field('video_link'); ?>" type="video/mp4">Your browser does not support HTML5 video.</video>

                                        <p class="video-caption"><strong><?=  __the_sub_field('video_sub_title');?></strong><br>
                                        <span><?=__the_sub_field('video_sub_description');?> </span></p>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
							<div class="controls tablet-group">
								<button id="prev" class="prev icon-angle-left"></button><button id="next" class="next icon-angle-right"></button>
							</div>
						<?php endif; ?>
					</section>
				<section class="tech-question tablet-spacer">
					<div class="left">
						<?php $graphic = get_field('home_technology_graphic', 2); ?>
						<img src="<?php echo $graphic['url']; ?>" alt="<?php __the_field('home_technology_title', 2); ?>" />
						<div class="inside">
							<h4><?php __the_field('home_technology_title', 2); ?></h4>
							<p><a class="button" href="<?php the_field('home_technology_link', 2); ?>"><?php __the_field('home_technology_link_title', 2); ?></a></p>
						</div>
					</div>
					<div class="right group spacer">
						<h3><?php __the_field('home_question_title', 2); ?></h3>
						<p class="description"><?php __the_field('home_question_description', 2); ?></p>
						<?php if ( have_rows('home_question_links', 2) ): ?>
							<?php while ( have_rows('home_question_links', 2) ) : the_row(); ?>
								<p><a class="button" href="<?php the_sub_field('question_link'); ?>"><?php __the_sub_field('question_link_title'); ?></a></p>
							<?php endwhile; ?>
						<?php endif; ?>
					</div>
				</section>
			</div>

<?php get_footer(); ?>
