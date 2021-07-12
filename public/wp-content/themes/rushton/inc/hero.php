<?php
/*
*
* Hero
* Used to render the hero on subpages
*
*/
?>

<?php 
  $hero_img = get_field('hero_image'); 
  $hero_title = get_field('hero_title'); 
?>
<div 
  class="hero" 
  style="background-image: url(<?php echo esc_url($hero_img['url']); ?>);" 
  alt="<?php echo $hero_img['alt']; ?>">
    <div class="row">
        <div class="columns small-12">
            <h1 class="title"><?php echo $hero_title; ?></h1>   
        </div>
    </div>
</div>