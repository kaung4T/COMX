<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

class Aepro_Post_Navigation extends Widget_Base{
    public function get_name() {
        return 'ae-post-navigation';
    }

    public function get_title() {
        return __( 'AE - Post Navigation', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_custom_help_url() {
        $helper = new Helper();
        return $helper->get_help_url_prefix() . $this->get_name();
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_general',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'post_title',
            [
                'label' => __( 'Prev-Next Post Title', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __( 'Yes', 'ae-pro' ),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __( 'No', 'ae-pro' ),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                'default' => '1'
            ]
        );

        $this->add_control(
            'layout_mode',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'layout-1' => __( 'Layout 1', 'ae-pro' ),
                    'layout-2' => __( 'Layout 2', 'ae-pro' ),
                ],
                'default' => 'layout-2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->add_control(
            'item_separator',
            [
                'label' => __( 'Separator', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter separator', 'ae-pro' ),
                'default' => __( ' | ', 'ae-pro' ),
                'condition' => [
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_previous',
            [
                'label' => __( 'Previous', 'ae-pro' ),
            ]
        );


        $this->add_control(
            'prev_label',
            [
                'label' => __( 'Previous Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( 'Previous', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-angle-double-left',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_next',
            [
                'label' => __( 'Next', 'ae-pro' ),
            ]
        );


        $this->add_control(
            'next_label',
            [
                'label' => __( 'Next Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( 'Next', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-angle-double-right',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-item-separator' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'item_separator!' => '',
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->add_control(
            'separator_size',
            [
                'label' => __( 'Separator Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-item-separator' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'item_separator!' => '',
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->add_responsive_control(
            'separator_spacing',
            [
                'label' => __( 'Spacing', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'horizontal',
                'default' => [ '5' ] ,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-item-separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_mode' => 'layout-1',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section-previous-style',
            [
                'label' => __( 'Nav Style', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'prev_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-anchorPrevLink, {{WRAPPER}} .ae-element-anchorNextLink' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'prev_hover_color',
            [
                'label' => __( 'Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-anchorPrevLink:hover,{{WRAPPER}} .ae-element-anchorNextLink:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-anchorPrevLink, {{WRAPPER}} .ae-element-anchorNextLink',
            ]
        );

        $this->add_control(
            'prev_icon_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-prev-icon.icon-wrapper i, {{WRAPPER}} .ae-element-next-icon.icon-wrapper i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'prev_icon!' => ''
                ],
            ]
        );
        $this->add_control(
            'prev_icon_size',
            [
                'label' => __( 'Icon Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-prev-icon.icon-wrapper i, {{WRAPPER}} .ae-element-next-icon.icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'prev_icon!' => ''
                ],
            ]
        );

        $this->add_control(
            'prev_title_settings',
            [
                'label' => __( 'Title Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'post_title' => '1',
                ]
            ]
        );
        $this->add_control(
            'prev_title_color',
            [
                'label' => __( 'Title Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-prev-title, {{WRAPPER}} .ae-element-next-title' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'prev_title_hover_color',
            [
                'label' => __( 'Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-prev-title:hover, {{WRAPPER}} .ae-element-next-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_title_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-prev-title, {{WRAPPER}} .ae-element-next-title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();
        $post_id = $post_data->ID;

        $previous_post_id = $helper->get_previous_post_id($post_id);
        $next_post_id = $helper->get_next_post_id($post_id);


        $this->add_render_attribute('post-class-wrapper', 'class', 'ae-element-wrapper' );

        if(!empty($settings['prev_label'])){
            $this->add_render_attribute('post-class-wrapper', 'class', 'ae-nav-prev-next' );
        }

        $this->add_render_attribute('post-class-prev', 'class', 'ae-element-anchorPrevLink' );
        $this->add_render_attribute('post-class-next', 'class', 'ae-element-anchorNextLink' );
        $this->add_render_attribute('post-class-prev-title', 'class', 'ae-element-prev-title' );
        $this->add_render_attribute('post-class-next-title', 'class', 'ae-element-next-title' );
        $this->add_render_attribute('post-class-prev-wrapper', 'class', 'ae-element-anchorPrevLink-wrapper' );
        $this->add_render_attribute('post-class-next-wrapper', 'class', 'ae-element-anchorNextLink-wrapper' );
        $this->add_render_attribute('post-class-prev-wrapper','class','ae-element-prev-'.$settings['layout_mode']);
        $this->add_render_attribute('post-class-next-wrapper','class','ae-element-next-'.$settings['layout_mode']);
        $this->add_render_attribute('post-prev-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-prev-icon-class','class','ae-element-prev-icon');
        $this->add_render_attribute('post-prev-icon','class',$settings['prev_icon']);
        $this->add_render_attribute('post-next-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-next-icon-class','class','ae-element-next-icon');
        $this->add_render_attribute('post-next-icon','class',$settings['next_icon']);
        $this->add_render_attribute('post-separator-class','class','ae-element-item-separator');

        ?>
        <div <?php echo $this->get_render_attribute_string( 'post-class-wrapper' ); ?>>
        <!-- call only if a value exists -->
        <?php if(!empty($previous_post_id)) :
            $previous_post = get_permalink($previous_post_id );
            $previous_post_title = get_the_title($previous_post_id );
            ?>
            <span <?php echo $this->get_render_attribute_string( 'post-class-prev-wrapper' ); ?>>
                <a href="<?php echo $previous_post;?>" <?php echo $this->get_render_attribute_string('post-class-prev'); ?>>
                    <?php if(!empty($settings['prev_icon'])){ ?>
                        <span <?php echo $this->get_render_attribute_string( 'post-prev-icon-class' ); ?>>
                            <i <?php echo $this->get_render_attribute_string( 'post-prev-icon' ); ?>></i>
                        </span>
                    <?php } ?>
                    <?php echo $settings['prev_label'];?>
                </a>
                <?php if(!empty($settings['post_title'])){ ?>

                    <a href="<?php echo $previous_post;?>" <?php echo $this->get_render_attribute_string('post-class-prev-title'); ?>>
                        <?php echo $previous_post_title;?>
                    </a>
                <?php } ?>
            </span>
        <?php endif; ?>

        <?php if(($settings['layout_mode']=='layout-1')  && is_numeric($next_post_id) && is_numeric($previous_post_id)) : ?>
        <span <?php echo $this->get_render_attribute_string('post-separator-class'); ?>>
            <?php echo $settings['item_separator'];?>
        </span>
        <?php endif; ?>

        <!-- call only if a value exists -->
        <?php if(!empty($next_post_id)) :
            $next_post= get_permalink($next_post_id );
            $next_post_title = get_the_title($next_post_id );?>
            <span <?php echo $this->get_render_attribute_string( 'post-class-next-wrapper' ); ?>>
            <?php
            switch ($settings['layout_mode']) {
                case "layout-1": ?>             <?php if(!empty($settings['post_title'])){ ?>
                                                    <a href="<?php echo $next_post;?>" <?php echo $this->get_render_attribute_string('post-class-next-title'); ?>>
                                                        <?php echo $next_post_title;?>
                                                    </a>
                                                <?php } ?>
                                                <a href="<?php echo $next_post;?>" <?php echo $this->get_render_attribute_string('post-class-next'); ?>>
                                                    <?php echo $settings['next_label'];?>
                                                        <?php if(!empty($settings['next_icon'])){ ?>
                                                            <span <?php echo $this->get_render_attribute_string( 'post-next-icon-class' ); ?>>
                                                                <i <?php echo $this->get_render_attribute_string( 'post-next-icon' ); ?>></i>
                                                            </span>
                                                        <?php } ?>
                                                </a>
                                                <?php break; ?>
                <?php
                case "layout-2" : ?>            <a href="<?php echo $next_post;?>" <?php echo $this->get_render_attribute_string('post-class-next'); ?>>
                                                    <?php echo $settings['next_label'];?>
                                                    <?php if(!empty($settings['next_icon'])){ ?>
                                                        <span <?php echo $this->get_render_attribute_string( 'post-next-icon-class' ); ?>>
                                                            <i <?php echo $this->get_render_attribute_string( 'post-next-icon' ); ?>></i>
                                                        </span>
                                                    <?php } ?>
                                                </a>
                                                <?php if(!empty($settings['post_title'])){ ?>
                                                    <a href="<?php echo $next_post;?>" <?php echo $this->get_render_attribute_string('post-class-next-title'); ?>>
                                                        <?php echo $next_post_title;?>
                                                    </a>
                                                <?php } ?>
                                                <?php break; ?>
            <?php }
            ?>


            </span>
        <?php endif; ?>
        </div>





<?php    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_navigation() );