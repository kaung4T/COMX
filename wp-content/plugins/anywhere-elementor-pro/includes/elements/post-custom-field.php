<?php
namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Aepro_Custom_Field extends Widget_Base {

	public function get_name() {
		return 'ae-custom-field';
	}

	public function get_title() {
		return __( 'AE - Custom Field', 'ae-pro' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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
			'section_title',
			[
				'label' => __( 'Custom Field', 'ae-pro' ),
			]
		);

		$this->add_control(
				'custom-field',
				[
						'label' => __( 'Name', 'ae-pro' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => __( 'Enter your custom field name', 'ae-pro' ),
						'default' => __( 'my_key', 'ae-pro' ),
				]
		);

		$this->add_control(
			'cf_type',
			[
				'label' => __( 'Type', 'ae-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'text' => __( 'Default', 'ae-pro' ),
					'html' => __( 'Html', 'ae-pro' ),
					'link' => __( 'Link', 'ae-pro' ),
					'image' => __( 'Image', 'ae-pro' ),
					'video' => __( 'Video', 'ae-pro' ),
					'audio' => __( 'Audio', 'ae-pro' ),
					'oembed' => __( 'oEmbed', 'ae-pro' ),
                    'date' => __( 'Date', 'ae-pro')
				],
				'default' => 'text'
			]
		);

		$this->add_control(
		        'link_type',
                [
                    'label' => __('Link Type', 'ae-pro'),
                    'type'  => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __('Default', 'ae-pro'),
                        'email'   => __('Email', 'ae-pro'),
                        'tel'     => __('Telephone', 'ae-pro')
                    ],
                    'default'   => 'default',
                    'condition' => [
	                    'cf_type' => 'link',
                    ]
                ]
        );

		if(class_exists('acf')){
			$this->add_control(
					'acf_support',
					[
							'label'	=> __('ACF Formatting','ae-pro'),
							'type'	=> Controls_Manager::SWITCHER,
							'label_off' => __( 'No', 'ae-pro' ),
							'label_on' => __( 'Yes', 'ae-pro' ),
							'condition' => [
									'cf_type' => ['text','link', 'audio', 'date']
							],

					]
			);
		}

		$date_format = $helper->ae_get_date_format();
		$date_format['default'] =  'Default';

        $this->add_control(
            'date_format',
            [
                'label' => __( 'Date format', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => $date_format,
                'default' => 'F j, Y',
                'condition' => [
                    'acf_support' => '',
                    'cf_type' => 'date'
                ],
                'description' => '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"> Click here</a> for documentation on date and time formatting.'
            ]
        );

        $this->add_control(
            'date_custom_format',
            [
                'label' => __( 'Date Format', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Date Format', 'ae-pro' ),
                'default' => 'y:m:d',
                'condition' => [
                    'date_format' => 'custom'
                ]
            ]
        );


		$this->add_control(
			'cf_video_type',
			[
				'label' => __( 'Video Type', 'ae-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'youtube' => __( 'Youtube Video', 'ae-pro' ),
					'vimeo' => __( 'Vimeo Video', 'ae-pro' ),
				],
				'default' => 'youtube',
				'condition' => [
					'cf_type' => 'video',
				]
			]
		);

		$this->add_control(
				'aspect_ratio',
				[
						'label' => __( 'Aspect Ratio', 'ae-pro' ),
						'type' => Controls_Manager::SELECT,
					'frontend_available' => true,
						'options' => [
								'169' => '16:9',
								'43' => '4:3',
								'32' => '3:2',
						],
						'default' => '169',
						'condition' => [
							'cf_type' => 'video',
						]
				]
		);

		$this->youtube_video_options();
		$this->vimeo_video_options();

        $this->add_control(
            'link_text_type',
            [
                'label' => __('Links To','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'static' => __('Static','ae-pro'),
                    'custom_field' => __('Custom Field','ae-pro'),
                    'post' => __('Post', 'ae-pro')
                ],
                'default'   => 'static',
                'condition' => [
                    'cf_type' => 'link',
                ]
            ]
        );

		$this->add_control(
			'cf_link_text',
			[
				'label' => __( 'Static Text', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Link Text', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					'cf_type' => 'link',
                    'link_text_type' => 'static'
				]
			]
		);
        $this->add_control(
            'cf_link_dynamic_text',
            [
                'label' => __( 'Enter Field Key', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Field Key', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'cf_type' => 'link',
                    'link_text_type' => 'custom_field'
                ],
                'description' => __( 'Data from this custom field will be used for anchor text', 'ae-pro')
            ]
        );

		$this->add_control(
				'cf_link_target',
				[
					'label' => __('Open in new tab','ae-pro'),
					'type'  => Controls_Manager::SWITCHER,
					'label_off' => __( 'No', 'ae-pro' ),
					'label_on' => __( 'Yes', 'ae-pro' ),
					'condition' => [
							'cf_type' => ['link', 'image'],
					]
				]
		);

        $this->add_control(
            'cf_link_download',
            [
                'label' => __('Download on Click', 'ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'label_off' => __( 'No', 'ae-pro' ),
                'label_on' => __( 'Yes', 'ae-pro' ),
                'return_value' => 1,
                'default' => __('label_off', 'ae-pro'),
                'condition' => [
                    'cf_type' => ['link'],
                ]
            ]
        );

		$this->add_control(
			'cf_label',
			[
				'label' => __( 'Label', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Label', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					'cf_type' => ['text','link', 'date'],
				]
			]
		);

		$this->add_control(
			'cf_icon',
			[
				'label' => __( 'Icon', 'ae-pro' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => [
					'cf_type' => ['text','link', 'date'],
				]
			]
		);

		$this->add_control(
			'header_size',
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
				'default' => 'h3',
				'condition' => [
					'cf_type' => ['text', 'date'],
				]
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
				'condition'=> [
					'cf_type!'=>'video',
				]
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
                    'media' => __('Full Image', 'ae-pro'),
                    'static' => __( 'Static URL', 'ae-pro' ),
                    'custom_field' => __( 'Custom Field', 'ae-pro' ),
                ],
                'default' => '',
                'condition' => [
                    'cf_type' => 'image',
                ]
            ]
        );

		$this->add_control(
			'cf_link_image',
			[
				'label' => __( 'Static URL', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter URL', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					'cf_type' => 'image',
					'links_to' => 'static'
				]
			]
		);
		$this->add_control(
			'cf_link_dynamic_image',
			[
				'label' => __( 'Enter Field Key', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Field Key', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					'cf_type' => 'image',
					'links_to' => 'custom_field'
				],
				'description' => __( 'Data from this custom field will be used for image link', 'ae-pro')
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'label' => __( 'Image Size', 'ae-pro' ),
				'default' => 'large',
				'exclude' => [ 'custom' ],
				'condition' => [
					'cf_type' => 'image',
				]

			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_field_style',
			[
				'label' => __( 'Custom Field', 'ae-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'=> [
					'cf_type!'=>'video',
				]

			]
		);

		$this->add_control(
			'custom_field_color',
			[
				'label' => __( 'Color', 'ae-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
				    'type' => Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-element-custom-field' => 'color: {{VALUE}};',
				],
				'condition' => [
					'cf_type' => ['text','html','link', 'date'],
				],
			]
		);

		$this->add_control(
			'cf_hover_color',
			[
				'label' => __( 'Text Hover Color', 'ae-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ae-element-custom-field:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
						'cf_type' => ['text','link', 'date'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .ae-element-custom-field',
				'condition' => [
						'cf_type' => ['text','link','html', 'date'],
				],
			]
		);

		$this->add_control(
			'icon_settings',
			[
				'label' => __( 'Icon Settings', 'ae-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
						'cf_icon!' => '',
						'cf_type' => ['text','link', 'date'],
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
					'cf_icon!' => '',
					'cf_type' => ['text','link', 'date'],
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
					'cf_icon!' => '',
					'cf_type' => ['text','link', 'date'],
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
					'cf_icon!' => '',
					'cf_type' => ['text','link', 'date'],
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
					'cf_icon!' => '',
					'cf_type' => ['text','link', 'date'],
				]
			]
		);

		$this->add_control(
			'cf_label_settings',
			[
				'label' => __( 'Label Settings', 'ae-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
						'cf_label!' => '',
						'cf_type' => ['text','link', 'date'],
				]
			]
		);

		$this->add_control(
			'cf_label_color',
			[
				'label' => __( 'Label Color', 'ae-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-element-custom-field-label' => 'color: {{VALUE}};',
				],
				'condition' => [
						'cf_label!' => '',
						'cf_type' => ['text','link', 'date'],
				]

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cf_label_typography',
				'label' => __( 'Label Typography', 'ae-pro' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .ae-element-custom-field-label',
				'condition' => [
						'cf_label!' => '',
						'cf_type' => ['text','link','html', 'date'],
				]
			]
		);

		$this->add_control(
			'cf_spacing',
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
					'{{WRAPPER}} .ae-element-custom-field-label' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
						'cf_type' => ['text','link', 'date'],
				]
			]
		);

		$this->add_control(
				'cf_space',
				[
						'label' => __( 'Size (%)', 'ae-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
								'size' => 100,
								'unit' => '%',
						],
						'size_units' => [ '%' ],
						'range' => [
								'%' => [
										'min' => 1,
										'max' => 100,
								],
						],
						'selectors' => [
								'{{WRAPPER}} .ae-element-custom-field img' => 'max-width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
								'cf_type' => 'image',
						]
				]
		);


		$this->add_control(
				'cf_opacity',
				[
						'label' => __( 'Opacity (%)', 'ae-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
								'size' => 1,
						],
						'range' => [
								'px' => [
										'max' => 1,
										'min' => 0.10,
										'step' => 0.01,
								],
						],
						'selectors' => [
								'{{WRAPPER}} .ae-element-custom-field img' => 'opacity: {{SIZE}};',
						],
						'condition' => [
								'cf_type' => 'image',
						]
				]
		);

		$this->add_control(
			'cf_bg',
			[
				'label' => __( 'Background Color', 'ae-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ae-element-custom-field' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'cf_type!' => 'video',
				]
			]
		);

		$this->add_group_control(
				Group_Control_Border::get_type(),
				[
						'name' => 'border',
						'label' => __( 'Border', 'ae-pro' ),
						'selector' => '{{WRAPPER}} .elementor-widget-container > div:not(.cf-type-image) > .ae-element-custom-field, {{WRAPPER}} .cf-type-image img',
						'condition' => [
								'cf_type!' => 'video',
						]
				]
		);

		$this->add_control(
				'image_border_radius',
				[
						'label' => __( 'Border Radius', 'ae-pro' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
								'{{WRAPPER}} .ae-element-custom-field, {{WRAPPER}} .ae-element-custom-field img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'condition' => [
								'cf_type!' => 'video',
						]
				]
		);

		$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
						'name' => 'item_box_shadow',
						'label' => __( 'Item Shadow', 'ae-pro' ),
						'selector' => '{{WRAPPER}} .ae-element-custom-field img',
				]
		);


		$this->add_control(
			'cf_text_padding',
			[
				'label' => __( 'Padding', 'ae-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ae-element-custom-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cf_type!' => 'video',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings();
		if(!isset($settings['cf_type']) || $settings['cf_type'] == ''){
			$settings['cf_type'] = 'text';
		}

		$helper = new Helper();
		$post_data = $helper->get_demo_post_data();
		$post_id = $post_data->ID;
        $post_title = $post_data->post_title;


		$custom_field = $settings['custom-field'];

		if(class_exists('acf') && in_array($settings['cf_type'],['text','link','audio', 'date']) && $settings['acf_support'] == 'yes'){
			$custom_field_val = get_field($custom_field,$post_id);
		}else{
			$custom_field_val = get_post_meta($post_id,$custom_field,true);
		}

		$cf_link_dynamic_text = '';
		if($settings['cf_link_dynamic_text'] != '') {
			$cf_link_dynamic_text = get_post_meta( $post_id, $settings['cf_link_dynamic_text'], true );
		}

		$cf_link_dynamic_image = '';
        if($settings['cf_type'] == 'image' && $settings['links_to'] == 'custom_field' && $settings['cf_link_dynamic_image'] != ''){
	        $cf_link_dynamic_image = get_post_meta( $post_id, $settings['cf_link_dynamic_image'], true );
        }

		$repeater = $helper->is_repeater_block_layout();

		if($repeater['is_repeater']){
			if(isset($repeater['field'])){
				$repeater_field = get_field($repeater['field'], $post_id);
				$custom_field_val = $repeater_field[0][$custom_field];
				//print_r($repeater_field);
				if($settings['cf_type'] == 'link' && $settings['cf_link_dynamic_text'] != '') {
					$cf_link_dynamic_text = $repeater_field[0][ $settings['cf_link_dynamic_text'] ];
				}
				if($settings['cf_type'] == 'image' && $settings['links_to'] == 'custom_field' && $settings['cf_link_dynamic_image'] != ''){
					$cf_link_dynamic_image = $repeater_field[0][ $settings['cf_link_dynamic_image'] ];
				}
			}else {
				$custom_field_val = get_sub_field($custom_field);
				if($settings['cf_type'] == 'link') {
					$cf_link_dynamic_text = get_sub_field( $settings['cf_link_dynamic_text'] );
				}
				if($settings['cf_type'] == 'image' && $settings['links_to'] == 'custom_field' && $settings['cf_link_dynamic_image'] != ''){
					$cf_link_dynamic_image = get_sub_field( $settings['cf_link_dynamic_image'] );
				}
			}
		}


		$this->add_render_attribute( 'cf-wrapper','class','cf-type-'.$settings['cf_type'] );
		$this->add_render_attribute( 'cf-wrapper','class','ae-cf-wrapper' );
		$this->add_render_attribute( 'custom-field-class', 'class', 'ae-element-custom-field' );
		$this->add_render_attribute( 'custom-field-label-class', 'class', 'ae-element-custom-field-label' );
		$this->add_render_attribute( 'post-cf-icon-class','class','icon-wrapper' );
		$this->add_render_attribute( 'post-cf-icon-class','class','ae-element-custom-field-icon' );
		$this->add_render_attribute( 'post-cf-icon','class',$settings['cf_icon'] );

		if(empty($custom_field_val)){
		    $this->add_render_attribute('cf-wrapper','class','hide');
        }

		if($settings['cf_link_target'] == 'yes'){
			$this->add_render_attribute( 'custom-field-class', 'target', '_blank' );
		}

        if($settings['cf_link_download'] == '1'){
            $this->add_render_attribute( 'custom-field-class', 'download', '' );
        }

		$cf_type = $settings['cf_type'];
		$eid = $this->get_id();
		$custom_field_html = '';
		switch ($cf_type) {

			case "html": 	if(!empty($custom_field_val)){
								$custom_field_html = '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>'.wpautop(do_shortcode($custom_field_val)).'</div>';
							}
							break;

			case "link":	if($settings['link_type'] == 'email'){
			                    $custom_field_val = 'mailto:'.$custom_field_val;
                            }elseif($settings['link_type'] == 'tel'){
			                    $custom_field_val = 'tel:'.$custom_field_val;
                            }

                            if(!empty($settings['cf_link_text']) && $settings['link_text_type'] == 'static'){
                                $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).'  href="'.$custom_field_val.'">'.$settings['cf_link_text'].'</a>';
                            }else if(!empty($cf_link_dynamic_text) && $settings['link_text_type'] == 'custom_field') {
                                $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).'  href="'.$custom_field_val.'">'. $cf_link_dynamic_text .'</a>';
                            }else{
                                if($settings['link_type'] != 'default'){
                                    $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'.$custom_field_val.'">'. get_post_meta($post_id,$custom_field,true) .'</a>';
                                }else{
                                    $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'.$custom_field_val.'">'.$custom_field_val.'</a>';
                                }
                            }

                            if($settings['link_text_type'] == 'post'){
			                    $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'. get_permalink($post_id) .'">'.$custom_field_val.'</a>';
                            }

							break;

			case "image":	$post_image_size = $settings['image_size'];

                            if($settings['links_to'] == 'post'){
                                $post_link = get_permalink($post_id );
                            }elseif($settings['links_to'] == 'media'){
                                $media_link = wp_get_attachment_image_src($custom_field_val ,'full');
                                $post_link = $media_link[0];
                            }elseif($settings['links_to'] == 'static'){
                                $post_link = $settings['cf_link_image'];
                            }elseif($settings['links_to'] == 'custom_field'){
                                $post_link = $cf_link_dynamic_image;
                            }

							if(is_numeric($custom_field_val)){
								$custom_field_html =  '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>';
                                if($settings['links_to'] != '') {
                                    $image_target = '';
                                    if($settings['cf_link_target'] == 'yes'){
                                        $image_target = ' target="_blank"';
                                    }
                                    $custom_field_html .= '<a href="' . $post_link . '" title="' . $post_title . '"' . $image_target . '>';
                                }
                                $custom_field_html .=  wp_get_attachment_image( $custom_field_val, $post_image_size );
                                if($settings['links_to'] != ''){
                                    $custom_field_html .= '</a>';
                                }
                                $custom_field_html .= '</div>';
							}else{
								$custom_field_html =  '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>';
                                if($settings['links_to'] != '') {
                                    $custom_field_html .= '<a href="' . $post_link . '" title="' . $post_title . '">';
                                }
								$custom_field_html .= '<img src="'.$custom_field_val.'" />';
                                if($settings['links_to'] != ''){
                                    $custom_field_html .= '</a>';
                                }
								$custom_field_html .= '</div>';
							}

							break;

			case "video":  add_filter( 'oembed_result', [ $this, 'ae_filter_oembed_result' ], 50, 3 );

						   $custom_field_html = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
						   $custom_field_html .= "<script type='text/javascript'>
												     jQuery(document).ready(function(){
												     	jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
												     });
												     jQuery(window).resize(function(){
    													jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
													 });
												     jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
												     </script>";
						   remove_filter( 'oembed_result', [ $this, 'ae_filter_oembed_result' ], 50 );
						   break;

			case "audio":
							$custom_field_html = wp_audio_shortcode([
													'src' => $custom_field_val
												]);
						   break;

			case "oembed": if($repeater['is_repeater']){
				                $custom_field_html = $custom_field_val;
                            }else{
				                $custom_field_html = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
                            }
				           break;

            case "date"  : 	if(empty($custom_field_val)){
                                break;
                            }

                            if($settings['acf_support'] == ''){
                                $format = "g:i A";
                                if($settings['date_format']=='custom') {
                                    $format = $settings['date_custom_format'];
                                }elseif($settings['date_format'] == 'default'){
                                    $format = get_option( 'date_format' );
                                }else{
                                    $format = $settings['date_format'];
                                }
                                $custom_field_html = date_i18n( $format, strtotime( $custom_field_val ) );
                            }else{
                                $custom_field_html = $custom_field_val;
                            }
                            $custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode($custom_field_html) );
                            break;

			default:
						$custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode($custom_field_val) );
						break;
		}?>

		<div <?php echo $this->get_render_attribute_string('cf-wrapper');?>>
            <?php if(($settings['cf_type']=='text') || ($settings['cf_type']=='link') || ($settings['cf_type']=='date')){ ?>

                <?php if(!empty($settings['cf_icon']) && !empty($custom_field_val)){ ?>
                    <span <?php echo $this->get_render_attribute_string( 'post-cf-icon-class' ); ?>>
					<i <?php echo $this->get_render_attribute_string( 'post-cf-icon' ); ?>></i>
				</span>
                <?php }

                if(!empty($settings['cf_label']) && !empty($custom_field_val)){ ?>
                    <span <?php echo $this->get_render_attribute_string('custom-field-label-class');?>>
					<?php echo $settings['cf_label'];?>
				</span>
                <?php }

            }
            echo $custom_field_html;?>
        </div>
    <?php
	}

	public function ae_filter_oembed_result($html){
		$settings = $this->get_settings();

		$params = [];

		if ( 'youtube' === $settings['cf_video_type'] ) {
			$youtube_options = [ 'autoplay', 'rel', 'controls', 'showinfo' ];

			foreach ( $youtube_options as $option ) {
				//if ( 'autoplay' === $option && $this->has_image_overlay() )
				//	continue;

				$value = ( 'yes' === $settings[ 'cf_yt_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
			}

			$params['wmode'] = 'opaque';
		}

		if ( 'vimeo' === $settings['cf_video_type'] ) {
			$vimeo_options = [ 'autoplay', 'loop', 'title', 'portrait', 'byline', 'muted', 'background' ];

			foreach ( $vimeo_options as $option ) {
				//if ( 'autoplay' === $option && $this->has_image_overlay() )
				//	continue;

				$value = ( 'yes' === $settings[ 'vimeo_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
				if($settings['vimeo_background' ] == 'yes'){
					unset($params ['autoplay']);
					unset($params ['loop']);
					unset($params ['title']);
				}
			}

			$params['color'] = str_replace( '#', '', $settings['vimeo_color'] );

		}

		if ( ! empty( $params ) ) {
			preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );
			$url = esc_url( add_query_arg( $params, $matches[1] ) );

			$html = str_replace( $matches[1], $url, $html );
		}

		return $html;
	}

	public function youtube_video_options(){
		$this->add_control(
				'heading_youtube',
				[
						'label' => __( 'Youtube Video Options', 'ae-pro' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'youtube',
						],
				]
		);

		// YouTube
		$this->add_control(
				'cf_yt_autoplay',
				[
						'label' => __( 'Autoplay', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'No', 'ae-pro' ),
						'label_on' => __( 'Yes', 'ae-pro' ),
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'youtube',
						],
				]
		);

		$this->add_control(
				'cf_yt_rel',
				[
						'label' => __( 'Suggested Videos', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'youtube',
						],
				]
		);

		$this->add_control(
				'cf_yt_controls',
				[
						'label' => __( 'Player Control', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'default' => 'yes',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'youtube',
						],
				]
		);

		$this->add_control(
				'cf_yt_showinfo',
				[
						'label' => __( 'Player Title & Actions', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'default' => 'yes',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'youtube',
						],
				]
		);
	}
	public function vimeo_video_options(){
		$this->add_control(
				'heading_vimeo',
				[
						'label' => __( 'Vimeo Video Options', 'ae-pro' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
						],
				]
		);

		// Vimeo

		$this->add_control(
			'vimeo_background',
			[
				'label' => __( 'Background Mode', 'ae-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'lablel_on' => __( 'Yes', 'ae-pro' ),
				'default' => '',
				'condition' => [
					'cf_type' => 'video',
					'cf_video_type' => 'vimeo',
				]
			]
		);

		$this->add_control(
				'vimeo_autoplay',
				[
						'label' => __( 'Autoplay', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'No', 'ae-pro' ),
						'label_on' => __( 'Yes', 'ae-pro' ),
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
				'vimeo_loop',
				[
						'label' => __( 'Loop', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'No', 'ae-pro' ),
						'label_on' => __( 'Yes', 'ae-pro' ),
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
				'vimeo_title',
				[
						'label' => __( 'Intro Title', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'default' => 'yes',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
				'vimeo_portrait',
				[
						'label' => __( 'Intro Portrait', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'default' => 'yes',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
				'vimeo_byline',
				[
						'label' => __( 'Intro Byline', 'ae-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'ae-pro' ),
						'label_on' => __( 'Show', 'ae-pro' ),
						'default' => 'yes',
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
				'vimeo_color',
				[
						'label' => __( 'Controls Color', 'ae-pro' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'condition' => [
								'cf_type' => 'video',
								'cf_video_type' => 'vimeo',
								'vimeo_background' => ''
						],
				]
		);

		$this->add_control(
		        'vimeo_muted',
                [
                        'label' => __( 'Muted', 'ae-pro' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_off' => __( 'No', 'ae-pro' ),
                        'lablel_on' => __( 'Yes', 'ae-pro' ),
                        'default' => '',
                        'condition' => [
	                        'cf_type' => 'video',
	                        'cf_video_type' => 'vimeo',
                        ]
                ]
        );
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Custom_Field() );