<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Newsup
 */
get_header(); 
?>
<!--==================== Newsup breadcrumb section ====================-->
<?php get_template_part('index','banner'); ?>
<!--==================== main content section ====================-->
<main id="content">
    <div class="container-fluid">
      <div class="row">
		<!-- Blog Area -->
			<?php if( class_exists('woocommerce') && (is_account_page() || is_cart() || is_checkout())) { ?>
			<div class="col-md-12 mg-card-box padding-20">
			<?php if (have_posts()) {  while (have_posts()) : the_post(); ?>
			<?php the_content(); endwhile; } } else {?>
			<div class="col-md-8">
				<div class="mg-card-box padding-20">
			<?php if( have_posts()) :  the_post(); ?>		
			<?php the_content(); ?>
			<?php endif; 
				while ( have_posts() ) : the_post();
				// Include the page
				the_content();
				comments_template( '', true ); // show comments
				wp_link_pages(array(
        'before' => '<div class="link btn-theme">' . esc_html__('Pages:', 'newsup'),
        'after' => '</div>',
    ));
				
				endwhile;
				newsup_page_edit_link();
			?>	
		</div>
			</div>
			<!--Sidebar Area-->
			<aside class="col-md-4">
				<?php get_sidebar(); ?>
			</aside>
			<?php } ?>
			<!--Sidebar Area-->
			</div>
		</div>
	</div>
</main>
<?php
get_footer();