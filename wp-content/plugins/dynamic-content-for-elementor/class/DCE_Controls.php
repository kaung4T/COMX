<?php

namespace DynamicContentForElementor;

/**
 * Widgets Class
 *
 * Register new elementor widget.
 *
 * @since 0.0.1
 */
class DCE_Controls {

    public $controls = [];
    public $group_controls = [];
    static public $dir = DCE_PATH . 'includes/controls';
    static public $namespace = '\\DynamicContentForElementor\\Controls\\';

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->controls = $this->get_controls();
        $this->group_controls = $this->get_group_controls();
    }

    public function get_controls() {
        $tmpControls = [];
        $controls = glob(self::$dir. '/DCE_*.php');
        foreach ($controls as $key => $value) {
            $class = pathinfo($value, PATHINFO_FILENAME);
            $type = str_replace('dce_control_', '', strtolower($class));
            $tmpControls[strtolower($type)] = $class;
        }
        return $tmpControls;
    }
    
    public function get_group_controls() {
        $tmpControls = [];
        $controls = glob(self::$dir. '/groups/DCE_*.php');
        foreach ($controls as $key => $value) {
            $class = pathinfo($value, PATHINFO_FILENAME);
            $type = str_replace('dce_control_', '', strtolower($class));
            $tmpControls[$type] = $class;
        }
        return $tmpControls;
    }
    
    /**
    * On Controls Registered
    *
    * @since 0.0.1
    *
    * @access public
    */
    public function on_controls_registered() {
        $this->includes();
        $this->register_controls();
    }
    
    public function includes() {
        //require_once( self::$dir . '/DCE_Control_Prototype.php' ); // obbligatorio in quanto esteso dagli altri
        foreach ($this->controls as $key => $value) {
            require_once self::$dir.'/'.$value.'.php';
        }
        foreach ($this->group_controls as $key => $value) {
            require_once self::$dir.'/groups/'.$value.'.php';
        }
    }
    
    /**
    * On Controls Registered
    *
    * @since 1.0.4
    *
    * @access public
    */
    public function register_controls() {
        $controls_manager = \Elementor\Plugin::$instance->controls_manager;
        
        //var_dump($this->controls); die();
        //var_dump($this->group_controls);
        
        foreach ($this->controls as $key => $name) {
            $class = self::$namespace . $name;
            $controls_manager->register_control($key, new $class());
        }
        foreach ($this->group_controls as $key => $name) {
            $class = self::$namespace . $name;
            $controls_manager->add_group_control($class::get_type(), new $class());
        }
        
        //var_dump($controls_manager); die();
    }

}

