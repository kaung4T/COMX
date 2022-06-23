<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor IncludeFile
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_IncludeFile extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-includefile';
    }

    static public function is_enabled() {
        return true;
    }
    
    public function get_title() {
        return __('File Include', 'dynamic-content-for-elementor');
    }
    public function get_description() {
      return __('Directly include files from a path in root as if you were writing in a theme. Ideal for developers who know no limits', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/file-include/';
    }
    public function get_icon() {
        return 'icon-dyn-incfile';
    }

    protected function _register_controls() {
        $this->start_controls_section(
                'section_includefile', [
            'label' => __('File Include', 'dynamic-content-for-elementor'),
                ]
        );
        if( current_user_can('administrator') || !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $this->add_control(
              'file', [
                  'label' => __('Path of file', 'dynamic-content-for-elementor'),
                  'description' => __('The path of file to include (ex: folder/file.html) ', 'dynamic-content-for-elementor'),
                  'placeholder' => 'wp-content/themes/my-theme/my-custom-file.php',
                  'type' => Controls_Manager::TEXT,
                  'frontend_available' => true,
                  'default' => '',
                  
              ]
        );
        }else{
            $this->add_control(
              'html_avviso',
              [
                 'type'    => Controls_Manager::RAW_HTML,
                 'raw' => __( '<div class="dce-notice dce-error dce-notice-error">You must be admin to set this widget.</div>', 'dynamic-content-for-elementor' ),
                 'content_classes' => 'avviso',
              ]
            );
        }
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        //$path = get_home_path();
        //echo $path;

        if( $settings['file'] != '' ){
            $problem = 'The file does not exist';
            if( is_admin() ){
                $path = get_home_path();
                if( file_exists( $path . $settings['file'] )) {
                    include $path . $settings['file'];
                }else{
                    echo $problem;
                }
            }else{
                if( file_exists( './' . $settings['file'] )){
                    include './' . $settings['file'];
                 }else{
                    echo $problem;
                 }
            }
            

        }else{
            echo 'Select file';
        }
    }

}
