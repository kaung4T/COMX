<?php

namespace Aepro\Ae_Pods\Skins;

use Aepro\Aepro;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Aepro\Classes\PodsMaster;
use Elementor\Group_Control_Typography;



class Skin_Website extends Skin_Base{

	public function get_id() {
		return 'website';
	}

	public function get_title() {
		return __( 'Website', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-pods/general/after_section_end', [$this, 'register_style_controls']);
	}

    public function register_controls( Widget_Base $widget)
    {

        $this->parent = $widget;

        parent::register_links_controls();

        $this->update_control(
            'links_to',
            [
                'options' => [
                    'static'        => __('Static Text', 'ae-pro'),
                    'post'          => __('Post Title', 'ae-pro' ),
                    'custom_field'  => __('Custom Field', 'ae-pro'),
                ],
                'default'   => 'static'
            ]
        );
    }

	public function render() {

		$settings = $this->parent->get_settings_for_display();
		$link_text = '';

		$field_args  =  [
			'field_name'    => $settings['field_name'],
			'field_type'    => $settings['field_type'],
		];

        if ($settings['pods_option_name'] != ''){
            $field_args['pods_option_name'] = $settings['pods_option_name'];
        }

		$url = PodsMaster::instance()->get_field_value( $field_args );
		$this->parent->add_render_attribute('anchor', 'href', $url);
		$this->parent->add_render_attribute('anchor', 'class', 'ae-acf-content-wrapper');

		$new_tab = $this->get_instance_value('new_tab');
		if($new_tab == 1){
			$this->parent->add_render_attribute('anchor', 'target', '_blank');
		}

		$no_follow = $this->get_instance_value('nofollow');
		if($no_follow == 1){
			$this->parent->add_render_attribute('anchor', 'rel', 'nofollow');
		}

		// Get Link Text
		$links_to = $this->get_instance_value('links_to');

		switch($links_to){

			case 'static' :   $link_text = $this->get_instance_value('static_text');
							  break;

			case 'post'   :   $curr_post = Aepro::$_helper->get_demo_post_data();
							  if(isset($curr_post) && isset($curr_post->ID)){
								  $link_text = get_the_title($curr_post->ID);
							  }
							  break;

			case 'custom_field' : $custom_field = $this->get_instance_value('link_cf');

									  if($custom_field != ''){

										 $field_args['field_name'] = $custom_field;
										 $link_text = PodsMaster::instance()->get_field_value( $field_args );
									  }
									  break;

		}


		$this->parent->add_render_attribute('wrapper', 'class', 'ae-acf-wrapper');

		if($url == '' || is_null($url)){
		    $this->parent->add_render_attribute('wrapper', 'class', 'ae-hide');
        }
		?>

        <div <?php echo $this->parent->get_render_attribute_string('wrapper'); ?>>
            <a <?php echo $this->parent->get_render_attribute_string('anchor'); ?>><?php echo $link_text; ?></a>
        </div>

		<?php

	}

	function register_style_controls(){

		$this->start_controls_section(
			'general-style',
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
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->start_controls_tabs('style');

		$this->start_controls_tab(
			'normal_style',
			[
				'label' => __('Normal')
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
					'{{WRAPPER}} a' => 'color:{{VALUE}}'
				]
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __('Background Color'),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'background:{{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'ae-pro' ),
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'ae-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a   ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Item Shadow', 'ae-pro' ),
				'selector' => '{{WRAPPER}} span',
			]
		);



		$this->end_controls_tab();


		$this->start_controls_tab(
			'hover_style',
			[
				'label' => __('Hover')
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
					'{{WRAPPER}} a:hover' => 'color:{{VALUE}}'
				]
			]
		);

		$this->add_control(
			'bg_color_hover',
			[
				'label' => __('Background Color'),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'background:{{VALUE}}'
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
					'{{WRAPPER}} a:hover' => 'border-color:{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __('Border Radius', 'ae-pro'),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_box_shadow',
				'label' => __( 'Item Shadow', 'ae-pro' ),
				'selector' => '{{WRAPPER}} a:hover',
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
					'{{WRAPPER}} a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __('Margin', 'ae-pro'),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->end_controls_section();
	}




}
