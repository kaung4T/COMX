<?php
/**
 * Search Template
 *
 * @package Page Builder Framework
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$grid_gap = get_theme_mod( 'sidebar_gap' );
$grid_gap ? true : $grid_gap = "divider";

get_header(); ?>

<div id="content">

    <div id="inner-content" class="wpbf-container wpbf-container-center">

        <div class="wpbf-grid wpbf-grid-<?php echo esc_attr( $grid_gap ); ?>">

            <?php do_action( 'wpbf_sidebar_left' ); ?>

            <main id="main" class="wpbf-main wpbf-search-content wpbf-medium-2-3">

                <?php echo do_action('ae_pro_search'); ?>

            </main>

            <?php do_action( 'wpbf_sidebar_right' ); ?>

        </div>

    </div>

</div>

<?php get_footer(); ?>
