<?php
/**
 * Default post entry layout
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post format
$format = get_post_format();

// Quote format is completely different
if ( 'quote' == $format ) {

	// Get quote entry content
	include plugin_dir_path( __FILE__ ) . 'entry/quote.php';
	//get_template_part( 'partials/entry/quote' );

	return;

}

// Add classes to the blog entry post class
$classes = oceanwp_post_entry_classes(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="realizzazioni-entry-inner clr">
	
		<?php
		// Get elements
		$elements = oceanwp_blog_entry_elements_positioning();

		// Loop through elements
		foreach ( $elements as $element ) {

			// Featured Image
			if ( 'featured_image' == $element ) {
				include plugin_dir_path( __FILE__ ) . 'media/blog-entry.php';
				//get_template_part( 'partials/entry/media/blog-entry', $format );

			}

			// Title
			if ( 'title' == $element ) {
				include plugin_dir_path( __FILE__ ) . 'header.php';
				//get_template_part( 'partials/entry/header' );

			}

			// Meta
			if ( 'meta' == $element ) {
				include plugin_dir_path( __FILE__ ) . 'meta.php';
				//get_template_part( 'partials/entry/meta' );

			}

			// Content
			if ( 'content' == $element ) {

				include plugin_dir_path( __FILE__ ) . 'content.php';
				//get_template_part( 'partials/entry/content' );

			}

			// Read more button
			if ( 'read_more' == $element ) {
				include plugin_dir_path( __FILE__ ) . 'readmore.php';
				//get_template_part( 'partials/entry/readmore' );

			}

		} ?>

	</div><!-- .realizzazioni-entry-inner -->

</article><!-- #post-## -->