<?php
/**
 * Post single content
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="entry-content clr" itemprop="text">
	<?php 

	//the_content();

	$global_ID = get_the_ID();
	$cptype = $post->post_type;

	$post_term_list = wp_get_post_terms( $global_ID, 'category_realizzazioni'); //$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'); 
	



	// foreach($term_list as $term_single) {
	// 		echo $term_single->slug; //do something here
	// }
	//if( is_tax( 'category_realizzazioni', 'Atelier' ) ){
	
	if( count($post_term_list) == 1 && has_term( 'atelier','category_realizzazioni', $global_ID ) ) {

		//echo 'aaaaaa';
		echo do_shortcode('[elementor-template id="452"]');
	}else{
		//echo 'bbbbbbb';
		//echo do_shortcode('[elementor-template id="175"]');
		
		the_content($global_ID);
	}

	wp_link_pages( array(
		'before'      => '<div class="page-links">' . __( 'Pages:', 'oceanwp' ),
		'after'       => '</div>',
		'link_before' => '<span class="page-number">',
		'link_after'  => '</span>',
	) ); ?>
</div><!-- .entry -->