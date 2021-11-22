<?php
	// Was language post data submitted?
	if ($_POST['en'] || $_POST['fr']) {

		// Switch languages fallback (in case JS is disabled)
		$french = strpos($_SERVER['REQUEST_URI'], '/fr/');
		$english = strpos($_SERVER['REQUEST_URI'], '/en/');
		if ($french === false && $english === false) {
			$new_uri = $_POST['en'] ? get_home_url() . '/en/' : get_home_url() . '/fr/';
		} else {
			if ($_POST['en']) {
				$new_uri = str_replace('/fr/', '/en/', $_SERVER['REQUEST_URI']);
			} elseif ($_POST['fr']) {
				$new_uri = str_replace('/en/', '/fr/', $_SERVER['REQUEST_URI']);
			}
		}

		// Redirection
		header('Location: '. $new_uri);
	}

	if (is_french()) {
		setlocale(LC_ALL, 'fr_FR.utf8');
	}
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8" lang="<?php the_language_code(); ?>"><![endif]-->
<!--[if gt IE 8]><!--><html lang="<?php the_language_code(); ?>"><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta property="og:image" content="<?php echo home_url( '/' ); ?><?='assets/fp-share-logo.png' ?>"/>
	<title><?php
		global $page, $paged;
		__the_field('site_name', 'option');
		if ( $paged >= 2 || $page >= 2 )
			echo ' | Page ' . max( $paged, $page );
		__wp_title();
		?></title>

	<link rel="apple-touch-icon" href="<?php echo home_url( '/' ); ?>apple-touch-icon.png">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header class="header clear">
		<div class="top clear">
			<form autocomplete="off" method="post" class="group">
				<button name="en" value="1" type="submit"<?php if (!is_french()) : ?> class="active"<?php endif; ?>><?php __the_field('english', 'option'); ?></button>/<button name="fr" value="1" type="submit"<?php if (is_french()) : ?> class="active"<?php endif; ?>><?php __the_field('french', 'option'); ?></button>
			</form>
			<div class="buttons">
				<button id="menu" class="active group"><?php __the_field('menu', 'option'); ?><span class="icon-bars"></span></button>
				<button id="close" class="group" ><?php __the_field('close', 'option'); ?><span class="icon-times"></span></button>
			</div>
		</div>
		<div class="logo group<?php if (is_french()) :?> french<?php endif; ?>">
			<a href="<?php echo home_url( '/' . (is_french() ? 'fr' : '') ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/logo<?php echo (is_french()) ? '-fr' : ''; ?>.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
		</div>
		<nav id="nav" class="nav group">
			<?php if ( have_rows('menu', 'option') ) : ?>
				<ul>
					<?php while ( have_rows('menu', 'option') ) : the_row(); ?>
						<?php $link = get_sub_field('menu_link'); ?>
						<?php if ($link) : ?>
							<?php
								// Translation home issue fix
								$link_url = get_the_permalink($link);
								if ($link_url === home_url('/') && is_french()) {
									$link_url .= 'fr/';
								}

								// Add active link
								global $post;
								$post_slug = $post->post_name;
								$link_slug = $link->post_name;
							?>
							<li>
								<?php if ( !get_sub_field('has_submenu') ) : ?>
									<a <?php if ($post_slug == $link_slug) : ?>class="active" <?php endif; ?>href="<?php echo $link_url; ?>"<?php if (get_sub_field('menu_open_in_new_tab')) : ?> target="_blank"<?php endif; ?>>
								<?php endif; ?>
								<span>
									<?php
										if (__get_sub_field('menu_title')) {
											__the_sub_field('menu_title');
										} else {
											echo __get_the_title($link);
										}
									?>
								</span>
								<?php if ( !get_sub_field('has_submenu') ) : ?>
									</a>
								<?php endif; ?>
								<?php if ( get_sub_field('has_submenu') ) : ?>
									<?php if ( have_rows('menu_submenu') ) : ?>
										<ul>
											<?php while ( have_rows('menu_submenu') ) : the_row(); ?>
												<li>
													<?php
														$link = get_sub_field('submenu_link');
														$link_slug = $link->post_name;
													?>
													<a <?php if ($post_slug == $link_slug) : ?>class="active" <?php endif; ?>href="<?php echo get_the_permalink($link); ?>"<?php if (get_sub_field('submenu_open_in_new_tab')) : ?> target="_blank"<?php endif; ?>>
														<span>
															<?php
																if (__get_sub_field('submenu_title')) {
																	__the_sub_field('submenu_title');
																} else {
																	echo __get_the_title($link);
																}
															?>
														</span>
													</a>
												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								<?php endif; ?>
							</li>
						<?php endif; ?>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
		</nav>
	</header>

	<div role="main">
