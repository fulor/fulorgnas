<?php
/**
 * Registering meta boxes
 * Including the meta boxes class
 * Meta boxes are added using options tree plugin
 */

// Include theme meta box api
include_once(get_template_directory() . '/inc/meta-boxes/theme-ot-meta-box-api.php');

// Hide the settings & documentation pages.
add_filter('ot_show_pages', '__return_false');
// Enable Theme mode
add_filter('ot_theme_mode', '__return_true');

// Include OptionTree.
include_once(get_template_directory() . '/options-tree/ot-loader.php');

// Meta boxes must be global for futher initialize
// will be unset on admin_init
global $meta_boxes;
$meta_boxes = array();


$meta_boxes[] = array(
    'id' => 'my_meta_box_primary',
    'title' => 'Основные характеристики',
    'desc' => '',
    'pages' => array('products'),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'label' => 'Тестовый блок:',
            'id' => 'test_list',
            'type' => 'list-item',
            'settings' => array(
                array(
                    'label' => 'Поле для заполнения:',
                    'id' => 'input_test',
                    'type' => 'text',
                ),

            ),
        ),
    ),
);
/**
 * Register meta boxes
 *
 * @return void
 */
add_action('admin_init', 'theme_register_meta_boxes');
function theme_register_meta_boxes()
{
    global $meta_boxes;

    // Make sure there are no errors when the plugin is deactivated or during upgrade
    if (!function_exists('ot_register_meta_box'))
        return;

    foreach ($meta_boxes as $meta_box) {
        if (isset($meta_box['only_on']) && !rw_maybe_include($meta_box['only_on']))
            continue;

        //new RW_Meta_Box( $meta_box );
        ot_register_meta_box($meta_box);
    }
    // unset global variable
    unset($meta_boxes);
}

/**
 * Check if meta boxes is included
 *
 * @return bool
 */
function rw_maybe_include($conditions)
{
    // Include in back-end only
    if (!defined('WP_ADMIN') || !WP_ADMIN)
        return false;

    // Always include for ajax
    if (defined('DOING_AJAX') && DOING_AJAX)
        return true;


    if (isset($_GET['post']))
        $post_id = $_GET['post'];
    elseif (isset($_POST['post_ID']))
        $post_id = $_POST['post_ID'];
    else
        $post_id = false;

    $post_id = (int)$post_id;

    foreach ($conditions as $cond => $v) {
        switch ($cond) {
            case 'id':
                if (!is_array($v))
                    $v = array($v);
                return in_array($post_id, $v);
                break;
            case 'slug':
                if (!is_array($v))
                    $v = array($v);
                $post = get_post($post_id);
                return in_array($post->post_name, $v);
                break;
            case 'template':
                if (!is_array($v))
                    $v = array($v);
                return in_array(get_post_meta($post_id, '_wp_page_template', true), $v);
            case 'function':
                if (!empty($v) AND function_exists('rw_' . $v))
                    return call_user_func('rw_' . $v);
            case 'meta':
                if (is_array($v) AND (1 == count($v))) {// We can have only one pair of meta-key and meta value to check
                    $result = false;
                    foreach ($v as $meta_key => $meta_value) {
                        $result = ($meta_value == get_post_meta($post_id, $meta_key, true));
                        if (!$result)
                            break;
                    }
                    return $result;
                } elseif (false) {// We can have several pair of meta to check with conditional operator
                    foreach ($v as $meta_key => $meta_value) {

                    }
                }
                break;
            default:

                break;
        }
    }

    // If no condition matched
    return false;
}

/**
 * Add ids to images while saving post.
 * Add compatibility to prev version of meta box
 * and replace images id with image thumbnail url.
 */
add_action('save_post', 'meta_box_add_id_upload_image', 10, 2);
function meta_box_add_id_upload_image($post_id, $post)
{
    global $pagenow, $meta_boxes;

    /* don't save during quick edit */
    if ($pagenow == 'admin-ajax.php')
        return $post_id;

    /* don't save during autosave */
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    /* don't save if viewing a revision */
    if ($post->post_type == 'revision')
        return $post_id;

    /* check permissions */
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return $post_id;
    }

    $upload_fields = array();

    // Loop through all upload fields and save ids
    foreach ($meta_boxes as $meta_box) {
        // Skip inappropriate post types
        if (!in_array($post->post_type, $meta_box['pages']))
            continue;
        foreach ($meta_box['fields'] as $field) {
            if ('upload' == $field['type']) {
                // call for add function
                theme_meta_box_add_upload_image_id($post_id, $field['id']);
            } elseif ('list-item' == $field['type']) {

            }
        }
    }
}

/**
 * Add image id
 *
 * @param int post id
 * @param string meta value key
 */
function theme_meta_box_add_upload_image_id($post_id, $meta_key)
{
    // Meta key for image id
    $image_id_key = $meta_key . '_id';

    // support previous meta boxes version
    if ($image_id = absint(get_post_meta($post_id, $meta_key, true))) {

        $src = wp_get_attachment_image_src($image_id, 'thumbnail');

        if ($src) {
            // Delelte old post meta in case we have multiple images
            delete_post_meta($post_id, $meta_key);

            // update image
            add_post_meta($post_id, $meta_key, $src[0]);
            // update image id
            update_post_meta($post_id, $image_id_key, $image_id) OR add_post_meta($post_id, $image_id_key, $image_id);
        } else {
            delete_post_meta($post_id, $meta_key);
        }

        return;
    }

    // first check if meta value is set
    if (get_post_meta($post_id, $meta_key, true) AND $image_id = get_image_id_by_url(get_post_meta($post_id, $meta_key, true))) {
        update_post_meta($post_id, $image_id_key, $image_id) OR add_post_meta($post_id, $image_id_key, $image_id);
    } else {
        // delete image id value
        delete_post_meta($post_id, $image_id_key);
    }
}

add_filter('rwmb_meta_boxes', 'YOURPREFIX_register_meta_boxes');

function YOURPREFIX_register_meta_boxes($meta_boxes)
{
    /*  // 1st meta box
      $meta_boxes[] = array(
          'id'       => 'personal',
          'title'    => 'Характеристики',
          'pages'    => array( 'products' ),
          'context'  => 'normal',
          'priority' => 'high',

          'fields' => array(
              array(
                  'name'  => 'Параметр',
                  'id'    => 'fname',
                  'type'  => 'text_list',
              ),
          )
      );
  */
    return $meta_boxes;
}