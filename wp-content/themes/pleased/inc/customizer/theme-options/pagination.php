<?php
/**
 * pagination options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'pleased_pagination', array(
	'title'               => esc_html__('Pagination','pleased'),
	'description'         => esc_html__( 'Pagination section options.', 'pleased' ),
	'panel'               => 'pleased_theme_options_panel',
) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'pleased_theme_options[pagination_enable]', array(
	'sanitize_callback' => 'pleased_sanitize_switch_control',
	'default'             => $options['pagination_enable'],
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[pagination_enable]', array(
	'label'               => esc_html__( 'Pagination Enable', 'pleased' ),
	'section'             => 'pleased_pagination',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// Site layout setting and control.
$wp_customize->add_setting( 'pleased_theme_options[pagination_type]', array(
	'sanitize_callback'   => 'pleased_sanitize_select',
	'default'             => $options['pagination_type'],
) );

$wp_customize->add_control( 'pleased_theme_options[pagination_type]', array(
	'label'               => esc_html__( 'Pagination Type', 'pleased' ),
	'section'             => 'pleased_pagination',
	'type'                => 'select',
	'choices'			  => pleased_pagination_options(),
	'active_callback'	  => 'pleased_is_pagination_enable',
) );
