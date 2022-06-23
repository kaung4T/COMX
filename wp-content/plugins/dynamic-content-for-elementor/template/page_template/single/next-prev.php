<?php
/**
 * The next/previous links to go to another post.
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Term
$typePost = get_post_type( get_the_ID() );
$term_tax = get_theme_mod( 'odynty_single_post_next_prev_taxonomy_'.$typePost, '' );
$term_tax = $term_tax ? $term_tax : '';
//echo 'blog nav '.$term_tax;

the_post_navigation( array(
    'prev_text'             => '<span class="title"><i class="fa fa-long-arrow-left"></i>%title</span>',
    'next_text'             => '<span class="title"><i class="fa fa-long-arrow-right"></i>%title</span>',
    'in_same_term'          => true,
    //'excluded_terms' 		=> array('18'),
    'taxonomy'              => esc_html__( $term_tax, 'oceanwp' ),
    'screen_reader_text'    => esc_html__( 'Continue Reading', 'oceanwp' ),
) ); ?>