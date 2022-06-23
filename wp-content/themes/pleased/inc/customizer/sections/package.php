<?php
/**
 * Package Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Package section
$wp_customize->add_section( 'pleased_package_section', array(
	'title'             => esc_html__( 'Package','pleased' ),
	'description'       => esc_html__( 'Package Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Package content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[package_section_enable]', array(
	'default'			=> 	$options['package_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[package_section_enable]', array(
	'label'             => esc_html__( 'Package Section Enable', 'pleased' ),
	'section'           => 'pleased_package_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// package title setting and control
$wp_customize->add_setting( 'pleased_theme_options[package_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['package_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[package_title]', array(
	'label'           	=> esc_html__( 'Title', 'pleased' ),
	'section'        	=> 'pleased_package_section',
	'active_callback' 	=> 'pleased_is_package_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'pleased_theme_options[package_title]', array(
		'selector'            => '#luxury-room .section-header h2.section-title',
		'settings'            => 'pleased_theme_options[package_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'pleased_package_title_partial',
    ) );
}

// Package content type control and setting
$wp_customize->add_setting( 'pleased_theme_options[package_content_type]', array(
	'default'          	=> $options['package_content_type'],
	'sanitize_callback' => 'pleased_sanitize_select',
) );

$wp_customize->add_control( 'pleased_theme_options[package_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'pleased' ),
	'section'           => 'pleased_package_section',
	'type'				=> 'select',
	'active_callback' 	=> 'pleased_is_package_section_enable',
	'choices'			=> pleased_package_content_type(),
) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[package_content_category]', array(
	'sanitize_callback' => 'pleased_sanitize_single_category',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Taxonomies_Control( $wp_customize,'pleased_theme_options[package_content_category]', array(
	'label'             => esc_html__( 'Select Category', 'pleased' ),
	'section'           => 'pleased_package_section',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_package_section_content_category_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[package_content_trip_types]', array(
	'sanitize_callback' => 'absint',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Taxonomies_Control( $wp_customize,'pleased_theme_options[package_content_trip_types]', array(
	'label'             => esc_html__( 'Select Trip Types', 'pleased' ),
	'section'           => 'pleased_package_section',
	'taxonomy'			=> 'itinerary_types',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_package_section_content_trip_types_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[package_content_activity]', array(
	'sanitize_callback' => 'absint',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Taxonomies_Control( $wp_customize,'pleased_theme_options[package_content_activity]', array(
	'label'             => esc_html__( 'Select Activities', 'pleased' ),
	'section'           => 'pleased_package_section',
	'taxonomy'			=> 'activity',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_package_section_content_activity_enable'
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'pleased_theme_options[package_content_destination]', array(
	'sanitize_callback' => 'absint',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Taxonomies_Control( $wp_customize,'pleased_theme_options[package_content_destination]', array(
	'label'             => esc_html__( 'Select Destinations', 'pleased' ),
	'section'           => 'pleased_package_section',
	'taxonomy'			=> 'travel_locations',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'pleased_is_package_section_content_destination_enable'
) ) );
