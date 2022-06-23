<?php
/**
 * Breadcrumb options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

$wp_customize->add_section( 'pleased_breadcrumb', array(
	'title'             => esc_html__( 'Breadcrumb','pleased' ),
	'description'       => esc_html__( 'Breadcrumb section options.', 'pleased' ),
	'panel'             => 'pleased_theme_options_panel',
) );

// Breadcrumb enable setting and control.
$wp_customize->add_setting( 'pleased_theme_options[breadcrumb_enable]', array(
	'sanitize_callback' => 'pleased_sanitize_switch_control',
	'default'          	=> $options['breadcrumb_enable'],
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[breadcrumb_enable]', array(
	'label'            	=> esc_html__( 'Enable Breadcrumb', 'pleased' ),
	'section'          	=> 'pleased_breadcrumb',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// Breadcrumb separator setting and control.
$wp_customize->add_setting( 'pleased_theme_options[breadcrumb_separator]', array(
	'sanitize_callback'	=> 'sanitize_text_field',
	'default'          	=> $options['breadcrumb_separator'],
) );

$wp_customize->add_control( 'pleased_theme_options[breadcrumb_separator]', array(
	'label'            	=> esc_html__( 'Separator', 'pleased' ),
	'active_callback' 	=> 'pleased_is_breadcrumb_enable',
	'section'          	=> 'pleased_breadcrumb',
) );
