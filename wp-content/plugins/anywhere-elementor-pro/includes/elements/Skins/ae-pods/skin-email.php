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



class Skin_Email extends Skin_Website {

	public function get_id() {
		return 'email';
	}

	public function get_title() {
		return __( 'Email', 'ae-pro' );
	}

	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action('elementor/element/ae-pods/general/after_section_end', [$this, 'register_style_controls']);
	}

	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

		$this->add_control(
			'links_to',
			[
				'label' => __('Links To', 'ae-pro'),
				'type'  => Controls_Manager::SELECT,
				'options' => [
				    'email'         => __('Email', 'ae-pro'),
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
				'default' => __('Email Now', 'ae-pro'),
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
			'enable_subject',
			[
				'label' => __('Add Subject', 'ae-pro'),
				'type'  => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'ae-pro' ),
				'label_on' => __( 'Yes', 'ae-pro' ),
				'default' => __('label_off', 'ae-pro'),
                'return_value' => 'yes'
			]
		);

		$this->add_control(
			'subject_source',
			[
				'label' => __('Links To', 'ae-pro'),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					'static'        => __('Static Text', 'ae-pro'),
					'dynamic_text'  => __('Custom Field', 'ae-pro'),
				],
				'default'   => 'static',
                'condition' => [
                        $this->get_control_id('enable_subject') => 'yes'
                ]
			]
		);

		$this->add_control(
		  'subject_static',
          [
               'label' => __('Subject', 'ae-pro'),
               'type'  => Controls_Manager::TEXTAREA,
               'condition' => [
	               $this->get_control_id('enable_subject') => 'yes',
	               $this->get_control_id('subject_source') => 'static',
               ]
          ]
        );

		$this->add_control(
			'subject_dynamic',
			[
				'label' => __('Custom Field', 'ae-pro'),
				'type'  => Controls_Manager::TEXT,
				'condition' => [
					$this->get_control_id('enable_subject') => 'yes',
					$this->get_control_id('subject_source') => 'dynamic_text',
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

		];

        if ($settings['pods_option_name'] != ''){
            $field_args['pods_option_name'] = $settings['pods_option_name'];
        }

		$email = PodsMaster::instance()->get_field_value( $field_args );

		// Get subject -> Dynamic or Static
		$subject = $this->get_subject( $field_args );

		$url = $this->get_mailto_href( $email, $subject);

		$this->parent->add_render_attribute('anchor', 'href', $url);

		// Get Link Text
		$links_to = $this->get_instance_value('links_to');

		switch($links_to){

            case 'email'  :   $link_text = $email;
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
										 $link_text = PodsMaster::instance()->get_field_value( $field_args );
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

	function get_mailto_href($email, $subject){

	    $parts = [];
	    $href = 'mailto:'.$email;

	    if($subject != ''){
		    $parts['subject'] = 'subject='.$subject;
        }

	    if(count($parts)){
	        $href = $href . '?' . implode('&', $parts);
        }

		return $href;
    }

    function get_subject( $field_args ){

	    $subject = '';

	    $enable_subject = $this->get_instance_value('enable_subject');

	    if($enable_subject){

	        // subject source
            $subject_source = $this->get_instance_value('subject_source');
            if($subject_source == 'static'){

                $subject = $this->get_instance_value('subject_static');

            }elseif($subject_source == 'dynamic_text'){

	            $field_args['field_name'] = $this->get_instance_value('subject_dynamic');

                $subject = PodsMaster::instance()->get_field_value( $field_args );

            }
        }

	    return $subject;

    }




}
