<?php /*
    header('Location: ' . home_url());
    exit; */
?>

<?php get_header(); ?>

<section id="wrapper">
    <div class="row">
        <div class="large-6 medium-6 columns small-centered text-center">
            <?php /* ----- MESSAGE BOX ----- */ ?>
            <div class="box pad-top pad-bottom">
                <div class="pad-bottom">
                    <h1>404</h1>
                </div>
                <p>Error 404: Page not found.</p>
                <a href="<?php echo home_url(); ?>" class="block-button">BACK TO HOME</a>
            </div>
        </div>
    </div>
</section>

<?php get_template_part('inc/inc-footer'); ?>
<?php get_footer(); ?>
