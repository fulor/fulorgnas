<?php

/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter('ot_show_pages', '__return_false');

add_filter('ot_show_new_layout', '__return_false');
/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter('ot_theme_mode', '__return_true');


/**
 * Required: include OptionTree.
 */
include_once(get_template_directory() . '/options-tree/ot-loader.php');

/**
 * Initialize the options before anything else.
 */
add_action('admin_init', 'powershop_theme_options', 1);

function powershop_theme_options()
{
    /**
     * Get a copy of the saved settings array.
     */
    $saved_settings = get_option('option_tree_settings', array());

    /**
     * Create a custom settings array that we pass to
     * the OptionTree Settings API Class.
     */
    $custom_settings = array(
        'contextual_help' => array(
            'content' => array(
                array(
                    'id' => 'general_help',
                    'title' => 'General',
                    'content' => '<p>Help content goes here!</p>'
                )
            ),
            'sidebar' => '<p>Sidebar content goes here!</p>'
        ),
        'sections' => array(
            array(
                'title' => 'Слайдер',
                'id' => 'slider'
            ),
        ),
        'settings' => array(
            array(
                'label' => 'Слайдер на главную',
                'id' => 'slider_home',
                'type' => 'list-item',
                'section' => 'slider',
                'settings' => array(
                    array(
                        'label' => 'Слайд',
                        'id' => 'full_slider',
                        'type' => 'upload'
                    ),
                    array(
                        'label' => 'Слайд для мобилок',
                        'id' => 'full_slider_mobile',
                        'type' => 'upload'
                    ),
                ),
            ),
        )
    );

    /* allow settings to be filtered before saving */
    $custom_settings = apply_filters('option_tree_settings_args', $custom_settings);

    /* settings are not the same update the DB */
    if ($saved_settings !== $custom_settings) {
        update_option('option_tree_settings', $custom_settings);
    }
}

/**
 * Add images ids to all sliders
 *
 * @since instrument 1.0
 */
function save_image_ids_in_lists()
{
    $options = get_option('option_tree');
    // find front_slides key
    foreach ($options as $slider_key => $list) :
        if (is_array($list))
            foreach ($list as $list_item_key => $list_item)
                if (is_array($list_item))
                    foreach ($list_item as $key => $value)
                        if (strpos($key, 'image') !== false)
                            $options[$slider_key][$list_item_key][$key . '_id'] = get_image_id_by_url($value);
    endforeach;
    update_option('option_tree', $options);
}

add_action('ot_after_theme_options_save', 'save_image_ids_in_lists');