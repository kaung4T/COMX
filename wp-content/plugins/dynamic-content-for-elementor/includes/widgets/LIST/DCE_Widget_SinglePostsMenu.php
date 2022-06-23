<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use DynamicContentForElementor\DCE_Helper;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Parent Child Menu
 *
 * Elementor widget for Linkness Elements
 *
 */
class DCE_Widget_SinglePostsMenu extends DCE_Widget_Prototype {

    public function get_name() {
            return 'single-posts-menu';
    }
    static public function is_enabled() {
        return true;
    }
    public function get_title() {
            return __( 'Single Posts List', 'dynamic-content-for-elementor' );
    }
    public function get_description() {
        return __('Create a custom menu from single pages', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/single-posts-list/';
    }
    public function get_icon() {
            return 'icon-dyn-listsingle';
    }
    /*public function get_style_depends() {
        return [ 'dce-list' ];
    }*/
    protected function _register_controls() {
        
        $this->start_controls_section(
            'section_content',
            [
                    'label' => __( 'Custom menu from single pages', 'dynamic-content-for-elementor' ),
            ]
        );
        

        $this->add_control(
            'singlepage_select',
            [
                'label' => __( 'Select Single Posts', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HIDDEN,
                'multiple' => true,
                'label_block' => true,
                'options' => array(), //DCE_Helper::get_all_posts(null,false,'menu_order'),
                //'groups' => DCE_Helper::get_all_posts(get_the_ID(),true),
            ]
        );
        
        $repeater = new Repeater();
        /*$repeater->add_control(
            'singlepage_select',
            [
                'label' => __( 'Select Single Posts', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'label_block' => true,
                'options' => DCE_Helper::get_all_posts(null,false,'menu_order'),
                //'groups' => DCE_Helper::get_all_posts(get_the_ID(),true),
                'default' => ''
            ]
        );*/
        $repeater->add_control(
                'singlepage_select',
                [
                    'label' => __( 'Select Single Posts', 'dynamic-content-for-elementor' ),
                    'type' 		=> 'ooo_query',
                    'placeholder'	=> __( 'Post Title', 'dynamic-content-for-elementor' ),
                    'label_block' 	=> true,
                    'query_type'	=> 'posts',
                ]
        );
        
        $this->add_control(
                'pages', [
            'label' => __('Pages', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::REPEATER,
            'prevent_empty' => false,
            'default' => [
            ],
            'fields' => array_values($repeater->get_controls()),
            //'title_field' => get_the_title('{{{ singlepage_select }}}'),
                ]
        );
        // ------
        $this->add_control(
            'menu_style',
            [
                'label' => __( 'Style', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'dynamic-content-for-elementor' ),
                    'vertical' => __( 'Vertical', 'dynamic-content-for-elementor' )
                ],
                'default' => 'vertical',
            ]
        );
        $this->add_control(
            'heading_options_menu',
            [
                    'label' => __( 'Options', 'dynamic-content-for-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
            ]
        );
        $this->add_control(
        'show_title',
            [
                'label' => __( 'Show Title', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'title_text',
            [
                'label' => __( 'Title text', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'show_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'show_childlist',
            [
                'label' => __( 'Show Child List', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'show_publish',
            [
                'label' => __( 'Show only published', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control(
        'show_border',
            [
                'label' => __( 'Show Border', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => __( 'Yes', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __( 'No', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-ban',
                    ],
                    '2' => [
                        'title' => __( 'Any', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-square-o',
                    ]
                ],
                /*'condition' => [
                    'menu_style' => 'vertical',
                ],*/
                'default' => '1',
                'render_type' => 'template',
                'prefix_class' => 'border-',
            ]
        );

        $this->add_control(
        'blockwidth_enable',
            [
                'label'         => __( 'Force Block width', 'dynamic-content-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'separator'     => 'before',
                'condition' => [
                    'show_border' => '2',
                ],
            ]
        );
        $this->add_control(
            'menu_width',
            [
                'label' => __( 'Box Width', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 120,
                ],
                'range' => [
                    'px' => [
                            'min' => 0,
                            'max' => 400,
                    ],
                ],
                'condition' => [
                    'blockwidth_enable' => 'yes',
                    'show_border' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu .box' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
        'show_separators',
            [
                'label' => __( 'Show Separator', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'solid' => [
                        'title' => __( 'Yes', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-check',
                    ],
                    'none' => [
                        'title' => __( 'No', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'toggle' => true,
                'default' => 'solid',
                'separator' => 'before',
                'selectors' => [
                            '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-style: {{VALUE}};',
                                ],
                'condition' => [
                    'menu_style' => 'horizontal',
                ],
            ]
        );
        
        $this->add_control(
            'heading_spaces_menu',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_childlist!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_space',
            [
                'label' => __( 'Header Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                        'size' => 0,
                ],
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-menu .dce-parent-title' => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2);',
                        '{{WRAPPER}} .dce-menu hr' => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2);',
                        '{{WRAPPER}} .dce-menu div.box' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_list_space',
            [
                'label' => __( 'List Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                        'size' => 0,
                ],
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-menu ul.first-level > li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_childlist!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_indent',
            [
                'label' => __( 'Indent', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-menu ul.first-level > li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_childlist!' => '',
                ],
            ]
        );
        
        $this->end_controls_section();
        // ---------------------------------- STYLE
        $this->start_controls_section(
            'section_style',
            [
                    'label' => __( 'Style', 'dynamic-content-for-elementor' ),
                    'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
        'menu_align',
            [
                'label' => __( 'Text Alignment', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', DCE_TEXTDOMAIN ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', DCE_TEXTDOMAIN ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', DCE_TEXTDOMAIN ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'prefix_class' => 'menu-align-',
                'default' => 'left',
                'selectors' => [
                     /*'{{WRAPPER}} .dce-menu' => 'text-align: {{VALUE}};',*/
                     '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_colors',
            [
                    'label' => __( 'List items', 'dynamic-content-for-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'show_childlist!' => '',
                    ],
            ]
        );
        $this->add_control(
            'menu_color',
            [
                'label' => __( 'Text Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'condition' => [
                    'show_childlist!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_color_hover',
            [
                'label' => __( 'Text Hover Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'condition' => [
                    'show_childlist!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_color_active',
            [
                'label' => __( 'Text Active Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'condition' => [
                    'show_childlist!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu ul li a.active' => 'color: {{VALUE}};',
                ],
            ]
        );   
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_list',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .dce-menu ul.first-level li',
                'condition' => [
                    'show_childlist!' => '',
                ],
            ]
        );
        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'menu_title_color',
            [
                'label' => __( 'Title Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'condition' => [
                    'show_title!' => '',
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-menu .dce-parent-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_tit',

                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .dce-menu .dce-parent-title',
                'condition' => [
                    'show_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_border',
            [
                'label' => __( 'Border', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_border' => ['1','2'],
                ],
            ]
        );
        $this->add_control(
            'menu_border_color',
            [
                'label' => __( 'Border Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'toggle' => false,
                
                'condition' => [
                    'show_border' => ['1','2'],
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-menu hr' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-color: {{VALUE}};',
                        '{{WRAPPER}} .dce-menu .box' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_border_size',
            [
                'label' => __( 'Border weight', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'toggle' => false,
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu hr, {{WRAPPER}} .dce-menu .box' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_border' => ['1','2']
                ],
            ]
        );
        $this->add_control(
            'menu_border_width',
            [
                'label' => __( 'Border width', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'toggle' => false,
                'size_units' => [ 'px','%'],
                'default' => [
                    'size' => '',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu hr' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_border' => ['1','2']
                ],
            ]
        );
        $this->add_control(
            'heading_separator',
            [
                'label' => __( 'Separator', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_separators' => 'solid',
                    'menu_style' => 'horizontal',
                ],
            ]
        );
        $this->add_control(
            'menu_color_separator',
            [
                'label' => __( 'Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'default' => '',
                'condition' => [
                   'show_separators' => 'solid',
                   'menu_style' => 'horizontal'
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_size_separator',
            [
                'label' => __( 'Weight', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_separators' => 'solid',
                    'menu_style' => 'horizontal',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;

        

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data(/*$settings['other_post_source'],$settings['other_post_parent']*/);
        $id_page = $dce_data['id'];
        $type_page = $dce_data['type'];
        $global_is = $dce_data['is'];
        // ------------------------------------------



        $styleMenu = $settings['menu_style'];
        $clssStyleMenu = $styleMenu; 

        echo '<nav class="dce-menu '.$clssStyleMenu.'" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">';
        if( $settings['show_border'] == 2  ) echo '<div class="box">';
        if ( $settings['show_title'] ) {

            echo '<h4 class="dce-parent-title">'.__($settings['title_text'], 'dynamic-content-for-elementor'.'_texts').'</h4>';
            if( $settings['show_border'] == 1  ) echo '<hr />';

        }
        if ( $settings['show_childlist'] ) {
            
            echo '<ul class="first-level">';

                $pages = $settings['pages'];
                if (!empty($pages)) {
                    foreach ($pages as $key => $item) :
                        $pageid = DCE_Helper::get_rev_ID($item['singlepage_select'],get_post_type($item['singlepage_select']));
                        if( $id_page == $pageid ){
                            $linkActive = ' class="active"';
                        }else{
                            $linkActive = '';
                        }
                        if( get_post_status( $pageid ) == 'publish' && $settings['show_publish'] ){
		                echo '<li class="item-'.$pageid.'"><a href="'.get_permalink( $pageid ).'"'.$linkActive.'>'.get_the_title( $pageid ).'</a>';
		                
		                echo '</li>';
                        }
                    endforeach;
                }else{


                    $children = $settings['singlepage_select'];
                    if (!empty($children)) {
                        foreach ( $children as $pageid ) {
                            if( $id_page == $pageid ){
                                $linkActive = ' class="active"';
                            }else{
                                $linkActive = '';
                            }
                            //echo $page->ID.' '.get_the_id();
                            
                                echo '<li class="item-'.$pageid.'"><a href="'.get_permalink( $pageid ).'"'.$linkActive.'>'.get_the_title( $pageid ).'</a>';
                                
                                echo '</li>';
                          
                            
                        }
                    }
                }
                echo '</ul>';

            }
            if( $settings['show_border'] == 2  ) echo '</div>';
            //var_dump(DCE_Helper::get_all_posts(null,false,'menu_order'));
            echo '</nav>';
    }

}
