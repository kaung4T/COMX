<?php

namespace Aepro\Ae_ACF\Skins;

use Aepro\Aepro;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Aepro\Classes\AcfMaster;
use Elementor\Group_Control_Typography;



class Skin_File extends Skin_Url{

	public function get_id() {
		return 'file';
	}

	public function get_title() {
		return __( 'File', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-acf/general/after_section_end', [$this, 'register_style_controls']);
	}

	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

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
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} a' => 'display: inline-block',
				]
			]
		);



	}

	public function render() {

		$settings = $this->parent->get_settings_for_display();
		$link_text = '';

		$field_args  =  [
			'field_name'    => $settings['field_name'],
			'field_type'    => $settings['field_type'],
			'is_sub_field'    => $settings['is_sub_field'],

		];

		if($settings['is_sub_field'] == 'repeater'){
			$field_args['parent_field'] = $settings['parent_field'];
		}

		$file = AcfMaster::instance()->get_field_value( $field_args );

		$file_data = $this->get_file_data($file);

		$this->parent->add_render_attribute('anchor', 'href', $file_data['url']);

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

            case 'title'  :   $link_text = $file_data['title'];
                              break;

            case 'caption' :  $link_text = $file_data['caption'];
	                          break;

            case 'filename' : $link_text = $file_data['filename'];
                              break;

			case 'static' :   $link_text = $this->get_instance_value('static_text');
							  break;

			case 'post'   :   $curr_post = Aepro::$_helper->get_demo_post_data();
							  if(isset($curr_post) && isset($curr_post->ID)){
								  $link_text = get_the_title($curr_post->ID);
							  }
							  break;

			case 'dynamic_text' : $custom_field = $this->get_instance_value('custom_field_text');

									  if($custom_field != ''){

										 $field_args['field_name'] = $custom_field;
										 $link_text = AcfMaster::instance()->get_field_value( $field_args );
									  }
									  break;

		}


		?>

		<a <?php echo $this->parent->get_render_attribute_string('anchor'); ?>><?php echo $link_text; ?></a>
		<?php

	}

	function register_style_controls(){

		parent::register_style_controls();


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
