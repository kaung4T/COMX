<?php

namespace Aepro\Ae_ACF\Skins;

use Aepro\Aepro;
use Aepro\Aepro_ACF;
use Aepro\Classes\AcfMaster;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class Skin_Text extends Skin_Base{

	public function get_id() {
		return 'text';
	}

	public function get_title() {
		return __( 'Text', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-acf/general/after_section_end', [$this, 'register_style_controls']);

	}

	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

		parent::register_text_controls();
	}

	public function render() {

		$settings = $this->parent->get_settings();
		$post     = Aepro::$_helper->get_demo_post_data();

		$field_args  =  [
							'field_name'    => $settings['field_name'],
							'field_type'    => $settings['field_type'],
							'is_sub_field'    => $settings['is_sub_field'],

						];

		if($settings['is_sub_field'] == 'repeater'){
			$field_args['parent_field'] = $settings['parent_field'];
		}

		$title_raw = AcfMaster::instance()->get_field_value( $field_args );

		$placeholder    = $this->get_instance_value('placeholder');
		$before_text    = $this->get_instance_value('prefix');
		$after_text     = $this->get_instance_value('suffix');
		$links_to       = $this->get_instance_value('links_to');
		$link_new_tab   = $this->get_instance_value('link_new_tab');
		$link           = '';

		if($title_raw == '' && $placeholder == ''){
			return;
		}elseif($title_raw == '' & $placeholder != ''){
			$title = $placeholder;
		}else{
			$title = '<span class="ae-prefix">'.$before_text.'</span>'.$title_raw.'<span class="ae-suffix">'.$after_text.'</span>';
		}

		// Process Content

		$title = $this->process_content( $title );

		if($links_to != ''){

			switch ($links_to){

				case 'post'     :   $link = get_permalink($post->ID);
							        break;

				case 'static'   :   $link = $this->get_instance_value('link_url');
									break;

				case 'custom_field' :   $link_cf = $this->get_instance_value('link_cf');
				                        $field_args['field_name'] = $link_cf;
										$link = AcfMaster::instance()->get_field_value( $field_args );

										break;

			}
		}


        $this->parent->add_render_attribute('wrapper-class', 'class', 'ae-acf-wrapper');
		$this->parent->add_render_attribute('title-class', 'class', 'ae-acf-content-wrapper');

		$html_tag = $this->get_instance_value('html_tag');





		if($link != ''){

			$this->parent->add_render_attribute('anchor', 'title', $title_raw);
			$this->parent->add_render_attribute('anchor', 'href', $link);
			if($link_new_tab == 'yes'){
				$this->parent->add_render_attribute('anchor', 'target', '_blank');
			}

			$title_html =  '<a '.$this->parent->get_render_attribute_string('anchor').'>'.$title.'</a>';
		}else{

			$title_html = $title;
		}

		$html = sprintf('<%1$s itemprop="name" %2$s>%3$s</%1$s>',$html_tag,$this->parent->get_render_attribute_string('title-class'),$title_html);
		if($title == ""){
            $this->parent->add_render_attribute('wrapper-class', 'class', 'ae-hide');
        }
        ?>
        <div <?php echo $this->parent->get_render_attribute_string('wrapper-class'); ?>>
		<?php
		echo $html;
		?>
        </div>
        <?php
	}


	function process_content( $content ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-text.php */
		$content = apply_filters( 'widget_text', $content, $this->parent->get_settings() );

		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = wptexturize( $content );

		if ( $GLOBALS['wp_embed'] instanceof \WP_Embed ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
		}

		return $content;
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
				'selector' => '{{WRAPPER}} .ae-acf-content-wrapper, {{WRAPPER}} .ae-acf-content-wrapper a',
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
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper, {{WRAPPER}} .ae-acf-content-wrapper a' => 'color:{{VALUE}}'
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
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						],
						'selectors' => [
							'{{WRAPPER}} .ae-acf-content-wrapper:hover, {{WRAPPER}} .ae-acf-content-wrapper:hover a' => 'color:{{VALUE}}'
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
						'scheme' => [
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

				]
			);

		$this->end_controls_section();

	}


}
