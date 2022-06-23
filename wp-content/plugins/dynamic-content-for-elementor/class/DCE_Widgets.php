<?php
namespace DynamicContentForElementor;

/**
 * Widgets Class
 *
 * Register new elementor widget.
 *
 * @since 0.0.1
 */
class DCE_Widgets {

    public $widgets = [];
    public $grouped_widgets = [];
    public static $group = array(
        'DYNAMIC' => 'Dynamic',
        'POST' => 'Post',
        'ARCHIVE' => 'Archive',
        'ACF' => 'ACF',
        'CREATIVE' => 'Creative',
        'DEV' => 'Developer',
        'TAX' => 'Taxonomy',
        'OTHER' => 'Miscellaneous'
    );
    static public $dir = DCE_PATH . 'includes/widgets';
    static public $namespace = '\\DynamicContentForElementor\\Widgets\\';

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->widgets = $this->get_widgets();
        $this->grouped_widgets = $this->get_widgets_by_group();
        add_action( 'elementor/elements/categories_registered', array($this, 'add_elementor_widget_category'), 9999999999 );
    }
    
    static public function find_widgets() {
        $widgets = DCE_Helper::dir_to_array(self::$dir);
        //var_dump($widgets); die();
        
        // remove unwanted files
        if (($key = array_search('index.php', $widgets)) !== false) {
            unset($widgets[$key]);
        }
        if (($key = array_search('DCE_Widget_Prototype.php', $widgets)) !== false) {
            unset($widgets[$key]);
        }
        
        return $widgets;
    }

    static public function get_widgets() {
        
        $widgets = self::find_widgets();
        
        // get a simple list
        $widgets_list = array();
        $process = $widgets;
        while (count($process) > 0) {
            $current = array_pop($process);
            if (is_array($current)) {
                // Using a loop for clarity. You could use array_merge() here.
                foreach ($current as $item) {
                    // As an optimization you could add "flat" items directly to the results array here.
                    array_push($process, $item);
                }
            } else {
                array_push($widgets_list, $current);
            }
        }
        //var_dump($widgets_list); die();
        
        $tmp = array();
        foreach ($widgets_list as $wkey => $value) {
            $cname = str_replace('.php', '', $value);
            //$aWidgetObjname = self::$namespace.$cname;
            //if ($aWidgetObjname::is_enabled() || $disabled) {
                $tmp[$wkey] = $cname;
            //}
        }
        $widgets_list = $tmp;
        
        return $widgets_list;
    }
    
    static public function get_widgets_by_group() {
        $widgets = self::find_widgets();
        
        $grouped_widgets = self::array_compact($widgets);
        //var_dump($widgets); die();
        
        $tmp = array();
        foreach ($grouped_widgets as $gkey => $values) {
            foreach ($values as $wkey => $value) {
                $cname = str_replace('.php', '', $value);
                //$aWidgetObjname = self::$namespace.$cname;
                //if ($aWidgetObjname::is_enabled() || $disabled) {
                    $tmp[$gkey][$wkey] = $cname;
                //}
            }
        }
        $grouped_widgets = $tmp;
        
        return $grouped_widgets;
    }
    
    static public function get_active_widgets() {
        $widgets = self::get_widgets();
        self::includes();
        $active_widgets = array();
        foreach ($widgets as $ckey => $className) {
            $myWdgtClass = self::$namespace . $className;
            if ($myWdgtClass::is_enabled()) {
                $active_widgets[$ckey] = $className;
            }
        }
        return $active_widgets;
    }
    
    static public function get_active_widgets_by_group() {
        $grouped_widgets = self::get_widgets_by_group();
        self::includes();
        $active_grouped_widgets = array();
        foreach ($grouped_widgets as $key => $value) {    
            foreach ($value as $ckey => $className) {
                $myWdgtClass = self::$namespace . $className;
                if ($myWdgtClass::is_enabled()) {
                    $active_grouped_widgets[$key][$ckey] = $className;
                }
            }
        }
        return $active_grouped_widgets;
    }
    
    static public function array_compact($myarray, $prekey = '') {
        $ret = array();
        if (is_array($myarray)) {
            foreach ($myarray as $akey => $arr) {
                //var_dump($akey);
                if (is_array($arr)) {
                    $tmp = self::array_compact($arr, $prekey.$akey);
                    $ret = self::array_merge_recursive($ret, $tmp);
                } else {
                    $tmp = self::array_compact($arr, $prekey);
                    $ret = self::array_merge_recursive($ret, $tmp);
                }
            }
        } else {
            $ret[$prekey][] = $myarray;
        }
        return $ret;
    }
    
    static public function array_merge_recursive(array &$array1, array &$array2) {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::array_merge_recursive($merged[$key], $value);
            } else {
                if (is_numeric($key)) {
                    $merged[] = $value;
                } else {
                    $merged[$key] = $value;
                }
            }
        }
        return $merged;
    }
    
    /**
    * On Widgets Registered
    *
    * @since 0.0.1
    *
    * @access public
    */
    public function on_widgets_registered() {
        $this->includes();
        $this->register_widget();
    }

    public static function includes() {
        require_once( self::$dir . '/DCE_Widget_Prototype.php' ); // obbligatorio in quanto esteso dagli altri
        self::includes_recursive(self::$dir);
    }
    
    public static function includes_recursive($dir) {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    self::includes_recursive($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    if (substr($value, 0, 1) != '.') { // hidden file
                        //echo $value;
                        $widget_name = self::$namespace.pathinfo($value, PATHINFO_FILENAME);
                        // chek if double widget are loaded
                        if (!class_exists($widget_name)) {
                            require_once ($dir . DIRECTORY_SEPARATOR . $value);
                        }
                    }

                }
            }
        }
        return true;
    }
    
    

    /**
     * Register Widget
     *
     * @since 0.5.0
     *
     * @access private
     */
    private function register_widget() {
        $excluded_widgets = json_decode(get_option(SL_PRODUCT_ID . '_excluded_widgets'), true);
        //var_dump($excluded_widgets);
        $grouped_widgets = self::get_widgets_by_group();
        foreach ($grouped_widgets as $aType) {
            //var_dump($aType);
            usort($aType, function($a,$b){
                $aw = self::$namespace.$a;
                $bw = self::$namespace.$b;
                if ($aw::get_position() == $bw::get_position()) return 0;
                return ($aw::get_position() < $bw::get_position()) ? -1 : 1;
            }); // ordered by key (position)
            //var_dump($aType);
            $aOrderedType = array();
            foreach ($aType as $myWdgtClass) {
                if (!$excluded_widgets || !isset($excluded_widgets[$myWdgtClass])) { // controllo se lo avevo escluso in quanto non interessante
                    $aWidgetObjname = self::$namespace.$myWdgtClass;
                    //var_dump($aWidgetObjname);
                    if ($aWidgetObjname::is_enabled()) {
                        $aWidgetObj = new $aWidgetObjname();
                        if ($aWidgetObj->satisfy_dependencies()) { // controllo se non è soddisfatta qualche dipendenza di plugin
                            \Elementor\Plugin::instance()->widgets_manager->register_widget_type($aWidgetObj);
                        }
                        
                        //$aWidgetObj->add_wpml_support();
                    }
                }
            }
        }
    }

    /**
     * Add category of Elementor
     *
     * @since 0.0.1
     *
     * @access public
     */
    public function add_elementor_widget_category($elements) {
        $i = 0;

        //echo 'category'; die();
        
        // categoria di default per i widget per cui non è stato definito un gruppo tramite il nome della classe
        $elements->add_category('dynamic-content-for-elementor', array(
            'title' => __('Dynamic Content', 'dynamic-content-for-elementor'),
        ));
        
        // creo le categorie per cui voglio personalizzare il nome
        foreach (self::$group as $gkey => $agroup) {
            $elements->add_category('dynamic-content-for-elementor-'.  strtolower($gkey), array(
                'title' => __('Dynamic Content - '.$agroup, 'dynamic-content-for-elementor'),
            ));
        }

        // creo nuove categorie dinamiche in caso aggiungo un nuovo gruppo di widget tramite nome della classe
        $grouped_widgets = self::get_widgets_by_group();
        //var_dump($grouped_widgets); die();
        foreach ($grouped_widgets as $key => $value) {
            if (!in_array($key, self::$group)) {
                $gkey = strtolower($key);
                $agroup = ucfirst($gkey);
                //var_dump($gkey); die();
                $elements->add_category('dynamic-content-for-elementor-'.  strtolower($gkey), array(
                    'title' => __('Dynamic Content - '.$agroup, 'dynamic-content-for-elementor'),
                ));
            }
        }

    }

}
