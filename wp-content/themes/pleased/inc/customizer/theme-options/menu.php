<?php
/**
 * Menu options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'pleased_menu', array(
	'title'             => esc_html__('Header Menu','pleased'),
	'description'       => esc_html__( 'Header Menu options.', 'pleased' ),
	'panel'             => 'nav_menus',
) );

// search enable setting and control.
$wp_customize->add_setting( 'pleased_theme_options[nav_search_enable]', array(
	'sanitize_callback' => 'pleased_sanitize_switch_control',
	'default'           => $options['nav_search_enable'],
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[nav_search_enable]', array(
	'label'             => esc_html__( 'Enable search', 'pleased' ),
	'section'           => 'pleased_menu',
	'on_off_label' 		=> pleased_switch_options(),
) ) );
