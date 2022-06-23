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

			<?php
				if(is_archive() || is_post_type_archive()){
					do_action( 'generate_archive_title' );
				}
			?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_article_schema( 'CreativeWork' ); ?>>
				<div class="inside-article">
					<?php echo do_action('aepro_archive_data',get_the_content()); ?>
				</div>
			</article>


			<?php endwhile; ?>

			<?php generate_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>
		<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
generate_construct_sidebars();
get_footer();