<?php
/**
 * The template for displaying all WooCommerce pages.
 *
 * @package Newsup
 */
get_header(); ?>
<!--==================== ti breadcrumb section ====================-->
<?php get_template_part('index','banner'); ?>

<!-- #main -->
<main id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<?php woocommerce_content(); ?>
			</div>
		</div><!-- .container -->
	</div>	
</main><!-- #main -->
<?php get_footer(); ?>