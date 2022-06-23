<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php echo do_action('ae_pro_search'); ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
