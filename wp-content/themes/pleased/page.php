<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

get_header(); 
if ( true === apply_filters( 'pleased_filter_frontpage_content_enable', true ) ) : ?>
<div id="inner-content-wrapper" class="wrapper page-section">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="single-wrapper">
				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div><!-- .single-wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php
	if ( pleased_is_sidebar_enable() ) {
		get_sidebar();
	} ?>
</div><!-- .page-section -->
<?php endif;
get_footer();
