<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{


    function manage_actions(){
        parent::manage_actions();
    }

    function remove_ocean_page_header(){
        return false;
    }

    function css_rules(){
        $css = '#inner-content{ padding:0 !important; }';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){


        return $hook_positions;
    }

    function set_fullwidth(){
        add_action('wp_enqueue_scripts', [$this,'css_rules']);
        add_action('body_class', [$this,'ae_wpbf_body']);
        remove_action('wpbf_sidebar_right', 'wpbf_do_sidebar_right');
    }
    function ae_wpbf_body($classes){
        if (($key = array_search('wpbf-sidebar-right', $classes)) !== false) {
            unset($classes[$key]);
        }
        if (($key = array_search('wpbf-sidebar-left', $classes)) !== false) {
            unset($classes[$key]);
        }

        $classes[] = 'wpbf-no-sidebar';
        return $classes;
    }
}