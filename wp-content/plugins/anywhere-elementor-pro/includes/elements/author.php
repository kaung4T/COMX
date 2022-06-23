<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

class Aepro_Author extends Widget_Base{
    public function get_name() {
        return 'ae-author';
    }

    public function get_title() {
        return __( 'AE - Author', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-person';
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
                'label' => __('General','ae-pro')
            ]
        );

        $this->add_control(
            'render_data',
            [
                'label' => __('Data', 'ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'avatar'          => __('Avatar', 'ae-pro'),
                    'first_name'      => __('First Name', 'ae-pro'),
                    'last_name'       => __('Last Name', 'ae-pro'),
                    'first_last'      => __('Full Name', 'ae-pro'),
                    'nickname'        => __('Nick Name', 'ae-pro'),
                    'description'     => __('Biography', 'ae-pro')
                ],
                'default' => 'avatar'
            ]
        );

        // some common controls
        $this->add_control(
            'add_link',
            [
                'label' => __('Show Link', 'ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'none'  => __('No Link', 'ae-pro'),
                    'author' => __('Author Link', 'ae-pro'),
                    'post'  => __('Post Link', 'ae-pro')
                ],
                'default'   => 'author',
                'condition' => [
                    'render_data!' => 'description'
                ]
            ]
        );
        $this->add_control(
            'author_tag',
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
                    'span' => __( 'span', 'ae-pro' )
                ],
                'default' => 'div',
                'condition' => [
                        'render_data!'  => ['avatar']
                ]
            ]
        );

        $this->register_avatar_controls();

        $this->register_author_meta_controls();



        $this->end_controls_section();

        $this->start_controls_section(
            'general_style_section',
            [
                'label' => __('Style','ae-pro'),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->register_avatar_style_controls();
        $this->render_author_meta_style_controls();

        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();
        $post_id = $post_data->ID;
        $author_id = $post_data->post_author;

        if($settings['render_data'] == 'avatar'){
            $this->render_avatar($author_id, $settings, $post_id);
        }else{
            $this->render_author_meta($author_id, $settings, $post_id);
        }

    }

    protected function render_avatar($author_id,$settings,$post_id){
        $author_name = get_the_author_meta('display_name', $author_id);
        $avt_size = $settings['avt_size']['size'];
        $avatar = get_avatar( $author_id, $avt_size );
        $author_link = get_author_posts_url($author_id);
        $this->add_render_attribute('avatar-class','class','ae-element-avatar');
        $this->add_render_attribute('avatar-class','class','overlay-'.$settings['show_overlay']);

        $link = '';
        $settings['add_link'];
        if($settings['add_link'] == 'author'){
            $link = get_author_posts_url($author_id);
        }elseif($settings['add_link'] == 'post'){
            $link = get_permalink($post_id);
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('avatar-class');?>>

            <?php if(!empty($link)){ ?>
                <a href="<?php echo $link; ?>" title="<?php echo $author_name;?>">
            <?php } ?>

                <?php  echo $avatar; ?>
                <?php if($settings['show_overlay'] == 'hover' || $settings['show_overlay'] == 'always'){?>
                    <div class="ae-avatar-overlay"></div>
                <?php } ?>

            <?php if(!empty($link)){ ?>
                </a>
            <?php } ?>
        </div>
        <?php
    }

    protected function render_author_meta($author_id,$settings,$post_id){
        // get author string
        if($settings['render_data'] == 'first_last'){
            $name['first'] = get_the_author_meta('first_name', $author_id);
            $name['last'] = get_the_author_meta('last_name', $author_id);
            $author_text = implode(' ', $name);
        }else{
            $author_text = get_the_author_meta($settings['render_data'], $author_id);
        }

        if($settings['add_link'] == 'author'){
            $link = get_author_posts_url($author_id);
        }elseif($settings['add_link'] == 'post'){
            $link = get_permalink($post_id);
        }

        if($settings['render_data'] == 'description'){
            $author_text = wpautop($author_text);
        }

        $title = '';

        $this->add_render_attribute('meta-wrapper','class', 'ae-author-meta-wrapper');

        ?>
        <div <?php echo $this->get_render_attribute_string('meta-wrapper'); ?>>
            <?php if($settings['add_link'] != 'none' && $settings['render_data'] != 'description'){ ?>
                <a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
            <?php } ?>

            <?php if($settings['icon'] != ''){
                $author_text = '<span class="icon-wrapper"><i class="fa ' . $settings['icon'] . '"></i></span> ' . $author_text;
            } ?>

            <?php echo sprintf('<%1$s itemprop="name" %2$s>%3$s</%1$s>',$settings['author_tag'],$this->get_render_attribute_string('post-author-class'),$author_text); ?>

            <?php if($settings['add_link'] != 'none' && $settings['render_data'] != 'description'){ ?>
                </a>
            <?php } ?>
        </div>
        <?php
    }

    protected function register_avatar_controls(){
        $this->add_control(
            'avt_size',
            [
                'label' => __( 'Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 96,
                ],
                'range' => [
                    'px' => [
                        'min' => 32,
                        'max' => 512,
                    ],
                ],
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );

        $this->add_responsive_control(
            'avatar_align',
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ]
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
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );
    }

    protected function register_avatar_style_controls(){
        $this->add_control(
            'heading_avatar_border',
            [
                'label' => __( 'Border', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'label' => __( 'avatar Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-avatar img',
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );

        $this->add_control(
            'avatar_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-avatar a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-avatar-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );

        $this->add_control(
            'overlay_style',
            [
                'label' => __( 'Overlay', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_overlay!' => 'never',
                ],
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'ae-pro' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-avatar-overlay',
                'condition' => [
                    'render_data' => 'avatar'
                ]
            ]
        );
    }

    protected function register_author_meta_controls(){

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-user',
                'condition' => [
                    'render_data!' => 'avatar',
                ]
            ]
        );
    }

    protected function render_author_meta_style_controls(){
        $this->add_control(
            'color',
            [
                'label'   => __('Color','ae-pro'),
                'type'    => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-author-meta-wrapper a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-author-meta-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-author-meta-wrapper icon-wrapper' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label'   => __('Hover Color','ae-pro'),
                'type'    => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-author-meta-wrapper a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-author-meta-wrapper:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-author-meta-wrapper:hover icon-wrapper' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-author-meta-wrapper ',
                'condition' => [
                    'render_data!' => ['avatar'],
                ],
            ]
        );
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Author() );