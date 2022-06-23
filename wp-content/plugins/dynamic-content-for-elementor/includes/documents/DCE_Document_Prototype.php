<?php

namespace DynamicContentForElementor\Documents;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Document
 *
 * Class to easify extend Elementor controls and functionality
 *
 */
class DCE_Document_Prototype {
    
    public $name = "Document";

	/**
	 * Is Common Document
	 *
	 * Defines if the current document is common for all element types or not
	 *
	 * @since 0.5.8
	 * @access private
	 *
	 * @var bool
	 */
	protected $is_common = false;

	/**
	 * Depended scripts.
	 *
	 * Holds all the Document depended scripts to enqueue.
	 *
	 * @since 0.5.8
	 * @access private
	 *
	 * @var array
	 */
	private $depended_scripts = [];

	/**
	 * Depended styles.
	 *
	 * Holds all the document depended styles to enqueue.
	 *
	 * @since 0.5.8
	 * @access private
	 *
	 * @var array
	 */
	private $depended_styles = [];

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function __construct() {

		// Enqueue scripts
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Enqueue styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		// Elementor hooks
		/*add_action( 'elementor/frontend/post/after_render', function( $element, $args ) {

			echo 'ciao';

		}, 10, 2 );*/

		if ( $this->is_common ) {
			// Add the advanced section required to display controls
			$this->add_common_sections_actions();
		}

		$this->add_actions();
	}
        
        static public function is_enabled() {
            return true;
        }

	/**
	 * Add script depends.
	 *
	 * Register new script to enqueue by the handler.
	 *
	 * @since 0.5.8
	 * @access public
	 *
	 * @param string $handler Depend script handler.
	 */
	public function add_script_depends( $handler ) {
		$this->depended_scripts[] = $handler;
	}

	/**
	 * Add style depends.
	 *
	 * Register new style to enqueue by the handler.
	 *
	 * @since 0.5.8
	 * @access public
	 *
	 * @param string $handler Depend style handler.
	 */
	public function add_style_depends( $handler ) {
		$this->depended_styles[] = $handler;
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the document requires.
	 *
	 * @since 0.5.8
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return $this->depended_scripts;
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers all the scripts defined as document dependencies and enqueues
	 * them. Use `get_script_depends()` method to add custom script dependencies.
	 *
	 * @since 0.5.8
	 * @access public
	 */
        final public function enqueue_scripts() {    
            if (\DynamicContentForElementor\DCE_Helper::is_edit_mode()) {
                $this->_enqueue_scripts();
            }
        }
        
        public function _enqueue_scripts() {    
            $scripts = $this->get_script_depends();
            if (!empty($scripts)) {                
                foreach ($scripts as $script) {
                    wp_enqueue_script($script);
                }
            }
        }

	/**
	 * Retrieve style dependencies.
	 *
	 * Get the list of style dependencies the document requires.
	 *
	 * @since 0.5.8
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	final public function get_style_depends() {
		return $this->depended_styles;
	}

	/**
	 * Retrieve document description.
	 *
	 * @since 0.5.8
	 * @access public
	 *
	 * @return string Widget description.
	 */
	public static function get_description() {}

	/**
	 * Enqueue styles.
	 *
	 * Registers all the styles defined as document dependencies and enqueues
	 * them. Use `get_style_depends()` method to add custom style dependencies.
	 *
	 * @since 0.5.8
	 * @access public
	 */
        final public function enqueue_styles() {    
            if (\DynamicContentForElementor\DCE_Helper::is_edit_mode()) {
                $this->_enqueue_styles();
            }
        }
        
        public function _enqueue_styles() {    
            $styles = $this->get_style_depends();
            if (!empty($styles)) {
                foreach ($styles as $style) {
                    wp_enqueue_style( $style );
                }
            }
        }
        
        
        public function _enqueue_alles() {    
            $this->_enqueue_styles();
            $this->_enqueue_scripts();
        }

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected final function add_common_sections( $element ) {

		// The name of the section
		$section_name = 'section_dce_document_scroll';

		// Check if this section exists
		$section_exists = \Elementor\Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), $section_name );

		if ( ! is_wp_error( $section_exists ) ) {
			// We can't and should try to add this section to the stack
			return false;
		}

		$element->start_controls_section(
			$section_name,
			[
				'tab' 	=> Controls_Manager::TAB_SETTINGS,
				'label' => __( 'Scrolling', 'dynamic-content-for-elementor' ),
			]
		);	

		$element->end_controls_section();

		

	}

	/**
	 * Add common sections
	 *
	 * @since 0.5.8
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function add_actions() {}

	/**
	 * Removes controls in bulk
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function remove_controls( $element, $controls = null ) {
		if ( empty( $controls ) )
			return;

		if ( is_array( $controls ) ) {
			$control_id = $controls;

			foreach( $controls as $control_id ) {
				$element->remove_control( $control_id );
			}
		} else {
			$element->remove_control( $controls );
		}
	}

}