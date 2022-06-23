<?php

use Elementor\Plugin;

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php while ( have_posts() ) : the_post();


                    if(Plugin::instance()->preview->is_preview_mode()){
                        the_content();
                    }else {
                        do_action('aepro_single_data');
                    }


                endwhile; // End of the loop. ?>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
