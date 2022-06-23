<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Icon Format
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */

class DCE_Widget_IconFormat extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-iconformat';
    }
    
    static public function is_enabled() {
        return true;
    }
    public function get_description() {
        return __('Add an icon for your post format and identify its type', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/icon-format/';
    }
    public function get_title() {
        return __('Icon Format', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-formats';
    }
    static public function get_position() {
        return 4;
    }
    /*public function get_style_depends() {
        return [ 'dce-iconFormat' ];
    }*/
    protected function _register_controls() {
        $this->start_controls_section(
            'section_cpt', [
                'label' => __('Icon Format', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_responsive_control(
            'icon_size', [
                'label' => __('Icon Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dashicons:before' => 'font-size: {{SIZE}}{{UNIT}};',
                    //'{{WRAPPER}} .dashicons' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'padding_size', [
                'label' => __('Padding Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dashicons' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
                'color_icon', [
            'label' => __('Color Icon', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dashicons:before' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'color_bg', [
            'label' => __('Color Background', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dashicons' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'icon_align', [
            'label' => __('Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
            ],
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];
        // ------------------------------------------


        $format = get_post_format($id_page);
        switch ($format) {
            /*
              aside
              chat
              gallery
              link
              image
              quote
              status
              video
              audio
             */
            case 'aside' :
                $strformat = 'dashicons-format-aside';
                break;

            case 'chat' :
                $strformat = 'dashicons-format-chat';
                break;
            case 'gallery' :
                $strformat = 'dashicons-format-gallery';
                break;
            case 'link' :
                $strformat = 'dashicons-admin-links';
                break;
            case 'image' :
                $strformat = 'dashicons-format-image';
                break;
            case 'quote' :
                $strformat = 'dashicons-format-quote';
                break;
            case 'status' :
                $strformat = 'dashicons-format-status';
                break;
            case 'video' :
                $strformat = 'dashicons-format-video';
                break;
            case 'audio' :
                $strformat = 'dashicons-format-audio';
                break;

            case '' :
            default:
                $strformat = 'dashicons-admin-post';
                break;
        }
    
        echo '<span class="dashicons '.$strformat.'"></span>';
        
    }

}
