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

get_header(); ?>

	<section id="primary" <?php generate_do_element_classes( 'content' ); ?>>
		<main id="main" <?php generate_do_element_classes( 'main' ); ?>>
		<?php do_action('generate_before_main_content'); ?>
		<?php if ( have_posts() ) : ?>

			<?php  echo do_action('aepro_archive_data',get_the_content()); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>
		<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php 
generate_construct_sidebars();
get_footer();