<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{


    function manage_actions(){
        parent::manage_actions();

        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

        // RTURNS A FULL WITH FOT THE PAGE TEMPLATE
        add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

        remove_action('genesis_before_loop','genesis_do_taxonomy_title_description', 15 );
        remove_action( 'genesis_after_header', 'genesis_do_subnav' );
        add_action('wp_enqueue_scripts', [$this,'css_rules']);
    }


    function css_rules(){
        //$css = 'body .site-inner { float: none !important; width: 100% !important; }';
       $css = '.site-inner{ padding:0 !important; }';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){


        return $hook_positions;
    }

    function set_fullwidth(){


    }
}