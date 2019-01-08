<?php
/**
 * This template file contains additional post-types and custom taxonomies
 */
 
add_action( 'init', 'ps_posttypes_and_taxonomies', 0 );
function ps_posttypes_and_taxonomies() {
	register_post_type( 'products',
		array(
			'labels' => array(
				'name' => 'Товары',
				'singular_name' =>'Товар',
				'add_new' =>'Добавить новый',
				'add_new_item' => 'Добавить товар'
			),
            'public' => true,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields', 'excerpt', 'revisions', 'page-attributes' ),
            'hierarchical' => true,
            'has_archive' => false,
            'capability_type' => 'page',
            'exclude_from_search' => false,
		)
	);

	register_taxonomy(
		'product_cat',
		'products',
		array(
			'labels' => array(
				'name' => 'Категория товаров',
			),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true,
            'rewrite'       => true,
            'query_var' => true,
            'show_in_nav_menus' => true
		)
	);
}