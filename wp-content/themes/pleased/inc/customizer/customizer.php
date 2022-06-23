<?php
/**
 * Pleased Customizer.
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

//load upgrade-to-pro section
require get_template_directory() . '/inc/customizer/upgrade-to-pro/class-customize.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pleased_customize_register( $wp_customize ) {
	$options = pleased_get_theme_options();

	// Load custom control functions.
	require get_template_directory() . '/inc/customizer/custom-controls.php';

	// Load customize active callback functions.
	require get_template_directory() . '/inc/customizer/active-callback.php';

	// Load partial callback functions.
	require get_template_directory() . '/inc/customizer/partial.php';

	// Load validation callback functions.
	require get_template_directory() . '/inc/customizer/validation.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	// Remove the core header textcolor control, as it shares the main text color.
	$wp_customize->remove_control( 'header_textcolor' );

	// Header title color setting and control.
	$wp_customize->add_setting( 'pleased_theme_options[header_title_color]', array(
		'default'           => $options['header_title_color'],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pleased_theme_options[header_title_color]', array(
		'priority'			=> 5,
		'label'             => esc_html__( 'Header Title Color', 'pleased' ),
		'section'           => 'colors',
	) ) );

	// Header tagline color setting and control.
	$wp_customize->add_setting( 'pleased_theme_options[header_tagline_color]', array(
		'default'           => $options['header_tagline_color'],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pleased_theme_options[header_tagline_color]', array(
		'priority'			=> 6,
		'label'             => esc_html__( 'Header Tagline Color', 'pleased' ),
		'section'           => 'colors',
	) ) );

	// Site identity extra options.
	$wp_customize->add_setting( 'pleased_theme_options[header_txt_logo_extra]', array(
		'default'           => $options['header_txt_logo_extra'],
		'sanitize_callback' => 'pleased_sanitize_select',
		'transport'			=> 'refresh'
	) );

	$wp_customize->add_control( 'pleased_theme_options[header_txt_logo_extra]', array(
		'priority'			=> 50,
		'type'				=> 'radio',
		'label'             => esc_html__( 'Site Identity Extra Options', 'pleased' ),
		'section'           => 'title_tagline',
		'choices'				=> array( 
			'hide-all'     => esc_html__( 'Hide All', 'pleased' ),
			'show-all'     => esc_html__( 'Show All', 'pleased' ),
			'title-only'   => esc_html__( 'Title Only', 'pleased' ),
			'tagline-only' => esc_html__( 'Tagline Only', 'pleased' ),
			'logo-title'   => esc_html__( 'Logo + Title', 'pleased' ),
			'logo-tagline' => esc_html__( 'Logo + Tagline', 'pleased' ),
			)
	) );

	// Add panel for common theme options
	$wp_customize->add_panel( 'pleased_theme_options_panel' , array(
	    'title'      => esc_html__( 'Theme Options','pleased' ),
	    'description'=> esc_html__( 'Pleased Theme Options.', 'pleased' ),
	    'priority'   => 150,
	) );

	// breadcrumb
	require get_template_directory() . '/inc/customizer/theme-options/breadcrumb.php';

	// load layout
	require get_template_directory() . '/inc/customizer/theme-options/layout.php';

	// load menu
	require get_template_directory() . '/inc/customizer/theme-options/menu.php';

	// load static homepage option
	require get_template_directory() . '/inc/customizer/theme-options/homepage-static.php';

	// load archive option
	require get_template_directory() . '/inc/customizer/theme-options/excerpt.php';

	// load archive option
	require get_template_directory() . '/inc/customizer/theme-options/archive.php';
	
	// load single post option
	require get_template_directory() . '/inc/customizer/theme-options/single-posts.php';

	// load pagination option
	require get_template_directory() . '/inc/customizer/theme-options/pagination.php';

	// load footer option
	require get_template_directory() . '/inc/customizer/theme-options/footer.php';

	// load reset option
	require get_template_directory() . '/inc/customizer/theme-options/reset.php';

	// Add panel for front page theme options.
	$wp_customize->add_panel( 'pleased_front_page_panel' , array(
	    'title'      => esc_html__( 'Front Page','pleased' ),
	    'description'=> esc_html__( 'Front Page Theme Options.', 'pleased' ),
	    'priority'   => 140,
	) );

	// load banner option
	require get_template_directory() . '/inc/customizer/sections/banner.php';

	// load about option
	require get_template_directory() . '/inc/customizer/sections/about.php';

	// load gallery option
	require get_template_directory() . '/inc/customizer/sections/gallery.php';

	// load package option
	require get_template_directory() . '/inc/customizer/sections/package.php';

	// load offer option
	require get_template_directory() . '/inc/customizer/sections/offer.php';

	// load service option
	require get_template_directory() . '/inc/customizer/sections/service.php';

	// load testimonial option
	require get_template_directory() . '/inc/customizer/sections/testimonial.php';

	// load blog option
	require get_template_directory() . '/inc/customizer/sections/blog.php';

}
add_action( 'customize_register', 'pleased_customize_register' );

/*
 * Load customizer sanitization functions.
 */
require get_template_directory() . '/inc/customizer/sanitize.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pleased_customize_preview_js() {
	wp_enqueue_script( 'pleased-customizer', get_template_directory_uri() . '/assets/js/customizer' . pleased_min() . '.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'pleased_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function pleased_customize_control_js() {
	// fontawesome
	wp_enqueue_style( 'font-awesome-css', get_template_directory_uri() . '/assets/css/font-awesome' . pleased_min() . '.css' );
	
	// Choose from select jquery.
	wp_enqueue_style( 'chosen-css', get_template_directory_uri() . '/assets/css/chosen' . pleased_min() . '.css' );
	wp_enqueue_script( 'jquery-chosen', get_template_directory_uri() . '/assets/js/chosen.jquery' . pleased_min() . '.js', array( 'jquery' ), '1.4.2', true );

	// simple icon picker
	wp_enqueue_style( 'simple-iconpicker-css', get_template_directory_uri() . '/assets/css/simple-iconpicker' . pleased_min() . '.css' );
	wp_enqueue_script( 'jquery-simple-iconpicker', get_template_directory_uri() . '/assets/js/simple-iconpicker' . pleased_min() . '.js', array( 'jquery' ), '', true );

	wp_enqueue_style( 'pleased-customize-controls-css', get_template_directory_uri() . '/assets/css/customize-controls' . pleased_min() . '.css' );
	wp_enqueue_script( 'pleased-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls' . pleased_min() . '.js', array(), '1.0', true );
	$pleased_reset_data = array(
		'reset_message' => esc_html__( 'Refresh the customizer page after saving to view reset effects', 'pleased' )
	);
	// Send list of color variables as object to custom customizer js
	wp_localize_script( 'pleased-customize-controls', 'pleased_reset_data', $pleased_reset_data );
}
add_action( 'customize_controls_enqueue_scripts', 'pleased_customize_control_js' );

if ( !function_exists( 'pleased_reset_options' ) ) :
	/**
	 * Reset all options
	 *
	 * @since Pleased 1.0.0
	 *
	 * @param bool $checked Whether the reset is checked.
	 * @return bool Whether the reset is checked.
	 */
	function pleased_reset_options() {
		$options = pleased_get_theme_options();
		if ( true === $options['reset_options'] ) {
			// Reset custom theme options.
			set_theme_mod( 'pleased_theme_options', array() );
			// Reset custom header and backgrounds.
			remove_theme_mod( 'header_image' );
			remove_theme_mod( 'header_image_data' );
			remove_theme_mod( 'background_image' );
			remove_theme_mod( 'background_color' );
			remove_theme_mod( 'header_textcolor' );
	    }
	  	else {
		    return false;
	  	}
	}
endif;
add_action( 'customize_save_after', 'pleased_reset_options' );
