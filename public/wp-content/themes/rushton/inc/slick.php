<?php 

$slick_options = get_field('slick_options');
$slick_left_arrow = get_field('slick_left_arrow');
$slick_right_arrow = get_field('slick_right_arrow');
$autoplay = false;
$background = false;
$captioned = false;
$thumbnails = false;
$image;

if(in_array('autoplay', $slick_options)){
    $autoplay = true;
}
if(in_array('background', $slick_options)){
    $background = true;
}
if(in_array('captioned', $slick_options)){
    $captioned = true;
}
if(in_array('thumbnails', $slick_options)){
    $thumbnails = true;
}
?>

<div class="slick slick-acf<?php foreach($slick_options as $option): echo ' ' . $option; endforeach; ?>"<?php if($autoplay && get_field('slick_autoplay_speed')): echo ' speed="' . get_field('slick_autoplay_speed') . '"'; endif; ?>>
<?php if(have_rows('slick_slides')): ?>
    
    <?php while (have_rows('slick_slides')): the_row(); $image = get_sub_field('image'); ?>
    <div class="slick-slide">
        <?php if($captioned): echo '<div class="cap-wrap">'; endif; ?>
        <?php if($background): echo '<div class="bg" style="background-image:url(' . $image['url'] . ')"></div>'; endif; ?>
        <?php if($image && !$background): ?>
        <img src="<?php echo $image['url']; ?>" alt="<?php bloginfo('name'); ?>">
        <?php endif; ?>

        <?php if(get_sub_field('content')): ?>
        <div class="slide-wrap">
            <div class="slide-content">
                <?php the_sub_field('content'); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if($captioned): echo '</div>'; endif; ?>    
    </div>
    <?php endwhile; ?>

    <?php if(in_array('arrows', $slick_options)): ?>
    <div class="slick-button slick-left">
        <?php echo get_field('slick_left_arrow'); ?>
    </div>
    <div class="slick-button slick-right">
        <?php echo get_field('slick_right_arrow'); ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
</div>

<?php if($thumbnails): ?>
<div class="nav-slider-wrap">
    <div id="slider-nav" class="slick nav-slider">
        <?php if(have_rows('slick_slides')): ?>
        <?php while (have_rows('slick_slides')): the_row(); $image = get_sub_field('image'); ?>
        <div class="slide">
            <div class="inner">
                <div class="bg img-div" style="background-image:url(<?php echo $image['sizes']['thumbnail']; ?>);"></div>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
</div>
<?php endif; ?>