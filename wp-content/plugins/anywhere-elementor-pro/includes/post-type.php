<?php
namespace Aepro;

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'wts_ae_post_type' ) ) :
    /**
     * Create AE Global Post Type
     * @since 0.1
     */
    add_action( 'init', 'Aepro\wts_ae_post_type', 0 );
    function wts_ae_post_type() {

        $labels = array(
            'name'                  => _x( 'AE Global Templates', 'Post Type General Name', 'ae-pro' ),
            'singular_name'         => _x( 'AE Template', 'Post Type Singular Name', 'ae-pro' ),
            'menu_name'             => __( 'AE Templates', 'ae-pro' ),
            'name_admin_bar'        => __( 'AE Templates', 'ae-pro' ),
            'archives'              => __( 'List Archives', 'ae-pro' ),
            'parent_item_colon'     => __( 'Parent List:', 'ae-pro' ),
            'all_items'             => __( 'All AE Templates', 'ae-pro' ),
            'add_new_item'          => __( 'Add New AE Template', 'ae-pro' ),
            'add_new'               => __( 'Add New', 'ae-pro' ),
            'new_item'              => __( 'New AE Template', 'ae-pro' ),
            'edit_item'             => __( 'Edit AE Template', 'ae-pro' ),
            'update_item'           => __( 'Update AE Template', 'ae-pro' ),
            'view_item'             => __( 'View AE Template', 'ae-pro' ),
            'search_items'          => __( 'Search AE Template', 'ae-pro' ),
            'not_found'             => __( 'Not found', 'ae-pro' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'ae-pro' )
        );
        $args = array(
            'label'                 => __( 'Post List', 'ae-pro' ),
            'labels'                => $labels,
            'supports'              => array( 'title','editor' ),
            'public'                => true,
            'rewrite'               => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'menu-icon'             => 'dashicon-move'
        );
        register_post_type( 'ae_global_templates', $args );

    }
endif;