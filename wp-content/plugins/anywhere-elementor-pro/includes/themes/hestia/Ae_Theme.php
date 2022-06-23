<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{

    function manage_actions(){
        parent::manage_actions();

       // add_filter( 'generate_show_title', function(){
        //    return false;
      //  });
        add_action('wp_enqueue_scripts', [$this,'css_rules']);

        return true;
    }

    function css_rules(){
        $css = '.main-raised{ margin:5px 0 0 0 !important; }';
        $css .= '.woocommerce-page .main-raised{ margin:30px 0 0 0 !important; }';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){

        return $hook_positions;
    }

    function set_fullwidth(){

        add_filter('body_class',function($classes){
            $classes[] = 'full-width-content';
            return $classes;
        });
    }
}