<?php
/**
 * Gallery Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Gallery section
$wp_customize->add_section( 'pleased_gallery_section', array(
	'title'             => esc_html__( 'Gallery','pleased' ),
	'description'       => esc_html__( 'Gallery Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Gallery content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[gallery_section_enable]', array(
	'default'			=> 	$options['gallery_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[gallery_section_enable]', array(
	'label'             => esc_html__( 'Gallery Section Enable', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// Gallery content type control and setting
$wp_customize->add_setting( 'pleased_theme_options[gallery_content_type]', array(
	'default'          	=> $options['gallery_content_type'],
	'sanitize_callback' => 'pleased_sanitize_select',
) );

$wp_customize->add_control( 'pleased_theme_options[gallery_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'type'				=> 'select',
	'active_callback' 	=> 'pleased_is_gallery_section_enable',
	'choices'			=> pleased_gallery_content_type(),
) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[gallery_content_category]', array(
	'sanitize_callback' => 'pleased_sanitize_category_list',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Category_Control( $wp_customize,'pleased_theme_options[gallery_content_category]', array(
	'label'             => esc_html__( 'Select Category', 'pleased' ),
	'description'     => esc_html__( 'Note: Press Ctrl and Click on multiple categories to select. Only the Posts that has featured image will be shown from selected categories will be shown.', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_gallery_section_content_category_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[gallery_content_trip_types]', array(
	'sanitize_callback' => 'pleased_sanitize_integers',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Category_Control( $wp_customize,'pleased_theme_options[gallery_content_trip_types]', array(
	'label'             => esc_html__( 'Select Trip Types', 'pleased' ),
	'description'     => esc_html__( 'Note: Press Ctrl and Click on multiple categories to select. Only the Posts that has featured image will be shown from selected categories will be shown.', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'taxonomy'			=> 'itinerary_types',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_gallery_section_content_trip_types_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[gallery_content_activity]', array(
	'sanitize_callback' => 'pleased_sanitize_integers',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Category_Control( $wp_customize,'pleased_theme_options[gallery_content_activity]', array(
	'label'             => esc_html__( 'Select Activities', 'pleased' ),
	'description'     => esc_html__( 'Note: Press Ctrl and Click on multiple categories to select. Only the Posts that has featured image will be shown from selected categories will be shown.', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'taxonomy'			=> 'activity',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_gallery_section_content_activity_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[gallery_content_destination]', array(
	'sanitize_callback' => 'pleased_sanitize_integers',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Category_Control( $wp_customize,'pleased_theme_options[gallery_content_destination]', array(
	'label'             => esc_html__( 'Select Destinations', 'pleased' ),
	'description'     => esc_html__( 'Note: Press Ctrl and Click on multiple categories to select. Only the Posts that has featured image will be shown from selected categories will be shown.', 'pleased' ),
	'section'           => 'pleased_gallery_section',
	'taxonomy'			=> 'travel_locations',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_gallery_section_content_destination_enable'
) ) );

// banner image setting and control.
$wp_customize->add_setting( 'pleased_theme_options[gallery_background_image]', array(
	'sanitize_callback' => 'pleased_sanitize_image',
	'default'          	=> $options['gallery_background_image'],
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pleased_theme_options[gallery_background_image]',
		array(
		'label'       		=> esc_html__( 'Background Image', 'pleased' ),
		'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'pleased' ), 1920, 525 ),
		'section'     		=> 'pleased_gallery_section',
		'active_callback'	=> 'pleased_is_gallery_section_enable',
) ) );
