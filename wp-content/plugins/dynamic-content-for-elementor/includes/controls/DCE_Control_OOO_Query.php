<?php
namespace DynamicContentForElementor\Controls;

use \Elementor\Control_Select2;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Control Query
 */
class DCE_Control_OOO_Query extends Control_Select2 {

	/**
	 * Get control type.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'ooo_query';                
	}

}