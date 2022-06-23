<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Fire-Modal-Window
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */

class DCE_Widget_ModalWindow extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-modalwindow';
    }
     static public function get_position() {
        return 5;
    }
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Fire Modal Window', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Add a “More info” or “Modal Window” button in your post, create a call-to-action', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/fire-modalwindow/';
    }
    public function get_icon() {
        return 'icon-dyn-modal';
    }
    public function get_script_depends() {
        return [ 'jquery', 'velocity', 'dce-modalwindow'];
    }
    /*public function get_style_depends() {
        return [ 'dce-modalWindow' ];
    }*/
    protected function _register_controls() {
        $this->start_controls_section(
            'section_modalwindow', [
                'label' => __('Fire Modal Window', 'dynamic-content-for-elementor'),
            ]
        );
        //
        $this->add_control(
            'text_btn', [
                'label' => __('Text Button', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Fire Modal Window',
            ]
        );
        $this->add_control(
            'icon_fmw',
            [
                'label' => __( 'Icon', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::ICON,
            ]
        );
        $this->add_control(
            'icon_fmw_align',
            [
                'label' => __( 'Icon Position', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'elementor' ),
                    'right' => __( 'After', 'elementor' ),
                ],
                'condition' => [
                    'icon_fmw!' => '',
                ],
            ]
        );
        $this->add_control(
            'space_icon_fmw',
            [
                'label' => __( 'Icon spacing', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                        'size' => '',
                ],
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .icon-modalwindow.icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .icon-modalwindow.icon-right' => 'padding-left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'icon_fmw!' => '',
                ],
            ]
        );
        //
        
        //
        /*$this->add_control(
            'template', [
                'label' => __('Select Template', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'separator' => 'before',
                //'options' => get_post_taxonomies( $post->ID ),
                'options' => DCE_Helper::get_all_template(),
                'default' => '',
                'label_block' => true,
            ]
        );*/
        $this->add_control(
                'template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'separator' => 'before',
                ]
        );

        /* $this->add_control(
          'width_btn',
          [
          'label' => __( 'Width', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 12.5,
          'unit' => 'em',
          ],
          'size_units' => [ 'em' ],
          'range' => [
          'em' => [
          'min' => 0,
          'max' => 50,
          'step' => 0.1
          ]
          ],
          'selectors' => [
          '{{WRAPPER}} .btn span' => 'width: {{SIZE}}{{UNIT}};',
          ],
          ]
          ); */
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style', [
                'label' => 'Button',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'align', [
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
                  ],
              ],
              'default' => '',
              'selectors' => [
                  '{{WRAPPER}}' => 'text-align: {{VALUE}};',
              ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_btn',
                'selector' => '{{WRAPPER}} .dce-modalwindow-section .cd-modal-action .btn',
            ]
        );




        $this->start_controls_tabs('fmw_btn');

        $this->start_controls_tab(
                'fmw_btn_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
            'color_txbtn', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-action .btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'color_bgbtn', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-modalwindow-section .cd-modal-action .btn, {{WRAPPER}} .dce-modalwindow-section .cd-modal-action .cd-modal-bg' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'fmw_btn_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
            'color_txbtn_hover', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-modalwindow-section .cd-modal-action .btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'color_bgbtn_hover', [
                'label' => __('Background color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-modalwindow-section .cd-modal-action .btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );
        

        $this->end_controls_tab();

        $this->end_controls_tabs();



       
        $this->add_responsive_control(
            'fmw_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'separator' => 'before',
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-action .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_control(
            'borderradius_btn', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-modalwindow-section .cd-modal-action .btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /* $this->add_control(
          'padding_item',
          [
          'label'         => __( 'Padding', 'dynamic-content-for-elementor' ),
          'type'          => Controls_Manager::DIMENSIONS,
          'size_units'    => [ 'em', 'px' ],
          'default' => [
          'top' => 2,
          'right' => 4,
          'bottom' => 2,
          'left' => 4,
          'unit' => 'em',
          ],
          'selectors'     => [
          '{{WRAPPER}} .cd-modal-action .btn span'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],

          ]
          ); */

        //
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'btn_border',
                'label' => __('Button Border', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .cd-modal-action .btn',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'label' => 'Button shadow',
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .cd-modal-action .btn',
            ]
        );
        $this->end_controls_section();
        

        $this->start_controls_section(
            'section_style_modal', [
                'label' => 'Modal Window',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        // ------------------------- Il Modale
        $this->add_control(
            'fmw_modal',
            [
                'label' => __( 'Modal', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient' ],
                /*'fields_options' => [
                    'background' => [
                        'frontend_available' => true,
                    ],
                    'video_link' => [
                        'frontend_available' => true,
                    ],
                ],*/
                'selector' => '{{WRAPPER}} .cd-modal-action .cd-modal-bg.is-visible',
                
            ]
        );
        $this->add_responsive_control(
            'fmw_modal_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .cd-modal .cd-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
        // ------------- Close Button
        $this->add_control(
            'fmw_closebutton',
            [
                'label' => __( 'Close button', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('fmw_button_colors');

        $this->start_controls_tab(
                'fmw_button_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
            'color_closemodal', [
                'label' => __('Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics:after, {{WRAPPER}} .cd-modal-close .dce-quit-ics:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
       
        $this->add_control(
                'fmw_button_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
                'fmw_button_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'fmw_button_hover_color', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cd-modal-close:hover .dce-quit-ics:after, {{WRAPPER}} .cd-modal-close:hover .dce-quit-ics:before' => 'background-color: {{VALUE}};',
            ],
            
                ]
        );
        $this->add_control(
                'fmw_button_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cd-modal-close .dce-quit-ics:hover' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        

        $this->end_controls_tab();

        $this->end_controls_tabs();
        //
        $this->add_responsive_control(
            'buttonsize_closemodal', [
                'label' => __('Button Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'weight_closemodal', [
                'label' => __('Close Width', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics:after, {{WRAPPER}} .cd-modal-close .dce-quit-ics:before' => 'height: {{SIZE}}{{UNIT}}; top: calc(50% - ({{SIZE}}{{UNIT}}/2));',
                ],
            ]
        );
        $this->add_control(
            'size_closemodal', [
                'label' => __('Close Size (%)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 60,
                    'unit' => '%',
                ],
                'size_units' => [ '%'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics:after, {{WRAPPER}} .cd-modal-close .dce-quit-ics:before' => 'width: {{SIZE}}{{UNIT}}; left: calc(50% - ({{SIZE}}{{UNIT}}/2));',
                ],
            ]
        );

        $this->add_responsive_control(
            'vertical_close', [
                'label' => __('Y Position', 'dynamic-content-for-elementor'),
               
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'horizontal_close', [
                'label' => __('X Position', 'dynamic-content-for-elementor'),
           
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cd-modal-close .dce-quit-ics' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;

        $template = $settings['template'];

        $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        ?>
        <section class="dce-modalwindow-section">
            <!-- section content here -->

            <div class="cd-modal-action">
                <a href="#" class="btn <?php echo $animation_class; ?>" data-type="modal-trigger">
                    <?php
                    if($settings['icon_fmw'] && $settings['icon_fmw_align'] == 'left'){
                        echo '<span class="icon-modalwindow icon-'.$settings['icon_fmw_align'].' '.$settings['icon_fmw'].'"></span>';
                    }
                    ?>
                    <?php echo __($settings['text_btn'], 'dynamic-content-for-elementor'.'_texts'); ?>
                    <?php
                    if($settings['icon_fmw'] && $settings['icon_fmw_align'] == 'right'){
                        echo '<span class="icon-modalwindow icon-'.$settings['icon_fmw_align'].' '.$settings['icon_fmw'].'"></span>';
                    }
                    ?>  

                    </a>
                <span class="cd-modal-bg"></span>
            </div>

            <div class="cd-modal">
                <div class="cd-modal-content">
                    <!-- modal content here -->
               <?php     
         echo do_shortcode('[dce-elementor-template id="' . $template . '"]'); ?>
                </div>
            </div>
            <a href="#" class="cd-modal-close">
                <span class="dce-quit-ics"></span>
            </a>
        </section>
        <?php
    }
}