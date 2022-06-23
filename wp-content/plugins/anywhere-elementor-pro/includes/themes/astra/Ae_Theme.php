<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{

    function manage_actions(){
        parent::manage_actions();
        add_filter('astra_the_title_enabled', function(){
            return false;
        });
        return true;
    }

    function theme_hooks($hook_positions){
        $hook_positions['astra_html_before'] = __('Astra HTML Before','ae-pro');
        $hook_positions['astra_body_top'] = __('Astra Body Top','ae-pro');
        $hook_positions['astra_body_bottom'] = __('Astra Body Bottom','ae-pro');
        $hook_positions['astra_head_top'] = __('Astra Head Top','ae-pro');
        $hook_positions['astra_head_bottom'] = __('Astra Head Bottom','ae-pro');
        $hook_positions['astra_header_before'] = __('Astra Header Before','ae-pro');
        $hook_positions['astra_header'] = __('Astra Header','ae-pro');
        $hook_positions['astra_masthead_top'] = __('Astra Mast Header Top','ae-pro');
        $hook_positions['astra_masthead'] = __('Astra Mast Header ','ae-pro');
        $hook_positions['astra_masthead_bottom'] = __('Astra Mast Header Bottom ','ae-pro');
        $hook_positions['astra_header_after'] = __('Astra Mast Header After ','ae-pro');
        $hook_positions['astra_main_header_bar_top'] = __('Astra Main Header Bar Top','ae-pro');
        $hook_positions['astra_main_header_bar_bottom'] = __('Astra Main Header Bar Bottom','ae-pro');
        $hook_positions['astra_masthead_content'] = __('Astra Mast Head Content','ae-pro');
        $hook_positions['astra_masthead_toggle_buttons_before'] = __('Astra Mast Head Toggle Button Before','ae-pro');
        $hook_positions['astra_masthead_toggle_buttons'] = __('Astra Mast Head Toggle Button','ae-pro');
        $hook_positions['astra_masthead_toggle_buttons_after'] = __('Astra Mast Head Toggle Button After','ae-pro');
        $hook_positions['astra_content_before'] = __('Astra Mast Before','ae-pro');
        $hook_positions['astra_content_after'] = __('Astra Content After','ae-pro');
        $hook_positions['astra_content_top'] = __('Astra Content Top','ae-pro');
        $hook_positions['astra_content_bottom'] = __('Astra Content Bottom','ae-pro');
        $hook_positions['astra_content_while_before'] = __('Astra Content While Before','ae-pro');
        $hook_positions['astra_content_while_after'] = __('Astra Content While After','ae-pro');
        $hook_positions['astra_entry_before'] = __('Astra Entry Before','ae-pro');
        $hook_positions['astra_entry_after'] = __('Astra Entry After','ae-pro');
        $hook_positions['astra_entry_content_before'] = __('Astra Entry Content Before','ae-pro');
        $hook_positions['astra_entry_content_after'] = __('Astra Entry Content After','ae-pro');
        $hook_positions['astra_entry_top'] = __('Astra Entry Top','ae-pro');
        $hook_positions['astra_entry_bottom'] = __('Astra Entry Bottom','ae-pro');
        $hook_positions['astra_single_header_before'] = __('Astra Single Header Before','ae-pro');
        $hook_positions['astra_single_header_after'] = __('Astra Single Header After','ae-pro');
        $hook_positions['astra_single_header_top'] = __('Astra Single Header Top','ae-pro');
        $hook_positions['astra_single_header_bottom'] = __('Astra Single Header Bottom','ae-pro');
        $hook_positions['astra_comments_before'] = __('Astra Comments Before','ae-pro');
        $hook_positions['astra_comments_after'] = __('Astra Comments After','ae-pro');
        $hook_positions['astra_sidebars_before'] = __('Astra Sidebars Before','ae-pro');
        $hook_positions['astra_sidebars_after'] = __('Astra Sidebars After','ae-pro');
        $hook_positions['astra_footer'] = __('Astra Footer','ae-pro');
        $hook_positions['astra_footer_before'] = __('Astra Footer Before','ae-pro');
        $hook_positions['astra_footer_after'] = __('Astra Footer After','ae-pro');
        $hook_positions['astra_footer_content_top'] = __('Astra Footer Content Top','ae-pro');
        $hook_positions['astra_footer_content'] = __('Astra Footer Content','ae-pro');
        $hook_positions['astra_footer_content_bottom'] = __('Astra Footer Content Bottom','ae-pro');
        $hook_positions['astra_archive_header'] = __('Astra Archive Header','ae-pro');
        $hook_positions['astra_pagination'] = __('Astra Pagination','ae-pro');
        $hook_positions['astra_entry_content_single'] = __('Astra Entry Content Single','ae-pro');
        $hook_positions['astra_entry_content_404_page'] = __('Astra Entry Content 404 Page','ae-pro');
        $hook_positions['astra_entry_content_blog'] = __('Astra Entry Content Blog','ae-pro');
        $hook_positions['astra_blog_post_featured_format'] = __('Astra Blog Post Featured Format','ae-pro');
        $hook_positions['astra_primary_content_top'] = __('Astra Primary Content Top','ae-pro');
        return $hook_positions;
    }

    function set_fullwidth(){
        add_filter('astra_page_layout',function($layout){
            return 'no-sidebar';
        });

        add_filter('body_class',function($classes){
            $classes[] = 'full-width-content';
            return $classes;
        });

        add_filter('astra_get_content_layout',function($layout){
            return 'page-builder';
        });
    }
}