<?php

use Elementor\Plugin;
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while ( have_posts() ) : the_post();

                do_action( 'storefront_single_post_before' );

                if(Plugin::instance()->preview->is_preview_mode()){
                    the_content();
                }else {
                    do_action('aepro_single_data');
                }

                do_action( 'storefront_single_post_after' );

            endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
