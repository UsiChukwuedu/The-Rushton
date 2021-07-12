<?php 
/************************
 Template Name: Home Page
 ***********************/
get_header(); ?>


<main id="wrapper">
	<div class="hero-wrapper">
		<div class="swiper-container mySwiper">
				  <!-- Additional required wrapper -->
				  
					 <?php 
							if( have_rows('slider_image') ): ?>
					<div class="swiper-wrapper">
						<?php 
						while( have_rows('slider_image') ): the_row();?>
					<!-- Slides -->	
					<div class="swiper-slide"><img src="<?php echo get_sub_field('image')['url']; ?>"  /></div>	
						
					
						<?php
				  endwhile; ?>
				  </div>
					<?php endif; /* have_rows(content) */ ?>
				</div>
		
		<div class="top">	
			<div class="text">We're also on <img src="<?php echo THEME_URL; ?>/img/uber-eat.png" ></div>		
		</div>
		<div class="content" >
			<div class="logo"><img class="" src="<?php echo THEME_URL; ?>/img/logo.svg" alt="logo"/></div>
			<div class="welcome-text"><?php echo get_field('welcome_text'); ?></div>
			<div class="order-button"><a href="#"><?php echo get_field('order_text'); ?></a></div>
			<div class="work-hour"><?php echo get_field('work_hours_1'); ?></div>
			<div class="work-day"><?php echo get_field('work_hours_2'); ?></div>
			<div class="address"><img src="<?php echo THEME_URL; ?>/img/location.png"><?php echo get_field('location'); ?></div>
			<div class="phone"><img src="<?php echo THEME_URL; ?>/img/phone-icon.png"><a href="tel:<?php echo get_field('phone');?>"><?php echo get_field('phone'); ?></a></div>
			
		</div>
	</div>
</main>
<?php get_footer(); ?>
