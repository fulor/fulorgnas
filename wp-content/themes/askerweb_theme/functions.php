<?php

//Load up our additional scripts and styles
require( get_template_directory() . '/inc/script-style.php' );
  
//Load up the theme options
require( get_template_directory() . '/inc/options.php' );
 
//Load up our custom theme utils
require( get_template_directory() . '/inc/utils.php' );
//Load up our custom post-types and taxonomies
require( get_template_directory() . '/inc/taxonomies.php' );

//Load up our custom meta boxes
require( get_template_directory() . '/inc/meta-boxes.php' );

//Load up our custom theme functions
require( get_template_directory() . '/inc/functions.php' );

//Load up our custom theme functions
add_theme_support( 'post-thumbnails' );

//Load up our custom post views to vidgets
require( get_template_directory() . '/inc/postviews.php' );


