<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>


<?php echo do_action('ae_pro_search'); ?>



<?php get_footer(); ?>