<?php
/**
 * Sanitization functions.
 *
 * @package Newsup
 */

if ( ! function_exists( 'newsup_sanitize_checkbox' ) ) :

    /**
     * Sanitize checkbox.
     *
     * @since 1.0.0
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool Whether the checkbox is checked.
     */
    function newsup_sanitize_checkbox( $checked ) {

        return ( ( isset( $checked ) && true === $checked ) ? true : false );

    }

endif;


if ( ! function_exists( 'newsup_sanitize_select' ) ) :

    /**
     * Sanitize select.
     *
     * @since 1.0.0
     *
     * @param mixed                $input The value to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return mixed Sanitized value.
     */
    function newsup_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_text_field( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

    }

endif;


if ( ! function_exists( 'newsup_sanitize_positive_integer' ) ) :

    /**
     * Sanitize positive integer.
     *
     * @since 1.0.0
     *
     * @param int                  $input Number to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return int Sanitized number; otherwise, the setting default.
     */
    function newsup_sanitize_positive_integer( $input, $setting ) {

        $input = absint( $input );

        // If the input is an absolute integer, return it.
        // otherwise, return the default.
        return ( $input ? $input : $setting->default );

    }

endif;


if ( ! function_exists( 'newsup_sanitize_number_range' ) ) :

    /**
     * Sanitize number range.
     *
     * @since 1.0.0
     *
     * @see absint() https://developer.wordpress.org/reference/functions/absint/
     *
     * @param int                  $input Number to check within the numeric range defined by the setting.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return int|string The number, if it is zero or greater and falls within the defined range; otherwise, the setting default.
     */
    function newsup_sanitize_number_range( $input, $setting ) {

        // Ensure input is an absolute integer.
        $input = absint( $input );

        // Get the input attributes associated with the setting.
        $atts = $setting->manager->get_control( $setting->id )->input_attrs;

        // Get min.
        $min = ( isset( $atts['min'] ) ? $atts['min'] : $input );

        // Get max.
        $max = ( isset( $atts['max'] ) ? $atts['max'] : $input );

        // Get Step.
        $step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

        // If the input is within the valid range, return it; otherwise, return the default.
        return ( $min <= $input && $input <= $max && is_int( $input / $step ) ? $input : $setting->default );

    }

endif;

if ( ! function_exists( 'newsup_sanitize_radio' ) ) :
function newsup_sanitize_radio( $val, $setting ) {
        $val = sanitize_key( $val );
        $choices = $setting->manager->get_control( $setting->id )->choices;
        return array_key_exists( $val, $choices ) ? $val : $setting->default;
    }
endif;


if ( ! function_exists( 'newsup_alpha_color_custom_sanitization_callback' ) ) :

	/**
 * Sanitize colors.
 *
 * @since 2.9.9.6
 * @param string $value The color.
 * @return string
 */
function newsup_alpha_color_custom_sanitization_callback( $value ) {
	// This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
	$pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';
	\preg_match( $pattern, $value, $matches );
	// Return the 1st match found.
	if ( isset( $matches[0] ) ) {
		if ( is_string( $matches[0] ) ) {
			return $matches[0];
		}
		if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
			return $matches[0][0];
		}
	}
	// If no match was found, return an empty string.
	return '';
}
endif;