<?php
/*
*
* Blog Full Width
* Used to render the full width blog layout sitewide
*
*/
?>

<div class="row full-post blog">
    <?php if(has_post_thumbnail()): ?>
        <div class="columns small-12 medium-7">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>
    <div class="content columns small-12 medium-5 align-middle">
        <small class="date"><?php echo get_the_date(); ?></small>
        <h3 class="title"><?php the_title(); ?></h3>
        <?php  the_excerpt(); ?>
        <button class="cta"><a href="<?php esc_url(the_permalink()); ?>">Read More</a></button>
    </div>
</div>