<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elelentor Post NextPrev
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_NextPrev extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-post-nextprev';
    }
    static public function is_enabled() {
        return true;
    }
    public function get_title() {
        return __('Prev Next', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Access pages adjacent the selected post based on a category/taxonomy or tag', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/prevnext/';
    }
    public function get_icon() {
        return 'icon-dyn-prevnext';
    }
     public function get_script_depends() {
        return []; //['ajaxify','dce-nextPrev'];
    }
    static public function get_position() {
        return 4;
    }
    /*public function get_style_depends() {
        return [ 'dce-nextPrev' ];
    }*/
    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_content', [
                'label' => __('PrevNext', 'dynamic-content-for-elementor')
            ]
        );
        $this->add_control(
            'style_postnav', [
                  'label' => __('Style', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SELECT,
                  'options' => [
                      'classic' => __('Classic', 'dynamic-content-for-elementor'),
                      'thumbflip' => __('Thumb Flip', 'dynamic-content-for-elementor'),
                      // 'dualflip' => __('Dual Flip', 'dynamic-content-for-elementor'),
                      // 'flip' => __('Thumb Flip', 'dynamic-content-for-elementor'),
                      // 'slide' => __('Slide', 'dynamic-content-for-elementor'),
                      
                      //'growpop' => __('Grow Pop', 'dynamic-content-for-elementor'),
                  ],
                  'default' => 'classic',
                  'prefix_class' => 'nav-',
                  'separator' => 'after',
                 'render_type' => 'template',
            ]
        );
        $this->add_control(
            'show_title', [
                'label' => __('Show Title', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'dynamic-content-for-elementor'),
                'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'show_prevnext', [
                'label' => __('Show PrevNext Text', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'dynamic-content-for-elementor'),
                'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'icon_right', [
                'label' => __('Icon Right', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-arrow-right',
                'include' => [
                    'fa fa-arrow-right',
                    'fa fa-angle-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-caret-square-o-right',
                    'fa fa-chevron-right',
                    'fa fa-caret-right',
                    'fa fa-angle-double-right',
                    'fa fa-hand-o-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-long-arrow-alt-right',
                    'fa fa-arrow-alt-circle-right',
                ],
            ]
        );
        $this->add_control(
            'icon_left', [
                'label' => __('Icon Left', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-arrow-left',
                'include' => [
                    'fa fa-arrow-left',
                    'fa fa-angle-left',
                    'fa fa-chevron-circle-left',
                    'fa fa-caret-square-o-left',
                    'fa fa-chevron-left',
                    'fa fa-caret-left',
                    'fa fa-angle-double-left',
                    'fa fa-hand-o-left',
                    'fa fa-arrow-circle-left',
                    'fa fa-long-arrow-alt-left',
                    'fa fa-arrow-alt-circle-left',
                ],
            ]
        );
        $this->add_control(
            'prev_label',
            [
                'label' => __( 'Previous Label', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Previous', 'dynamic-content-for-elementor' ),
                'condition' => [
                    'show_prevnext' => 'yes',
                    'style_postnav' => 'classic'
                ],
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => __( 'Next Label', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Next', 'dynamic-content-for-elementor' ),
                'condition' => [
                    'show_prevnext' => 'yes',
                    'style_postnav' => 'classic'
                ],
            ]
        );
         $this->add_control(
            'same_term', [
                'label' => __('Same term', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                'default' => '0'
            ]
        );
        $this->add_control(
            'taxonomy_type', [
                'label' => __('Taxonomy Type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_taxonomies(),
                'default' => '',
                'description' => ' if "Same term" is true.',
                'condition' => [
                    'same_term' => '1',
                ]
            ]
        );
        $this->add_control(
            'invert_prevnext', [
                'label' => __('Invert order', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
            ]
        );
         $this->add_control(
            'Navigation_heading',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_control(
            'navigation_space', [
                'label' => __('Navigation Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} nav.post-navigation' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'fluttua' => '',
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_control(
            'space', [
                'label' => __('Navigation Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],

                'selectors' => [
                    '{{WRAPPER}} .nav-links a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'fluttua' => '',
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_control(
            'custom_width', [
                'label' => __('Custom Width', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Custom Width', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    '0' => [
                        'title' => __('No', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .nav-links > div' => 'width: auto;',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ],
                'default' => '1'
            ]
        );
        $this->add_control(
            'width', [
                'label' => __('Width', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px'],
                'default' => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nav-links > div' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_width' => '1',
                ]
            ]
        );
        /* $this->add_control(
          'width',
          [
          'label' => __( 'Largezza elementi', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 5,
          ],
          'range' => [
          'px' => [
          'min' => 0,
          'max' => 100,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .nav-links > div' => 'padding: {{SIZE}}{{UNIT}};',
          ],
          ]
          ); */
        $this->end_controls_section();


        $this->start_controls_section(
            'section_position', [
                'label' => 'Position',
            ]
        );
        $this->add_control(
            'fluttua', [
                'label' => __('Floating', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'prefix_class' => 'float',
            ]
        );
        $this->add_control(
            'verticale', [
                'label' => __('Vertical', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'prefix_class' => 'vertical',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_icons', [
                'label' => 'Icons',
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
                        'min' => 10,
                        'max' => 80,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nav-links span .fa' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_space', [
                'label' => __('Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.nav-classic nav.post-navigation .nav-next .fa' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.nav-classic nav.post-navigation .nav-previous .fa' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_verticalalign', [
                'label' => __('Shift', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.nav-classic .nav-links .fa' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_space_tf', [
                'label' => __('Block Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.nav-thumbflip .icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style_postnav' => 'thumbflip'
                ]
            ]
        );

       /* $this->add_responsive_control(
            'icon_verticalalign_tf', [
                'label' => __('Shift (%)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                ],
                'range' => [
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.nav-thumbflip .nav-links > div' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style_postnav' => 'thumbflip'
                ]
            ]
        );*/

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style', [
                'label' => 'NextPrev',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color_1', [
                'label' => __('Color Navigation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nav-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} a .nav-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_control(
            'color_2', [
                'label' => __('Post title Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nav-post-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} a .nav-post-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_1',
                'label' => 'Typography prev/next',
                'selector' => '{{WRAPPER}} .nav-title',
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_2',
                'label' => 'Typography post title',
                'selector' => '{{WRAPPER}} .nav-post-title',
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );


        /* ICON */
        $this->add_control(
            'color_3', [
                'label' => __('Icon Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} i.fa' => 'color: {{VALUE}};',
                    '{{WRAPPER}} a i.fa' => 'color: {{VALUE}};',
                ],
            ]
        );


        /* Thumb flip */
        $this->add_control(
            'bgcolor_tf', [
                'label' => __('Background color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a .icon-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'thumbflip'
                ]
            ]
        );


        /////////// ROLL HOVER
        $this->add_control(
            'rollhover_heading',
            [
                'label' => __( 'Rollover', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'hover_color', [
                'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover span' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        $this->add_control(
            'hover_color_title', [
                'label' => __('Hover title Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover .nav-post-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'classic'
                ]
            ]
        );
        
        /* ICON Hover */
        $this->add_control(
            'hover_color_icon', [
                'label' => __('Hover icon Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover i.fa' => 'color: {{VALUE}};',
                ],
            ]
        );
        /* Thumb flip Hover */
        $this->add_control(
            'hover_bgcolor_tf', [
                'label' => __('Hover Background color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover .icon-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style_postnav' => 'thumbflip'
                ]
            ]
        );

        /*$this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );*/
       
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];
        // ------------------------------------------


        //$settings['link_to']
        $taxonomy_type = $settings['taxonomy_type'];
        $same_term = $settings['same_term'];
        //$this->plugin_url         = plugin_dir_url( __FILE__ );
        //$this->plugin_path        = plugin_dir_path( __FILE__ );

        $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        
        //$html = sprintf( '<div class="dce-nextprev %1$s">', $animation_class );
        //echo 'navigazione '.$taxonomy_type.'  '.get_the_ID();
        $title_nav = '';
        $prev_nav_tx = '';
        $next_nav_tx = '';
        if ($settings['show_title'] == 'yes') {
            $title_nav = '<span class="nav-post-title">%title</span>';
        }
        if ($settings['show_prevnext'] == 'yes') {
            if($settings['prev_label'] != ''){
                $prev_nav_tx = __($settings['prev_label'], 'dynamic-content-for-elementor'.'_texts');
            }else{
                $prev_nav_tx = esc_html__('Previous', 'dynamic-content-for-elementor');
            }
            if($settings['next_label'] != ''){
                $next_nav_tx = __($settings['next_label'], 'dynamic-content-for-elementor'.'_texts');
            }else{
                $next_nav_tx = esc_html__('Next', 'dynamic-content-for-elementor');
            }
            
           $prev_nav_tx = '<span class="nav-post-label">'.$prev_nav_tx.'</span>';
           $next_nav_tx = '<span class="nav-post-label">'.$next_nav_tx.'</span>';
        }
        $icon_right = $settings['icon_right'];
        $icon_left = $settings['icon_left'];


        $next_img = '';
        if(get_next_post()){
            $next_post = get_next_post();
            $next_img = get_the_post_thumbnail($next_post->ID,'thumbnail');
        }

        $previous_img = '';
        if(get_previous_post()){
            $previous_post = get_previous_post();
            $previous_img = get_the_post_thumbnail($previous_post->ID,'thumbnail');
        }

        $prevText = '';
        $nextText = '';
        if($settings['style_postnav'] == 'classic'){
            
            $prevText = '<span class="nav-title"><i class="' . $icon_left . '"></i><span>' . $prev_nav_tx . $title_nav.'</span>';
            $nextText = '<span class="nav-title"><i class="' . $icon_right . '"></i><span>' . $next_nav_tx . $title_nav.'</span>';
        
        }else if($settings['style_postnav'] == 'thumbflip'){
            
            $prevText = '<span class="icon-wrap"><i class="icon ' . $icon_left . '"></i></span>'.$previous_img;
            $nextText = '<span class="icon-wrap"><i class="icon ' . $icon_right . '"></i></span>'.$next_img;
        }

        $options_postnav = array(
            'prev_text' => $prevText,
            'next_text' => $nextText,
            //'in_same_term' => $same_term,
            //'excluded_terms'  => array('18'),
            //'taxonomy' => $taxonomy_type,
            'screen_reader_text' => '', //esc_html__('Continue Reading', 'oceanwp'),
                );
        if ($taxonomy_type) {
            $options_postnav['taxonomy'] = $taxonomy_type;
        }
        if ($same_term) {
            $options_postnav['in_same_term'] = $same_term;
        }

        echo '<nav>';
        $html = the_post_navigation($options_postnav);
        echo '</nav>';
        //$html .= '</div>';
        //echo $html;
        ?>
        <div class="svg-wrap">
            <svg width="64" height="64" viewBox="0 0 64 64">
                <defs>
                <path id="arrow-left-5" d="M48 10.667q1.104 0 1.885 0.781t0.781 1.885-0.792 1.896l-16.771 16.771 16.771 16.771q0.792 0.792 0.792 1.896t-0.781 1.885-1.885 0.781q-1.125 0-1.896-0.771l-18.667-18.667q-0.771-0.771-0.771-1.896t0.771-1.896l18.667-18.667q0.771-0.771 1.896-0.771zM32 10.667q1.104 0 1.885 0.781t0.781 1.885-0.792 1.896l-16.771 16.771 16.771 16.771q0.792 0.792 0.792 1.896t-0.781 1.885-1.885 0.781q-1.125 0-1.896-0.771l-18.667-18.667q-0.771-0.771-0.771-1.896t0.771-1.896l18.667-18.667q0.771-0.771 1.896-0.771z" />
            
                <path id="arrow-right-5" d="M29.333 10.667q1.104 0 1.875 0.771l18.667 18.667q0.792 0.792 0.792 1.896t-0.792 1.896l-18.667 18.667q-0.771 0.771-1.875 0.771t-1.885-0.781-0.781-1.885q0-1.125 0.771-1.896l16.771-16.771-16.771-16.771q-0.771-0.771-0.771-1.896 0-1.146 0.76-1.906t1.906-0.76zM13.333 10.667q1.104 0 1.875 0.771l18.667 18.667q0.792 0.792 0.792 1.896t-0.792 1.896l-18.667 18.667q-0.771 0.771-1.875 0.771t-1.885-0.781-0.781-1.885q0-1.125 0.771-1.896l16.771-16.771-16.771-16.771q-0.771-0.771-0.771-1.896 0-1.146 0.76-1.906t1.906-0.76z" />
                </defs>
            </svg>
        </div>
        <!-- <nav class="nav-thumbflip">
            <a class="prev" href="#">
                <span class="icon-wrap"><svg class="icon" width="32" height="32" viewBox="0 0 64 64"><use xlink:href="#arrow-left-5"></svg></span>
                <img src="img/9.png" alt="Previous thumb"/>
            </a>
            <a class="next" href="#">
                <span class="icon-wrap"><svg class="icon" width="32" height="32" viewBox="0 0 64 64"><use xlink:href="#arrow-right-5"></svg></span>
                <img src="img/10.png" alt="Next thumb"/>
            </a>
        </nav> -->
        <?php

    }

}
