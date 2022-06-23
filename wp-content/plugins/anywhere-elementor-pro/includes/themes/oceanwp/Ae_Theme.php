<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{


    function manage_actions(){
        parent::manage_actions();
            add_filter( 'ocean_display_page_header', [ $this, 'remove_ocean_page_header'] );
            add_action('wp_enqueue_scripts', [$this,'css_rules']);
    }

    function remove_ocean_page_header(){
        return false;
    }

    function css_rules(){
        $css = '.blog-entry.grid-entry .blog-entry-inner{ padding:0 !important; }';
        $css .= '.search #main #content-wrap{ padding:0 !important; }';
        wp_add_inline_style('ae-pro-css',$css);
    }

    function theme_hooks($hook_positions){
        $hook_positions['ocean_before_outer_wrap'] = __('Ocean Before Outer Wrap', 'ae-pro');
        $hook_positions['ocean_before_wrap'] = __('Ocean Before Wrap', 'ae-pro');
        $hook_positions['ocean_before_main'] = __('Ocean Before Main', 'ae-pro');
        $hook_positions['ocean_before_content_wrap'] = __('Ocean Before Content Wrap', 'ae-pro');
        $hook_positions['ocean_before_primary'] = __('Ocean Before Primary', 'ae-pro');
        $hook_positions['ocean_before_content'] = __('Ocean Before Content', 'ae-pro');
        $hook_positions['ocean_before_content_inner'] = __('Ocean Before Content Inner', 'ae-pro');
        $hook_positions['ocean_after_content_inner'] = __('Ocean After Content Inner', 'ae-pro');
        $hook_positions['ocean_after_content'] = __('Ocean After Content', 'ae-pro');
        $hook_positions['ocean_after_primary'] = __('Ocean After Primary', 'ae-pro');
        $hook_positions['ocean_after_content_wrap'] = __('Ocean After Content Wrap', 'ae-pro');
        $hook_positions['ocean_after_main'] = __('Ocean After Main', 'ae-pro');
        $hook_positions['ocean_before_footer'] = __('Ocean Before Footer', 'ae-pro');
        $hook_positions['ocean_after_footer'] = __('Ocean After Footer', 'ae-pro');
        $hook_positions['ocean_after_wrap'] = __('Ocean After Wrap', 'ae-pro');
        $hook_positions['ocean_after_outer_wrap'] = __('Ocean After Outer Wrap', 'ae-pro');
        $hook_positions['ocean_before_sidebar'] = __('Ocean Before Sidebar', 'ae-pro');
        $hook_positions['ocean_before_sidebar_inner'] = __('Ocean Before Sidebar Inner', 'ae-pro');
        $hook_positions['ocean_after_sidebar_inner'] = __('Ocean After Sidebar Inner', 'ae-pro');
        $hook_positions['ocean_after_sidebar'] = __('Ocean After Sidebar', 'ae-pro');
        $hook_positions['ocean_before_page_header'] = __('Ocean Before Page Header ', 'ae-pro');
        $hook_positions['ocean_before_page_header_inner'] = __('Ocean Before Page Header Inner', 'ae-pro');
        $hook_positions['ocean_after_page_header_inner'] = __('Ocean After Page Header Inner', 'ae-pro');
        $hook_positions['ocean_after_page_header'] = __('Ocean After Page Header', 'ae-pro');
        $hook_positions['ocean_before_footer_bottom'] = __('Ocean Before Footer Bottom', 'ae-pro');
        $hook_positions['ocean_before_footer_bottom_inner'] = __('Ocean Before Footer Bottom Inner', 'ae-pro');
        $hook_positions['ocean_after_footer_bottom_inner'] = __('Ocean After Footer Bottom Inner', 'ae-pro');
        $hook_positions['ocean_after_footer_bottom'] = __('Ocean After Footer Bottom', 'ae-pro');
        $hook_positions['ocean_before_footer_inner'] = __('Ocean Before Footer Inner', 'ae-pro');
        $hook_positions['ocean_after_footer_inner'] = __('Ocean After Footer Inner', 'ae-pro');
        $hook_positions['ocean_before_footer_widgets'] = __('Ocean Before Footer Widgets', 'ae-pro');
        $hook_positions['ocean_before_footer_widgets_inner'] = __('Ocean Before Footer Widgets Inner', 'ae-pro');
        $hook_positions['ocean_after_footer_widgets_inner'] = __('Ocean After Footer Widgets Inner', 'ae-pro');
        $hook_positions['ocean_after_footer_widgets'] = __('Ocean After Footer Widgets', 'ae-pro');
        $hook_positions['ocean_before_header'] = __('Ocean Before Header', 'ae-pro');
        $hook_positions['ocean_before_header_inner'] = __('Ocean Before Header Inner', 'ae-pro');
        $hook_positions['ocean_after_header_inner'] = __('Ocean After Header Inner', 'ae-pro');
        $hook_positions['ocean_after_header'] = __('Ocean After header', 'ae-pro');
        $hook_positions['ocean_before_logo'] = __('Ocean Before Logo', 'ae-pro');
        $hook_positions['ocean_before_logo_inner'] = __('Ocean Before Logo Inner', 'ae-pro');
        $hook_positions['ocean_after_logo_inner'] = __('Ocean After Logo Inner', 'ae-pro');
        $hook_positions['ocean_after_logo'] = __('Ocean After Logo', 'ae-pro');
        $hook_positions['ocean_before_mobile_icon'] = __('Ocean Before Mobile Icon', 'ae-pro');
        $hook_positions['ocean_after_mobile_icon'] = __('Ocean After Mobile Icon', 'ae-pro');
        $hook_positions['ocean_before_nav'] = __('Ocean Before Nav', 'ae-pro');
        $hook_positions['ocean_before_nav_inner'] = __('Ocean Before Nav Inner', 'ae-pro');
        $hook_positions['ocean_after_nav_inner'] = __('Ocean After Nav Inner', 'ae-pro');
        $hook_positions['ocean_after_nav'] = __('Ocean After Nav', 'ae-pro');
        $hook_positions['ocean_social_share'] = __('Ocean Social Share', 'ae-pro');
        $hook_positions['ocean_before_top_bar'] = __('Ocean Before Top Bar', 'ae-pro');
        $hook_positions['ocean_before_top_bar_inner'] = __('Ocean Before Top Bar Inner', 'ae-pro');
        $hook_positions['ocean_after_top_bar_inner'] = __('Ocean After Top Bar Inner', 'ae-pro');
        $hook_positions['ocean_after_top_bar'] = __('Ocean After Top Bar', 'ae-pro');

        return $hook_positions;
    }

    function set_fullwidth(){
        add_filter('ocean_post_layout_class',function($class){
            return 'full-width';
        });
    }
}