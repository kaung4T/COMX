<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor background-particles BETA
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_Backgroundparticles extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-background-particles';
    }
    
    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Background Particles', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-background_particles todo';
    }
    public function get_script_depends() {
        return [];
    }
    static public function get_position() {
        return 8;
    }
    protected function _register_controls() {
        $this->start_controls_section(
                'section_dynamictemplate', [
                'label' => __('background-particles', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
          'html_background-particles',
          [
             'type'    => Controls_Manager::RAW_HTML,
             'raw' => __( '<div>Questo è un widget che diventerà un\'background-particles.</div>', 'dynamic-content-for-elementor' ),
           'content_classes' => 'html-background-particles',
          ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $id_page = ''; //get_the_ID();
        $type_page = '';
        //
        if( $settings['data_source'] == 'yes' ){
            global $global_ID;
            global $global_TYPE;
            global $in_the_loop;
            global $global_is;
            //
            if (!empty($global_ID)) {
                $id_page = $global_ID;
                $type_page = get_post_type($id_page);
                //echo 'global ...';
            } else {
                $id_page = get_the_id();
                $type_page = get_post_type();
                //echo 'natural ...';
            }
        }else{
            $id_page = $settings['other_post_source'];
            $type_page = get_post_type($id_page);
        }
        // ------------------------------------------
        //


    }

}
