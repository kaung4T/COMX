<?php
/**
 * Customizer sanitization functions
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

if ( ! function_exists( 'pleased_sanitize_select' ) ) :
	/**
	 * Sanitize select, radio.
	 *
	 * @since Pleased 1.0.0
	 *
	 * @param mixed                $input The value to sanitize.
	 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
	 * @return mixed Sanitized value.
	 */
	function pleased_sanitize_select( $input, $setting ) {
		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
endif;

if ( ! function_exists( 'pleased_sanitize_image' ) ) :
	/**
	 * Image sanitization callback example.
	 *
	 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
	 * send back the filename, otherwise, return the setting default.
	 *
	 * - Sanitization: image file extension
	 * - Control: text, WP_Customize_Image_Control
	 *
	 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
	 *
	 * @param string               $image   Image filename.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	function pleased_sanitize_image( $image, $setting ) {
		/*
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		 */
	    $mimes = array(
	        'jpg|jpeg|jpe' => 'image/jpeg',
	        'gif'          => 'image/gif',
	        'png'          => 'image/png',
	        'bmp'          => 'image/bmp',
	        'tif|tiff'     => 'image/tiff',
	        'ico'          => 'image/x-icon'
	    );
		// Return an array with file extension and mime_type.
	   $file = wp_check_filetype( $image, $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
	   return ( $file['ext'] ? $image : $setting->default );
	}
endif;

if ( ! function_exists( 'pleased_sanitize_number_range' ) ) :
	/**
	 * Number Range sanitization callback example.
	 *
	 * - Sanitization: number_range
	 * - Control: number, tel
	 *
	 * Sanitization callback for 'number' or 'tel' type text inputs. This callback sanitizes
	 * `$number` as an absolute integer within a defined min-max range.
	 *
	 * @see absint() https://developer.wordpress.org/reference/functions/absint/
	 *
	 * @param int                  $number  Number to check within the numeric range defined by the setting.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return int|string The number, if it is zero or greater and falls within the defined range; otherwise,
	 *                    the setting default.
	 */
	function pleased_sanitize_number_range( $number, $setting ) {
		// Ensure input is an absolute integer.
		$number = absint( $number );

		// Get the input attributes associated with the setting.
		$atts = $setting->manager->get_control( $setting->id )->input_attrs;

		// Get minimum number in the range.
		$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );

		// Get maximum number in the range.
		$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );

		// Get step.
		$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

		// If the number is within the valid range, return it; otherwise, return the default
		return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
	}
endif;

if ( ! function_exists( 'pleased_sanitize_page' ) ) :
	/**
	* Sanitizes page/post
	* @param  $input entered value
	* @return sanitized output
	*
	* @since Pleased 1.0.0
	*/
	function pleased_sanitize_page( $input ) {

		// Ensure $input is an absolute integer.
		$page_id = absint( $input );

		// If $page_id is an ID of a published page, return it; otherwise, return false
		return ( 'publish' == get_post_status( $page_id ) ? $page_id : false );
	
	}
endif;

if ( ! function_exists( 'pleased_sanitize_checkbox' ) ) :
	/**
	 * Sanitize checkbox.
	 *
	 * @since Pleased 1.0.0
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 * @return bool Whether the checkbox is checked.
	 */
	function pleased_sanitize_checkbox( $checked ) {

		return ( ( isset( $checked ) && true == $checked ) ? true : false );

	}
endif;

if ( ! function_exists( 'pleased_sanitize_category_list' ) ) :
	/**
	 * Sanitizes category list
	 * @param  $input entered value
	 * @return sanitized output
	 *
	 * @since Pleased 1.0.0
	 */
	function pleased_sanitize_category_list( $input ) {
		if ( $input != '' ) {
			$args = array(
							'type'			=> 'post',
							'child_of'      => 0,
							'parent'        => '',
							'orderby'       => 'name',
							'order'         => 'ASC',
							'hide_empty'    => 0,
							'hierarchical'  => 0,
							'taxonomy'      => 'category',
						);

			$categories = ( get_categories( $args ) );

			$category_list 	=	array();

			foreach ( $categories as $category )
				$category_list 	=	array_merge( $category_list, array( $category->term_id ) );

			if ( count( array_intersect( $input, $category_list ) ) == count( $input ) ) {
		    	return $input;
		    }
		    else {
	    		return '';
	   		}
	    }
	    else {
	    	return '';
	    }
	}
endif;

if( ! function_exists( 'pleased_sanitize_single_category' ) ) :
	/**
	 * Sanitizes dropdown single category
	 * @param  $input entered value
	 * @return sanitized output
	 *
	 * @since Pleased 1.0.0
	 */
	function pleased_sanitize_single_category( $input ) {
		if ( $input != '' ) {
			$args = array(
							'type'			=> 'post',
							'child_of'      => 0,
							'parent'        => '',
							'orderby'       => 'name',
							'order'         => 'ASC',
							'hide_empty'    => 0,
							'hierarchical'  => 0,
							'taxonomy'      => 'category',
						);

			$categories = get_categories( $args );

			foreach ( $categories as $category )
				$category_ids[] 	= $category->term_id;

			if ( in_array( $input, $category_ids ) ) {
		    	return $input;
		    }
		    else {
	    		return '';
	   		}
	    }
	    else {
	    	return '';
	    }
	}
endif;

if ( ! function_exists( 'pleased_santize_allow_tag' ) ) :
	/**
	 * Text field with allowed tag anchor sanitization callback example.
	 *
	 * @see absint() https://developer.wordpress.org/reference/functions/absint/
	 *
	 * @param string  $input  
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string The input with only allowed tag i.e. anchor
	 */
	function pleased_santize_allow_tag( $input ) {
		$input = wp_kses( $input, array(
			'br' => array(),
			'a' => array(
				'target' => array(),
				'href' => array(),
				)
			) );

		return $input;
	}
endif;

/**
 * Sanitize data from custom Switch Control class.
 * @param  string $input 
 * @return boolean    
 */
function pleased_sanitize_switch_control( $input ) {
	$input = sanitize_text_field( $input );
	if ( 'false' === $input ) {
		return false;
	} else {
		return true;
	}
}

if ( ! function_exists( 'pleased_sanitize_integers' ) ) :
	/**
	* Sanitizes integers
	* @param  $input entered value
	* @return sanitized output
	*
	* @since Travel Insight Pro 1.0
	*/
	function pleased_sanitize_integers( $input ) {

		// Ensure $input is an absolute integer.
		$output = array_map( 'absint', $input );

		return $output;
	}
endif;
