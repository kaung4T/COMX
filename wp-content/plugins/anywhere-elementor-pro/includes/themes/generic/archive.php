<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeneratePress
 */
 
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) : ?>

    <?php  echo do_action('aepro_archive_data', get_the_content()); ?>

<?php else : ?>

	<?php get_template_part( 'no-results', 'archive' ); ?>

<?php endif;

get_footer();