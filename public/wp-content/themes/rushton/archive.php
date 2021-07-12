<?php get_header(); ?>

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

<section id="wrapper">
	<div class="row header-row">
		<div class="large-12 columns">
			<h1><?php echo single_cat_title('', false); ?></h1>
			<?php if(sizeof($categories) > 1): ?>
			<div class="categories sub-head">
				<ul>
					<?php $cats = wp_list_categories($args); ?>
					<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">VIEW ALL</a>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="edgepad results">
		<div class="row">

			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part('inc/content'); ?>
				
				<?php endwhile; ?>
			<?php else : ?>
				<h2>No posts</h2>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_template_part('inc/inc-footer'); ?>

<?php get_footer(); ?>
