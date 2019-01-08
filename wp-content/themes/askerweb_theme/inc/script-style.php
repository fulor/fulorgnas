<?php
 
// Load jQuery
if ( ! function_exists( 'core_mods' ) ) {
	function core_mods() {
		if ( ! is_admin() ) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', (get_template_directory_uri() ."/inc/js/jquery-1.8.3.min.js"), false);
			wp_enqueue_script('jquery');
		}
	}
	//core_mods();
}
 
function _scripts_and_styles() {
	//core_mods();

	wp_register_script( 'libs', get_template_directory_uri() . '/inc/js/libs.min.js', array(), '05102018', true );
	wp_register_script( 'template', get_template_directory_uri() . '/inc/js/template.min.js', array(), '06102018', true );
	wp_enqueue_script( 'libs' );
	wp_enqueue_script( 'template' );


	wp_register_style( 'style', get_template_directory_uri() .'/inc/css/style.min.css', array(), '05102018' );
	wp_register_style( 'style-bootstrap', get_template_directory_uri() .'/inc/assets/libs.min.css', array(), '05102018' );
//	if(is_front_page()) {
//		wp_register_style('home', get_template_directory_uri() . '/inc/css/home.min.css', array(), '280120171');
//		wp_enqueue_style( 'home' );
//	}
	wp_enqueue_style( 'style' );
	wp_enqueue_style( 'style-bootstrap' );

	if (!is_admin()){
		remove_action( 'wp_head',             'print_emoji_detection_script',     7    );
		remove_action( 'wp_print_styles',     'print_emoji_styles'                     );
		remove_action( 'wp_head',                    'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head',             'wp_resource_hints',               2     );
		wp_deregister_script('wp-embed');
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}

add_action( 'wp_enqueue_scripts', '_scripts_and_styles' );
function wpb_disable_feed() {
	wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}

add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);