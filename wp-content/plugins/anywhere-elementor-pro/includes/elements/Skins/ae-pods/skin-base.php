<?php

namespace Aepro\Ae_Pods\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base
{

	protected function _register_controls_actions()
	{
		add_action('elementor/element/ae-pods/general/before_section_end', [$this, 'register_controls']);

	}

	public function register_controls(Widget_Base $widget){

		$this->parent = $widget;

	}

	public function register_text_controls()
    {

        $this->add_control(
            'prefix',
            [
                'label' => __('Before Text'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label' => __('After Text'),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => __('Placeholder Text'),
                'type' => Controls_Manager::TEXT,
                'description' => __('To be used as default text when there is no data in Pods Field', 'ae-pro')
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
    }

    public function register_links_controls(){

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
				'description' => __( 'Mention Pods field that contains an url', 'ae-pro')
			]
		);

        $this->add_control(
            'download',
            [
                'label' => __('Download on Click', 'ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'label_off' => __( 'No', 'ae-pro' ),
                'label_on' => __( 'Yes', 'ae-pro' ),
                'return_value' => 1,
                'default' => __('label_off', 'ae-pro'),
                'condition' => [
                    $this->get_control_id('links_to') => ['static', 'custom_field']
                ],
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
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => [
                    $this->get_control_id('enable_unfold') => 'yes',
                ]
            ]
        );

        /*$this->add_control(
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
        );*/

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

	public function register_gallery_type(){
        $this->add_control(
            'gallery_type',
            [
                'label' => __('Type', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'carousel' => __( 'Carousel' , 'ae-pro'),
                        'grid' =>__( 'Grid' , 'ae-pro'),
                    ],
                'default'=>'carousel',
                'prefix_class' => 'ae-pods-gallery-',
                'render_type' => 'template',
            ]
        );
    }

    protected function gallery_field_control(){

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'exclude' => [ 'custom' ],
            ]
        );
        $this->add_control(
            'open_lightbox',
            [
                'label' => __('Lightbox', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'default' => __( 'Default' , 'ae-pro'),
                        'yes' =>__( 'Yes' , 'ae-pro'),
                        'no' =>__('No' , 'ae-pro'),
                    ],
                'default'=>'no'
            ]
        );
    }

    protected function gallery_image_carousel_control()
    {

        $this->start_controls_section(
            'carousel_controls',
            [
                'label' => __('Carousel', 'ae-pro'),
                'condition' => [
                    $this->get_control_id('gallery_type') => 'carousel'
                ]
            ]
        );


        $this->add_responsive_control(
            'slide_per_view',
            [
                'label' => __( 'Slides Per View', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                /* 'options'=> [
                     1 => __('1','ae-pro'),
                     2 => __('2','ae-pro'),
                     3 => __('3','ae-pro'),
                     4 => __('4','ae-pro'),
                 ], */
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'slides_per_group',
            [
                'label' => __( 'Slides Per Group', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 900,
                        'step' => 1
                    ]
                ]

            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
            ]

        );

        $this->add_control(
            'duration',
            [
                'label' => __('Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 3000,
                ],
                'range' => [
                    'px' =>[
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1000,
                    ]
                ],
                'condition' => [
                    $this->get_control_id('autoplay') => 'yes'
                ],
            ]
        );

        // Todo:: different effects management
        $this->add_control(
            'effect',
            [
                'label' => __('Effects', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'ae-pro'),
                    'slide' => __('Slide', 'ae-pro'),
                    'cube' => __('Cube', 'ae-pro'),
                    'coverflow' => __('Coverflow', 'ae-pro'),
                    'flip' => __('Flip', 'ae-pro'),
                ],
                'default'=>'slide',
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Space Between Slides', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>[
                    'size' => 15,
                ],
                'tablet_default' => [
                    'size' => 10,
                ],
                'mobile_default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px'=>[
                        'min'=> 0,
                        'max'=> 50,
                        'step'=> 5,
                    ]
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'auto_height',
            [
                'label' => __('Auto Height', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' => __('Zoom', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

    }

    protected function gallery_pagination_controls(){

        $this->start_controls_section(
            'pagination_controls',
            [
                'label' => __('Pagination', 'ae-pro'),
                'condition' => [
                    $this->get_control_id('gallery_type') => 'carousel'
                ]
            ]
        );

        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Pagination', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );



        $this -> add_control(
            'ptype',
            [
                'label' => __(' Pagination Type' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        ''        => __('None', 'ae-pro'),
                        'bullets' => __( 'Bullets' , 'ae-pro'),
                        'fraction' =>__( 'Fraction' , 'ae-pro'),
                        'progress' =>__('Progress' , 'ae-pro'),
                    ],
                'default'=>'bullets'
            ]
        );

        $this->add_control(
            'clickable',
            [
                'label' =>__('Clickable' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'condition'=> [
                    $this->get_control_id('ptype') => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'navigation_button',
            [
                'label' => __('Previous/Next Button' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes' , 'ae-pro'),
                'label_off' => __('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => __('Keyboard Control' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' =>__('Scroll bar', 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=>'yes',
                'label_on' =>__('Yes' , 'ae-pro'),
                'label_off'=>__('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    public function gallery_common_style_control(){

        $this->start_controls_section(
            'general_style',
            [
                'label' => __('General', 'ae-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    $this->get_control_id('gallery_type') => 'carousel'
                ]

            ]
        );

        $this->add_control(
            'heading_style_arrow',
            [
                'label' => __('Arrow', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' =>
                    [
                        'px' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1
                        ],
                    ],

                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => '-webkit-mask-size:{{SIZE}}px auto; mask-size:{{SIZE}}px auto; width:{{SIZE}}px; height:calc({{SIZE}}px*2)',
                    '{{WRAPPER}} .ae-swiper-button-next' => '-webkit-mask-size:{{SIZE}}px auto; mask-size:{{SIZE}}px auto; width:{{SIZE}}px; height:calc({{SIZE}}px*2)',
                ],
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-prev.swiper-button-disabled' => 'background-color:{{VAlUE}}; opacity: .5;',
                    '{{WRAPPER}} .ae-swiper-button-next.swiper-button-disabled' => 'background-color:{{VAlUE}}; opacity: .5;'
                ],
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );


        $this->add_control(
            'heading_style_dots',
            [
                'label' => __('Dots', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width:{{SIZE}}px; height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Dots Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );




        $this->add_control(
            'heading_style_scroll',
            [
                'label' => __('Scrollbar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'scroll_size',
            [
                'label' => __('Scrollbar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => __('Scrollbar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scroll_color',
            [
                'label' => __('Scroll Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_progress',
            [
                'label' => __('Progress Bar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'progressbar_color',
            [
                'label' => __('Prgress Bar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progress_color',
            [
                'label' => __('Prgress Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progressbar_size',
            [
                'label' => __('Prgress Bar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );

        $this->end_controls_section();


    }

    protected function grid_view(){
        $this->start_controls_section(
            'grid_view_controls',
            [
                'label' => __('Grid', 'ae-pro'),
                'condition' => [
                    $this->get_control_id('gallery_type') => 'grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'ae-pro'),
                'type'  => Controls_Manager::NUMBER,
                'desktop_default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'min' => 1,
                'max' => 12,
                'selectors' => [
                    '{{WRAPPER}} .ae-grid' => 'grid-template-columns:repeat({{VALUE}}, 1fr);'
                ]
            ]
        );

        $this->add_control(
            'masonry',
            [
                'label' =>__('Masonry Layout' , 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    $this->get_control_id('columns!') => 1
                ]
            ]

        );

        $this->add_responsive_control(
            'gutter',
            [
                'label' => __('Gutter','ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range'=>[
                    'px'=>[
                        'min' => 0,
                        'max' =>40,
                        'step' => 2,
                    ]
                ],
                'default'=>[
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}}  .ae-grid' => 'grid-column-gap:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ]
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
                    '{{WRAPPER}} .ae-grid-item-inner.ae_image_ratio_yes .ae-pods-gallery-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
                'condition' => [
                    $this->get_control_id('enable_image_ratio') => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function grid_overlay_controls(){

        $this->start_controls_section(
            'grid_overlay_controls',
            [
                'label' => __('Grid Overlay', 'ae-pro'),
                'condition' => [
                    $this->get_control_id('gallery_type') => 'grid'
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
                    'hide-on-hover' => __('Hide on Hover' , 'ae-pro')
                ],
                'default'   => 'hover',
                'prefix_class'  => 'overlay-'
            ]
        );


        $this->add_control(
            'caption',
            [
                'label' => __('Caption' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]
            ]


        );

        $this->add_control(
            'icon_style',
            [
                'label'=>__('Icon','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]

            ]
        );

        /*$this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-link',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]
            ]
        );*/

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'View', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Default', 'ae-pro' ),
                    'stacked' => __( 'Stacked', 'ae-pro' ),
                    'framed' => __( 'Framed', 'ae-pro' ),

                ],
                'default' => 'default',
                'prefix_class' => 'ae-icon-view-',
                'condition'=>[
                    $this->get_control_id('selected_icon!')=>'',
                    $this->get_control_id('show_overlay!') => 'never'
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function grid_style(){

        $this->start_controls_section(
            'grid_style',
            [
                'label' => __('Grid', 'ae-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    $this->get_control_id('gallery_type') => 'grid'
                ]
            ]
        );

        $this->add_control(
            'item_style',
            [
                'label' => __('Item','ae-pro'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->start_controls_tabs('style_tabs');

        $this->start_controls_tab(
            'normal',
            [
                'label' => __('Normal','ae-pro')
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'grid_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item-inner',
            ]
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'hover',
            [
                'label' => __('Hover','ae-pro')
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'grid_border_hover',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item-inner:hover',
            ]
        );

        $this->add_control(
            'item_border_radius_hover',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item-inner:hover *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-grid-item-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow_hover',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item-inner:hover ',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function grid_overlay_style_control(){

        $this->start_controls_section(
            'grid_overlay_style',
            [
                'label' => __('Grid Overlay', 'ae-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    $this->get_control_id('gallery_type') => 'grid'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'ae-pro' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-grid-overlay',
                'condition'=>[
                    $this->get_control_id('show_overlay!')=>'never',
                ]
            ]
        );

        $this->add_control(
            'animation',
            [
                'label' => __('Animation', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('None','ae-pro'),
                    'pulse' => __('Pulse', 'ae-pro'),
                    'headShake' => __('Head Shake', 'ae-pro'),
                    'tada' => __('Tada', 'ae-pro'),
                    'fadeIn' => __('Fade In', 'ae-pro'),
                    'fadeInDown' => __('Fade In Down', 'ae-pro'),
                    'fadeInLeft' => __('Fade In Left', 'ae-pro'),
                    'fadeInRight' => __('Fade In Right', 'ae-pro'),
                    'fadeInUp' => __('Fade In Up', 'ae-pro'),
                    'rotateInDownLeft' => __('Rotate In Down Left','ae-pro'),
                    'rotateInDownRight' => __('Rotate In Down Right','ae-pro'),
                    'rotateInUpLeft' => __('Rotate In Up Left','ae-pro'),
                    'rotateInUpRight' =>__('Rotate In Up Right','ae-pro'),
                    'zoomIn' => __('Zoom In','ae-pro'),
                    'zoomInDown' => __('Zoom In Down','ae-pro'),
                    'zoomInLeft' => __('Zoom In Left', 'ae-pro'),
                    'zoomInRight' => __('Zoom In Right', 'ae-pro'),
                    'zoomInUp' => __('Zoom In Up', 'ae-pro'),
                    'slideInLeft' => __('Slide In Left', 'ae-pro'),
                    'slideInRight' => __('Slide In Right', 'ae-pro'),
                    'slideInUp' => __('Slide In Up', 'ae-pro'),
                    'slideInDown' => __('Slide In Down', 'ae-pro'),
                ],
                'default'=>'fadeIn',
                'condition' =>[
                    $this->get_control_id('show_overlay')=> ['hover', 'hide-on-hover'],
                ]
            ]
        );

        $this->add_control(
            'animation_time',
            [
                'label' => __('Animation Time','ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.00
                ],
                'range' => [
                    'min' => 1.00,
                    'max' => 10.00,
                    'step' => 0.01
                ],
                'condition' => [
                    $this->get_control_id('animation!') => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-overlay' => 'animation-duration:{{SIZE}}s;'
                ]
            ]
        );

        $this->add_control(
            'caption_style',
            [
                'label' => __('Caption','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]

        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-overlay-caption',
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => __('Color','ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-caption' => 'color:{{VALUE}};'
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'caption_color_hover',
            [
                'label' => __('Hover Color','ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-caption:hover' => 'color:{{VALUE}};'
                ],
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'icon_overlay_style',
            [
                'label'=>__('Icon','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],

            ]

        );

        $this->add_control(
            'primary_color',
            [
                'label' => __( 'Primary Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-stacked .ae-overlay-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed .ae-overlay-icon, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed .ae-overlay-icon svg, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __( 'Secondary Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-framed .ae-overlay-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked .ae-overlay-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked .ae-overlay-icon svg' => 'color: {{VALUE}};',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'primary_color_hover',
            [
                'label' => __( 'Primary Color Hover', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-stacked:hover .ae-overlay-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed:hover .ae-overlay-icon:hover, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed:hover .ae-overlay-icon:hover svg, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon svg' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],'condition'=>[
                $this->get_control_id('icon!')=>'',
            ],

            ]
        );

        $this->add_control(
            'secondary_color_hover',
            [
                'label' => __( 'Secondary Color Hover', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-framed:hover .ae-overlay-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked:hover .ae-overlay-icon:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked:hover .ae-overlay-icon:hover svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => __( 'Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-overlay-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'icon_padding',
            [
                'label' => __( 'Icon Padding', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],

            ]
        );

        $this->add_control(
            'rotate',
            [
                'label' => __( 'Rotate', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .ae-overlay-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('view') => 'framed',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
            ]
        );

        $this->end_controls_section();

    }


    /*public function register_links_controls(){
        $this->add_control(
            'links_to',
            [
                'label' => __('Links To', 'ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'title'         => __('Title', 'ae-pro'),
                    'caption'       => __('Caption', 'ae-pro'),
                    'filename'      => __('File Name', 'ae-pro'),
                    'static'        => __('Static Text', 'ae-pro'),
                    'post'          => __('Post Title', 'ae-pro' ),
                    'dynamic_text'  => __('Custom Field', 'ae-pro'),
                ],
                'default'   => 'static'
            ]
        );

        $this->add_control(
            'static_text',
            [
                'label' => __('Static Text', 'ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'default' => __('Download Now', 'ae-pro'),
                'condition'    => [
                    $this->get_control_id('links_to') => 'static'
                ]
            ]
        );

        $this->add_control(
            'custom_field_text',
            [
                'label' => __('Custom Field', 'ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'condition'    => [
                    $this->get_control_id('links_to') => 'dynamic_text'
                ]
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
                        'title' => __( 'Justify', 'ae-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ]
                ],
                'prefix_class' => 'ae-align-',
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-wrapper' => 'text-align:{{VALUE}}',
                    '{{WRAPPER}} .ae-acf-content-wrapper' => 'display:inline-block;',
                    '{{WRAPPER}}.ae-align-justify .ae-acf-content-wrapper' => 'width:100%; text-align:center;'
                ]
            ]
        );

    }*/

}