<?php

namespace Aepro\Ae_ACF\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base
{

	protected function _register_controls_actions()
	{
		add_action('elementor/element/ae-acf/general/before_section_end', [$this, 'register_controls']);

	}

	public function register_controls(Widget_Base $widget){

		$this->parent = $widget;

	}

	public function register_text_controls(){

		$this->add_control(
			'prefix',
			[
				'label' => __('Before Text'),
				'type'  => Controls_Manager::TEXT,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __('After Text'),
				'type'  => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __('Placeholder Text'),
				'type'  => Controls_Manager::TEXT,
				'description' => __('To be used as default text when there is no data in ACF Field', 'ae-pro')
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label'     => __('Html Tag', 'ae-pro'),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
					'article' => 'article'
				],
				'default' => 'h2'
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
					'static' => __( 'Static URL', 'ae-pro' ),
					'custom_field' => __( 'Custom Field', 'ae-pro' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'link_url',
			[
				'label' => __( 'Static URL', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter URL', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					$this->get_control_id('links_to') => 'static'
				]
			]
		);
		$this->add_control(
			'link_cf',
			[
				'label' => __( 'Enter Field Key', 'ae-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Field Key', 'ae-pro' ),
				'default' => __( '', 'ae-pro' ),
				'condition' => [
					$this->get_control_id('links_to') => 'custom_field'
				],
				'description' => __( 'Mention ACF field that contains an url', 'ae-pro')
			]
		);

		$this->add_control(
			'link_new_tab',
			[
				'label' => __('Open in new tab','ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'label_on' => __( 'Yes', 'ae-pro' ),
				'default' => __('label_off', 'ae-pro'),
				'condition' => [
					$this->get_control_id('links_to!') => ''
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
					],
					'justify' => [
						'title' => __( 'Justified', 'ae-pro' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'ae-align-',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ae-acf-wrapper' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .ae-acf-content-wrapper' => 'display:inline-block;',
					'{{WRAPPER}}.ae-align-justify .ae-acf-content-wrapper' => 'width:100%; text-align:center;'
				]
			]
		);


	}

	public function add_unfold_section(){

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
					$this->get_control_id('enable_unfold') => 'yes',
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
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->add_control(
            'button_controls_heading',
            [
                'label' => __('Button', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_button_controls' );

        $this->start_controls_tab(
            'tab_button_unfold',
            [
                'label' => __( 'Unfold', 'ae-pro' ),
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );
        $this->add_control(
            'unfold_text',
            [
                'label'     => __( 'Show More Text', 'ae-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Show More',
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->add_control(
            'unfold_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_fold',
            [
                'label' => __( 'Fold', 'ae-pro' ),
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
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
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->add_control(
            'fold_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_icon_position',
            [
                'label' => __( 'Icon Position', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'ae-pro' ),
                    'right' => __( 'After', 'ae-pro' ),
                ],
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                    $this->get_control_id('unfold_icon!') => '',
                ],
            ]
        );

        $this->add_control(
            'button_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                    $this->get_control_id('unfold_icon!') => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-unfold-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-acf-unfold-button-icon.elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'unfold_button_align',
            [
                'label' => __('Button Align', 'ae-pro'),
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
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-unfold' => 'text-align: {{VALUE}}'
                ],
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

		$this->end_controls_section();

	}

	public function register_select_controls(){

		$this->add_control(
			'data_type',
			[
				'label' => __('Display Data', 'ae-pro'),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					'label' => __('Label', 'ae-pro'),
					'key' => __( 'Key', 'ae-pro' ),
				],
				'separator' => 'before',
				'default' => 'label',
 			]
		);

		$this->add_control(
			'show_all_choices',
			[
				'label' => __('Show All Options/Choices', 'ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'label_on' => __( 'Yes', 'ae-pro' ),
				'default' => __('label_off', 'ae-pro'),
				'description' => __('This will even display choices that were not selected. You can style them separately.', 'ae-pro')
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __('Layout', 'ae-pro'),
				'label_block' => false,
				'type'  => Controls_Manager::CHOOSE,
				'options' => [
					'vertical' => [
						'title' => __( 'Vertical', 'ae-pro' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => __( 'Horizontal', 'ae-pro' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'default' => 'horizontal',
			]
		);

		$this->add_responsive_control(
			'horizontal_align',
			[
				'label' => __( 'Align', 'ae-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'prefix_class' => 'ae-icl-align-'
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __('Icon', 'ae-pro'),
				'type'  => Controls_Manager::ICON
			]
		);

		$this->add_control(
			'icon_unchecked',
			[
				'label' => __('Icon (Unchecked)', 'ae-pro'),
				'type'  => Controls_Manager::ICON
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __('Enable Divider','ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'ae-pro' ),
				'label_on' => __( 'On', 'ae-pro' ),
				'return_value' => 'yes',
				'render_type' => 'template',
				'prefix_class'  => 'ae-sep-divider-',
				'selectors' => [
					'{{WRAPPER}} .ae-icon-list-item:not(:last-child):after' => 'content: ""',
				]
			]
		);

		$this->add_control(
			'separator',
			[
				'label' => __('Separator', 'ae-pro'),
				'type'  => Controls_Manager::TEXT,
				'default' => '',
				'render_type' => 'template',
				'condition' => [
					$this->get_control_id('layout') => 'horizontal',
					$this->get_control_id('divider!') => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .ae-custom-sep .ae-icon-list-item:not(:last-child):after' => 'content:"{{VALUE}}"; white-space:pre;'
				]
 			]
		);

	}

}