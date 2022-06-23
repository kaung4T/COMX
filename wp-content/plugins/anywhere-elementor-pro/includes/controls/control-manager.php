<?php

namespace Aepro;


use Elementor\Controls_Manager;

class Aepro_Control_Manager{

    private static $_instance = null;

    const TAB_AE_PRO = 'tab_ae_pro';

    public static function instance()
    {
        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
		if(version_compare(ELEMENTOR_VERSION,'1.5.5')){
			add_filter( 'elementor/init', [ $this, 'add_ae_tab'], 10,1);
		}else{
			add_filter( 'elementor/controls/get_available_tabs_controls', [ $this, 'add_ae_tab'], 10,1);
		}
    }

    public function add_ae_tab($tabs){
        if(version_compare(ELEMENTOR_VERSION,'1.5.5')){
			Controls_Manager::add_tab(self::TAB_AE_PRO, __( 'AE PRO', 'ae-pro' ));
		}else{
			$tabs[self::TAB_AE_PRO] = __( 'AE PRO', 'ae-pro' );
		}    
        return $tabs;
    }
}

Aepro_Control_Manager::instance();