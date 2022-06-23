<?php

namespace Aepro;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;


class Aepro_Post_Comments extends Widget_Base{
    public function get_name() {
        return 'ae-post-comments';
    }

    public function get_title() {
        return __( 'AE - Post Comments <sup>Beta</sup>', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $helper = new Helper();
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => __('Style','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'theme' => __('Theme Default','ae-pro')
                ],
                'default' => 'theme'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
			[
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_heading',
            [
                'label' => __('Button Styles','ae-pro'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __( 'Content Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .submit',
            ]
        );

        $this->start_controls_tabs('button_style');
            $this->start_controls_tab('button_normal',[ 'label' => __('Normal','ae-pro') ]);

                $this->add_control('button_text_color',[
                    'label' => __('Color', 'ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .submit' => 'color:{{VALUE}};'
                    ]
                ]);

                $this->add_control('button_color',[
                    'label' => __('Background Color', 'ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .submit' => 'background:{{VALUE}};'
                    ]
                ]);

                $helper->box_model_controls($this,[
                    'name' => 'button',
                    'label' => __('Button','ae-pro'),
                    'border' => true,
                    'border-radius' => true,
                    'margin' => false,
                    'padding' => true,
                    'box-shadow' => true,
                    'selector' => '{{WRAPPER}} .submit'
                ]);

            $this->end_controls_tab();

            $this->start_controls_tab('button_hover',[ 'label' => __('Hover','ae-pro') ]);

                $this->add_control('button_text_color_hover',[
                    'label' => __('Color', 'ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .submit:hover' => 'color:{{VALUE}};'
                    ]
                ]);

                $this->add_control('button_color_hover',[
                    'label' => __('Background Color', 'ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .submit:hover' => 'background:{{VALUE}};'
                    ]
                ]);

                $helper->box_model_controls($this,[
                    'name' => 'button_hover',
                    'label' => __('Button','ae-pro'),
                    'border' => true,
                    'border-radius' => true,
                    'margin' => false,
                    'padding' => false,
                    'box-shadow' => true,
                    'selector' => '{{WRAPPER}} .submit:hover'
                ]);
            $this->end_controls_tab();
        $this->end_controls_tabs();






        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();


        global $post;
        $post = $post_data;
        setup_postdata($post);
            comments_template();
        wp_reset_postdata();
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Comments() );