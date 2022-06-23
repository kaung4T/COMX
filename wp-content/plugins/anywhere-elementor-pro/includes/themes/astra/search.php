<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Astra
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" <?php astra_primary_class(); ?>>

    <?php astra_primary_content_top(); ?>

    <?php astra_archive_header(); ?>

    <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <?php ;/* Start the Loop */ ?>
            <div class="ast-row">


                    <?php echo do_action('ae_pro_search'); ?>

            </div>

        <?php else : ?>

            <?php get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>

    </main><!-- #main -->


    <?php astra_primary_content_bottom(); ?>

</div><!-- #primary -->

<?php get_footer(); ?>