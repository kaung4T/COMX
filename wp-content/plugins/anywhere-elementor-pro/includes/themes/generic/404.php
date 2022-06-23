 <?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

 echo do_action('aepro_404');

get_footer();