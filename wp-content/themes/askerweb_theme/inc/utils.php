<?php
/**
 * Theme utilities functions
 *
 * @package WordPress
 * @subpackage Renuzit
 */


/* =Add Theme Options support
 * currently OptionsTree id used
-------------------------------------------------------------- */

/**
 * Output theme meta value
 *
 */
function theme_option( $key, $default = '', $filter = '' ) {
	$value = get_theme_option( $key, $default );
	switch ($filter) {
		case 'wpautop':
			$value = wpautop($value);
			break;
	}

	echo $value;
}

/**
 * Get theme option
 *
 * @uses of_get_option
 */
function get_theme_option( $key, $default = '' ) {
	$option = $default;
	if (function_exists('ot_get_option'))
		$option = ot_get_option($key, $default);
	return $option;
}


/**
 * Get Option.
 *
 * Helper function to return the option value.
 * If no value has been saved, it returns $default.
 *
 * @param     string    The option ID.
 * @param     string    The default option value.
 * @return    mixed
 *
 */
if ( ! function_exists( 'ot_get_option' ) ) {

  function ot_get_option( $option_id, $default = '' ) {
    
    /* get the saved options */ 
    $options = get_option( 'option_tree' );
    
    /* look for the saved value */
    if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {
      return $options[$option_id];
    }
    
    return $default;
    
  }
  
}


/* =$wp_query Helper Functions
-------------------------------------------------------------- */

/**
 * Output number of posts on current page
 * @return void
 */
function post_count() {
	echo get_post_count();
}

/**
 * Return number of posts on current page
 * @return int number of posts on current page
 */
function get_post_count() {
	global $wp_query;
	return $wp_query->post_count;
}

/**
 * Output number of found posts
 * @return void
 */
function found_posts() {
	echo get_found_posts();
}

/**
 * Return number of all found posts if this variable is not empty
 * @return int number of posts
 */
function get_found_posts() {
	global $wp_query;
	return $wp_query->found_posts ? $wp_query->found_posts : get_post_count();
}

/**
 * Output post number, starting from 1
 * @return void
 */
function the_post_number() {
	echo get_the_post_number()+1;
}

/**
 * Get post number in the query
 * @return int
 * @todo post number must calculate also page number and offset if isset
 */
function get_the_post_number() {
	global $wp_query;
	return $wp_query->current_post;
}

/**
 * Is first post in the loop
 * @return bool
 * @todo add additional param to count page
 */
function is_first_post() {
	return get_the_post_number()==0;
}

/**
 * Is last post in the loop
 * @return bool
 * @todo add additional param to count page
 */
function is_last_post() {
	return get_post_count()==(get_the_post_number()+1);
}

/**
 * Get page number in the loop
 * @return int page index number
 */
function get_the_page_number() {
	global $wp_query;
	return (0==$wp_query->query_vars['paged']) ? 1 : $wp_query->query_vars['paged'];
}

/**
 * Is first page in the loop
 * @return bool 
 */
function is_first_page() {
	return get_the_page_number()==0;
}

/**
 * Is last page in the query
 * @return bool
 */
function is_last_page() {
	global $wp_query;
	return get_the_page_number()>=$wp_query->max_num_pages;
}


/* =Post meta helper functions
-------------------------------------------------------------- */

/**
 * Output post meta value
 *
 */
function the_post_meta($key, $default='') {
	echo get_the_post_meta($key) ? get_the_post_meta($key) : $default;
}

/**
 * Output post meta value
 *
 */
function get_the_post_meta($key, $single=true) {
	return get_post_meta(get_the_ID(), $key, $single);
}


/* = Usefull theme functions
-------------------------------------------------------------- */

/**
 * This is rewrite of default function get_template_part
 * Add ability to searches for theme files in subdirectories
 */
function get_theme_part( $slug, $name = null ) {
	$templates = array();
	if ( isset($name) )
	{
		$templates[] = "{$slug}/{$name}.php";
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	locate_template($templates, true, false);
	do_action('theme_part_after', $slug, $name);		
}


/**
 * Output even or odd post class
 */
function even_odd_post_class($even='even', $odd='odd') {
	echo 'class="'.get_even_odd_post_class($even, $odd).'"';
}

/**
 * Return either even or odd class_name
 * can be used only inside the loop
 */
function get_even_odd_post_class($even='even', $odd='odd') {
	return (0==get_the_post_number()%2) ? $even : $odd;
}


/* = Other usefull functions
-------------------------------------------------------------- */

/**
 * Removes Paragraph Tags From Around Images
 * thanks to Chris Coyier for it (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
 */
function filter_ptags_on_images( $content ) {
   return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}
add_filter( 'the_content', 'filter_ptags_on_images' );

/**
 * Returns requested $value
 *
 * @since 6.0
 * @access public
 *
 * @param string $value The value to retrieve
 * @return string|bool The value if it exists, false if not
 */
function get_posted_value( $value ) {
	if ( isset( $_REQUEST[$value] ) )
		return stripslashes( $_REQUEST[$value] );
	return false;
}

/**
 * Outputs requested value
 *
 * @since 6.0
 * @access public
 *
 * @param string $value The value to retrieve
 */
function the_posted_value( $value ) {
	echo esc_attr( get_posted_value( $value ) );
}

/**
 * Cut Strings (detects words)
 *
 * @return string
 */
function cut($string, $max_length){
	$string = strip_tags($string);
	if (strlen($string) > $max_length){
		$string = substr($string, 0, $max_length);
		$pos = strrpos($string, " ");
		if($pos === false) {
				return substr($string, 0, $max_length)."...";
		}
			return substr($string, 0, $pos)."...";
	}else{
		return $string;
	}
}

/**
 * Function to return current page url
 *
 * @return string (page url)
 */
function current_url() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"] == "on")
		{$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
}

/**
 * Get image id by image url
 *
 * @param string $url url to image
 */
function get_image_id_by_url($url='') {
	global $wpdb;
	// checck if image is in upload dir
	$uploads = wp_upload_dir();
	if (strpos($url, $uploads['baseurl'])===false)
		return 0;
	// remove home url from image url
	$url = substr($url, strlen(home_url()), ( strlen($url) - strlen(home_url()) ));
	// search for image id 
	$sql = "SELECT ID FROM $wpdb->posts WHERE guid LIKE '%$url%' LIMIT 1";
	$id = $wpdb->get_var($sql);

	return absint($id);
}