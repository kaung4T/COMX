<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Astra
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" <?php astra_primary_class(); ?>>

    <?php astra_primary_content_top(); ?>

    <main id="main" class="site-main" role="main">

        <?php astra_entry_before(); ?>

        <section class="error-404 not-found">

            <?php astra_entry_top(); ?>

            <?php echo do_action('aepro_404'); ?>

            <?php astra_entry_bottom(); ?>

        </section><!-- .error-404 -->

        <?php astra_entry_after(); ?>

    </main><!-- #main -->

    <?php astra_primary_content_bottom(); ?>

</div><!-- #primary -->

<?php get_footer(); ?>

