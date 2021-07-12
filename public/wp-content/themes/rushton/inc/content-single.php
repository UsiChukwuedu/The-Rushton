<?php
/**
 * Content Single
 *
 * Loop content in single post template (single.php)
 *
 */
?>

<?php
	$exclcat            = array( 'fp-feature', 'fp-aside-feature' );                               
	$output_categories  = array();
	$args = array(
		'orderby'            => 'name',
		'show_count'         => 0, //Use 1 to show the count
		'taxonomy'           => 'category',
		'use_desc_for_title' => 1,
		'echo'               => 1, //Use 0 to not output results
		'title_li'           => '', //creates an <li> entry with text entered here - can be blank
		'exclude'            => 1,
		'hide_empty'         => 0
	);
	$categories = get_categories( $args );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="content">
		<div class="content-col pad-top pad-bottom large-9 medium-10 small-12 small-centered columns">
			<?php /* if (get_the_post_thumbnail()): the_post_thumbnail('full'); else: echo '<img src="'; echo get_template_directory_uri(); echo '/img/placeholder.png" alt="fallback image">'; endif; */ ?>
			<?php /* USE ABOVE IS YOU WANT AN <IMG> ELEMENT INSTEAD OF BACKGROUND IMAGE */ ?>
			
			<?php /* ----- BANNER ----- */ ?>
			<div class="bg" style="background-image:url(<?php if (get_field('banner_image')): the_field('banner_image'); elseif(get_the_post_thumbnail()): echo get_the_post_thumbnail_url(); else: echo THEME_URL . '/img/placeholder.png'; endif ?>);"></div>
			
			<?php /* ----- HEADER ----- */ ?>
			<div class="header">
				<h1><?php the_title(); ?></h1>
				<span><?php echo get_the_date('M j, Y'); ?></span>
			</div>

			<?php /* ----- POST CONTENT ----- */ ?>
			<div class="post paragraphs">
				<?php the_content(); ?>
			</div>

			<?php /* ----- SOCIAL AND BACK BUTTON ----- */ ?>
			<div class="options">
				<div class="share">
					<h3>Share</h3>
					<div>
						<a class="icon-link" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>" target="_blank">
							<i class="fab fa-facebook-f"></i>
						</a>
						<a class="icon-link" href="https://twitter.com/home?status=<?php the_permalink();?>" target="_blank">
							<i class="fab fa-twitter"></i>
						</a>
						<a class="icon-link" href="https://pinterest.com/pin/create/button/?url=<?php the_field('full_image'); ?>&amp;media=&amp;description=<?php the_permalink();?>" target="_blank">
							<i class="fab fa-pinterest"></i>
						</a>
						<a class="icon-link" href="mailto:?subject=Check out this article on <?php bloginfo('name'); ?>&amp;body=Check out this article I was just reading: <?php the_permalink();?>">
							<i class="fa fa-envelope"></i>
						</a>
					</div>
				</div>
				<?php $cat = get_the_category(); ?>
				<a class="back text-link black" href="<?php if(sizeof($categories) > 1): echo get_category_link($cat[0]->cat_ID); else: echo get_permalink(get_option('page_for_posts')); endif; ?>">
					<span>Back to <?php if(sizeof($categories) > 1): echo $cat[0]->cat_name; else: echo get_the_title(get_option('page_for_posts')); endif; ?></span>
				</a>
			</div>
		</div>
	</div>
</article>