 <?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>


    <div id="primary" <?php generate_do_element_classes( 'content' ) ?>>
        <main id="main" <?php generate_do_element_classes( 'main' ); ?>>
            <?php do_action('generate_before_main_content'); ?>
            <div class="inside-article">
                <?php do_action( 'generate_before_content'); ?>
                <?php do_action( 'generate_after_entry_header'); ?>
                <div class="entry-content" itemprop="text">
                    <?php echo do_action('aepro_404'); ?>
                    <?php //get_search_form(); ?>
                </div><!-- .entry-content -->
                <?php do_action( 'generate_after_content'); ?>
            </div><!-- .inside-article -->
            <?php do_action('generate_after_main_content'); ?>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();