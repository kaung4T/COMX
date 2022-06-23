<?php

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php if ( have_posts() ) : ?>

                    <?php  echo do_action('aepro_archive_data',get_the_content()); ?>

                <?php else :

                    get_template_part( 'content', 'none' );

                endif; ?>


            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
