<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{

    function manage_actions(){
        parent::manage_actions();
        remove_action( 'generate_before_content','generate_featured_page_header_inside_single', 10);
        remove_action( 'generate_after_entry_title', 'generate_post_meta' );
        remove_action( 'generate_after_entry_content', 'generate_footer_meta' );
        remove_action( 'generate_after_entry_content', 'generate_footer_meta' );
        remove_action( 'generate_before_content','generate_featured_page_header_inside_single' );

        add_filter( 'generate_show_title', function(){
            return false;
        });

        return true;
    }

    function theme_hooks($hook_positions){
        $hook_positions['generate_before_header'] = __('GP Before Header', 'ae-pro');
        $hook_positions['generate_before_header_content'] = __('GP Before Header Content', 'ae-pro');
        $hook_positions['generate_after_header_content'] = __('GP After Header Content', 'ae-pro');
        $hook_positions['generate_after_header'] = __('GP After Header', 'ae-pro');
        $hook_positions['generate_inside_container'] = __('GP Inside Container', 'ae-pro');
        $hook_positions['generate_before_footer'] = __('GP Before Footer', 'ae-pro');
        $hook_positions['generate_before_footer_content'] = __('GP Before Footer Content', 'ae-pro');
        $hook_positions['generate_after_footer_widgets'] = __('GP After Footer Widgets', 'ae-pro');
        $hook_positions['generate_credits'] = __('GP Credits', 'ae-pro');
        $hook_positions['generate_after_footer_content'] = __('GP After Footer Content', 'ae-pro');
        $hook_positions['generate_before_main_content'] = __('GP Before Main Content', 'ae-pro');
        $hook_positions['generate_before_content'] = __('GP Before Content', 'ae-pro');
        $hook_positions['generate_after_entry_header'] = __('GP After Entry Header', 'ae-pro');
        $hook_positions['generate_after_content'] = __('GP After Content', 'ae-pro');
        $hook_positions['generate_after_main_content'] = __('GP After Main Content', 'ae-pro');
        $hook_positions['generate_sidebars'] = __('GP Sidebars', 'ae-pro');
        $hook_positions['generate_archive_title'] = __('GP Archive Title', 'ae-pro');
        $hook_positions['generate_inside_comments'] = __('GP Inside Comments', 'ae-pro');
        $hook_positions['generate_below_comments_title'] = __('GP Below Comments Title', 'ae-pro');
        $hook_positions['generate_before_entry_title'] = __('GP Before Entry Title', 'ae-pro');
        $hook_positions['generate_after_entry_title'] = __('GP After Entry Title', 'ae-pro');
        $hook_positions['generate_after_entry_content'] = __('GP After Entry Content', 'ae-pro');
        $hook_positions['generate_before_right_sidebar_content'] = __('GP Before Right Sidebar Content', 'ae-pro');
        $hook_positions['generate_after_right_sidebar_content'] = __('GP After Right Sidebar Content', 'ae-pro');
        $hook_positions['generate_before_left_sidebar_content'] = __('GP Before Left Sidebar Content', 'ae-pro');
        $hook_positions['generate_after_left_sidebar_content'] = __('GP After Left Sidebar Content', 'ae-pro');
        $hook_positions['generate_inside_navigation'] = __('GP Inside Navigation', 'ae-pro');
        $hook_positions['generate_inside_mobile_menu'] = __('GP inside Mobile Menu', 'ae-pro');
        $hook_positions['generate_paging_navigation'] = __('GP Paging Navigation', 'ae-pro');
        $hook_positions['generate_before_logo'] = __('GP Before Logo', 'ae-pro');
        $hook_positions['generate_after_logo'] = __('GP After Logo', 'ae-pro');
        $hook_positions['generate_before_archive_title'] = __('GP Before Archive Title', 'ae-pro');
        $hook_positions['generate_after_archive_title'] = __('GP After Archive Title', 'ae-pro');
        $hook_positions['generate_after_archive_description'] = __('GP After Archive Description', 'ae-pro');
        return $hook_positions;
    }

    function set_fullwidth(){
        add_filter('generate_sidebar_layout', function($layout){
           return 'no-sidebar';
        });

        add_filter('body_class',function($classes){
            $classes[] = 'full-width-content';
            return $classes;
        });
    }
}