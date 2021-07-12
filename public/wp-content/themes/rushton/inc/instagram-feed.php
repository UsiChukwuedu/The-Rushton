<?php include(locate_template('inc/instagram-api.php')); ?>
<?php 
    $title = get_field('title', 'option');
    $handle = get_field('instagram_handle', 'option');
?>

<section class="instagram-feed">
<div class="instagram-container row">
    <div class="first columns small-12 medium-4">
        <div class="row">
        <a class="tile" href="<?php echo esc_url($instagram_posts[0]->permalink) ?>" target="_blank">
            <img src="<?php echo esc_url($instagram_posts[0]->img_url); ?>" />
            <?php get_template_part('inc/instagram-logo'); ?>
        </a>
        </div>
        <div class="row">
            <div class="columns small-6">
                <a class="tile" href="<?php echo esc_url($instagram_posts[1]->permalink) ?>" target="_blank">
                    <img src="<?php echo esc_url($instagram_posts[1]->img_url); ?>" />
                    <?php get_template_part('inc/instagram-logo'); ?>
                </a>
            </div>
            <div class="columns small-6">
                <a class="tile" href="<?php echo esc_url($instagram_posts[2]->permalink) ?>" target="_blank">
                    <img src="<?php echo esc_url($instagram_posts[2]->img_url); ?>" />
                    <?php get_template_part('inc/instagram-logo'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="columns small-12 medium-8">
        <div class="social row">
            <h4 class="title"><?php echo $title; ?></h4>
            <div>
                <a class="link" target="_blank" href="https://www.instagram.com/<?php echo $handle; ?>">
                    <?php get_template_part('inc/instagram-logo'); ?>
                    <span class="handle">@<?php echo $handle; ?></span>
                </a>
            </div>
        </div>
        <div class="row">
            <?php for($i = 3; $i < 7; $i++): ?>
                <div class="columns small-6 medium-3">
                    <a class="tile" target="_blank" href="<?php echo esc_url($instagram_posts[$i]->permalink) ?>">
                        <img src="<?php echo esc_url($instagram_posts[$i]->img_url); ?>" />
                        <?php get_template_part('inc/instagram-logo'); ?>
                    </a>
                </div>
            <?php endfor; ?>
        </div>
        <div class="row">
            <?php for($i = 7; $i < 11; $i++): ?>
                <div class="columns small-6 medium-3">
                    <a class="tile" href="<?php echo esc_url($instagram_posts[$i]->permalink) ?>" target="_blank">
                        <img src="<?php echo esc_url($instagram_posts[$i]->img_url); ?>" />
                        <?php get_template_part('inc/instagram-logo'); ?>
                    </a>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
</section>