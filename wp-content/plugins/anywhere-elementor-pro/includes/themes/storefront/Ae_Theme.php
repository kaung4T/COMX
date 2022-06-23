<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{


    function manage_actions(){
        parent::manage_actions();
        add_filter('woocommerce_get_breadcrumb', '__return_false');
    }

    function remove_ocean_page_header(){
        return false;
    }

    function css_rules(){
        $css = 'body #primary { float: none !important; width: 100% !important; }';
        //$css .= '.search #main #content-wrap{ padding:0 !important; }';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){


       return $hook_positions;
    }

    function set_fullwidth(){
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
        add_action('wp_enqueue_scripts', [$this,'css_rules']);
    }
}