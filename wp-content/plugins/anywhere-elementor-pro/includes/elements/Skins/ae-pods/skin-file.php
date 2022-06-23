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



class Skin_File extends Skin_Website {

    public function get_id() {
        return 'file';
    }

    public function get_title() {
        return __( 'File - Download', 'ae-pro' );
    }

    protected function _register_controls_actions() {

        parent::_register_controls_actions();
        add_action('elementor/element/ae-pods/general/after_section_end', [$this, 'register_style_controls']);
    }

    public function register_controls( Widget_Base $widget){

        $this->parent = $widget;

        parent::register_links_controls();

        $this->update_control(
            'links_to',
            [
                'options' => [
                    'title'         => __('Title', 'ae-pro'),
                    'caption'       => __('Caption', 'ae-pro'),
                    'filename'      => __('File Name', 'ae-pro'),
                    'static'        => __('Static Text', 'ae-pro'),
                    'post'          => __('Post Title', 'ae-pro' ),
                    'custom_field'  => __('Custom Field', 'ae-pro'),
                ],
                'default'   => 'static'
            ]
        );

		$this->update_responsive_control(
			'text_align',
			[
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

		$file_array = PodsMaster::instance()->get_field_object( $field_args );

		if(isset($file_array) && !empty($file_array)) {
            $field_options = PodsMaster::instance()->get_field_options( $field_args );
            if ($field_options['file_format_type'] == 'single') {
                $files[0] = $file_array;
            } else {
                $files = $file_array;
            }
        }
        if(isset($files)) {

            ?>
            <ul>
                <?php
                foreach ($files as $file) {
                    $file_data = $this->get_file_data($file['ID']);

                    $this->parent->set_render_attribute('anchor', 'href', $file_data['url']);

                    $new_tab = $this->get_instance_value('new_tab');
                    if ($new_tab == 1) {
                        $this->parent->set_render_attribute('anchor', 'target', '_blank');
                    }

                    $no_follow = $this->get_instance_value('nofollow');
                    if ($no_follow == 1) {
                        $this->parent->set_render_attribute('anchor', 'rel', 'nofollow');
                    }

                    $download_on_click = $this->get_instance_value('download');
                    if ($download_on_click == 1) {
                        $this->parent->set_render_attribute('anchor', 'download', 'download');
                    }

                    // Get Link Text
                    $links_to = $this->get_instance_value('links_to');

                    switch ($links_to) {

                        case 'title'  :
                            $link_text = $file_data['title'];
                            break;

                        case 'caption' :
                            $link_text = $file_data['caption'];
                            break;

                        case 'filename' :
                            $link_text = $file_data['filename'];
                            break;

                        case 'static' :
                            $link_text = $this->get_instance_value('static_text');
                            break;

                        case 'post'   :
                            $curr_post = Aepro::$_helper->get_demo_post_data();
                            if (isset($curr_post) && isset($curr_post->ID)) {
                                $link_text = get_the_title($curr_post->ID);
                            }
                            break;

                        case 'custom_field' :
                            $custom_field = $this->get_instance_value('link_cf');
                            if ($custom_field != '') {

                                $field_args['field_name'] = $custom_field;
                                $link_text = PodsMaster::instance()->get_field_value($field_args);
                            }
                            break;

                    }
                    ?>
                    <li>
                        <a <?php echo $this->parent->get_render_attribute_string('anchor'); ?>><?php echo $link_text; ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }

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
