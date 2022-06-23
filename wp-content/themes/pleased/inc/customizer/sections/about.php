<?php
/**
 * About Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add About section
$wp_customize->add_section( 'pleased_about_section', array(
	'title'             => esc_html__( 'About Us','pleased' ),
	'description'       => esc_html__( 'About Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// About content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[about_section_enable]', array(
	'default'			=> 	$options['about_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[about_section_enable]', array(
	'label'             => esc_html__( 'About Section Enable', 'pleased' ),
	'section'           => 'pleased_about_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// about pages drop down chooser control and setting
$wp_customize->add_setting( 'pleased_theme_options[about_content_page]', array(
	'sanitize_callback' => 'pleased_sanitize_page',
) );

$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[about_content_page]', array(
	'label'             => esc_html__( 'Select Page', 'pleased' ),
	'section'           => 'pleased_about_section',
	'choices'			=> pleased_page_choices(),
	'active_callback'	=> 'pleased_is_about_section_enable',
) ) );

// about btn title setting and control
$wp_customize->add_setting( 'pleased_theme_options[about_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['about_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[about_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'pleased' ),
	'section'        	=> 'pleased_about_section',
	'active_callback' 	=> 'pleased_is_about_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'pleased_theme_options[about_btn_title]', array(
		'selector'            => '#about-us .section-content a.btn-default',
		'settings'            => 'pleased_theme_options[about_btn_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'pleased_about_btn_title_partial',
    ) );
}
