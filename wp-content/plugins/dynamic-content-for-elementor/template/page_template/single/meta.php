<?php
/**
 * Post single meta
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get meta sections
$sections = oceanwp_blog_single_meta();

// Return if sections are empty
if ( empty( $sections ) ) {
	return;
}

// Return if quote format
if ( 'quote' == get_post_format() ) {
	return;
} ?>

<ul class="meta clr">

	<?php
	// Loop through meta sections
	foreach ( $sections as $section ) { ?>

		<?php if ( 'author' == $section ) { ?>
			<li class="meta-author" itemprop="name"><i class="icon-user"></i><?php echo the_author_posts_link(); ?></li>
		<?php } ?>

		<?php if ( 'date' == $section ) { ?>
			<li class="meta-date" itemprop="datePublished"><i class="icon-clock"></i><?php echo get_the_date(); ?></li>
		<?php } ?>

		<?php if ( 'categories' == $section ) { ?>
			<li class="meta-cat"><i class="icon-folder"></i><?php the_taxonomies( ' / ', get_the_ID() ); ?></li>
		<?php } ?>

		<?php if ( 'comments' == $section && comments_open() && ! post_password_required() ) { ?>
			<li class="meta-comments"><i class="icon-bubble"></i><?php comments_popup_link( esc_html__( '0 Comments', 'oceanwp' ), esc_html__( '1 Comment',  'oceanwp' ), esc_html__( '% Comments', 'oceanwp' ), 'comments-link' ); ?></li>
		<?php } ?>

	<?php } ?>
	
</ul>