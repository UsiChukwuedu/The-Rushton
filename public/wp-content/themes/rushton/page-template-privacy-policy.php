<?php 
/************************
 Template Name: Privacy Policy
 ***********************/
get_header(); ?>

<section id="wrapper">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="row">
                <div class="large-9 medium-10 medium-centered columns">
                    <div class="header-wrap">
                        <h1><?php the_title(); ?></h1>
                    </div>
                    <?php /* ----- CONTENT ----- */ ?>
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endwhile;?>    
    <?php else:?>
        <h2>No posts</h2>
    <?php endif; ?>
</section>

<?php get_template_part('inc/inc-footer'); ?>
<?php get_footer(); ?>
