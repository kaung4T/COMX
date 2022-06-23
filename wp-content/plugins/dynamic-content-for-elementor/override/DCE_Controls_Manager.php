<?php

namespace Elementor;

use \Elementor\Controls_Manager;

/**
 * Description of DCE_Controls_Manager
 *
 * @author fra
 */
class DCE_Controls_Manager extends Controls_Manager {
    /* public function __construct($controls_manager) {
      $this->_clone_controls_manager($controls_manager);
      } */

    // get init data from original control_manager
    public function _clone_controls_manager($controls_manager) {
        
        $controls = $controls_manager->get_controls();
        foreach ($controls as $key => $value) {
            $this->controls[$key] = $value;
        }

        $control_groups = $controls_manager->get_control_groups();
        foreach ($control_groups as $key => $value) {
            $this->control_groups[$key] = $value;
        }
        //$this->control_groups = $controls_manager->get_control_groups();
        //var_dump($this->control_groups); die();

        $this->stacks = $controls_manager->get_stacks();
        $this->tabs = $controls_manager::get_tabs();
    }

    public $excluded_extensions = array();

    public function set_excluded_extensions($extensions) {
        $this->excluded_extensions = $extensions;
    }

    /**
     * Add control to stack.
     *
     * This method adds a new control to the stack.
     *
     * @since 1.0.0
     * @access public
     *
     * @param Controls_Stack $element      Element stack.
     * @param string         $control_id   Control ID.
     * @param array          $control_data Control data.
     * @param array          $options      Optional. Control additional options.
     *                                     Default is an empty array.
     *
     * @return bool True if control added, False otherwise.
     */
    public function add_control_to_stack(Controls_Stack $element, $control_id, $control_data, $options = []) {

        if ($element->get_name() == 'form') {
            if (\DynamicContentForElementor\DCE_Helper::is_plugin_active('elementor-pro')) {
                $form_extensions = \DynamicContentForElementor\DCE_Extensions::get_form_extensions();
                foreach($form_extensions as $akey => $a_form_ext) {
                    $exc_ext = !isset($this->excluded_extensions[$a_form_ext]);
                    $a_form_ext_class = \DynamicContentForElementor\DCE_Extensions::$namespace.$a_form_ext;
                    if (method_exists($a_form_ext_class, '_add_to_form')) {
                        $control_data = $a_form_ext_class::_add_to_form($element, $control_id, $control_data, $options);
                    }
                }
            }
        }
        
        
        if (!in_array($element->get_name(), array('popup_triggers', 'popup_timing'))) { // avoid EPRO Popup condition issue
            $exc_ext_token = !isset($this->excluded_extensions['DCE_Extension_Tokens']);
            if ($exc_ext_token) {
                //add Dynamic Tags to $control_data
                $control_data = \DynamicContentForElementor\Extensions\DCE_Extension_Tokens::_add_dynamic_tags($control_data);
            }
        }

        
        return parent::add_control_to_stack($element, $control_id, $control_data, $options);
    }
}
