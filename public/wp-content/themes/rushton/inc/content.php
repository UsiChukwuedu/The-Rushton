<?php
/**
 * Content
 *
 * Displays content shown in the 'index.php' loop
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="large-4 medium-small-6 columns col" href="<?php the_permalink(); ?>">
		<a href="<?php the_permalink(); ?>" class="inner" data-equalizer-watch>
			<?php /* if (get_the_post_thumbnail()): the_post_thumbnail('full'); else: echo '<img src="'; echo get_template_directory_uri(); echo '/img/placeholder.png" alt="fallback image">'; endif; */ ?>
			<?php /* USE ABOVE IS YOU WANT AN <IMG> ELEMENT INSTEAD OF BACKGROUND IMAGE */ ?>
			<div class="bg" style="background-image:url(<?php if (get_the_post_thumbnail()): echo get_the_post_thumbnail_url(); else: echo THEME_URL . '/img/placeholder.png'; endif ?>);"></div>
			<div class="info">
				<h6><?php $postid = get_the_category($post->ID); echo $postid[0]->name; ?></h6>
				<span><?php echo get_the_date('M j, Y'); ?></span>
				<h3><?php the_title(); ?></h3>
				<?php if(get_field('short_description')): echo '<p>' . get_field('short_description') . '</p>'; endif;; ?>
				<span class="read-more">Read More</span>
			</div>
		</a>
	</div>
</article>
