<?php
namespace Elementor;
 
use \Elementor\Widgets_Manager;



/**
 * Description of DCE_Widgets_Manager
 *
 * @author fra
 */
class DCE_Widgets_Manager extends Widgets_Manager {
    
    /**
        * Widget types.
        *
        * Holds the list of all the widget types.
        *
        * @since 1.0.0
        * @access private
        *
        * @var Widget_Base[]
        */
       private $_widget_types = null;
       
       
       /**
	 * Widgets manager constructor.
	 *
	 * Initializing Elementor widgets manager.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function __construct($_widget_types = null) {
		$this->_widget_types = $_widget_types;
                
                if (!class_exists('\\Elementor\\Widget_Base')) {
                    //require_once ELEMENTOR_PATH . 'includes/base/element-base.php';
                    //require_once ELEMENTOR_PATH . 'includes/base/widget-base.php';   
                }

		//parent::__construct();
	}

    
    
    /**
	 * Register widget type.
	 *
	 * Add a new widget type to the list of registered widget types.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Elementor widget.
	 *
	 * @return true True if the widget was registered.
	*/
	public function register_widget_type( Widget_Base $widget ) {
		if ( is_null( $this->_widget_types ) ) {
			$this->init_widgets();
		}

                //var_dump($widget->get_name());
		$this->_widget_types[ $widget->get_name() ] = $widget;

		return true;
	}
        
        
   
}

