<?php
/**
 * Customizer default options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 * @return array An array of default values
 */

function pleased_get_default_theme_options() {
	$pleased_default_options = array(
		// Color Options
		'header_title_color'			=> '#fff',
		'header_tagline_color'			=> '#fff',
		'header_txt_logo_extra'			=> 'show-all',

		// breadcrumb
		'breadcrumb_enable'				=> true,
		'breadcrumb_separator'			=> '/',
		
		// layout 
		'site_layout'         			=> 'wide',
		'sidebar_position'         		=> 'right-sidebar',
		'post_sidebar_position' 		=> 'right-sidebar',
		'page_sidebar_position' 		=> 'right-sidebar',
		'nav_search_enable'				=> true,

		// excerpt options
		'long_excerpt_length'           => 25,
		'read_more_text'           		=> esc_html__( 'Read More', 'pleased' ),
		
		// pagination options
		'pagination_enable'         	=> true,
		'pagination_type'         		=> 'default',

		// footer options
		'copyright_text'           		=> sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'pleased' ), '[the-year]', '[site-link]' ),
		'scroll_top_visible'        	=> true,

		// reset options
		'reset_options'      			=> false,
		
		// homepage options
		'enable_frontpage_content' 		=> false,

		// blog/archive options
		'your_latest_posts_title' 		=> esc_html__( 'Blogs', 'pleased' ),
		'hide_date' 					=> false,

		// single post theme options
		'single_post_hide_date' 		=> false,
		'single_post_hide_author'		=> false,
		'single_post_hide_category'		=> false,
		'single_post_hide_tags'			=> false,

		/* Front Page */

		// Banner
		'banner_section_enable'			=> false,

		// About
		'about_section_enable'			=> false,
		'about_btn_title'				=> esc_html__( 'Discover More', 'pleased' ),

		// Gallery
		'gallery_section_enable'		=> false,
		'gallery_content_type'			=> 'category',
		'gallery_background_image'		=> get_template_directory_uri() . '/assets/uploads/gallery.jpg',

		// Package
		'package_section_enable'		=> false,
		'package_title'					=> esc_html__( 'Luxurious Packages', 'pleased' ),
		'package_content_type'			=> 'category',

		// Offer
		'offer_section_enable'			=> false,
		'offer_content_type'			=> 'page',
		'offer_btn_label'				=> esc_html__( 'Book Now', 'pleased' ),
		'offer_background_image'		=> get_template_directory_uri() . '/assets/uploads/special.jpg',

		// service
		'service_section_enable'		=> false,
		'service_title'					=> esc_html__( 'Our Services', 'pleased' ),

		// testimonial
		'testimonial_section_enable'	=> false,
		'testimonial_background_image'	=> get_template_directory_uri() . '/assets/uploads/testimonial.jpg',
		'testimonial_seperator_image'	=> get_template_directory_uri() . '/assets/uploads/testimonial-01.jpg',

		// blog
		'blog_section_enable'			=> false,
		'blog_title'					=> esc_html__( 'Our Latest Blogs', 'pleased' ),
		'blog_btn_title'				=> esc_html__( 'Discover More', 'pleased' ),

	);

	$output = apply_filters( 'pleased_default_theme_options', $pleased_default_options );

	// Sort array in ascending order, according to the key:
	if ( ! empty( $output ) ) {
		ksort( $output );
	}

	return $output;
}