<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>

    <section id="primary" <?php generate_do_element_classes( 'content' ); ?>>
        <main id="main" <?php generate_do_element_classes( 'main' ); ?>>
            <?php do_action('generate_before_main_content'); ?>

                <?php echo do_action('ae_pro_search'); ?>

            <?php do_action('generate_after_main_content'); ?>
        </main><!-- #main -->
    </section><!-- #primary -->

<?php
generate_construct_sidebars();
get_footer();
?>