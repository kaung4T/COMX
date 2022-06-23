<?php

namespace Aepro;

use Aepro\Aepro_Post_List;
use Elementor\Plugin;

class Post_Helper{
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('wp_ajax_ae_post_data',[ $this, 'ajax_post_data']);
        add_action('wp_ajax_nopriv_ae_post_data',[ $this, 'ajax_post_data']);


        add_action('wp_ajax_ae_portfolio_data',[ $this, 'ajax_portfolio_data']);
        add_action('wp_ajax_nopriv_ae_portfolio_data',[ $this, 'ajax_portfolio_data']);

    }

    public function ajax_post_data(){
        $fetch_mode = $_REQUEST['fetch_mode'];

        $results = [];
        switch($fetch_mode){
            case 'posts' :  $params = $query_params = [
                                            's'         => $_REQUEST['q'],
                                        ];
                            $query = new \WP_Query( $params );

                            foreach ( $query->posts as $post ) {
                                $results[] = [
                                    'id'   => $post->ID,
                                    'text' => $post->post_title,
                                ];
                            }
                            break;

            case 'paged' : //print_r($_POST);
                           ob_start();
                           $this->get_widget_output($_POST['pid'],$_POST['wid']);
                           $results = ob_get_contents();
                           ob_end_clean();
                           break;

            case 'selected_posts' : $args = array(
                                                'post__in' => $_POST['selected_posts'],
                                                'post_type' => 'any',
                                                'orderby' => 'post__in'
                                            );
                                    $posts = get_posts($args);
                                    if(count($posts)){
                                        foreach($posts as $p){
                                            $results[] = [
                                                'id'    => $p->ID,
                                                'text'  => $p->post_title
                                            ];
                                        }
                                    }
                                    break;

        }

        wp_send_json_success( $results );
    }

    function get_widget_output($post_id,$widget_id){
        $elementor = Plugin::$instance;


        $meta = Plugin::instance()->db->get_plain_editor( $post_id );
        $widget = $this->find_element_recursive( $meta, $widget_id );
        //echo '<pre>'; print_r($widget); echo '</pre>';

        $widget_instance = $elementor->elements_manager->create_element_instance( $widget );
        $widget['settings'] = $widget_instance->get_active_settings();


        if(isset($widget['settings'])){

        	if($widget['widgetType'] == 'ae-post-blocks'){
		        require_once AE_PRO_PATH . 'includes/elements/post-blocks.php';
		        $post_list  = new Aepro_Post_Blocks();
	        }elseif($widget['widgetType'] == 'ae-portfolio'){
		        require_once AE_PRO_PATH . 'includes/elements/portfolio.php';
		        $post_list  = new Aepro_Portfolio();
	        }

	        $post_list->generate_output($widget['settings'],false);
        }
    }

    private function find_element_recursive( $elements, $widget_id ) {
        foreach ( $elements as $element ) {
            if ( $widget_id === $element['id'] ) {
                return $element;
            }

            if ( ! empty( $element['elements'] ) ) {
                $element = $this->find_element_recursive( $element['elements'], $widget_id );

                if ( $element ) {
                    return $element;
                }
            }
        }

        return false;
    }

    public function get_authors(){
        $user_query = new \WP_User_Query(
            [
                'who' => 'authors',
                'has_published_posts' => true,
                'fields' => [
                    'ID',
                    'display_name',
                ],
            ]
        );

        $authors = [];

        foreach ( $user_query->get_results() as $result ) {
            $authors[ $result->ID ] = $result->display_name;
        }

        return $authors;
    }

    public function get_taxonomy_terms($taxonomy){

        $tax_array = [];
        $terms = get_terms([
            'taxonomy'  => $taxonomy,
            'hide_empty' => false
        ]);

        if(count($terms)){
            //echo $taxonomy.'<br/><pre>'; print_r($terms); echo '</pre><hr/>';
            foreach($terms as $term){
                $tax_array[$term->term_id] = $term->name;
            }
        }

        return $tax_array;
    }
    public function get_aepro_the_archive_title()
    {
        if ( is_category() ) {
            /* translators: Category archive title. 1: Category name */
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            /* translators: Tag archive title. 1: Tag name */
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            /* translators: Author archive title. 1: Author name */
            $title = get_the_author();
        } elseif ( is_year() ) {
            /* translators: Yearly archive title. 1: Year */
            $title = get_the_date( _x( 'Y', 'yearly archives date format' ) );
        } elseif ( is_month() ) {
            /* translators: Monthly archive title. 1: Month name and year */
            $title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
        } elseif ( is_day() ) {
            /* translators: Daily archive title. 1: Date */
            $title = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );
        } elseif ( is_tax( 'post_format' ) ) {
            if ( is_tax( 'post_format', 'post-format-aside' ) ) {
                $title = _x( 'Asides', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
                $title = _x( 'Galleries', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
                $title = _x( 'Images', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
                $title = _x( 'Videos', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
                $title = _x( 'Quotes', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
                $title = _x( 'Links', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
                $title = _x( 'Statuses', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
                $title = _x( 'Audio', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
                $title = _x( 'Chats', 'post format archive title' );
            }
        } elseif ( is_post_type_archive() ) {
            /* translators: Post type archive title. 1: Post type name */
            $title = post_type_archive_title( '', false );
        } elseif ( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = single_term_title( '', false ) ;
        } else {
            $title = __( 'Archives' );
        }

        /**
         * Filters the archive title.
         *
         * @since 4.1.0
         *
         * @param string $title Archive title to be displayed.
         */
        return apply_filters( 'ae_get_the_archive_title', $title );
    }
    public function get_aepro_the_archive_description($term = 0, $taxonomy = 'post_tag'){
        return term_description($term,$taxonomy);
    }

}

Post_Helper::instance();