<?php
namespace DynamicContentForElementor\Compatibility;

// https://wpml.org/documentation/plugins-compatibility/elementor/how-to-add-wpml-support-to-custom-elementor-widgets/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPML Compatibility
 *
 * Registers translatable widgets
 *
 * @since 1.5.4
 */
class WPML {

	/**
	 * @since 1.5.4
	 * @var Object
	 */
	public static $instance = null;

	/**
	 * Returns the class instance
	 * 
	 * @since 1.0.16
	 *
	 * @return Object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the class
	 *
	 * @since 1.5.4
	 *
	 * @return void
	 */
	public function __construct() {
                
		// WPML String Translation plugin exist check
                add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );
	}

	/**
	 * Adds additional translatable nodes to WPML
	 *
	 * @since 1.5.4
	 *
	 * @param  array   $nodes_to_translate WPML nodes to translate
	 * @return array   $nodes_to_translate Updated nodes
	 */
	public function wpml_widgets_to_translate_filter( $widgets ) {
                $dce_widgets = \DynamicContentForElementor\DCE_Widgets::get_active_widgets_by_group();
                //var_dump($dce_widgets); die();
                
                foreach($dce_widgets as $kgroup => $agroup) {
                    foreach ($agroup as $awidget) {
                        $aWidgetObjname = \DynamicContentForElementor\DCE_Widgets::$namespace.$awidget;
                        //var_dump($aWidgetObjname);
                        $myWidget = new $aWidgetObjname();
                        
                        $fields = array();
                        $controls = $myWidget->get_controls();
                        //var_dump($stack); die();
                        if (!empty($controls)) {
                            //var_dump($stack['controls']); die();
                            foreach ($controls as $akey => $acontrol) {
                                $type = false;
                                switch ($acontrol['type']) {
                                    case 'text': 
                                    case 'heading': 
                                        $type = 'LINE';
                                        break;
                                    case 'textarea': 
                                        $type = 'AREA';
                                        break;
                                    case 'wysiwyg': 
                                        $type = 'VISUAL';
                                        break;
                                    case 'url':
                                        $type = 'LINK';
                                        break;
                                }   
                                    
                                if ($type) {
                                    $fields[] = array(
                                            'field'       => $akey,
                                            'type'        => __( $acontrol['label'], 'dynamic-content-for-elementor' ),
                                            'editor_type' => $type, // 'LINE', 'VISUAL', 'AREA', 'LINK'
                                    );
                                }
                            }
                        }
                        if (!empty($fields)) {
                            $widgets[ $myWidget->get_name() ] = array(
                                'conditions' => array( 'widgetType' => $myWidget->get_name() ),
                                'fields'     => $fields,
                            );
                        }
                    }

                }
                //var_dump($widgets); die();
		return $widgets;
	}

	/**
	 * Returns the class instance.
	 *
	 * @since 1.5.4
	 *
	 * @return Object
	 */
	public static function get_instance() {
		
		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}