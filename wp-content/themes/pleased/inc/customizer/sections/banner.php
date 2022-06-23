<?php
/**
 * Banner Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Banner section
$wp_customize->add_section( 'pleased_banner_section', array(
	'title'             => esc_html__( 'Banner','pleased' ),
	'description'       => esc_html__( 'Banner Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Banner content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[banner_section_enable]', array(
	'default'			=> 	$options['banner_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[banner_section_enable]', array(
	'label'             => esc_html__( 'Banner Section Enable', 'pleased' ),
	'section'           => 'pleased_banner_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// banner pages drop down chooser control and setting
$wp_customize->add_setting( 'pleased_theme_options[banner_content_page]', array(
	'sanitize_callback' => 'pleased_sanitize_page',
) );

$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[banner_content_page]', array(
	'label'             => esc_html__( 'Select Page', 'pleased' ),
	'section'           => 'pleased_banner_section',
	'choices'			=> pleased_page_choices(),
	'active_callback'	=> 'pleased_is_banner_section_enable',
) ) );
