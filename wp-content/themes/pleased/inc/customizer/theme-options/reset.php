<?php
/**
 * Reset options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

/**
* Reset section
*/
// Add reset enable section
$wp_customize->add_section( 'pleased_reset_section', array(
	'title'             => esc_html__('Reset all settings','pleased'),
	'description'       => esc_html__( 'Caution: All settings will be reset to default. Refresh the page after clicking Save & Publish.', 'pleased' ),
) );

// Add reset enable setting and control.
$wp_customize->add_setting( 'pleased_theme_options[reset_options]', array(
	'default'           => $options['reset_options'],
	'sanitize_callback' => 'pleased_sanitize_checkbox',
	'transport'			  => 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[reset_options]', array(
	'label'             => esc_html__( 'Check to reset all settings', 'pleased' ),
	'section'           => 'pleased_reset_section',
	'type'              => 'checkbox',
) );
