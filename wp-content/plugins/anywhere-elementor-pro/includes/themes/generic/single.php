<?php
/**
 * The Template for displaying all single posts.
 *
 * @package GeneratePress
 */

use Elementor\Plugin;
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();


while ( have_posts() ) : the_post();
    do_action('aepro_single_data');
endwhile;


get_footer();