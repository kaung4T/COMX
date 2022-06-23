<?php
/**
* Homepage (Static ) options
*
* @package Theme Palace
* @subpackage Pleased
* @since Pleased 1.0.0
*/

// Homepage (Static ) setting and control.
$wp_customize->add_setting( 'pleased_theme_options[enable_frontpage_content]', array(
	'sanitize_callback'   => 'pleased_sanitize_checkbox',
	'default'             => $options['enable_frontpage_content'],
) );

$wp_customize->add_control( 'pleased_theme_options[enable_frontpage_content]', array(
	'label'       	=> esc_html__( 'Enable Content', 'pleased' ),
	'description' 	=> esc_html__( 'Check to enable content on static front page only.', 'pleased' ),
	'section'     	=> 'static_front_page',
	'type'        	=> 'checkbox',
) );