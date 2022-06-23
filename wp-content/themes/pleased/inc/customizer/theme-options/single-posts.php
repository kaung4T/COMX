<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'pleased_single_post_section', array(
	'title'             => esc_html__( 'Single Post','pleased' ),
	'description'       => esc_html__( 'Options to change the single posts globally.', 'pleased' ),
	'panel'             => 'pleased_theme_options_panel',
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'pleased_theme_options[single_post_hide_date]', array(
	'default'           => $options['single_post_hide_date'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[single_post_hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'pleased' ),
	'section'           => 'pleased_single_post_section',
	'on_off_label' 		=> pleased_hide_options(),
) ) );

// Archive author meta setting and control.
$wp_customize->add_setting( 'pleased_theme_options[single_post_hide_author]', array(
	'default'           => $options['single_post_hide_author'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[single_post_hide_author]', array(
	'label'             => esc_html__( 'Hide Author', 'pleased' ),
	'section'           => 'pleased_single_post_section',
	'on_off_label' 		=> pleased_hide_options(),
) ) );

// Archive author category setting and control.
$wp_customize->add_setting( 'pleased_theme_options[single_post_hide_category]', array(
	'default'           => $options['single_post_hide_category'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[single_post_hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'pleased' ),
	'section'           => 'pleased_single_post_section',
	'on_off_label' 		=> pleased_hide_options(),
) ) );

// Archive tag category setting and control.
$wp_customize->add_setting( 'pleased_theme_options[single_post_hide_tags]', array(
	'default'           => $options['single_post_hide_tags'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[single_post_hide_tags]', array(
	'label'             => esc_html__( 'Hide Tag', 'pleased' ),
	'section'           => 'pleased_single_post_section',
	'on_off_label' 		=> pleased_hide_options(),
) ) );
