<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Animated Text BETA
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_AnimateText extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-animateText';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('AnimateText', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-animate_text';
    }
    static public function get_position() {
        return 2;
    }
    
    public function get_description() {
        return __('Advanced animation to your text', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/animated-text/';
    }
    
    /**
     * A list of scripts that the widgets is depended in
     * @since 1.3.0
     * */
    public function get_script_depends() {
        return [ 'dce-tweenMax-lib','dce-timelineMax-lib','dce-attr-lib','dce-splitText-lib' ];
    }
    
    /*public function get_style_depends() {
        return [ 'dce-parallax'];
    }*/

    protected function _register_controls() {
        $this->start_controls_section(
                'section_animateText', [
            'label' => __('AnimateText', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
            'animatetext_splittype', [
                'label' => __('Type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                
                'options' => [
                    'chars' => __('Chars', 'dynamic-content-for-elementor'),
                    'words' => __('Words', 'dynamic-content-for-elementor'),
                    'lines' => __('Lines', 'dynamic-content-for-elementor'),
                    
                ],
                'frontend_available' => true,
                'default' => 'chars',
            ]
        );
        
        


        

        

        $repeater = new Repeater();

        $repeater->start_controls_tabs('tabs_repeater'); // start tabs ---------------------------------
        $repeater->start_controls_tab('tab_content', [ 'label' => __('Content', 'dynamic-content-for-elementor')]);
        //
        
        $repeater->add_control(
            'text_word', [
                'label' => __('Word', 'dynamic-content-for-elementor'),
                'description' => __('Text before elemnet', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );
        
        $repeater->end_controls_tab();
        $repeater->start_controls_tab('tab_style', [ 'label' => __('Style', 'dynamic-content-for-elementor')]);       
        //
        
        $repeater->add_control(
            'color_item', [
                'label' => __('Text color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-animatetext{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],

            ]
        );
        
        $repeater->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_item',
                'label' => 'Typography item',
                
                'selector' => '{{WRAPPER}} .dce-animatetext{{CURRENT_ITEM}}',
                
            ]
        );
        
        
        

        
        
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs(); 

        $this->add_control(
            'words', [
                'label' => __('Text', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'text_word' => 'Write a word',
                    ]
                    
                ],
                'separator' => 'after',
                'frontend_available' => true,
                'fields' => array_values($repeater->get_controls()),
                'title_field' => '#',
                'title_field' => '{{{ text_word }}}',
            ]
        );
        $this->add_control(
          'animatetext_repeat',
          [
            'label'   => __( 'Repeat', 'dynamic-content-for-elementor' ),
            'type'    => Controls_Manager::NUMBER,
            'label_block' => false,
            'separator' => 'before',
            'frontend_available' => true,
            'description' => 'Infinite: -1 or do not repeat: 0',
            'default' => -1,
            'min'     => -1,
            'max'     => 25,
            'step'    => 1,
            
          ]
        );
        
        $this->end_controls_section();




        // ---------------------------------------------------- IN

        $this->start_controls_section(
            'section_animateText_in', [
                'label' => __('IN', 'dynamic-content-for-elementor'),
            ]
        );
        
        /*$this->add_control(
            'animationstyle_mode_in', [
                'label' => __('styles/Custom', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'custom',
                'label_on' => __('Custom', 'dynamic-content-for-elementor'),
                'label_off' => __('Style', 'dynamic-content-for-elementor'),
                'return_value' => 'custom',
                'separator' => 'before'
            ]
        );*/
        $this->add_control(
            'animatetext_animationstyle_in', [
                'label' => __('Animation style', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fading' => __('Fading', 'dynamic-content-for-elementor'),
                    'from_left' => __('From Left', 'dynamic-content-for-elementor'),
                    'from_right' => __('From Right', 'dynamic-content-for-elementor'),
                    'from_top' => __('From Top', 'dynamic-content-for-elementor'),
                    'from_bottom' => __('From Bottom', 'dynamic-content-for-elementor'),
                    'zoom_front' => __('Zoom Front', 'dynamic-content-for-elementor'),
                    'zoom_back' => __('Zoom Back', 'dynamic-content-for-elementor'), 
                    
                    //'shaking' => __('Shaking', 'dynamic-content-for-elementor'),
                    //'tada' => __('Tadaaa', 'dynamic-content-for-elementor'),
                    //'ghosting' => __('Ghoasting', 'dynamic-content-for-elementor'), 
                    //'floating' => __('Floating', 'dynamic-content-for-elementor'),
                    //'jumping' => __('Jumping', 'dynamic-content-for-elementor'),
                    //'reveals' => __('Reveals', 'dynamic-content-for-elementor'),

                    //'elastic' => __('Elastic', 'dynamic-content-for-elementor'),
                    'random_position' => __('Random position', 'dynamic-content-for-elementor'),
                    'from_bottom' => __('From Bottom', 'dynamic-content-for-elementor'),

                ],
                'frontend_available' => true,
                'default' => 'fading',
            ]
        );
        $this->add_control(
           'animatetext_splitorigin_in', [
              'label' => __('Origin', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::CHOOSE,
              
              'options' => [
                  'null' => [
                      'title' => __('Start', 'dynamic-content-for-elementor'),
                      'icon' => 'eicon-h-align-left', //'eicon-v-align-top',
                  ],
                  'center' => [
                      'title' => __('Center', 'dynamic-content-for-elementor'),
                      'icon' => 'eicon-h-align-center', //'eicon-v-align-middle',
                  ],
                  'end' => [
                      'title' => __('End', 'dynamic-content-for-elementor'),
                      'icon' => 'eicon-h-align-right', //'eicon-v-align-bottom',
                  ],
                  
              ],
              'default' => 'null',
              'frontend_available' => true,
              /*'condition' => [
                    'animatetext_animationstyle_in!' => ['random_position']
                ],*/
            ]
        );

        /*$this->add_control(
                'animationtext-toggle-in', [
            'label' => __('Velocity', 'plugin-name'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => __('Default', 'dynamic-content-for-elementor'),
            'label_on' => __('Custom', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->start_popover();*/

        $this->add_control(
            'speed_animation_in',
            [
                'label' => __( 'Speed', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.7,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.2,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'amount_speed_in',
            [
                'label' => __( 'Amounth (negative values produce a contrary effect of origin)', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'delay_animation_in',
            [
                'label' => __( 'Delay', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 0.1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'animFrom_easing_in', [
                'label' => __('Easing', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_gsap_ease(),
                'default' => 'easeInOut',
                'frontend_available' => true,
                'label_block' => false,
                
            ]
        );
        $this->add_control(
            'animFrom_easing_ease_in', [
                'label' => __('Equation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_gsap_timingFunctions(),
                'default' => 'Power3',
                'frontend_available' => true,
                'label_block' => false,
                
            ]
        );
        /*$this->end_popover();*/

        $this->end_controls_section();


        // ---------------------------------------------------- OUT
        $this->start_controls_section(
            'section_animateText_out', [
                'label' => __('OUT', 'dynamic-content-for-elementor'),
            ]
        );
        
       /* $this->add_control(
            'animationstyle_mode_out', [
                'label' => __('styles/Custom', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'custom',
                'label_on' => __('Custom', 'dynamic-content-for-elementor'),
                'label_off' => __('Style', 'dynamic-content-for-elementor'),
                'return_value' => 'custom',
                'separator' => 'before'
            ]
        );*/
        $this->add_control(
            'animatetext_animationstyle_out', [
                'label' => __('Animation style', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fading' => __('Fading', 'dynamic-content-for-elementor'),
                    'to_left' => __('To Left', 'dynamic-content-for-elementor'),
                    'to_right' => __('To Right', 'dynamic-content-for-elementor'),
                    'to_top' => __('To Top', 'dynamic-content-for-elementor'),
                    'to_bottom' => __('To Bottom', 'dynamic-content-for-elementor'),
                    'zoom_front' => __('Zoom Front', 'dynamic-content-for-elementor'),
                    'zoom_back' => __('Zoom Back', 'dynamic-content-for-elementor'), 
                    
                    //'shaking' => __('Shaking', 'dynamic-content-for-elementor'),
                    //'tada' => __('Tadaaa', 'dynamic-content-for-elementor'),
                    //'ghosting' => __('Ghoasting', 'dynamic-content-for-elementor'), 
                    //'floating' => __('Floating', 'dynamic-content-for-elementor'),
                    //'jumping' => __('Jumping', 'dynamic-content-for-elementor'),
                    //'reveals' => __('Reveals', 'dynamic-content-for-elementor'),

                    'random_position' => __('Random position', 'dynamic-content-for-elementor'),
                    'elastic' => __('Elastic', 'dynamic-content-for-elementor'),

                ],
                'frontend_available' => true,
                'default' => 'fading',
            ]
        );
        /*$this->add_control(
            'animatetext_splitorigin', [
                'label' => __('Split origin', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                
                'options' => [
                    'left' => __('Center', 'dynamic-content-for-elementor'),
                    'left' => __('Left', 'dynamic-content-for-elementor'),
                    'right' => __('Right', 'dynamic-content-for-elementor'),
                    
                ],
                'frontend_available' => true,
                'default' => 'chars',
                'separator' => 'after',
            ]
        );*/
        $this->add_control(
           'animatetext_splitorigin_out', [
                'label' => __('Origin', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                
                'options' => [
                    'null' => [
                        'title' => __('Start', 'dynamic-content-for-elementor'),
                        'icon' => 'eicon-h-align-left', //'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'dynamic-content-for-elementor'),
                        'icon' => 'eicon-h-align-center', //'eicon-v-align-middle',
                    ],
                    'end' => [
                        'title' => __('End', 'dynamic-content-for-elementor'),
                        'icon' => 'eicon-h-align-right', //'eicon-v-align-bottom',
                    ],
                    
                ],
                'default' => 'null',
                'frontend_available' => true,
                /*'condition' => [
                    'animatetext_animationstyle_out!' => ['random_position']
                ],*/
            ]
        );
        
        /*$this->add_control(
                'animationtext-toggle-out', [
            'label' => __('Velocity', 'plugin-name'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => __('Default', 'dynamic-content-for-elementor'),
            'label_on' => __('Custom', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->start_popover();*/

        $this->add_control(
            'speed_animation_out',
            [
                'label' => __( 'Speed', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.7,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.2,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'amount_speed_out',
            [
                'label' => __( 'Amounth (negative values produce a contrary effect of origin)', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'delay_animation_out',
            [
                'label' => __( 'Delay', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 0.1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'animFrom_easing_out', [
                'label' => __('Easing', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_gsap_ease(),
                'default' => 'easeInOut',
                'frontend_available' => true,
                'label_block' => false,
                
            ]
        );
        $this->add_control(
            'animFrom_easing_ease_out', [
                'label' => __('Equation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_gsap_timingFunctions(),
                'default' => 'Power3',
                'frontend_available' => true,
                'label_block' => false,
                
            ]
        );
        /*$this->end_popover();*/

        $this->end_controls_section();


        // ---------------------------------------------------- STYLE
        $this->start_controls_section(
            'section_style', [
                'label' => __('Animate Text', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
        'animatetext_align',
            [
                'label' => __( 'Alignment', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,

                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    
                ],
                //'prefix_class' => 'align-',
                'render_type' => 'template',
                'default' => 'left',
                'selectors' => [
                     '{{WRAPPER}} .dce-animatetext' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-animatetext' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dce-animatetext a' => 'color: {{VALUE}};',
                ],
            ]
        );

        
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dce-animatetext',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .dce-animatetext',
            ]
        );
        
        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-animatetext' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;
        
        //echo $settings['animationstyle_mode'];

        $effect = $settings['animatetext_animationstyle_in'];
        if($effect) $effect = ' dce-animatetext-'.$effect;
        $words = $settings['words'];
        $firstWord = $words[0]['text_word'];
        echo '<div class="dce-animatetext'.$effect.'">';
        echo $firstWord;
        echo '</div>'; 
        echo '<div style="display:none;" class="testi-nascosti">'; 
          
          if (!empty($words)) {
              $counter_item = 0;
              foreach ($words as $key => $w) :
                 echo '<div class="dce-animatetext-item dce-animatetext-item-'.$counter_item.$effect.'">';
                  //echo 'a ';
                  echo $w['text_word']; 
                  $counter_item++;
                  echo '</div>';
              endforeach;
          }
        echo '</div>'; 
        
    }

    protected function _content_template_() {
        
    }
}