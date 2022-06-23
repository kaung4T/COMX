<?php

namespace Aepro\Ae_ACF\Skins;

use Aepro\Aepro;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Widget_Base;
use Aepro\Classes\AcfMaster;
use Elementor\Group_Control_Typography;
use function get_permalink;
use Elementor\Group_Control_Image_Size;
use function wp_get_attachment_image_src;
use function wp_get_attachment_url;


class Skin_Image extends Skin_URL {

	public function get_id() {
		return 'image';
	}

	public function get_title() {
		return __( 'Image', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-acf/general/after_section_end', [$this, 'register_style_controls']);
        //add_action('elementor/element/ae-acf/image_general-style/before_section_end', [$this, 'register_style_control_extend']);
	}

	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'exclude' => [ 'custom' ],
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
                    '{{WRAPPER}} .ae_acf_image_wrapper.ae_acf_image_ratio_yes .ae_acf_image_block' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
                'condition' => [
                    $this->get_control_id('enable_image_ratio') => 'yes',
                ]
            ]
        );

        $this->add_control(
            'enable_link',
            [
                'label' => __('Enable Link', 'ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'no' => __( 'No', 'ae-pro' ),
                'yes' => __( 'Yes', 'ae-pro' ),
                'return_value' => 'yes',
                'default' => __('no', 'ae-pro'),
            ]
        );

		$this->add_control(
			'url_type',
			[
				'label' => __('Links To', 'ae-pro'),
				'type'  => Controls_Manager::SELECT,
				'options' => [
				    'media' => __('Full Image', 'ae-pro'),
					'static'        => __('Static URL', 'ae-pro'),
					'post'          => __('Post URL', 'ae-pro' ),
					'dynamic_url'  => __('Custom Field', 'ae-pro'),
				],
				'default'   => 'static',
                'condition' => [
                    $this->get_control_id('enable_link') => 'yes'
                ]
			]
		);

		$this->add_control(
			'static_url',
			[
				'label' => __('Static URL', 'ae-pro'),
				'type'  => Controls_Manager::TEXT,
				'default' => __('http://', 'ae-pro'),
				'condition'    => [
					$this->get_control_id('url_type') => 'static',
                    $this->get_control_id('enable_link') => 'yes'

				]
			]
		);

		$this->add_control(
			'custom_field_url',
			[
				'label' => __('Custom Field', 'ae-pro'),
				'type'  => Controls_Manager::TEXT,
				'condition'    => [
					$this->get_control_id('url_type') => 'dynamic_url',
                    $this->get_control_id('enable_link') => 'yes'
				]
			]
		);

        $this->add_control(
            'open_lightbox',
            [
                'label' => __( 'Lightbox', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'default' => __( 'Default', 'ae-pro' ),
                    'yes' => __( 'Yes', 'ae-pro' ),
                    'no' => __( 'No', 'ae-pro' ),
                ],
                'condition' => [
                    $this->get_control_id('url_type') => 'media',
                    $this->get_control_id('enable_link') => 'yes',
                ],
            ]
        );

		$this->add_control(
			'new_tab',
			[
				'label' => __('Open in new tab', 'ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'label_on' => __( 'Yes', 'ae-pro' ),
				'return_value' => 1,
				'default' => __('label_off', 'ae-pro'),
                'condition'    => [
                    $this->get_control_id('enable_link') => 'yes',
                ]
			]
		);

		$this->add_control(
			'nofollow',
			[
				'label' => __('Add nofollow', 'ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'label_on' => __( 'Yes', 'ae-pro' ),
				'return_value' => 1,
				'default' => __('label_off', 'ae-pro'),
                'condition'    => [
                    $this->get_control_id('enable_link') => 'yes'
                ]
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Align', 'ae-pro'),
				'type'  => Controls_Manager::CHOOSE,
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
					//'{{WRAPPER}} a' => 'display: inline-block',
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
                'prefix_class'  => 'overlay-',
                'selectors' => [
                    '{{WRAPPER}}.overlay-always .ae-acf-overlay-block' => 'display: block;',
                    '{{WRAPPER}}.overlay-hover .ae_acf_image_wrapper:hover .ae-acf-overlay-block' => 'display: block;',
                ]
            ]
        );

        $this->add_control(
            'overlay_icon',
            [
                'label' => __( 'Overlay Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-link',
            ]
        );




	}

	public function register_style_image_controls()
    {
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'ae-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Max Width', 'ae-pro') . ' (%)',
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs('image_effects');

        $this->start_controls_tab('normal',
            [
                'label' => __('Normal', 'ae-pro'),
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => __('Opacity', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .ae_acf_image_wrapper img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('hover',
            [
                'label' => __('Hover', 'ae-pro'),
            ]
        );

        $this->add_control(
            'opacity_hover',
            [
                'label' => __('Opacity', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .ae_acf_image_wrapper:hover img',
            ]
        );

        $this->add_control(
            'background_hover_transition',
            [
                'label' => __('Transition Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'ae-pro'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ae_acf_image_wrapper img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'ae-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ae_acf_image_wrapper img',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' 	=> __( 'Overlay Color', 'ae-pro' ),
                'type' 		=> Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'ae-por' ),
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
                    '{{WRAPPER}} .ae_acf_image_wrapper .ae-acf-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

    }

    public function register_style_icon_controls(){
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                //'condition' => [
                //    $this->get_control_id('show_icon' ) => 'yes',
                //],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-overlay-block i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => __( 'Hover', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper:hover .ae-acf-overlay-block i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    //'{{WRAPPER}} .ae-acf-overlay-block' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-acf-overlay-block  i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function register_style_overlay_controls(){
        $this->start_controls_section(
            'section_overlay_style',
            [
                'label' => __( 'Overlay', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'overlay_style' );

        $this->start_controls_tab( 'overlay_style_default', [ 'label' => __( 'Default', 'ae-pro' ) ] );

        $this->add_control(
            'overlay_color',
            [
                'label' 	=> __( 'Background Color', 'ae-pro' ),
                'type' 		=> Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab( 'overlay_style_hover', [ 'label' => __( 'Hover', 'ae-pro' ) ] );

        $this->add_control(
            'overlay_color_hover',
            [
                'label' 	=> __( 'Background Color', 'ae-pro' ),
                'type' 		=> Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae_acf_image_wrapper:hover .ae-acf-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function register_style_control_extend(){
        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'ae-por' ),
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
                    '{{WRAPPER}} .ae_acf_image_wrapper img' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
    }

	public function render() {

		$settings = $this->parent->get_settings();
		$link_text = '';

		$field_args  =  [
			'field_name'    => $settings['field_name'],
			'field_type'    => $settings['field_type'],
			'is_sub_field'    => $settings['is_sub_field'],

		];

		if($settings['is_sub_field'] == 'repeater'){
			$field_args['parent_field'] = $settings['parent_field'];
		}

		$image_id = AcfMaster::instance()->get_field_value( $field_args );

		//$file_data = $this->get_file_data($file);

        $image_size = $this->get_instance_value('image_size_size');

        $image_url = wp_get_attachment_url($image_id);

        $image_arr = wp_get_attachment_image_src($image_id, $image_size);

        $image = $image_arr[0];

		if($this->get_instance_value('enable_link') == 'yes') {
            // Get Link
            $url_type = $this->get_instance_value('url_type');
            $url = '';

            switch ($url_type) {

                case 'static' :
                    $url = $this->get_instance_value('static_url');
                    break;

                case 'post'   :
                    $curr_post = Aepro::$_helper->get_demo_post_data();
                    if (isset($curr_post) && isset($curr_post->ID)) {
                        $url = get_permalink($curr_post->ID);
                    }
                    break;

                case 'dynamic_url' :
                    $custom_field = $this->get_instance_value('custom_field_url');

                    if ($custom_field != '') {

                        $field_args['field_name'] = $custom_field;
                        $url = AcfMaster::instance()->get_field_value($field_args);
                    }
                    break;

            }

            $this->parent->add_render_attribute('anchor', 'href', $image_url);

            $this->parent->add_render_attribute( 'anchor', ['data-elementor-open-lightbox' => $this->get_instance_value('open_lightbox') ] );

            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                $this->parent->add_render_attribute( 'anchor', ['class' => 'elementor-clickable',] );
            }

            $new_tab = $this->get_instance_value('new_tab');
            if ($new_tab == 1) {
                $this->parent->add_render_attribute('anchor', 'target', '_blank');
            }

            $no_follow = $this->get_instance_value('nofollow');
            if ($no_follow == 1) {
                $this->parent->add_render_attribute('anchor', 'rel', 'nofollow');
            }

        }
        $this->parent->add_render_attribute('image_wrapper', 'class', 'ae_acf_image_wrapper');
        if($this->get_instance_value('enable_image_ratio') == 'yes') {
            $this->parent->add_render_attribute('image_wrapper', 'class', 'ae_acf_image_ratio_yes');
        }
        ?>
        <div <?php echo $this->parent->get_render_attribute_string('image_wrapper'); ?>>
        <?php if($this->get_instance_value('enable_link') == 'yes'){
		?>
		    <a <?php echo $this->parent->get_render_attribute_string('anchor'); ?>>
        <?php }
        if($this->get_instance_value('enable_image_ratio') == 'yes'){ ?>
            <div class="ae_acf_image_block">
        <?php } ?>
                <img src="<?php echo $image; ?>" />
        <?php if($this->get_instance_value('enable_image_ratio') == 'yes'){ ?>
            </div>
        <?php } ?>
                <div class="ae-acf-overlay-block">
                    <div class="ae-acf-overlay"></div>
                    <i class="<?php echo $this->get_instance_value('overlay_icon'); ?>"></i>
                </div>
        <?php if($this->get_instance_value('enable_link') == 'yes') { ?>
            </a>
        <?php
        } ?>
        </div>
    <?php
	}

	function register_style_controls(){

		//parent::register_style_controls();

		$this->register_style_image_controls();

		$this->register_style_icon_controls();

		//$this->register_style_overlay_controls();

	}

	function get_file_data($file){

	    $file_data = false;

	    // Get attachemnt info
		if(is_numeric($file)){
			$file_data = acf_get_attachment($file);
		}

		return $file_data;
    }




}
