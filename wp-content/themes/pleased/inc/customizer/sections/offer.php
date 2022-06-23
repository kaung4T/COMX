<?php
/**
 * Offers Section options
 *
 * @offer Theme Palace
 * @suboffer Pleased
 * @since Pleased 1.0.0
 */

// Add Offers section
$wp_customize->add_section( 'pleased_offer_section', array(
	'title'             => esc_html__( 'Special Offers','pleased' ),
	'description'       => esc_html__( 'Offers Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Offers content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[offer_section_enable]', array(
	'default'			=> 	$options['offer_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[offer_section_enable]', array(
	'label'             => esc_html__( 'Offers Section Enable', 'pleased' ),
	'section'           => 'pleased_offer_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// offer title setting and control
$wp_customize->add_setting( 'pleased_theme_options[offer_btn_label]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['offer_btn_label'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[offer_btn_label]', array(
	'label'           	=> esc_html__( 'Button Label', 'pleased' ),
	'section'        	=> 'pleased_offer_section',
	'active_callback' 	=> 'pleased_is_offer_section_enable',
	'type'				=> 'text',
) );

// banner image setting and control.
$wp_customize->add_setting( 'pleased_theme_options[offer_background_image]', array(
	'sanitize_callback' => 'pleased_sanitize_image',
	// 'default'          	=> $options['offer_background_image'],
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pleased_theme_options[offer_background_image]',
		array(
		'label'       		=> esc_html__( 'Background Image', 'pleased' ),
		'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'pleased' ), 1920, 1080 ),
		'section'     		=> 'pleased_offer_section',
		'active_callback'	=> 'pleased_is_offer_section_enable',
) ) );

// Offers content type control and setting
$wp_customize->add_setting( 'pleased_theme_options[offer_content_type]', array(
	'default'          	=> $options['offer_content_type'],
	'sanitize_callback' => 'pleased_sanitize_select',
) );

$wp_customize->add_control( 'pleased_theme_options[offer_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'pleased' ),
	'section'           => 'pleased_offer_section',
	'type'				=> 'select',
	'active_callback' 	=> 'pleased_is_offer_section_enable',
	'choices'			=> pleased_offer_content_type(),
) );

// offer pages drop down chooser control and setting
$wp_customize->add_setting( 'pleased_theme_options[offer_content_page]', array(
	'sanitize_callback' => 'pleased_sanitize_page',
) );

$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[offer_content_page]', array(
	'label'             => esc_html__( 'Select Page', 'pleased' ),
	'section'           => 'pleased_offer_section',
	'choices'			=> pleased_page_choices(),
	'active_callback'	=> 'pleased_is_offer_section_content_page_enable',
) ) );

// offer trips drop down chooser control and setting
$wp_customize->add_setting( 'pleased_theme_options[offer_content_trip]', array(
	'sanitize_callback' => 'pleased_sanitize_page',
) );

$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[offer_content_trip]', array(
	'label'             => esc_html__( 'Select Trip', 'pleased' ),
	'section'           => 'pleased_offer_section',
	'choices'			=> pleased_trip_choices(),
	'active_callback'	=> 'pleased_is_offer_section_content_trip_enable',
) ) );

