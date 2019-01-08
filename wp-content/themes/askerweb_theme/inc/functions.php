<?php
//Enable sessions
//add_action('init', 'myStartSession', 1);
//add_action('wp_logout', 'myEndSession');
//
//function myStartSession()
//{
//    if (!session_id()) {
//        session_start();
//    }
//}
//
//function myEndSession()
//{
//    session_destroy();
//}

function wp_pagination_ajax($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged)) $paged = 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        echo "<ul class=\"pager_item\">";
        //if ($paged > 1) echo "<li class=\"pager_prev\"><a href='" . get_pagenum_link(1) . "'></a></li>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1))) {
                echo ($paged == $i) ? "<li  class=\"is-active\"><a href='#'>" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "'>" . $i . "</a></li>";
            }
        }

        //if ($paged < $pages) echo "<li class=\"pager_next\"><a href='" . get_pagenum_link($pages) . "'></a></li>";
        echo "</ul>\n";
    }
}

function wp_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged)) $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    if (1 != $pages) {
        echo "<ul class=\"pager_item\">";
        if ($paged > 1) echo "<li class=\"pager_prev\"><a href='" . get_pagenum_link(1) . "'>«</a></li>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1))) {
                echo ($paged == $i) ? "<li  class=\"is-active\"><a href='#'>" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "'>" . $i . "</a></li>";
            }
        }
        echo "</ul>\n";
    }
}

function wp_pagination_search($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;

    $paged = ($_GET['pageds']) ? $_GET['pageds'] : 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    unset($_GET['pageds']);
    if (1 != $pages) {
        echo "<ul class=\"pager_item\">";
        if ($paged > 1) echo "<li class=\"pager_prev\"><a href='?pageds=" . (1) . "&" . http_build_query($_GET) . "'>«</a></li>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1))) {
                echo ($paged == $i) ? "<li  class=\"is-active\"><a href='#'>" . $i . "</a></li>" : "<li><a href='?pageds=" . ($i) . "&" . http_build_query($_GET) . "'>" . $i . "</a></li>";
            }
        }
        if ($paged != $pages) echo "<li><span>из</span></li><li><a href=\"?pageds=" . $pages . "&" . http_build_query($_GET) . "\" rel=\"nofollow\">" . $pages . "</a></li>";
        echo "</ul>\n";
        if ($paged != $pages) {
            echo "<div class=\"pager_item pager_next\">
                      <a href=\"?pageds=" . ($paged + 1) . "&" . http_build_query($_GET) . "\">Следующая страница</a>
                    </div>";
        }
    }
}

function the_breadcrumb()
{
    /* === ОПЦИИ === */
    //$text['home'] = 'Главная'; // текст ссылки "Главная"
    $text['category'] = '%s'; // текст для страницы рубрики
    $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
    $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
    $text['author'] = 'Статьи автора %s'; // текст для страницы автора
    $text['404'] = 'Ошибка 404'; // текст для страницы 404
    $text['page'] = 'Страница %s'; // текст 'Страница N'
    $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

    $wrap_before = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
    $wrap_after = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
    $sep = '/'; // разделитель между "крошками"
    $sep_before = '<span class="sep">'; // тег перед разделителем
    $sep_after = '</span>'; // тег после разделителя
    $show_home_link = 0; // 1 - показывать ссылку "Главная", 0 - не показывать
    $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
    $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
    $before = '<span class="current">'; // тег перед текущей "крошкой"
    $after = '</span>'; // тег после текущей "крошки"
    /* === КОНЕЦ ОПЦИЙ === */

    global $post;
    $home_url = home_url('/');
    $link_before = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link_after = '</span>';
    $link_attr = ' itemprop="item"';
    $link_in_before = '<span itemprop="name">';
    $link_in_after = '</span>';
    $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
    $frontpage_id = get_option('page_on_front');
    $parent_id = ($post) ? $post->post_parent : '';
    $sep = ' ' . $sep_before . $sep . $sep_after . ' ';
    //$home_link = $link_before . '<a href="' . $home_url . '"' . $link_attr . ' class="home">' . $link_in_before . $text['home'] . $link_in_after . '</a>' . $link_after;

    if (is_home() || is_front_page()) {

        //if ($show_on_home) echo $wrap_before . $home_link . $wrap_after;
        if ($show_on_home) echo $wrap_before . $wrap_after;

    } else {

        echo $wrap_before;
        //if ($show_home_link) echo $home_link;

        if ( is_category() ) {
            $cat = get_category(get_query_var('cat'), false);
            if ($cat->parent != 0) {
                $cats = get_category_parents($cat->parent, TRUE, $sep);
                $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                if ($show_home_link) echo $sep;
                echo $cats;
            }
            if ( get_query_var('paged') ) {
                $cat = $cat->cat_ID;
                echo /*$sep .*//* sprin*/tf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo /*$sep .*/ $before . sprintf($text['category'], single_cat_title('', false)) . $after;
            }

        } elseif ( is_search() ) {
            if (have_posts()) {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
            } else {
                if ($show_home_link) echo $sep;
                echo $before . sprintf($text['search'], get_search_query()) . $after;
            }

        } elseif ( is_day() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
            if ($show_current) echo $sep . $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            if ($show_current) echo $sep . $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            //if ($show_home_link) echo $sep;
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_url . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current) echo $sep . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $sep);
                if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
                if ( get_query_var('cpage') ) {
                    echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
                } else {
                    if ($show_current) echo $before . get_the_title() . $after;
                }
            }

            // custom post type
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            if ( get_query_var('paged') ) {
                echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . $post_type->label . $after;
            }

        } elseif ( is_attachment() ) {
            if ($show_home_link) echo $sep;
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE, $sep);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current) echo $sep . $before . get_the_title() . $after;

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current) echo /*$sep .*/ $before . get_the_title() . $after;

        } elseif ( is_page() && $parent_id ) {
            if ($parent_id != $frontpage_id) {
                //if ($show_home_link) echo $sep;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $sep;
                }
            }
            if ($show_current) echo $sep . $before . get_the_title() . $after;

        } elseif ( is_tag() ) {
            if ( get_query_var('paged') ) {
                $tag_id = get_queried_object_id();
                $tag = get_tag($tag_id);
                echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
            }

        } elseif ( is_author() ) {
            global $author;
            $author = get_userdata($author);
            if ( get_query_var('paged') ) {
                if ($show_home_link) echo $sep;
                echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
            }

        } elseif ( is_404() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . $text['404'] . $after;

        } elseif ( has_post_format() && !is_singular() ) {
            if ($show_home_link) echo $sep;
            echo get_post_format_string( get_post_format() );
        }

        echo $wrap_after;

    }
}
//function the_breadcrumb()
//{
//    echo "<div class=\"bx-breadcrumb\" >";
//    if (!is_front_page()) {
//        echo "<div class=\"bx-breadcrumb-item\" id=\"bx_breadcrumb_0\" itemscope=\"\" itemtype=\"http://data-vocabulary.org/Breadcrumb\">
//            <a href=\"" . get_option('home') . "\" title=\"Главная\"  itemprop=\"url\">
//            <span itemprop=\"title\">Главная</span></a>
//            </div> ";
//        $page = get_queried_object();
//        $startLink = "<div class=\"bx-breadcrumb-item\">
//				 &#8594;
//				<span>";
//        $endLink = "</span>
//			</div><div style=\"clear:both\"></div></div>";
//        if (is_category() || is_single() || is_tax()) {
//            if (is_tax()) {
//                echo $startLink . single_term_title('', 0) . $endLink;
//            } elseif (is_category()) {
//                echo $startLink . get_the_category(' ') . $endLink;
//            }
//            if (is_single()) {
//                echo $startLink . $page->post_title . $endLink;
//            }
//        } elseif (is_page()) {
//            echo $startLink . $page->post_title . $endLink;
//        } else {
//            echo "<li>Главная</li>";
//        }
//    }
//
//}

function theme_setup()
{
    if (function_exists('register_nav_menus')) {
        register_nav_menus(
            array(
                'primary-menu' => 'Главное меню',
                'second-menu' => 'Правое меню',
                'sidebar-menu' => 'Боковое меню',
                'bottom-menu' => 'Нижнее меню',
            )
        );
    }

    // Clean up the <head>
    function remove_head_links()
    {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
    }

    add_action('init', 'remove_head_links');


    if (function_exists('add_theme_support')) {
        // Add RSS links to <head> section
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(255, 400); // default Post Thumbnail dimensions
        add_image_size('product-thumb', 140, 115, false);

    }
    /*
    if ( function_exists( 'add_image_size' ) ) {
        add_image_size('product', 125, 155, true);
        add_image_size('product-thumb', 205, 110);
        add_image_size('blog-thumb', 300, 200);
        add_image_size('article-recomends', 210, 140);
    }*/
}

add_action('init', 'theme_setup', 0);

function register_my_widgets()
{
    register_sidebar(array(
        'name' => 'Подвал',
        'id' => "footer-side",
        'description' => '',
        'class' => '',
        'before_widget' => '<div id="%1$s" class="text-widjet">',
        'after_widget' => "</div>\n",
        'before_title' => '',
        'after_title' => "",
    ));

}

add_action('init', 'register_my_widgets', 0);

class Walker_Header_Menu extends Walker
{

    // Tell Walker where to inherit it's parent and id values
    var $db_fields = array(
        'parent' => 'menu_item_parent',
        'id' => 'db_id'
    );

    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= '<ul >';
    }

    //end of the sub menu wrap
    function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= '
					</ul>   
        ';
    }

    /**
     * At the start of each element, output a <li> and <a> tag structure.
     *
     * Note: Menu objects include url and title properties, so we will use those.
     */

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $output .= sprintf("\n<li ><div><a href='%s'>%s</a>\n",
            $item->url,
            $item->title
        );
    }

}

//remove_filter('the_content', 'wpautop');

add_action('wp_ajax_sendPost', 'sendPost');
add_action('wp_ajax_nopriv_sendPost', 'sendPost');

add_filter('wp_mail_content_type', 'set_html_content_type');
function set_html_content_type()
{
    return 'text/html';
}

function sendPost()
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $model = $_POST['model'];

    $toEmail = "openzamkoff@yandex.ru";

    $subject = 'Заголовок письма';

    $message = "Поступил " . $subject . " с сайта " . $_SERVER['HTTP_HOST'] . "<br>".
        "<strong>Имя:</strong> " . $name . "<br>".
        "<strong>Телефон:</strong> " . $phone . "<br>";
    if(!empty($model)){
        $message .= "<strong>Наименование модели:</strong> " . $model . "<br>";
    }


    $headers = 'From: test <no-reply@example.com>' . "\r\n";
    wp_mail($toEmail, $subject, $message, $headers);
    die(wp_send_json(array('status' => 'success')));
}
