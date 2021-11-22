<?php get_header(); ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php $banner = get_field('banner_image'); ?>
			<section class="banner spacer" style="background-image: url(<?php echo $banner['url']; ?>);"></section>
			
			<?php $faq = false; $count = 0 ?>
			<?php $section = false; ?>
			<?php if ( have_rows('layout') ) : ?>
				<?php while ( have_rows('layout') ) : the_row(); ?>
					<?php if ( get_row_layout() == 'layout_faq' ) : $faq = true; // FAQ ?>
						<section class="dropdown mobile">
							<ul class="spacer group">
								<li><span class="drop" id="dropdown"><?php __the_title(); ?><span class="icon-angle-down"></span></span>
									<ul>
										<?php if ( have_rows('faq') ) : $i = 0; ?>
											<?php while ( have_rows('faq') ) : the_row(); $i++; ?>
												<li><a class="anchor" href="#<?php echo sanitize_title(__get_sub_field('faq_title')); ?><?php echo $i; ?>"><?php __the_sub_field('faq_title'); ?></a></li>
											<?php endwhile; ?>
										<?php endif; ?>
									</ul>
								</li>
							</ul>
						</section>

					<?php elseif ( get_row_layout() == 'layout_section' && $count == 0 ) : $section = true; // Section ?> <!-- Modified 2019-05-09-->
						<section class="dropdown mobile">
							<ul class="spacer group">
								<li><span class="drop" id="dropdown"><?php __the_title(); ?><span class="icon-angle-down"></span></span>
									<ul>
										<?php if ( have_rows('section') ) : $i = 0; ?>
											<?php while ( have_rows('section') ) : the_row(); $i++; ?>
												<li><a class="anchor" href="#<?php echo sanitize_title(__get_sub_field('section_title')); ?><?php echo $i; ?>"><?php __the_sub_field('section_title'); ?></a></li>
											<?php endwhile; ?>
										<?php endif; ?>
									</ul>
								</li>
							</ul>
						</section>
                    <?php $count++; ?> <!-- Modified -->
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
			
			<section class="content clear">
				<article class="article spacer group<?php if (!get_field('sidebar_content') && !$faq && !$section) : ?> full<?php endif;?>">
					<h1><?php __the_title(); ?></h1>
					<?php __the_content(); ?>

					<?php if ( have_rows('layout') ) : ?>
						<?php while ( have_rows('layout') ) : the_row(); ?>

							<?php if ( get_row_layout() == 'layout_faq' ) : // FAQ ?>

								<?php if ( have_rows('faq') ) : $i = 0; ?>
									<div class="faq">
										<?php while ( have_rows('faq') ) : the_row(); $i++; ?>
											<h3 id="<?php echo sanitize_title(__get_sub_field('faq_title')); ?><?php echo $i; ?>"><?php __the_sub_field('faq_title'); ?></h3>
											<p><?php the_sub_field('faq_description'); ?></p>
											<?php if ( have_rows('faq_questions') ) : ?>
												<div class="questions">
													<?php while ( have_rows('faq_questions') ) : the_row(); ?>
														<div class="question">
															<h6 class="question-title"><?php __the_sub_field('question_title'); ?><span class="icon-plus"></span></h6>
															<div class="description">
																<?php __the_sub_field('question_description'); ?>
															</div>
														</div>
													<?php endwhile; ?>
												</div>
											<?php endif; ?>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>

							<?php elseif ( get_row_layout() == 'layout_section' ) : // Section ?>

								<?php if ( have_rows('section') ) : $i = 0; ?>
									<div class="section">
										<?php while ( have_rows('section') ) : the_row(); $i++; ?>
											<h3 id="<?php echo sanitize_title(__get_sub_field('section_title')); ?><?php echo $i; ?>"><?php __the_sub_field('section_title'); ?></h3>
											<?php __the_sub_field('section_content'); ?>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>

							<?php elseif ( get_row_layout() == 'layout_two_column' ) : // Two Column ?>

								<?php if ( have_rows('row') ) : ?>
									<?php while ( have_rows('row') ) : the_row(); ?>
										<div class="two-column clear">
											<h6><?php __the_sub_field('row_title'); ?></h6>
											<div class="column image">
												<?php $image = get_sub_field('row_image'); ?>
												<img src="<?php echo $image['url']; ?>" alt="<?php __the_sub_field('row_title'); ?>" />
											</div>
											<div class="column">
												<?php __the_sub_field('row_content'); ?>
											</div>
										</div>
									<?php endwhile; ?>
								<?php endif; ?>

							<?php elseif ( get_row_layout() == 'layout_content' ) : // Content ?>

								<?php __the_sub_field('additional_content'); ?>

							<?php elseif ( get_row_layout() == 'layout_team' ) : // Team Members Listing ?>

								<div class="team-members clear">
									<?php
										$args = array(
											'post_type' => 'team',
											'posts_per_page' => -1,
											'orderby' => 'menu_order'
										);
										$the_query = new WP_Query( $args );
									?>
									<?php if ( $the_query->have_posts() ) : ?>
									<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
										<div class="member">
											<?php $image = get_field('team_photo'); ?>
											<div class="image" style="background-image: url(<?php echo $image['sizes']['medium'] ?>);"></div>
											<h4><?php __the_title(); ?></h4>
											<p><a class="yellow button" href="<?php the_permalink(); ?>"><?php __the_field('read_bio', 'option'); ?></a></p>
										</div>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
									<?php endif; ?>
								</div>

							<?php elseif ( get_row_layout() == 'layout_callout' ) : // Callout ?>

								<div class="callout group spacer">
									<?php __the_sub_field('callout_content'); ?>
									<?php if ( have_rows('callout_links') ): ?>
										<p>
											<?php while ( have_rows('callout_links') ) : the_row(); ?>
												<a class="button" href="<?php the_sub_field('callout_link'); ?>"><?php __the_sub_field('callout_link_title'); ?></a>
											<?php endwhile; ?>
										</p>
									<?php endif; ?>
								</div>

							<?php elseif ( get_row_layout() == 'layout_videos' ) : // Videos?>
								<?php if ( have_rows('video') ) : ?>
									<div class="video-layout clear">
										<?php while ( have_rows('video') ) : the_row(); ?>
											<div class="video-cont <?php the_sub_field('custom_css'); ?>">
												 <?php  echo __get_sub_field('video_poster_link_en'); ?>
												<div class="video">
													<?php  if(__get_sub_field('video_youtube_id') !== '') { ?>
													<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/<?php __the_sub_field('video_youtube_id'); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
													<?php } else { ?>
													<video id="video-<?=get_row_index(); ?>" class="iframe"  width="560" height="315" controls="true"  poster="<?php __the_sub_field('video_poster_link'); ?>" muted="">
													  <source src="<?php __the_sub_field('video_link'); ?>" type="video/mp4">
													  Your browser does not support HTML5 video.
													</video>
													<?php } ?>
												</div>
												<h6><?php __the_sub_field('video_title'); ?></h6>
												<p><?php __the_sub_field('video_description'); ?></p>
											</div>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>

							<?php endif; ?>

						<?php endwhile; ?>
					<?php endif; ?>

				</article>
				<?php if (__get_field('sidebar_content') || $faq || $section) : ?>
					<aside>
						<?php if ($faq || $section) : // FAQ Links ?>
							<div class="faq group spacer larger desktop">
								<h5><?php __the_title(); ?></h5>
								<?php if ( have_rows('layout') ) : ?>
									<?php while ( have_rows('layout') ) : the_row(); ?>

										<?php if ( have_rows('faq') ) : $i = 0; // FAQ ?>

											<?php while ( have_rows('faq') ) : the_row(); $i++; ?>
												<p><a class="anchor" href="#<?php echo sanitize_title(__get_sub_field('faq_title')); ?><?php echo $i; ?>"><?php __the_sub_field('faq_title'); ?></a></p>
											<?php endwhile; ?>

										<?php elseif ( have_rows('section') ) : $i = 0; // Section ?>

											<?php while ( have_rows('section') ) : the_row(); $i++; ?>
												<p><a class="anchor" href="#<?php echo sanitize_title(__get_sub_field('section_title')); ?><?php echo $i; ?>"><?php __the_sub_field('section_title'); ?></a></p>
											<?php endwhile; ?>

										<?php endif; ?>

									<?php endwhile; ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if (__get_field('sidebar_content')) : // Sidebar ?>
							<div class="inside group spacer larger">
								<?php __the_field('sidebar_content'); ?>
								<?php if ( have_rows('sidebar_links') ): ?>
									<?php while ( have_rows('sidebar_links') ) : the_row(); ?>
										<p><a class="button" href="<?php the_sub_field('sidebar_link'); ?>"><?php __the_sub_field('sidebar_link_title'); ?></a></p>
									<?php endwhile; ?>
								<?php endif; ?>
							</div>
							<?php if (__get_field('sidebar_content_video')) : // Sidebar ?>
							<div class="faq group spacer larger">
								<?php __the_field('sidebar_content_video'); ?>
							</div>
						<?php endif; ?>
						<?php endif; ?>

					</aside>
				<?php endif; ?>
			</section>
		<?php endwhile; ?>

<?php get_footer(); ?>
