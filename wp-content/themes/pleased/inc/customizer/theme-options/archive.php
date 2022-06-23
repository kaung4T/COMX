<?php
/**
 * Archive options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add archive section
$wp_customize->add_section( 'pleased_archive_section', array(
	'title'             => esc_html__( 'Blog/Archive','pleased' ),
	'description'       => esc_html__( 'Archive section options.', 'pleased' ),
	'panel'             => 'pleased_theme_options_panel',
) );

// Your latest posts title setting and control.
$wp_customize->add_setting( 'pleased_theme_options[your_latest_posts_title]', array(
	'default'           => $options['your_latest_posts_title'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'pleased_theme_options[your_latest_posts_title]', array(
	'label'             => esc_html__( 'Your Latest Posts Title', 'pleased' ),
	'description'       => esc_html__( 'This option only works if Static Front Page is set to "Your latest posts."', 'pleased' ),
	'section'           => 'pleased_archive_section',
	'type'				=> 'text',
	'active_callback'   => 'pleased_is_latest_posts'
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'pleased_theme_options[hide_date]', array(
	'default'           => $options['hide_date'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'pleased' ),
	'section'           => 'pleased_archive_section',
	'on_off_label' 		=> pleased_hide_options(),
) ) );
