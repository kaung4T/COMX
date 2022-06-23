<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
            <?php astra_content_while_before(); ?>

            <div class="ast-row">

                <?php echo do_action('aepro_archive_data',get_the_content()); ?>

            </div>

            <?php astra_content_while_after(); ?>

        <?php else : ?>

            <?php get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>

    </main><!-- #main -->

    <?php astra_primary_content_bottom(); ?>

</div><!-- #primary -->

<?php get_footer(); ?>
