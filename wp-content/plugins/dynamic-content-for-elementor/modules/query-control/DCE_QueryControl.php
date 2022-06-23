<?php
namespace DynamicContentForElementor\Modules\QueryControl;

use Elementor\Core\Base\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DCE_QueryControl extends Module {

	/**
	 * Module constructor.
	 *
	 * @since 1.6.0
	 * @param array $args
	 */
	public function __construct() {
		//parent::__construct();
		$this->add_actions();
	}

	/**
	 * Get Name
	 * 
	 * Get the name of the module
	 *
	 * @since  1.6.0
	 * @return string
	 */
	public function get_name() {
		return 'dce-query-control';
	}

	/**
	 * Add Actions
	 * 
	 * Registeres actions to Elementor hooks
	 *
	 * @since  1.6.0
	 * @return void
	 */
	protected function add_actions() {
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	public function ajax_call_filter_autocomplete( array $data ) {

		if ( empty( $data['query_type'] ) || empty( $data['q'] ) ) {
			throw new \Exception( 'Bad Request' );
		}

		$results = call_user_func( [ $this, 'get_' . $data['query_type'] ], $data );

		return [
			'results' => $results,
		];
	}
        
        protected function get_options( $data ) {
		$results = [];
                $fields = \DynamicContentForElementor\DCE_Helper::get_options($data['q']);
                if (!empty($fields)) {
                    foreach ( $fields as $field_key => $field_name ) {
                        $results[] = [
                            'id' 	=> $field_key,
                            'text' 	=> $field_name,
                        ];
                    }
                }
		return $results;
	}
        
	protected function get_fields( $data ) {
		$results = [];
                if ($data['object_type'] == 'any') {
                    $object_types = array('post', 'user', 'term');
                } else {
                    $object_types = array($data['object_type']);
                }
                foreach ($object_types as $object_type) {
                    $function = 'get_'.$object_type.'_fields';
                    $fields = \DynamicContentForElementor\DCE_Helper::{$function}($data['q']);
                    if (!empty($fields)) {
                        foreach ( $fields as $field_key => $field_name ) {
                            $results[] = [
                                'id' 	=> $field_key,
                                'text' 	=> ($data['object_type'] == 'any' ? '['.$object_type.'] ' : '').$field_name,
                            ];
                        }
                    }
                }
		return $results;
	}
        
        protected function get_terms_fields( $data ) {
		$results = [];
                $results = $this->get_fields($data);
                $terms = \DynamicContentForElementor\DCE_Helper::get_taxonomy_terms(null, true, $data['q']);
                if (!empty($terms)) {
                    foreach ( $terms as $field_key => $field_name ) {
                        $term = \DynamicContentForElementor\DCE_Helper::get_term_by('id', $field_key);
                        $field_key = 'term_'.$term->slug;
                        $results[] = [
                            'id' 	=> $field_key,
                            'text' 	=> ($data['object_type'] == 'any' ? '[taxonomy_term] ' : '').$field_name,
                        ];
                    }
                }
                //var_dump($results); die();
		return $results;
	}
        
        protected function get_taxonomies_fields( $data ) {
		$results = [];
                $results = $this->get_fields($data);
                $taxonomies = \DynamicContentForElementor\DCE_Helper::get_taxonomies(false, null, $data['q']);
                if (!empty($taxonomies)) {
                    foreach ( $taxonomies as $field_key => $field_name ) {
                        if ($field_key) {
                            $field_key = 'taxonomy_'.$field_key;
                            $results[] = [
                                'id' 	=> $field_key,
                                'text' 	=> '[taxonomy] ' . $field_name,
                            ];
                        }
                    }
                }
                //var_dump($results); die();
		return $results;
	}
        
        protected function get_metas( $data ) {
		$results = [];
                $function = 'get_'.$data['object_type'].'_metas';
                $fields = \DynamicContentForElementor\DCE_Helper::{$function}(false, $data['q']);
		foreach ( $fields as $field_key => $field_name ) {
                    if ($field_key) {
                        $results[] = [
                            'id' 	=> $field_key,
                            'text' 	=> $field_name,
                        ];
                    }
		}
		return $results;
	}

	protected function get_posts( $data ) {
		$results = [];
                $object_type = (!empty($data['object_type'])) ? $data['object_type'] : 'any';
		$query_params = [
			'post_type' 	 => $object_type,
			's' 		 => $data['q'],
			'posts_per_page' => -1,
		];
		if ( 'attachment' === $query_params['post_type'] ) {
			$query_params['post_status'] = 'inherit';
		}
                //$query = new \DynamicContentForElementor\DCE_Query( $query_params );
		$query = new \WP_Query( $query_params );
		foreach ( $query->posts as $post ) {
                    $post_title = $post->post_title;
                    if (empty($data['object_type']) || $object_type == 'any') {
                        $post_title = '['.$post->ID.'] '.$post_title.' ('.$post->post_type.')';
                    }    
                    if (!empty($data['object_type']) && $object_type == 'elementor_library') {
                        $etype = get_post_meta($post->ID, '_elementor_template_type', true);
                        $post_title = '['.$post->ID.'] '.$post_title.' ('.$post->post_type.' > '.$etype.')';
                    }    
                    
                    $results[] = [
                            'id' 	=> $post->ID,
                            'text' 	=> $post_title,
                    ];
		}
		return $results;
	}

	protected function get_terms( $data ) {
		$results = [];
                $taxonomies = (!empty($data['object_type'])) ? $data['object_type'] : get_object_taxonomies('');
		$query_params = [
			'taxonomy' 	=> $taxonomies,
			'search' 	=> $data['q'],
			'hide_empty' 	=> false,
		];
		$terms = get_terms( $query_params );
		foreach ( $terms as $term ) {
			$term_name = $term->name;
                        if (empty($data['object_type'])) {
                            $taxonomy = get_taxonomy( $term->taxonomy );
                            $term_name = $taxonomy->labels->singular_name.': '.$term_name;
                        }
			$results[] = [
				'id' 	=> $term->term_id,
				'text' 	=> $term_name,
			];
		}
		return $results;
	}

	protected function get_users( $data ) {
		$results = [];
                $query_params = [
			'search' => '*'.$data['q'].'*'
		];
                if (empty($data['object_type'])) {
                    $query_params['role__in'] = \DynamicContentForElementor\DCE_Helper::str_to_array(',', $data['object_type']);
                }
		$users = get_users( $query_params ); // Array of WP_User objects
		foreach ( $users as $user ) {
			$results[] = [
				'id' 	=> $user->ID,
				'text' 	=> $user->display_name,
			];
		}
		return $results;
	}
        
        protected function get_authors( $data ) {
		$results = [];
		$query_params = [
			'who' 					=> 'authors',
			'has_published_posts' 	=> true,
			'fields' 				=> [
				'ID',
				'display_name',
			],
			'search' 				=> '*' . $data['q'] . '*',
			'search_columns' 		=> [
				'user_login',
				'user_nicename',
			],
		];
		$user_query = new \WP_User_Query( $query_params );
		foreach ( $user_query->get_results() as $author ) {
			$results[] = [
				'id' 	=> $author->ID,
				'text' 	=> $author->display_name,
			];
		}
		return $results;
	}

	/**
	 * Calls function to get value titles depending on ajax query type
	 *
	 * @since  1.6.0
	 * @return array
	 */
	public function ajax_call_control_value_titles( $request ) {
		$results = call_user_func( [ $this, 'get_value_titles_for_' . $request['query_type'] ], $request );
		return $results;
	}

        protected function get_value_titles_for_metas( $request ) {
		$ids = (array) $request['id'];
		$results = [];
                $function = 'get_'.$request['object_type'].'_metas';
                foreach ($ids as $aid) {
                    $fields = \DynamicContentForElementor\DCE_Helper::{$function}(false, $aid);
                    foreach ( $fields as $field_key => $field_name ) {
                        if (in_array($field_key, $ids)) {
                            $results[$field_key] = $field_name;
                        }
                    }
                }
		return $results;
	}
        
        protected function get_value_titles_for_fields( $request ) {
		$ids = (array) $request['id'];
		$results = [];
                if ($request['object_type'] == 'any') {
                    $object_types = array('post', 'user', 'term');
                } else {
                    $object_types = array($request['object_type']);
                }
                foreach ($object_types as $object_type) {
                $function = 'get_'.$object_type.'_fields';
                    foreach ($ids as $aid) {
                        $fields = \DynamicContentForElementor\DCE_Helper::{$function}($aid);
                        if (!empty($fields)) {
                            foreach ( $fields as $field_key => $field_name ) {
                                if (in_array($field_key, $ids)) {
                                    $results[$field_key] = $field_name;
                                }
                            }
                        }
                    }
                }
		return $results;
	}
        
	protected function get_value_titles_for_posts( $request ) {
		$ids = (array) $request['id'];
		$results = [];
		$query = new \DynamicContentForElementor\DCE_Query( [
			'post_type' 		=> 'any',
			'post__in' 		=> $ids,
			'posts_per_page' 	=> -1,
		] );
		foreach ( $query->posts as $post ) {
			$results[ $post->ID ] = $post->post_title;
		}
                //var_dump($query->posts); die();
		return $results;
	}

        protected function get_value_titles_for_terms( $request ) {
		$ids = (array) $request['id'];
		$results = [];
                $tid = reset($ids);
                if (is_numeric($tid)) {
                    $query_params = [
                        'include' => $ids,
                    ];
                } else {
                    $query_params = [
                        'slug' => $ids,
                    ];
                }
                //var_dump($ids); die();
		$terms = get_terms( $query_params );
		foreach ( $terms as $term ) {
			$results[ $term->term_id ] = $term->name;
		}
		return $results;
	}
        
	protected function get_value_titles_for_taxonomies( $request ) {
            $ids = (array) $request['id'];
            $results = [];
            foreach ($ids as $value) {
                $taxonomies = \DynamicContentForElementor\DCE_Helper::get_taxonomies(false, null, $value);
                if (!empty($taxonomies)) {
                    foreach ( $taxonomies as $field_key => $field_name ) {
                        if ($field_key) {
                            $results[ $field_key ] = $field_name;
                        }
                    }
                }
            }
            return $results;
	}

        protected function get_value_titles_for_users( $request ) {
            $ids = (array) $request['id'];
		$results = [];
		$query_params = [			
			'fields' 				=> [
                            'ID',
                            'display_name',
			],
			'include' 				=> $ids,
		];
		$user_query = new \WP_User_Query( $query_params );
		foreach ( $user_query->get_results() as $user ) {
			$results[ $user->ID ] = $user->display_name;
		}
		return $results;
        }
        
	protected function get_value_titles_for_authors( $request ) {
		$ids = (array) $request['id'];
		$results = [];
		$query_params = [
			'who' 					=> 'authors',
			'has_published_posts' 	=> true,
			'fields' 				=> [
				'ID',
				'display_name',
			],
			'include' 				=> $ids,
		];
		$user_query = new \WP_User_Query( $query_params );
		foreach ( $user_query->get_results() as $author ) {
			$results[ $author->ID ] = $author->display_name;
		}
		return $results;
	}
        
        
        protected function get_value_titles_for_terms_fields( $request ) {
		$ids = (array) $request['id'];
                $ids_post = array();
                $ids_term = array();
                foreach($ids as $aid) {
                    if (substr($aid, 0, 5) == 'term_') {
                        $ids_term[] = substr($aid,5);
                    } else {
                        $ids_post[] = $aid;
                    }
                }
                $results = [];
                if (!empty($ids_post)) {
                    $request['id'] = $ids_post;
                    $posts = $this->get_value_titles_for_fields($request);
                    if (!empty($posts)) {
                        foreach ($posts as $key => $value) {
                            $results[$key] = $value;
                        }
                    }
                }
                if (!empty($ids_term)) {
                    $request['id'] = $ids_term;
                    $terms = $this->get_value_titles_for_terms($request);
                    if (!empty($terms)) {
                        foreach ($terms as $key => $value) {
                            $results['term_'.$key] = $value;
                        }
                    }
                }
                //$results = $this->get_value_titles_for_fields($request) + $this->get_value_titles_for_terms($request);
		return $results;
	}
        
        protected function get_value_titles_for_taxonomies_fields( $request ) {
		$ids = (array) $request['id'];
                $ids_post = array();
                $ids_tax = array();
                foreach($ids as $aid) {
                    if (substr($aid, 0, 9) == 'taxonomy_') {
                        $ids_tax[] = substr($aid,9);
                    } else {
                        $ids_post[] = $aid;
                    }
                }
                $results = [];
                if (!empty($ids_post)) {
                    $request['id'] = $ids_post;
                    $posts = $this->get_value_titles_for_fields($request);
                    if (!empty($posts)) {
                        foreach ($posts as $key => $value) {
                            $results[$key] = $value;
                        }
                    }
                }
                if (!empty($ids_tax)) {
                    $request['id'] = $ids_tax;
                    $taxonomies = $this->get_value_titles_for_taxonomies($request);
                    if (!empty($taxonomies)) {
                        foreach ($taxonomies as $key => $value) {
                            $results['taxonomy_'.$key] = $value;
                        }
                    }
                }
                //$results = $this->get_value_titles_for_fields($request) + $this->get_value_titles_for_terms($request);
		return $results;
	}
        

	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'dce_query_control_value_titles', [ $this, 'ajax_call_control_value_titles' ] );
		$ajax_manager->register_ajax_action( 'dce_query_control_filter_autocomplete', [ $this, 'ajax_call_filter_autocomplete' ] );
	}
}
