<?php

namespace Aepro;

use Elementor;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use WP_Query;

class Aepro_Post_Content extends Widget_Base {
	public function get_name() {
		return 'ae-post-content';
	}

	public function get_title() {
		return __( 'AE - Post Content', 'ae-pro' );
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
		$this->start_controls_section(
			'section_layout_settings',
			[
				'label' => __( 'Layout Settings', 'ae-pro' )
			]
		);
		$this->add_control(
			'content_type',
			[
				'label'   => __( 'Content', 'ae-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'post_content' => __( 'Post Content', 'ae-pro' ),
					'term_content' => __( 'Term Description', 'ae-pro' )
				],
				'default' => 'post_content',
			]
		);
		$this->add_control(
			'desc_on_first_page',
			[
				'label'        => __( 'Show only on first page', 'ae-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'ae-pro' ),
				'label_off'    => __( 'No', 'ae-pro' ),
				'return_value' => 'yes',
				'condition'    => [
					'content_type' => 'term_content',
				]
			]
		);
		$this->add_control(
			'show_excerpt',
			[
				'label'     => __( 'Show Excerpt', 'ae-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'1' => [
						'title' => __( 'Yes', 'ae-pro' ),
						'icon'  => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'ae-pro' ),
						'icon'  => 'fa fa-ban',
					]
				],
				'default'   => '0',
				'condition' => [
					'content_type' => 'post_content',
				]
			]
		);

		$this->add_control(
			'excerpt_size',
			[
				'label'     => __( 'Excerpt Size', 'ae-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '10',
				'condition' => [
					'show_excerpt' => '1',
				]
			]
		);
		$this->add_control(
			'enable_the_content_hooks',
			[
				'label'        => __( 'Enable "the_content" hooks', 'ae-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'ae-pro' ),
				'label_off'    => __( 'No', 'ae-pro' ),
				'return_value' => 'yes',
				'condition'    => [
					'content_type' => 'post_content',
				]
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_unfold_layout',
            [
                'label' => __( 'Unfold', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'enable_unfold',
            [
                'label'        => __( 'Enable Unfold', 'ae-pro' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'ae-pro' ),
                'label_off'    => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'unfold_text',
            [
                'label'     => __( 'Show More Text', 'ae-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Show More',
                'condition' => [
                    'enable_unfold' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'fold_text',
            [
                'label'     => __( 'Show Less Text', 'ae-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Show Less',
                'condition' => [
                    'enable_unfold' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'unfold_animation_speed',
            [
                'label' => __( 'Animation Speed', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 5000,
                        'step' => 100
                    ],
                ],
                'default' => [
                    'size' => 500,
                ],
                'condition' => [
                    'enable_unfold' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'auto_hide_unfold_button',
            [
                'label'        => __( 'Auto Hide Unfold Button', 'ae-pro' ),
                'description'  => __( 'When Content is less than Unfold height'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'ae-pro' ),
                'label_off'    => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'enable_unfold' => 'yes',
                ]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'Content', 'ae-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Content Color', 'ae-pro' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-element-post-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'     => __( 'Content Align', 'ae-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'ae-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ae-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'ae-pro' ),
						'icon'  => 'fa fa-align-right',
					]
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .ae-element-post-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Content Typography', 'ae-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ae-element-post-content',
			]
		);

        $text_columns = range( 1, 10 );
        $text_columns = array_combine( $text_columns, $text_columns );
        $text_columns[''] = __( 'Default', 'ae-pro' );

        $this->add_responsive_control(
            'text_columns',
            [
                'label' => __( 'Columns', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-content' => 'columns: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Columns Gap', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-content' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'text_columns!' => '',
                ]
            ]
        );

        $helper->column_rule_controls($this,[
            'name' => 'content_rule_style',
            'label' => __('Content Rule','ae-pro'),
            'selector' => '{{WRAPPER}} .ae-element-post-content',
        ]);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_unfold_style',
            [
                'label' => __( 'Unfold', 'ae-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_unfold' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'unfold_max_height',
            [
                'label' => __( 'Max Height', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ],
                ],
                'default' => [
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold-yes.ae-element-post-content' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'unfold_color',
            [
                'label'     => __( 'Background Color', 'ae-pro' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold' => 'background-image: linear-gradient(to bottom, transparent, {{VALUE}});',
                ]
            ]
        );

        $this->add_control(
            'unfold_button_settings_heading',
            [
                'label' => __('Button', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->start_controls_tabs( 'tabs_button_styles' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'unfold_button_color',
            [
                'label'     => __( 'Color', 'ae-pro' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold-link' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'unfold_button_bg_color',
            [
                'label'     => __( 'Background Color', 'ae-pro' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold-link' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $helper->box_model_controls($this,[
            'name' => 'unfold_button_style',
            'label' => __('Button','ae-pro'),
            'border' => true,
            'border-radius' => true,
            'margin' => true,
            'padding' => true,
            'box-shadow' => true,
            'selector' => '{{WRAPPER}} .ae-post-content-unfold-link'
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'unfold_button_color_hover',
            [
                'label'     => __( 'Color', 'ae-pro' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold-link:hover' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'unfold_button_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'ae-pro' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-content-unfold-link:hover' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $helper->box_model_controls($this,[
            'name' => 'unfold_button_style_hover',
            'label' => __('Button','ae-pro'),
            'border' => false,
            'border-radius' => true,
            'margin' => false,
            'padding' => false,
            'box-shadow' => true,
            'selector' => '{{WRAPPER}} .ae-post-content-unfold-link:hover'
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
        $settings             = $this->get_settings();
	    $this->add_render_attribute( 'post-content-class', 'class', 'ae-element-post-content' );
	    if($settings['enable_unfold'] == 'yes'){
            $this->add_render_attribute( 'post-content-class', 'class', 'ae-post-content-unfold-' . $settings['enable_unfold']);
            $this->add_render_attribute( 'post-content-unfold-class', 'class', ['ae-post-content-unfold', 'fold'] );
            $this->add_render_attribute( 'post-content-unfold-class', 'data-unfold-max-height', $settings['unfold_max_height']['size'] );
            $this->add_render_attribute( 'post-content-unfold-class', 'data-unfold-text', $settings['unfold_text'] );
            $this->add_render_attribute( 'post-content-unfold-class', 'data-fold-text', $settings['fold_text'] );
            $this->add_render_attribute( 'post-content-unfold-class', 'data-animation-speed', $settings['unfold_animation_speed']['size'] );
            $this->add_render_attribute( 'post-content-unfold-class', 'data-auto-hide-unfold', $settings['auto_hide_unfold_button'] );
        }
		$content_type         = $settings['content_type'];
		$helper               = new Helper();
		$content              = "";

		switch ( $content_type ) {
			case "post_content":    $post_data = $helper->get_demo_post_data();
                                    $post_excerpt = wpautop( $post_data->post_excerpt );
                                    $post_content = wpautop( $post_data->post_content );
                                    if ( $post_data->post_type == 'elementor_library' ) {
                                        return false;
                                    }

				                   if ( $settings['show_excerpt'] ) { ?>

                                            <?php
                                            if ( $post_excerpt != '' ) {
                                                $post_excerpt = strip_shortcodes( $post_excerpt );
                                                $content = wp_trim_words( $post_excerpt, $settings['excerpt_size'], '...' );
                                            } else {
                                                $post_content = strip_shortcodes( $post_content );
                                                $content = wp_trim_words( $post_content, $settings['excerpt_size'], '...' );
                                            }
                                            ?>

                                    <?php } else { ?>
                                            <?php
                                            if ( $settings['enable_the_content_hooks'] == 'yes' ) {
                                                $_post = get_post( $post_data->ID );
                                                setup_postdata( $_post );
                                                the_content();
                                                wp_reset_postdata();
                                            } else {
                                                if ( Plugin::$instance->db->is_built_with_elementor( $post_data->ID ) ) {
                                                    echo Elementor\Plugin::instance()->frontend->get_builder_content( $post_data->ID, true );
                                                } else {
                                                    $content = do_shortcode( $post_content );
                                                    if ( isset( $GLOBALS['wp_embed'] ) ) {
                                                        $content = $GLOBALS['wp_embed']->autoembed( $content );
                                                    }
                                                    //echo $content;
                                                }
                                            }
                                            ?>
                                    <?php }
                                    break;
            case "term_content":    if ( $settings['desc_on_first_page'] == 'yes' ) {
                                        $desc_on_first_page = true;
                                    } else {
                                        $desc_on_first_page = false;
                                    }
                                    if ( ( $desc_on_first_page && ! is_paged() ) || ! $desc_on_first_page ) { ?>
                                            <?php
                                            if ( Plugin::instance()->editor->is_edit_mode() ) {
                                                $preview_term = $helper->get_preview_term_data();
                                                if ( isset($preview_term['prev_term_id']) ) {
                                                    $content = Post_Helper::instance()->get_aepro_the_archive_description( $preview_term['prev_term_id'], $preview_term['taxonomy'] );
                                                } else {
                                                    $content = 'This is term description.';
                                                }

                                            } else {
                                                $content = Post_Helper::instance()->get_aepro_the_archive_description();
                                            }
                                            //echo $content;
                                            ?>
                                    <?php }
                                    break;
			default:                $content = "Demo";
			                        break;

		}
		?>
        <div <?php echo $this->get_render_attribute_string( 'post-content-class' ); ?>>
            <?php if($settings['enable_unfold'] == 'yes'){ ?>
                <div class="ae-element-post-content-inner">
            <?php } ?>
		        <?php echo $content; ?>
            <?php if($settings['enable_unfold'] == 'yes'){ ?>
                </div>
                <p <?php echo $this->get_render_attribute_string( 'post-content-unfold-class' ); ?>><span class="ae-post-content-unfold-link" href="#"><?php echo $settings['unfold_text']; ?></span></p>
            <?php } ?>
        </div>
        <?php
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Content() );