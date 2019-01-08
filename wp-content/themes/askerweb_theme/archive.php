<?php get_header(); ?>
<div class="blog blog-info-center">
    <div class="content-wrapper">
        <div class="breadcrumb"><?php the_breadcrumb() ?></div>
        <div id="blog-content">
            <div id="blog">
                <?php
                $page_id = get_the_ID();
                $temp = $wp_query;
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $wp_query= null;
                $args = array(
                    'cat'   => 1,
                    'order' => 'asc',
                    'posts_per_page' => 3,
                    'paged' => $paged
                );
                $wp_query = new WP_Query();
                $wp_query->query($args);
                while ($wp_query->have_posts()) :
                    $wp_query->the_post();
                    ?>
                    <div class="blogs-item">
                        <a class="post-img-link" href="<?php the_permalink() ?>">
                            <div class="link-posts"><?php the_post_thumbnail('blog-thumb') ?></div>
                        </a>
                        <div class="blogs-content">
                            <div class="title-item">
                                <a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a>
                            </div>
                            <div class="desc-item">
                                <?php
                                $excerpt = get_the_excerpt();
                                echo wp_trim_words($excerpt, '15'); ?>
                            </div>
                            <div class="button-readmore">
                                <a href="<?php the_permalink(); ?>" class="more-link">Подробнее</a>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
                <div class="clearfix"></div>
                <div class="pagination">
                    <?php wp_pagination(); ?>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
