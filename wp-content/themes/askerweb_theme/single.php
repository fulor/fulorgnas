<?php get_header(); ?>
<div class="single-page">
    <div class="content-wrapper">
        <div class="breadcrumb"><?php the_breadcrumb() ?></div>
        <div class="single-content">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>

