<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if ( have_posts() ) : ?>

                <?php  echo do_action('aepro_archive_data',get_the_content()); ?>

            <?php else :

                get_template_part( 'content', 'none' );

            endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
