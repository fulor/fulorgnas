<?php get_header(); ?>
<?php the_breadcrumb() ?>
<?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; ?>
<?php get_footer(); ?>