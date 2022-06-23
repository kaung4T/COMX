<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{


    function manage_actions(){
        parent::manage_actions();
        //add_filter('woocommerce_get_breadcrumb', '__return_false');
        //remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
        //remove_action( 'woo_main_before', 'woo_display_breadcrumbs', 10 );

        add_filter('body_class',function($classes){
            $classes[] = 'no-sidebar';
            return $classes;
        });
        add_action('wp_enqueue_scripts', [$this,'css_rules']);



                remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
                remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


        if(is_singular()) {
            add_filter('post_thumbnail_html', function () {
                $html = '';
                return $html;

            });
        }

    }

    function css_rules(){
        $css = 'body #primary { float: none !important; width: 100% !important; }';
        $css .= '#content{ padding:0 !important; }';
        $css .= '.search-form button.search-submit { position: unset; padding: 10px;}';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){


        return $hook_positions;
    }

    function set_fullwidth(){

    }
}