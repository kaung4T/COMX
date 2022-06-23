<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

    <div id="primary" class="content-area">

        <main id="main" class="site-main" role="main">

            <div class="error-404 not-found">

                <div class="page-content">

                    <?php echo do_action('aepro_404'); ?>

                </div><!-- .page-content -->
            </div><!-- .error-404 -->

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer();
