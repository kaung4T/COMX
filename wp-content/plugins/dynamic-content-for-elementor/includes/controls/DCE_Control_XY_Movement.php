<?php
namespace DynamicContentForElementor\Controls;

use Elementor\Control_Base_Multiple;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor XY control.
 *
 */
class DCE_Control_XY_Movement extends Control_Base_Multiple {

	/**
	 * Get box shadow control type.
	 *
	 * Retrieve the control type, in this case `xy Movement`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'xy_movement';
	}
	public function enqueue() {
		// Styles
		//wp_register_style( 'emojionearea', 'https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.css', [], '3.4.1' );
		//wp_enqueue_style( 'emojionearea' );

		// Scripts
		//wp_register_script( 'emojionearea', 'https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.js', [], '3.4.1' );
		wp_register_script( 'xy-movement-control', plugins_url('/assets/js/xy_movement-control.js', DCE__FILE__), [ 'jquery' ], '1.0.0' );
		wp_enqueue_script( 'xy-movement-control' );
	}
	/**
	 * Get box shadow control default value.
	 *
	 * Retrieve the default value of the box shadow control. Used to return the
	 * default values while initializing the box shadow control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	/*public function get_default_value() {
		return array_merge(
			parent::get_default_value(), [
				'x' => '',
				'y' => '',
			]
		);
	}*/
	public function get_default_value() {
		return array_merge(
			parent::get_default_value(), [
				'x' => '',
				'y' => '',
			]
		);
	}
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(), [
				'label_block' => false,
			]
		);
	}
	/**
	 * Get box shadow control sliders.
	 *
	 * Retrieve the sliders of the box shadow control. Sliders are used while
	 * rendering the control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'x' => [
				'label' => __( 'X', 'dynamic-content-for-elementor' ),
				'min' => -100,
				'max' => 100,
				'step' => 1
			],
			'y' => [
				'label' => __( 'Y', 'dynamic-content-for-elementor' ),
				'min' => -100,
				'max' => 100,
				'step' => 1
			],
		];
	}

	/**
	 * Render box shadow control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title control-title-first">{{{ data.label }}}</label>
			<button href="#" class="reset-controls" title="Reset"><i class="fa fa-close"></i></button>
		</div>
		<?php
		foreach ( $this->get_sliders() as $slider_name => $slider ) :
			$control_uid = $this->get_control_uid( $slider_name );
			?>
			<div class="elementor-control-field elementor-control-type-slider">
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title-xymovement"><?php echo $slider['label']; ?></label>
				<div class="elementor-control-input-wrapper">
					<div class="elementor-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
					<div class="elementor-slider-input">
						<input id="<?php echo esc_attr( $control_uid ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" step="<?php echo esc_attr( $slider['step'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}