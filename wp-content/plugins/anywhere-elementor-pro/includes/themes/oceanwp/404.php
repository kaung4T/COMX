<?php
/**
 * The template for displaying 404 pages.
 *
 * @package OceanWP WordPress theme
 */

get_header(); ?>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<div id="content-wrap" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

		<div id="primary" class="content-area clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="clr site-content">

				<?php do_action( 'ocean_before_content_inner' ); ?>

				<article class="entry clr">

					<div class="error404-content clr">

						<?php echo do_action('aepro_404'); ?>

					</div><!-- .error404-content -->

				</article><!-- .entry -->

				<?php do_action( 'ocean_after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'ocean_after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'ocean_after_primary' ); ?>

	</div><!--#content-wrap -->

	<?php do_action( 'ocean_after_content_wrap' ); ?>
	
<?php get_footer(); ?>
