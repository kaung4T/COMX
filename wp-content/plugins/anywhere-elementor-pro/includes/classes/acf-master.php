<?php

namespace Aepro\Classes;

use Aepro\Aepro;

class AcfMaster{


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

							if($field_args['is_sub_field'] == 'repeater'){
								$field_value = $this->get_repeater_field_data( $field_args['field_name'], $field_args['parent_field'], $post->ID);
							}else{
								$field_value = get_field( $field_args['field_name'], $post->ID, true );
							}

				break;

			case 'term' :	$term   =   Aepro::$_helper->get_preview_term_data();
							$field_value = get_field( $field_args['field_name'], $term['taxonomy'].'_'.$term['prev_term_id'], false);
				break;

			case 'user' :   // Get current author of current archive using queries object
                            $author = Aepro::$_helper->get_preview_author_data();
                            $field_value = get_field( $field_args['field_name'], 'user_' . $author['prev_author_id'], true);

				break;

            case 'option' : // Get Option page's field value
                            $field_value = get_field( $field_args['field_name'], 'option', true);

                break;


		}



		return $field_value;
	}

	public function get_repeater_field_data( $field_name, $repeater_field, $data_id ){



		$repeater = Aepro::$_helper->is_repeater_block_layout();

		if(isset($repeater['field'])){
			// editing a block layout. Return first item matched

			$repeater_fields_arr = explode('.', $repeater_field);
			$main_field = get_field($repeater_fields_arr[0], $data_id);
			$leaf = $main_field;

			foreach($repeater_fields_arr as $rf){

				if($rf == $repeater_fields_arr[0]){
					continue;
				}

				if(isset($leaf[0][$rf])){
					$leaf = $main_field[0][$rf];
				}else{
					break;
				}
			}

			$value = $leaf[0][$field_name];

		}else{
			// fetch data using get_sub_field.
			$repeater_fields_arr = explode('.', $repeater_field);

			if(count($repeater_fields_arr) == 1){
				return get_sub_field($field_name);
			}else{
				// Todo:: Nested Repeater Fields
			}

		}

		return $value;
	}

	protected function get_sub_field_data(){



	}

	function get_field_object($field_args, $data){

		switch($field_args['field_type']){
			case 'post' :   $field_object = get_field_object( $field_args['field_name'], $data);
							break;

			case 'term' :   $term = get_term_by('term_taxonomy_id', $data['prev_term_id']);
							$field_object = get_field_object( $field_args['field_name'], $term );
							break;
            case 'option' : $field_object = get_field_object( $field_args['field_name'], $data);
                            break;
            case 'user' :   $field_object = get_field_object( $field_args['field_name'], $data);
                break;
		}

		return $field_object;
	}

}

AcfMaster::instance();