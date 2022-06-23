<?php

namespace Aepro;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Custom_Taxonomy extends Widget_Base{
    public function get_name() {
        return 'ae-taxonomy';
    }

    public function get_title() {
        return __( 'AE - Taxonomy', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-text-align-left';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_custom_help_url() {
        $helper = new Helper();
        return $helper->get_help_url_prefix() . $this->get_name();
    }

    protected function _register_controls() {
        $helper = new Helper();
        $helper->ae_get_custom_taxonomies();
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'ae_taxonomy',
            [
                'label'         => __('Select Taxonomy','ae-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => $helper->ae_get_custom_taxonomies(),
                'default'       => ''
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __( 'H1', 'ae-pro' ),
                    'h2' => __( 'H2', 'ae-pro' ),
                    'h3' => __( 'H3', 'ae-pro' ),
                    'h4' => __( 'H4', 'ae-pro' ),
                    'h5' => __( 'H5', 'ae-pro' ),
                    'h6' => __( 'H6', 'ae-pro' ),
                    'div' => __( 'div', 'ae-pro' ),
                    'span' => __( 'span', 'ae-pro' ),
                    'p' => __( 'p', 'ae-pro' ),
                ],
                'default' => 'span'
            ]
        );

        $this->add_control(
            'tax_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => ''
            ]
        );

        $this->add_control(
            'tax_label',
            [
                'label' => __( 'Taxonomy Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'tax_vertical',
            [
                'label' => __( 'Vertical List', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'ae-pro' ),
                'label_off' => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => __('Alignment','ae-pro'),
                'type'      => Controls_Manager::CHOOSE,
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tax_separator',
            [
                'label' => __( 'Separator', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter separator', 'ae-pro' ),
                'default' => __( ', ', 'ae-pro' ),
                'condition' => [
                    'tax_vertical' => '',
                ],
            ]
        );

        $this->add_control(
            'disable_link',
            [
                'label' => __( 'Disable Link', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'link_on' => __( 'Yes', 'ae-pro' ),
                'link_off' => __( 'No    ', 'ae-pro' ),
                'return_value' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item' => 'color: {{VALUE}};'
                ],

            ]
        );

        $this->add_control(
            'text_hover_color',
            [
                'label' => __( 'Text Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-custom-tax *'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tax_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-custom-tax .ae-term-item',
            ]
        );

        $this->add_control(
            'tax_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tax_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tax_hover_bg_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tax_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tax_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax .ae-term-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tax_settings',
            [
                'label' => __( 'Label Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'tax_label!' => '',
                ]
            ]
        );


        $this->add_control(
            'tax_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tax_label!' => '',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tax_label_typography',
                'label' => __( 'Label Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-custom-tax-label',
                'condition' => [
                    'tax_label!' => '',
                ]
            ]
        );

        $this->add_control(
            'tax_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-custom-tax-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tax_label!' => '',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_settings',
            [
                'label' => __( 'Icon Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Icon Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_size',
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
                    '{{WRAPPER}} .icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tax_icon!' => '',
                ]
            ]
        );

        $this->end_controls_section();



    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();
        $post_id = $post_data->ID;

        $post_terms = wp_get_post_terms($post_id,$settings['ae_taxonomy']);

        $this->add_render_attribute('post-tax-class-wrapper', 'class', 'ae-custom-tax-wrapper' );
        $this->add_render_attribute('post-tax-class', 'class', 'ae-custom-tax' );
        $this->add_render_attribute('post-tax-label-class', 'class', 'ae-custom-tax-label' );
        $this->add_render_attribute('post-tax-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-tax-icon-class','class','ae-element-post-tax-icon');
        $this->add_render_attribute('post-tax-icon','class',$settings['tax_icon']);

        if(empty($settings['tax_separator'])){
            $settings['tax_separator'] = '';
        }

        if($settings['tax_vertical'] == 'yes'){
            $this->add_render_attribute('post-tax-class', 'class', 'ae-tax-vertical' );
            $settings['tax_separator'] = '';
        }

        $terms = [];
        if(!is_wp_error($post_terms) && count($post_terms)){
            foreach($post_terms as $term){
                if($settings['disable_link'] == 'yes'){
                    $link_html = $term->name;
                }else{
                    $link_html = "<a href='".get_term_link($term)."' title='" . $term->name . "'>".$term->name."</a>";
                }

                $terms[] = sprintf( '<%1$s class="ae-term-item">%2$s</%1$s>', $settings['html_tag'], $link_html );
            }

            ?>
            <div <?php echo $this->get_render_attribute_string('post-tax-class-wrapper'); ?>>
                <?php if(!empty($settings['tax_icon'])){ ?>
                    <span <?php echo $this->get_render_attribute_string( 'post-tax-icon-class' ); ?>>
                        <i <?php echo $this->get_render_attribute_string( 'post-tax-icon' ); ?>></i>
                    </span>
                <?php } ?>

                <?php if(!empty($settings['tax_label'])){
                    ?>
                    <span <?php echo $this->get_render_attribute_string('post-tax-label-class');?>>
                       <?php echo $settings['tax_label'];?>
                    </span>
                    <?php
                } ?>

                <div <?php echo $this->get_render_attribute_string('post-tax-class'); ?>>
                    <?php echo implode($settings['tax_separator'],$terms);?>
                </div>

            </div>
            <?php
        }



    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Custom_Taxonomy() );