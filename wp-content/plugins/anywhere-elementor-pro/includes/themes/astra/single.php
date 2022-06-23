<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */
use Elementor\Plugin;
get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<main id="main" class="site-main" role="main">

            <?php
            // Start loop
            while ( have_posts() ) : the_post(); ?>

               <?php astra_entry_before(); ?>

					<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php astra_entry_top(); ?>
							<?php if(Plugin::instance()->preview->is_preview_mode()){
								the_content();
							}else{
								do_action('aepro_single_data');
							}
							?>
						<?php astra_entry_bottom(); ?>

					</article><!-- #post-## -->

				<?php //astra_entry_after(); ?>

                <?php

            endwhile; ?>

           </main><!-- #main -->

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>