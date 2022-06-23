<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

class Aepro_Post_Image extends Widget_Base{
    public function get_name() {
        return 'ae-post-image';
    }

    public function get_title() {
        return __( 'AE - Post Image', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-image-box';
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
            'section_layout_settings',
            [
                'label' => __( 'Layout Settings', 'ae-pro' )
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => [ 'custom' ],
            ]
        );

        /*$image_alert_style = 'style="font-style: italic; font-size: 12px; color: #999;"';

        $this->add_control(
                'image_size_alert',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'ae_image_alert',
                    'raw' => __( '<i '. $image_alert_style . '>Note: If Size - Medium Large.</i>', 'ae-pro' ),
                    'separator' => 'none',
                    'condition' => [
                        'image_size' => 'medium_large',
                    ],
                ]
        );*/

        $this->add_responsive_control(
            'image_align',
            [
                'label' => __( 'Image Alignment', 'ae-pro' ),
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'links_to',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Link to', 'ae-pro'),
                'options' => [
                    ''  => __('None', 'ae-pro'),
                    'post' => __('Post', 'ae-pro'),
                    'media' => __('Full Image', 'ae-pro')
                ],
                'default' => 'post'
            ]
        );

        $this->add_control(
            'enable_image_ratio',
            [
                'label' => __( 'Enable Image Ratio', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'ae-pro' ),
                'label_off' => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => __('Image Ratio', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.66,
                ],
                'tablet_default' => [
                    'size' => '',
                ],
                'mobile_default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_thumb_wrapper.ae_image_ratio_yes .ae-post-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
                'condition' => [
                    'enable_image_ratio' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section-overlay',
            [
                'label' => __('Overlay','ae-pro')
            ]
        );

        $this->add_control(
            'show_overlay',
            [
                'label' => __('Show Overlay','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'hover' => __('On Hover','ae-pro'),
                    'always' => __('Always','ae-pro'),
                    'never' => __('Never','ae-pro'),
                ],
                'default'   => 'never',
                'prefix_class'  => 'overlay-'
            ]
        );




        $this->end_controls_section();

        $this->start_controls_section(
            'section-general-style',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'heading_image_border',
            [
                'label' => __( 'Image Border', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __( 'Image Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-image .ae_thumb_wrapper img',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-image .ae_thumb_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-image .ae_thumb_wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-image .ae_thumb_wrapper .ae-post-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => __( 'Box Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-image .ae_thumb_wrapper img',
            ]
        );

        $this->add_control(
            'overlay_style',
            [
                'label' => __( 'Overlay', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_overlay!' => 'never'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'ae-pro' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-post-overlay',
                'condition' => [
                        'show_overlay!' => 'never'
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
        //echo 'Image - '.$post_id;
        $post_image_size = $settings['image_size'];
        $post_image = get_the_post_thumbnail( $post_id, $post_image_size );
        $post_title = $post_data->post_title;

        if(!isset($settings['links_to']) || $settings['links_to'] == 'post'){
            $post_link = get_permalink($post_id );
        }elseif($settings['links_to'] == 'media'){
            $media_link = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'full');
            $post_link = $media_link[0];

        }

        $this->add_render_attribute('post-image-class','class','ae-element-post-image');
        $this->add_render_attribute('post-image-class','class','ae-element-post-image');
        $this->add_render_attribute('thumb_wrapper', 'class', 'ae_thumb_wrapper');
        if($settings['enable_image_ratio'] == 'yes') {
            $this->add_render_attribute('thumb_wrapper', 'class', 'ae_image_ratio_yes');
        }
        ?>
        <?php if($post_image != "") { ?>
            <div <?php echo $this->get_render_attribute_string('post-image-class'); ?>>

                <div <?php echo $this->get_render_attribute_string('thumb_wrapper'); ?>>
                    <?php if (!isset($settings['links_to']) || $settings['links_to'] != ''){ ?>
                    <a href="<?php echo $post_link; ?>" title="<?php echo $post_title; ?>">
                        <?php }
                        if($settings['enable_image_ratio']){ ?>
                        <div class="ae-post-image">
                        <?php } ?>
                        <?php echo $post_image; ?>
                        <?php if($settings['enable_image_ratio']){ ?>
                        </div>
                        <?php } ?>
                        <div class="ae-post-overlay"></div>
                        <?php if (!isset($settings['links_to']) || $settings['links_to'] != ''){ ?>
                    </a>
                <?php } ?>
                </div>
            </div>
            <?php
        }
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Image() );