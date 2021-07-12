<?php 
	get_header(); 
	$recent_blog_count = 3;
	$blog_category_id = 7
?>

<main id="wrapper">
	<section class="header row">
		<small class="date columns small-12"><?php echo get_the_date(); ?></small>
		<h1 class="title columns small-12"><?php the_title(); ?></h1>
		<div class="img columns small-12">
			<?php the_post_thumbnail('full'); ?>
		</div>
	</section>
	<section class="content-container row">
		<div class="content columns small-12 medium-7 large-6">
			<?php the_content(); ?>
		</div>
		<div class="recent-news columns small-12 medium-4 medium-offset-1 large-offset-2">
			<?php
			     $args = array(
					'posts_per_page' => $recent_blog_count,
					'category' => $blog_category_id,
					'post_type' => 'post',
				);
				$postlist = get_posts( $args ); 
			?>

			<?php if($postlist): ?>
				<h3 class="title">Recent News</h3>
				<ul class="postlist">
					<?php 
						foreach($postlist as $post): 
						setup_postdata($post);
					?>
						<li class="post">
							<a href="<?php esc_url(the_permalink()); ?>">
								<h2 class="title"><?php the_title(); ?></h2>
								<small class="date"><?php echo get_the_date(); ?></small>
							</a>
						</li>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</ul>
				<div class="share row">
					<h3 class="title columns small-12 medium-6">Share Article</h3>
					<ul class="share-icons columns small-12 medium-6">
						<li class="share-icon">fb</li>
						<li class="share-icon">tw</li>
						<li class="share-icon">in</li>
						<li class="share-icon">em</li>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_template_part('inc/inc-footer'); ?>
<?php get_footer(); ?>
