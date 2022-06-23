<?php

namespace Aepro\Classes;

use Aepro\Aepro;
use function pods;

class PodsMaster{


	private static $_instance = null;


	protected $post_id;

	protected $field_name;

	protected $field_list;

	protected $field_types;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function set_field_types(){

		$acf_free = [

			'text'      => 'Text',
			'textarea'  => 'Text Area',
			'number'    => 'Text Area',
			'range'     => 'Text Area',
			'email'     => 'Text Area',
			'url'       => 'Text Area',
			'password'  => 'Text Area',
			'image'     => 'Text Area',
			'file'      => 'Text Area',
			'wysiwyg'   => 'Text Area',
			'oembed'    => 'Text Area',
			'gallery'   => 'Text Area',

		];


		$this->field_types = $acf_free;

	}

	/**
	 * @param $data
	 * @param $field_name
	 * @param $field_type
	 *
	 * $data -
	 * $field_name - Key for ACF Field
	 * $field_type - term, post, option, user
	 *
	 * @return mixed|string
	 */
	public function get_field_value( $field_args ){

		$field_value = '';

		switch ($field_args['field_type']){

			case 'post' :   $post   =  Aepro::$_helper->get_demo_post_data();
                            $post_type = get_post_type($post->ID);
                            $pods = pods($post_type, $post->ID);
                            $field_value = $pods->field($field_args['field_name'], true);

				break;

			case 'term' :	$term   =   Aepro::$_helper->get_preview_term_data();
                            $pods = pods($term['taxonomy'], $term['prev_term_id']);
                            $field_value = $pods->field($field_args['field_name']);
				break;

			case 'user' :   // Get current author of current archive using queries object
                            $author = Aepro::$_helper->get_preview_author_data();
                            $pods = pods('user', $author['prev_author_id']);
                            $field_value = $pods->field($field_args['field_name']);
				break;

            case 'option' : // Get Option page's field value
                            $pods = pods( $field_args['pods_option_name'] );
                            $field_value = $pods->field($field_args['field_name']);

                break;


		}



		return $field_value;
	}

    function get_field_object_select_field($field_args, $data){

	    $field_object = array();
        switch($field_args['field_type']){
            case 'post' : $post_type = get_post_type($data);
                $pods = pods($post_type, $data );

                $field_content = $pods->fields($field_args['field_name']);
                if(isset($field_content)) {
                    $field_object = $this->delimited_string_to_array($field_content['options']['pick_custom']);
                }
                break;

            case 'term' :   $term = get_term_by('term_taxonomy_id', $data['prev_term_id']);
                $pods = pods( $term->taxonomy, $data['prev_term_id'] );
                $field_content = $pods->fields($field_args['field_name']);
                if(isset($field_content)) {
                    $field_object = $this->delimited_string_to_array($field_content['options']['pick_custom']);
                }
                break;
            case 'option' :
                $pods = pods( $data );
                $field_content = $pods->fields($field_args['field_name']);
                if(isset($field_content)) {
                    $field_object = $this->delimited_string_to_array($field_content['options']['pick_custom']);
                }

                break;
            case 'user' :
                $author = Aepro::$_helper->get_preview_author_data();
                $pods = pods('user', $author['prev_author_id']);
                $field_content = $pods->fields($field_args['field_name']);
                if(isset($field_content)) {
                    $field_object = $this->delimited_string_to_array($field_content['options']['pick_custom']);
                }
                break;
        }

        return $field_object;
    }

	function get_field_object($field_args){

		switch($field_args['field_type']){
			case 'post' :   $post = Aepro::$_helper->get_demo_post_data();
			                $post_type = get_post_type($post->ID);
			                $pods = pods($post_type, $post->ID );
                            $field_object = $pods->field($field_args['field_name']);
							break;

			case 'term' :   $term_data = Aepro::$_helper->get_preview_term_data();
			                $term = get_term_by('term_taxonomy_id', $term_data['prev_term_id']);
                            $pods = pods( $term->taxonomy, $term_data['prev_term_id'] );
							$field_object = $pods->field( $field_args['field_name'] );
							break;
            case 'option' :

                            $pods = pods( $field_args['pods_option_name'] );
                            $field_object = $pods->field($field_args['field_name']);
                            break;
            case 'user' :
                            $author = Aepro::$_helper->get_preview_author_data();
                            $pods = pods('user', $author['prev_author_id']);
                            $field_object = $pods->field($field_args['field_name']);
                break;
		}

		return $field_object;
	}

	function get_field_options($field_args){
        $field_options = '';

        switch ($field_args['field_type']){

            case 'post' :   $post   =  Aepro::$_helper->get_demo_post_data();
                $post_type = get_post_type($post->ID);
                $pods = pods($post_type, $post->ID);
                $field_options = $pods->fields($field_args['field_name'])['options'];
                break;

            case 'term' :	$term   =   Aepro::$_helper->get_preview_term_data();
                $pods = pods($term['taxonomy'], $term['prev_term_id']);
                $field_options = $pods->fields($field_args['field_name'])['options'];
                break;

            case 'user' :   // Get current author of current archive using queries object
                $author = Aepro::$_helper->get_preview_author_data();
                $pods = pods('user', $author['prev_author_id']);
                $field_options = $pods->fields($field_args['field_name'])['options'];

                break;

            case 'option' : // Get Option page's field value
                $pods = pods( 'pods_options');
                $field_options = $pods->fields($field_args['field_name'])['options'];

                break;


        }



        return $field_options;
    }

    function delimited_string_to_array($choices){
        $final_arr = array();

        $delimiter = array("\n", "\r\n", "\r");
        $converted_string = str_replace($delimiter, ', ', $choices);
        $converted_arr = explode(', ',  $converted_string);
        foreach ($converted_arr as $ca) {
            $item = explode('|', $ca);
            $final_arr[$item[0]] = $item[1];
        }

        return $final_arr;
    }

}

PodsMaster::instance();