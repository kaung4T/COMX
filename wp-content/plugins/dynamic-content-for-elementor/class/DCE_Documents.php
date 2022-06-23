<?php

namespace DynamicContentForElementor;

/**
 * Documents Class
 *
 * Register new elementor widget.
 *
 * @since 0.0.1
 */
class DCE_Documents {

    public $documents = [];
    static public $dir = DCE_PATH . 'includes/documents';
    static public $namespace = '\\DynamicContentForElementor\\Documents\\';

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->documents = self::get_documents();
    }

    static public function get_documents() {
        $tmpDocuments = [];
        $documents = glob(self::$dir. '/DCE_*.php');
        foreach ($documents as $key => $value) {
            $class = pathinfo($value, PATHINFO_FILENAME);
            if ($class != 'DCE_Document_Prototype') {
                $tmpDocuments[strtolower($class)] = $class;
            }
        }
        return $tmpDocuments;
    }
    
    
    static public function get_active_documents() {
        $tmpDocuments = self::get_documents();
        self::includes();
        $activeDocuments = array();
        foreach ($tmpDocuments as $key => $name) {
            $class = self::$namespace . $name;
            if ($class::is_enabled()) {
                $activeDocuments[$key] = $name;
            }   
        }
        return $activeDocuments;
    }
    
    /**
    * On extensions Registered
    *
    * @since 0.0.1
    *
    * @access public
    */
    public function on_documents_registered() {
        $this->includes();
        $this->register_documents();
    }
    
    static public function includes() {
        require_once( self::$dir . '/DCE_Document_Prototype.php' ); // obbligatorio in quanto esteso dagli altri
        
        foreach (self::get_documents() as $key => $value) {
            require_once self::$dir.'/'.$value.'.php';
        }
    
    }
    
    /**
    * On Controls Registered
    *
    * @since 1.0.4
    *
    * @access public
    */
    public function register_documents() {
        $documents = [];
        
        $excluded_documents = json_decode(get_option(SL_PRODUCT_ID . '_excluded_documents', '[]'), true);
        //var_dump($excluded_widgets);
        foreach ($this->documents as $key => $name) {
            if (!isset($excluded_documents[$name])) { // controllo se lo avevo escluso in quanto non interessante
                $class = self::$namespace . $name;
                //var_dump($aWidgetObjname);
                if ($class::is_enabled()) {
                    $documents[] = new $class();
                }
            }
        }
        
        //var_dump($extensions); die();
    }

}
