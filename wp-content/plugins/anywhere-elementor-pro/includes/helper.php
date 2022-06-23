<?php

namespace Aepro;

use WP_Query;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use ElementorPro\Modules\ThemeBuilder\Module as EProModule;

class Helper{

    function get_rule_post_types( $output = 'object'){
        $final_post_types = array();
        $all_post_types = get_post_types(array('public' => true), $output);


        $skip_post_types = array(
            'attachment',
            'ae_global_templates',
            'elementor_library'
        );

        if($output === 'names'){
            return array_diff($all_post_types,$skip_post_types);
        }

        foreach($all_post_types as $name => $post_type){
            if(!in_array($name,$skip_post_types)){
                $final_post_types[$name] = $post_type->label;
            }
        }

        return $final_post_types;
    }

    function get_post_types_with_archive(){
        $ret_post_types = array();
        $post_types = get_post_types(array('has_archive' => true), 'object');

        $ret_post_types['post'] = 'Post Archive';
        foreach($post_types as $name => $post_type){
           $ret_post_types[$name] = $post_type->label.' Archive';
        }
        return $ret_post_types;
    }

    public function get_ae_acf_repeater_fields(){
        $acf_fields = [];

	    if ( isset( $_REQUEST['post'] ) ) {
		    if(get_post_type($_REQUEST['post']) == 'ae_global_templates') {

		    	$preview_post_ID = get_post_meta( $_REQUEST['post'], 'ae_preview_post_ID', true );

		    }elseif(get_post_type($_REQUEST['post']) == 'elementor_library' && class_exists('ElementorPro\Modules\ThemeBuilder\Module')){

		    	$document = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_document( $_REQUEST['post'] );
		    	if(isset($document)){
				    $preview_post_ID = $document->get_settings( 'preview_id' );
			    }else{
		    		return $acf_fields;
			    }

		    }else{

		    	$preview_post_ID = $_REQUEST['post'];

		    }

		    $fields = $this->ae_acf_get_field_objects( $preview_post_ID );
		    if ( $fields ) {
			    foreach ( $fields as $field_name => $field ) {
				    if ( $field['type'] == 'repeater' ) {
					    $acf_fields[ $field['name'] ] = $field['label'];
				    }
			    }
	        }
	    }
        return $acf_fields ;
    }

    function get_demo_post_data()
    {
        $post_data = array();
	    if(!isset($GLOBALS['post'])){
		    return $post_data;
	    }
        $preview_post_ID = '';
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_post_ID = $GLOBALS['post']->ID;
            $preview_post_ID = get_post_meta($ae_post_ID,'ae_preview_post_ID',true);
            if ($preview_post_ID != '' && $preview_post_ID != 0):
                $post_data = get_post($preview_post_ID);
            else:
                $args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $demo_data = get_posts( $args );
                $post_data = $demo_data[0];
            endif;
        }elseif($GLOBALS['post']->post_type == 'elementor_library' && class_exists('ElementorPro\Modules\ThemeBuilder\Module')){
            $document = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_document( $GLOBALS['post']->ID );
            if ( $document ) {
                $preview_id = $document->get_settings( 'preview_id' );

                if ( empty( $preview_id ) ) {
                    $post_data = get_post(0);
                    return $post_data;
                }
                $post_data = get_post( $preview_id );
            }
        }else{
            $post_data = $GLOBALS['post'];
        }


        if(empty($post_data)){
            $post_data = get_post(0);
        }

        return $post_data;
    }

    function get_ae_post_templates(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'post_template',
            'posts_per_page'    => -1
        );

        $ret_array['global'] = esc_html__('Global','ae-pro');
        $ret_array['none'] = esc_html__('None','ae-pro');
        $post_templates = get_posts($args);
        foreach($post_templates as $pt){
            $ret_array[$pt->ID] = $pt->post_title;
        }
        return $ret_array;
    }

    function get_ae_product_templates(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'wc_product_single'
        );

        $ret_array[''] = esc_html__('Default','ae-pro');
        $post_templates = get_posts($args);
        foreach($post_templates as $pt){
            $ret_array[$pt->ID] = $pt->post_title;
        }
        return $ret_array;
    }

    function get_ae_active_archive_template(){

        if(is_date()){
            $args = array(
                'post_type'   => 'ae_global_templates',
                'meta_query'  => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'date_template',
                        'compare' => '='
                    )
                )
            );

            $date_template = new WP_Query($args);

            if($date_template->found_posts){
                $date_template->the_post();
                $date_template = get_the_ID();
            }else{
                wp_reset_postdata();
                return false;
            }
            wp_reset_postdata();
            $date_template = apply_filters('ae_template_filter', $date_template);
            return $date_template;
        }

        $helper = new Helper();
        $is_blog = $helper->is_blog();

        // Check if post type archive
        if(is_post_type_archive() || $is_blog){
            if($is_blog){
                $post_type = 'post';
            }else{
                $query = get_queried_object();
                $post_type = $query->name;
            }

            if($post_type == 'product'){
                //return false;
            }
            // apply template for post type archive
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'post_type_archive_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_post_type_archive',
                        'value'   => $post_type,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);

            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();
            $ae_tid = apply_filters('ae_template_filter', $ae_tid);
            return $ae_tid;
        }


        // Check if it is author archive
        if(is_author()){
            $query = get_queried_object();
            $author_id = $query->ID;

            // check template for author template through author meta
            $author_template = get_the_author_meta('ae_author_template',$author_id);

            if(!$author_template || $author_template == 'global'){
                // check global AE Template for Author Archive
                $args = array(
                    'post_type'   => 'ae_global_templates',
                    'meta_query'  => array(
                        array(
                            'key' => 'ae_render_mode',
                            'value'   => 'author_template',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'ae_apply_global',
                            'value'   => 'true',
                            'compare' => '='
                        )
                    )
                );

                $author_template = new WP_Query($args);

                if($author_template->found_posts){
                    $author_template->the_post();
                    $author_template = get_the_ID();
                }else{
                    wp_reset_postdata();
                    return false;
                }
                wp_reset_postdata();
            }

            $author_template = apply_filters('ae_template_filter', $author_template);
            return $author_template;
        }


        // Not post type archive -- It can be taxonomy archive
        $query = get_queried_object();
        if(is_category()){
            $taxonomy = 'category';
        }elseif(is_tag()){
            $taxonomy = 'post_tag';
        }elseif(is_tax()){
            $query = get_queried_object();
            $taxonomy = $query->taxonomy;
        }

        // Todo:: add term level template implementation
        // get term template
        $term_id = $query->term_id;
        $ae_tid = get_term_meta($term_id,'ae_term_template',true);


        if((empty($ae_tid) || $ae_tid == 'global') && !empty($taxonomy)){
            // apply global template for taxonomy archive
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'archive_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_apply_global',
                        'value'   => 'true',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_taxonomy',
                        'value'   => $taxonomy,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);

            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();
        }

        $ae_tid = apply_filters('ae_template_filter', $ae_tid);

        if($ae_tid == 'none'){
        	return false;
        }

        return $ae_tid;
    }

    function get_ae_active_post_template($post_id,$post_type){
        $ae_post_template = get_post_meta($post_id, 'ae_post_template', true);

        if(isset($ae_post_template) && $ae_post_template == 'none'){
            return false;
        }

        if(!isset($ae_post_template) || empty($ae_post_template) || $ae_post_template == 'global'){
            // apply global template
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'post_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_apply_global',
                        'value'   => 'true',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_post_type',
                        'value'   => $post_type,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);
            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();

        }else{
            // set individual post template
            $ae_tid = $ae_post_template;
        }


        $ae_tid = apply_filters('ae_template_filter', $ae_tid);
        return $ae_tid;
    }

    function get_ae_woo_product_data(){
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_woo_ID = $GLOBALS['post']->ID;
            $preview_woo_ID = get_post_meta($ae_woo_ID,'ae_preview_post_ID',true);
            if ($preview_woo_ID != ''):
                $product_data = wc_get_product($preview_woo_ID);
            else:
             // Todo:: Get product from template meta field if available
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $preview_data = get_posts( $args );
                $product_data =  wc_get_product($preview_data[0]->ID);
            endif;
        }else{
            global $product;
            $product_data = $product;
        }

        return $product_data;
    }

    function ae_get_intermediate_image_sizes(){
        global $_wp_additional_image_sizes;

        $default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large', 'full' ];
        $image_options = array();
        foreach ( $default_image_sizes as $size ) {
            $image_sizes[ $size ] = [
                'width' => (int) get_option( $size . '_size_w' ),
                'height' => (int) get_option( $size . '_size_h' ),
                'crop' => (bool) get_option( $size . '_crop' ),
            ];
        }

        if ( $_wp_additional_image_sizes ) {
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }

        foreach($image_sizes as $size => $image_size){
            $image_options[$size] = ($size).' '.$image_size['width'].' x '.$image_size['height'];
        }

        return $image_options;
    }

    function ae_get_intermediate_image_sizes_for_acf_photo_gallery(){
        global $_wp_additional_image_sizes;

        $default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large', 'full' ];
        $image_options = array();
        foreach ( $default_image_sizes as $size ) {
            $image_sizes[ $size ] = [
                'width' => (int) get_option( $size . '_size_w' ),
                'height' => (int) get_option( $size . '_size_h' ),
                'crop' => (bool) get_option( $size . '_crop' ),
            ];
        }

        if ( $_wp_additional_image_sizes ) {
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }

        foreach($image_sizes as $size => $image_size){
            $image_options[$size] = ($size);
        }

        return $image_options;
    }

    function get_ae_render_mode_hook(){

        $render_modes = apply_filters('ae_pro_filter_hook_render_mode', array(
            'normal' => 'Normal',
            'post_template' => 'Post Template',
            'archive_template' => 'Taxonomy Archive Template',
            'post_type_archive_template' => 'Post Type Archive Template',
            'block_layout'   => __('Block Layout','ae-pro'),
            '404'            => __('404 Template','ae-pro'),
            'search'         => __('Search Template', 'ae-pro'),
            'author_template' => __('Author Archive', 'ae-pro'),
            'date_template'   => __('Date Archive', 'ae-pro')
        ));

        if(class_exists('acf')){
            $render_modes['acf_repeater_layout'] = __('ACF Repeater Block', 'ae-pro');
        }
        return $render_modes;
    }

    function ae_get_post_css(){
        if(!is_single() && !is_page()){
            return '';
        }

        $post = $this->get_demo_post_data();
        $css = '';
        $image_sizes = $this->ae_get_intermediate_image_sizes();
        foreach($image_sizes as $image_size => $size_data){
            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$image_size);
            $css .= '.ae-featured-bg-source-post.ae-featured-img-size-'.$image_size.'{ background-image:url('.$img_src[0].'); }';
        }
        return $css;
    }

    function ae_get_cf_image_css(){
        if(!is_single() && !is_page()){
            return '';
        }

        $post = $this->get_demo_post_data();
        $css = '';

        //if(isset($post->ID) && !$post->ID){
        //	return '';
       //}

        $fields = $this->ae_acf_get_field_objects($post->ID);

        $acf_field = [];

        if( $fields )
        {
            foreach( $fields as $field_name => $field )
            {
                if($field['type'] == 'image'){
                    $acf_field[] = $field;
                }
            }
        }

        $image_sizes = $this->ae_get_intermediate_image_sizes();

        if(count($acf_field)) {
            foreach ($acf_field as $acff) {
                foreach ($image_sizes as $image_size => $size_data) {
                    $img_src = wp_get_attachment_image_src($acff['value'], $image_size);
                    $css .= '.ae-featured-bg-source-custom_field.ae-feature-bg-custom-field-'. $acff['name'] . '.ae-featured-img-size-' . $image_size . '{ background-image:url(' . $img_src[0] . '); }';
                }
            }
        }
        return $css;
    }

    function ae_get_term_cf_image_css(){
        if(!is_single() && !is_page()){
            return '';
        }

        $term = $this->get_preview_term_data();
        $css = '';

        if(empty($term) || !$term['prev_term_id']){
	        return '';
        }

        $fields = get_field_objects('term_'.$term['prev_term_id']);

        $acf_field = [];

        if( $fields )
        {
            foreach( $fields as $field_name => $field )
            {
                if($field['type'] == 'image'){
                    $acf_field[] = $field;
                }
            }
        }

        $image_sizes = $this->ae_get_intermediate_image_sizes();

        if(count($acf_field)) {
            foreach ($acf_field as $acff) {
                foreach ($image_sizes as $image_size => $size_data) {
                    $img_src = wp_get_attachment_image_src($acff['value'], $image_size);
                    $css .= '.ae-featured-bg-source-term_custom_field.ae-feature-bg-custom-field-'. $acff['name'] . '.ae-featured-img-size-' . $image_size . '{ background-image:url(' . $img_src[0] . '); }';
                }
            }
        }
        return $css;
    }

    function ae_get_custom_taxonomies(){
        $args = array(
            'public'   => true,
            '_builtin' => false

        );
        $tax_array = array();
        $taxonomies = get_taxonomies($args,'objects');
        if(count($taxonomies)){
            foreach($taxonomies as $slug => $taxonomy){
                $tax_array[$slug] = $taxonomy->labels->name;
            }
        }
        return $tax_array;
    }

    function get_rules_taxonomies(){
        $args = array(
            'public'   => true

        );
        $tax_array = array();
        $taxonomies = get_taxonomies($args,'objects');
        if(count($taxonomies)){
            foreach($taxonomies as $slug => $taxonomy){
                $tax_array[$slug] = $taxonomy->labels->name;
            }
        }
        return $tax_array;
    }

    function ae_get_date_format(){
        $date_format = array(
            'F j, Y g:i a' => date('F j, Y g:i a'),
            'F j, Y' => date( 'F j, Y' ),
            'F, Y' => date( 'F, Y' ),
            'g:i a' => date( 'g:i a' ),
            'g:i:s a' => date( 'g:i:s a' ),
            'l, F jS, Y' => date( 'l, F jS, Y' ),
            'M j, Y @ G:i' => date( 'M j, Y @ G:i' ),
            'Y/m/d \a\t g:i A' => date( 'Y/m/d \a\t g:i A' ),
            'Y/m/d \a\t g:ia' => date( 'Y/m/d \a\t g:ia' ),
            'Y/m/d g:i:s A' => date( 'Y/m/d g:i:s A' ),
            'Y/m/d' => date( 'Y/m/d' ),
            'Y-m-d \a\t g:i A' => date( 'Y-m-d \a\t g:i A' ),
            'Y-m-d \a\t g:ia' => date( 'Y-m-d \a\t g:ia' ),
            'Y-m-d g:i:s A' => date( 'Y-m-d g:i:s A' ),
            'Y-m-d' => date( 'Y-m-d' ),
            'custom' => __( 'Custom', 'ae-pro' )
        );
        return $date_format;
    }

    function get_previous_post_id( $post_id ) {
        // Get a global post reference since get_adjacent_post() references it
        global $post;
        // Store the existing post object for later so we don't lose it
        $oldGlobal = $post;
        // Get the post object for the specified post and place it in the global variable
        $post = get_post( $post_id );
        // Get the post object for the previous post
        $previous_post = get_previous_post();
        // Reset our global object
        $post = $oldGlobal;
        if ( '' == $previous_post )
            return false;
        return $previous_post->ID;
    }

    function get_next_post_id( $post_id ) {
        // Get a global post reference since get_adjacent_post() references it
        global $post;
        // Store the existing post object for later so we don't lose it
        $oldGlobal = $post;
        // Get the post object for the specified post and place it in the global variable
        $post = get_post( $post_id );
        // Get the post object for the next post
        $next_post = get_next_post();
        // Reset our global object
        $post = $oldGlobal;
        if ( '' == $next_post )
            return false;
        return $next_post->ID;
    }

    function get_woo_registered_tabs($output = ''){

    	global $product;
	    if(!is_object($product)) {
		    $product = wc_get_product(get_the_ID());
	    }

        $registered_tabs = [];

        $tabs = apply_filters( 'woocommerce_product_tabs', array() );

        if($output == 'full'){
            return $tabs;
        }

        foreach($tabs as $tab_key => $tab){
            $registered_tabs[$tab_key] = $tab['title'];
        }

        return $registered_tabs;
    }

    function get_woo_archive_template(){

        if(function_exists('is_shop')){
            if(is_shop() || is_tax('product_cat')){
                $args = array(
                    'post_type' => 'ae_global_templates',
                    'meta_query' => array(
                        array(
                            'key' => 'ae_render_mode',
                            'value'   => 'post_type_archive_template',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'ae_rule_post_type_archive',
                            'value'   => 'product',
                            'compare' => '='
                        )
                    )
                );
                $templates = new WP_Query($args);

                if($templates->found_posts){
                    $templates->the_post();
                    $ae_tid = get_the_ID();
                }else{
                    return false;
                }
                wp_reset_postdata();
                return $ae_tid;
            }
        }
        return false;
    }

    function ae_block_layouts()
    {


        $block_layouts = [];
        $ae_id = [];
        if(isset($_GET['post'])) {
            $ae_id = array($_GET['post']);
        }
        $templates = get_posts([
            'numberposts'	=> -1,
            'post_type'		=> 'ae_global_templates',
            'meta_key'		=> 'ae_render_mode',
            'meta_value'	=> 'block_layout',
            'post__not_in'  =>  $ae_id
        ]);

        if(count($templates)){
            foreach($templates as $template){
                $block_layouts[$template->ID] = $template->post_title;
            }
        }
        return $block_layouts;
    }


    function has_404_template(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_query' => array(
                array(
                    'key' => 'ae_render_mode',
                    'value' => '404',
                    'compare' => '='
                )
            )
        );

        $templates = new WP_Query($args);

        if($templates->found_posts){
            $templates->the_post();
            $ae_tid = get_the_ID();
            wp_reset_postdata();
            return $ae_tid;
        }else{
            return false;
        }
    }

    function has_search_template(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_query' => array(
                array(
                    'key' => 'ae_render_mode',
                    'value' => 'search',
                    'compare' => '='
                )
            )
        );

        $templates = new WP_Query($args);

        if($templates->found_posts){
            $templates->the_post();
            $ae_tid = get_the_ID();
            wp_reset_postdata();
            return $ae_tid;
        }else{
            return false;
        }
    }

    function ae_acf_repeater_layouts()
    {
        $block_layouts = [];
        $ae_id = [];
        if(isset($_GET['post'])) {
            $ae_id = array($_GET['post']);
        }
        $templates = get_posts([
            'numberposts'	=> -1,
            'post_type'		=> 'ae_global_templates',
            'meta_key'		=> 'ae_render_mode',
            'meta_value'	=> 'acf_repeater_layout',
            'post__not_in'  =>  $ae_id
        ]);

        if(count($templates)){
            foreach($templates as $template){
                $block_layouts[$template->ID] = $template->post_title;
            }
        }
        return $block_layouts;
    }

    function is_blog(){
        if ( is_front_page() && is_home() ) {
            //echo "Default homepage";
            return true;
        } elseif ( is_front_page() ) {
            return false;
        } elseif ( is_home() ) {
            return true;
        } else {
            return false;
        }
    }

    function is_canvas_enabled($tid){
        $template = get_post_meta($tid,'ae_elementor_template', true);
        if($template == 'ec'){
            return true;
        }
        return false;
    }

	function is_heder_footer_enabled($tid){
		$template = get_post_meta($tid,'ae_elementor_template', true);
		if($template == 'ehf'){
			return true;
		}
		return false;
	}

    function is_full_override($tid){
        $full_override = get_post_meta($tid,'ae_full_override',true);
        if($full_override){
            return true;
        }

        return false;
    }

    function get_saved_preview_post(){
        $options[] = __(' -- Select Post -- ');
        if(isset($_GET['post'])){
            $prev_post_id = get_post_meta($_GET['post'],'ae_preview_post_ID',true);
            if($prev_post_id){
                $prev_post = get_post($prev_post_id);
                $options[ $prev_post->ID ] = $prev_post->post_title;
            }
        }
        return $options;
    }

    function get_saved_preview_term(){
        $options[] = __(' -- Select Term --');
        if(isset($_GET['post'])){
            $prev_term_id = get_post_meta($_GET['post'],'ae_preview_term',true);
            $taxonomy = get_post_meta($_GET['post'],'ae_rule_taxonomy',true);
            if($prev_term_id){
                $prev_term = get_term_by('id',$prev_term_id,$taxonomy);
                $options[$prev_term->term_id] = $prev_term->name;
            }
        }

        return $options;
    }

    function get_preview_term_data(){
        $term_data = [
            'prev_term_id' => '',
            'taxonomy' => ''
        ];
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_template_id = $GLOBALS['post']->ID;
            $term_data['prev_term_id'] = get_post_meta($ae_template_id,'ae_preview_term',true);
            $term_data['taxonomy'] = get_post_meta($ae_template_id,'ae_rule_taxonomy',true);
        }elseif(is_category() || is_tag() || is_tax()){
			$queried_object = get_queried_object();
			$term_data['prev_term_id'] = $queried_object->term_id;
			$term_data['taxonomy']     = $queried_object->taxonomy;
        }

        return $term_data;
    }

    function get_preview_author_data(){
        $author_data = [
            'prev_author_id' => ''
        ];
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_template_id = $GLOBALS['post']->ID;
            $author_data['prev_author_id'] = get_post_meta($ae_template_id,'ae_preview_author',true);
        }else{
            if (is_author()){
                $author = get_queried_object();
                $author_data['prev_author_id'] = $author->ID;
            }
        }

        return $author_data;
    }

    function get_taxonomy_templates(){
        $ae_tax_templates = [];
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'archive_template',
            'posts_per_page'    => -1
        );

        $templates = get_posts($args);
        if(count($templates)){
            foreach($templates as $template){
                // get assigned taxonomy
                $taxonomy = get_post_meta($template->ID,'ae_rule_taxonomy',true);
                $ae_tax_templates[$taxonomy][$template->ID] = $template->post_title;
            }
        }
        return $ae_tax_templates;
    }

    function box_model_controls($widget, $args){

        $defaults = [
            'border' => true,
            'border-radius' => true,
            'margin' => true,
            'padding' => true,
            'box-shadow' => true
        ];

        $args = wp_parse_args( $args, $defaults );

        if($args['border']){
            $widget->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $args['name'].'_border',
                    'label' => __( $args['label'].' Border', 'ae-pro' ),
                    'selector' => $args['selector'],
                ]
            );
        }

        if($args['border-radius']) {
            $widget->add_control(
                $args['name'] . '_border_radius',
                [
                    'label' => __('Border Radius', 'ae-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        $args['selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        if($args['box-shadow']){
            $widget->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => $args['name'].'_box_shadow',
                    'label' => __( 'Box Shadow', 'ae-pro' ),
                    'selector' => $args['selector'],
                ]
            );
        }

        if($args['padding']) {
            $widget->add_control(
                $args['name'] . '_padding',
                [
                    'label' => __('Padding', 'ae-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        $args['selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }


        if($args['margin']){
            $widget->add_control(
                $args['name'].'_margin',
                [
                    'label' => __( 'Margin', 'ae-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        $args['selector'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
    }

    public function get_current_url(){
        global $wp;

        // get current url with query string.
        $current_url =  home_url( $wp->request );

        // get the position where '/page.. ' text start.
        $pos = strpos($current_url , '/page');

        if($pos === false){
            $finalurl = substr($current_url,0,$pos);
        }else{
            $finalurl = $current_url;
        }

        echo $finalurl;
    }

    public function get_author_list(){
        $users = get_users();

        foreach ($users as $user){
            $author_list[$user->ID] = $user->data->display_name.' ('.$user->data->user_login.')';
        }

        return $author_list;
    }

    public function get_acf_repeater_name(){
        if($GLOBALS['post']->post_type == 'ae_global_templates') {
            $ae_acf_repeater_layout_id = $GLOBALS['post']->ID;
            $ae_acf_repeater_name = get_post_meta($ae_acf_repeater_layout_id, 'ae_acf_repeater_name', true);
            return $ae_acf_repeater_name;
        }
    }

    public function is_repeater_block_layout(){
        $repeater_data = [];

        $doc = \Elementor\Plugin::$instance->documents->get_current();
        if(!isset($doc) || is_null($doc)){
            $repeater_data['is_repeater'] = false;
            return $repeater_data;
        }
        $doc_post = $doc->get_post();

        if($doc_post->post_type == 'revision'){
            $doc_post = get_post($doc_post->post_parent);
        }

        $render_mode = get_post_meta($doc_post->ID, 'ae_render_mode', true);

        if($GLOBALS['post']->ID == $doc_post->ID && $render_mode == 'acf_repeater_layout'){

            $repeater_data['is_repeater'] = true;
            $repeater_data['field'] = get_post_meta($doc_post->ID, 'ae_acf_repeater_name', true);

        }elseif($doc_post->post_type == 'ae_global_templates' && $render_mode == 'acf_repeater_layout'){
            $repeater_data['is_repeater'] = true;
        }else{
            $repeater_data['is_repeater'] = false;
        }

        return $repeater_data;

    }

    public function paginate_links( $args = '' ) {
        global $wp_query, $wp_rewrite;

        // Setting up default values based on the current URL.

        $pagenum_link = html_entity_decode( get_pagenum_link() );
        $url_parts    = explode( '?', $pagenum_link );

        // Get max pages and current page out of the current query, if available.
        $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
        $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

        // Append the format placeholder to the base URL.
        $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

        // URL base depends on permalink settings.
        $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

        $defaults = array(
            'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
            'format'             => $format, // ?page=%#% : %#% is replaced by the page number
            'total'              => $total,
            'current'            => $current,
            'aria_current'       => 'page',
            'show_all'           => false,
            'prev_next'          => true,
            'prev_text'          => __( '&laquo; Previous' ),
            'next_text'          => __( 'Next &raquo;' ),
            'end_size'           => 1,
            'mid_size'           => 1,
            'type'               => 'plain',
            'add_args'           => array(), // array of query args to add
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        );

        $args = wp_parse_args( $args, $defaults );


        if ( ! is_array( $args['add_args'] ) ) {
            $args['add_args'] = array();
        }

        // Merge additional query vars found in the original URL into 'add_args' array.
        if ( isset( $url_parts[1] ) ) {
            // Find the format argument.
            $format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
            $format_query = isset( $format[1] ) ? $format[1] : '';
            wp_parse_str( $format_query, $format_args );

            // Find the query args of the requested URL.
            wp_parse_str( $url_parts[1], $url_query_args );

            // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
            foreach ( $format_args as $format_arg => $format_arg_value ) {
                unset( $url_query_args[ $format_arg ] );
            }

            $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
        }

        // Who knows what else people pass in $args
        $total = (int) $args['total'];
        if ( $total < 2 ) {
            return;
        }
        $current  = (int) $args['current'];
        $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
        if ( $end_size < 1 ) {
            $end_size = 1;
        }
        $mid_size = (int) $args['mid_size'];
        if ( $mid_size < 0 ) {
            $mid_size = 2;
        }
        $add_args = $args['add_args'];
        $r = '';
        $page_links = array();
        $dots = false;


        if ( $args['prev_next'] && $current && 1 < $current ) :
            $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
            $link = str_replace( '%#%', $current - 1, $link );
            if ( $add_args )
                $link = add_query_arg( $add_args, $link );
            $link .= $args['add_fragment'];

            /**
             * Filters the paginated links for the given archive pages.
             *
             * @since 3.0.0
             *
             * @param string $link The paginated link URL.
             */
            $page_links[] = '<a data-ae-page-id="' . ($current - 1) .'" class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
        endif;
        for ( $n = 1; $n <= $total; $n++ ) :
            if ( $n == $current ) :
                $page_links[] = "<span data-ae-page-id='" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "' aria-current='" . esc_attr( $args['aria_current'] ) . "' class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span>";
                $dots = true;
            else :
                if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                    $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                    $link = str_replace( '%#%', $n, $link );
                    if ( $add_args )
                        $link = add_query_arg( $add_args, $link );
                    $link .= $args['add_fragment'];

                    /** This filter is documented in wp-includes/general-template.php */
                    $page_links[] = "<a data-ae-page-id='" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "' class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
                    $dots = true;
                elseif ( $dots && ! $args['show_all'] ) :
                    $page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
                    $dots = false;
                endif;
            endif;
        endfor;
        if ( $args['prev_next'] && $current && $current < $total ) :
            $link = str_replace( '%_%', $args['format'], $args['base'] );
            $link = str_replace( '%#%', $current + 1, $link );
            if ( $add_args )
                $link = add_query_arg( $add_args, $link );
            $link .= $args['add_fragment'];

            /** This filter is documented in wp-includes/general-template.php */
            $page_links[] = '<a data-ae-page-id="' . ($current + 1) .'" class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
        endif;
        switch ( $args['type'] ) {
            case 'array' :
                return $page_links;

            case 'list' :
                $r .= "<ul class='page-numbers'>\n\t<li>";
                $r .= join("</li>\n\t<li>", $page_links);
                $r .= "</li>\n</ul>\n";
                break;

            default :
                $r = join("\n", $page_links);
                break;
        }
        return $r;
    }

    public function ae_get_wp_nav_menu(){
        $menus = wp_get_nav_menus();
        $menu_arr = array();
        foreach ( $menus as $menu ) {
            $menu_arr[ $menu->slug ] = $menu->name;
        }
        return $menu_arr;
    }

	public function ae_acf_get_field_objects($id){
		if(isset($id) && !$id){
			return '';
		}

		return get_field_objects($id);
	}

	public function ae_is_product_on_sale($id){
    	$flag = false;
		if (get_post_type($id) === 'product') {
			$p = wc_get_product($id);
			if ( $p->is_type( 'variable' ) ) {
				$available_variations = $p->get_available_variations();
				for ( $i = 0; $i < count( $available_variations ); $i++ ) {
					$variation_id     = $available_variations[ $i ]['variation_id'];
					$variable_product = new \WC_Product_Variation( $variation_id );

					if ( $variable_product->is_on_sale() ) {
						$flag = true;
						break;
					}
				}
			}else{
				if ($p->is_on_sale()) {
					$flag = true;
				}
			}

		}
		return $flag;
	}

	/*public function ae_taxonomy_terms(){
        $term_data = $this->get_preview_term_data();
        $terms = get_terms( array(
            'taxonomy' => $term_data['taxonomy'],
            'hide_empty' => false,
            'parent' => 0,
        ) );
       // echo '<pre>'; print_r($terms); die();
        $ae_terms = array();
        if(count($terms)){
            foreach($terms as $term){
                $ae_terms[$term->term_id] = $term->name;
            }
        }
        return $ae_terms;
    }*/

    public function ae_taxonomy_terms($taxonomy, $settings){


    	$parent = 0;

    	if($settings['ae_taxonomy'] == 'child_of_current'){

		    if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			    $preview_term = Aepro::$_helper->get_preview_term_data();
			    if ( isset($preview_term['prev_term_id']) ) {
				    $taxonomy   = $preview_term['taxonomy'];
				    $parent     = $preview_term['prev_term_id'];
			    } else {
					// do nothing
			    }
		    } else {
			    // get child of current term
			    $current_term = get_queried_object();
			    $taxonomy   = $current_term->taxonomy;
			    $parent     = $current_term->term_id;
		    }

	    }


        $hide_empty = false;
        if($settings['show_hide_empty'] == 'yes'){
            $hide_empty = true;
        }
        $terms = get_terms( array(
            'taxonomy' => $taxonomy,
            'hide_empty' => $hide_empty,
            'parent' => $parent,
        ) );
        return $terms;
    }

    public function get_ae_placeholder_image_src() {
        $placeholder_image = AE_PRO_URL . 'includes/assets/images/aep-placeholder.jpg';
        return $placeholder_image;
    }

    public function get_facetwp_data($type){
        $facet_arr = [
            ''  =>  __('Select' , 'ae-pro'),
        ];
        $facet_helper =  FWP()->helper;
        $facet_setting = $facet_helper->load_settings();
        $factes = $facet_setting['facets'];
        foreach ($factes as $facet){
            if($type == $facet['type']) {
                $facet_arr[$facet['name']] = $facet['label'];
            }
        }
        return $facet_arr;

    }

    public function column_rule_controls($widget, $args) {

        $widget->add_responsive_control(
            $args['name'] . '_content_rule_border',
            [
            'label' => $args['label'] . ' Border',
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __( 'None', 'ae-pro' ),
                'solid' => _x( 'Solid', 'Border Control', 'ae-pro' ),
                'double' => _x( 'Double', 'Border Control', 'ae-pro' ),
                'dotted' => _x( 'Dotted', 'Border Control', 'ae-pro' ),
                'dashed' => _x( 'Dashed', 'Border Control', 'ae-pro' ),
                'groove' => _x( 'Groove', 'Border Control', 'ae-pro' ),
            ],
            'selectors' => [
                $args['selector'] => 'column-rule-style: {{VALUE}};',
            ],
                'condition' => [
                    'text_columns!' => '',
                ]
        ]);

        $widget->add_responsive_control(
            $args['name'] . '_content_rule_width',
            [
            'label' => $args['label'] . ' Width',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
            'selectors' => [
                $args['selector'] => 'column-rule-width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                $args['name'] . '_content_rule_border!' => '',
                'text_columns!' => '',
            ],
        ]);

        $widget->add_control(
            $args['name'] . '_content_rule_color',
            [
            'label' => $args['label'] . ' Color',
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                $args['selector'] => 'column-rule-color: {{VALUE}};',
            ],
            'condition' => [
                $args['name'] . '_content_rule_border!' => '',
                'text_columns!' => '',
            ],
        ]);
    }

    function get_help_url_prefix(){
        return 'https://wpvibes.link/go/widget-';
        return '';
    }

    function get_widget_admin_note_html($note, $link = '', $link_text = 'Click Here')
    {
        $note = '<p class="ae-editor-note"><i>' . $note;
        if (trim($link) != '') {
            $note .= ' <a href="' . $link . '" target="_blank">' . $link_text . '</a>';
        }
        $note .= '</i></p>';

        return $note;
    }

    public function get_current_url_non_paged(){

		global $wp;
	    $url = get_pagenum_link(1);

	    return trailingslashit($url);

    }
}
