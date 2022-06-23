<?php

namespace Aepro\Ae_Pods\Skins;

use Aepro\Aepro;
use Aepro\Aepro_Pods;
use Aepro\Classes\PodsMaster;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class Skin_Number extends Skin_Base{

	public function get_id() {
		return 'number';
	}

	public function get_title() {
		return __( 'Number', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-pods/general/after_section_end', [$this, 'register_style_controls']);

	}

	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

		$this->register_number_controls();
	}

	public function render() {

		$settings = $this->parent->get_settings();
		$post     = Aepro::$_helper->get_demo_post_data();

		$singular_string = '';
		$plural_string = '';
		$output_string = '';
		$print_plain = false;

		$field_args  =  [
							'field_name'    => $settings['field_name'],
							'field_type'    => $settings['field_type'],

						];

        if ($settings['pods_option_name'] != ''){
            $field_args['pods_option_name'] = $settings['pods_option_name'];
        }

		$number = PodsMaster::instance()->get_field_value( $field_args );

		$default_blank = $this->get_instance_value('default_blank');
		$default_zero = $this->get_instance_value('default_zero');

		if($number == '' && $default_blank != ''){
		    $number = $default_blank;
		    $print_plain = true;
        }elseif($number == 0 && $default_zero != ''){
			$number = $default_zero;
			$print_plain = true;
        }


		$this->parent->add_render_attribute('wrapper', 'class', 'ae-acf-wrapper');

		if($print_plain){
		    ?>

            <div <?php echo $this->parent->get_render_attribute_string('wrapper'); ?>>
                <div class="ae-acf-content-wrapper">
					<?php echo $number; ?>
                </div>
            </div>

            <?php

            return;
        }

		if($number !== ''){

			$singular_prefix    = $this->get_instance_value('singular_prefix');
			$plural_prefix      = $this->get_instance_value('plural_prefix');
			$singular_suffix    = $this->get_instance_value('singular_suffix');
			$plural_suffix      = $this->get_instance_value('plural_suffix');


			if(!empty($singular_prefix)){
				$singular_string  = '<span class="ae-prefix">'.$singular_prefix.'</span>';
			}

			$singular_string .= '%s';

			if(!empty($singular_suffix)){
				$singular_string  .= '<span class="ae-suffix">'.$singular_suffix.'</span>';
			}


			if(!empty($plural_prefix)){
				$plural_string  = '<span class="ae-prefix">'.$plural_prefix.'</span>';
			}

			$plural_string .= '%s';

			if(!empty($plural_suffix)){
				$plural_string  .= '<span class="ae-suffix">'.$plural_suffix.'</span>';
			}


			$output_string = sprintf( _n( $singular_string, $plural_string, $number, 'ae-pro' ), number_format_i18n( $number ) );

		}else{
			$this->parent->add_render_attribute('wrapper', 'class', 'ae-hide');
		}


		?>


		<div <?php echo $this->parent->get_render_attribute_string('wrapper'); ?>>
			<div class="ae-acf-content-wrapper">
				<?php echo $output_string; ?>
			</div>
		</div>
		<?php

	}


	function register_style_controls(){

		$this->start_controls_section(
			'general_style',
			[
				'label' => __('General', 'ae-pro'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ae-acf-content-wrapper',
			]
		);

			$this->start_controls_tabs('style');

				$this->start_controls_tab(
					'normal_style',
					[
						'label' => __('Normal', 'ae-pro')
					]
				);

				$this->add_control(
					'color',
					[
						'label' => __('Color'),
						'type'  => Controls_Manager::COLOR,
                        [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_3,
                        ],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper' => 'color:{{VALUE}}'
						]
					]
				);

				$this->add_control(
					'bg_color',
					[
						'label' => __('Background Color'),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper' => 'background:{{VALUE}}'
						]
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'border',
						'label' => __( 'Border', 'ae-pro' ),
						'selector' => '{{WRAPPER}} .ae-acf-content-wrapper',
					]
				);

				$this->add_control(
					'border_radius',
					[
						'label' => __( 'Border Radius', 'ae-pro' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'box_shadow',
						'label' => __( 'Shadow', 'ae-pro' ),
						'selector' => '{{WRAPPER}} .ae-acf-content-wrapper',
					]
				);


				$this->end_controls_tab();  // Normal Tab End

				$this->start_controls_tab(
					'hover_style',
					[
						'label' => __('Hover', 'ae-pro')
					]
				);


				$this->add_control(
					'color_hover',
					[
						'label' => __('Color'),
						'type'  => Controls_Manager::COLOR,
                        [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_3,
                        ],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper:hover' => 'color:{{VALUE}}'
						]
					]
				);

				$this->add_control(
					'bg_color_hover',
					[
						'label' => __('Background Color'),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper:hover' => 'background:{{VALUE}}'
						]
					]
				);

				$this->add_control(
					'border_color_hover',
					[
						'label' => __('Border Color'),
						'type'  => Controls_Manager::COLOR,
                        [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_3,
                        ],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper:hover' => 'border-color:{{VALUE}}'
						]
					]
				);

				$this->add_responsive_control(
					'border_radius_hover',
					[
						'label' => __('Border Radius', 'ae-pro'),
						'type'  => Controls_Manager::DIMENSIONS,
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'hover_box_shadow',
						'label' => __( 'Shadow', 'ae-pro' ),
						'selector' => '{{WRAPPER}} .ae-acf-content-wrapper:hover',
					]
				);


				$this->end_controls_tab();

			$this->end_controls_tabs();


			$this->add_responsive_control(
				'padding',
				[
					'label' => __('Padding', 'ae-pro'),
					'type'  => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'selectors' => [
						'{{WRAPPER}} .ae-acf-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],

				]
			);

			$this->add_responsive_control(
				'margin',
				[
					'label' => __('Margin', 'ae-pro'),
					'type'  => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .ae-acf-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
                    'condition' => [
	                    $this->get_control_id('align!') => 'justify'
                    ]

				]
			);

		$this->end_controls_section();

	}

	function register_number_controls(){


	    $this->add_control(
            'default_blank',
            [
                'label' => __('Default Value (Blank)', 'ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'description'   => __('To be use when field value is blank','ae-pro')
            ]
        );

		$this->add_control(
			'default_zero',
			[
				'label' => __('Default Value (Zero)', 'ae-pro'),
				'type'  => Controls_Manager::TEXT,
				'description'   => __('To be use when field value is zero','ae-pro')
			]
		);

		$this->add_control(
			'singular_prefix',
			[
				'label' => __('Singular Prefix', 'ae-pro'),
				'type'  => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'plural_prefix',
			[
				'label' => __('Plural Prefix', 'ae-pro'),
				'type'  => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'singular_suffix',
			[
				'label' => __('Singular Suffix', 'ae-pro'),
				'type'  => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'plural_suffix',
			[
				'label' => __('Plural Suffix', 'ae-pro'),
				'type'  => Controls_Manager::TEXT
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Align', 'ae-pro' ),
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
	}


}
