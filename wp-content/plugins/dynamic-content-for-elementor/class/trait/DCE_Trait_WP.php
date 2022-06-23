<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_WP {
    
    public static function get_post_fields($meta = false, $group = false) {
        $postFieldsKey = array();
        $postTmp = get_post();
        if ($postTmp) {
            $postProp = array();
            $postPropAll = get_object_vars($postTmp);
            if (!empty($meta) && is_string($meta)) {
                foreach ($postPropAll as $key => $value) {
                    $pos_key = stripos($value, $meta);
                    $pos_name = stripos($key, $meta);
                    if ($pos_key === false && $pos_name === false) {
                        continue;
                    }
                    $postProp[$key] = $value;
                }
            } else {
                $postProp = $postPropAll;
            }
            //$postMeta = get_registered_meta_keys('post');
            //$postFields = array_merge(array_keys($postProp), array_keys($postMeta));

            if ($meta) {
                $metas = self::get_post_metas($group, (is_string($meta)) ? $meta : null);
                $postFieldsKey = $metas;
            }

            $postFields = array_keys($postProp);
            if (!empty($postFields)) {
                foreach ($postFields as $value) {
                    $name = str_replace('post_', '', $value);
                    $name = str_replace('_', ' ', $name);
                    $name = ucwords($name);
                    if ($group) {
                        $postFieldsKey['POST'][$value] = $name;
                    } else {
                        $postFieldsKey[$value] = $name;
                    }
                }
                if ($group) {
                    $postFieldsKey = array_merge(['POST' => $postFieldsKey['POST']], $postFieldsKey); // in first position
                }
            }
        }
        //var_dump($postFieldsKey); die();
        return $postFieldsKey;
    }

    public static function get_post_data($args) {
        $defaults = array(
            'posts_per_page' => 5,
            'offset' => 0,
            'category' => '',
            'category_name' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' => '',
            'post_type' => 'post',
            'post_mime_type' => '',
            'post_parent' => '',
            'author' => '',
            'author_name' => '',
            'post_status' => 'publish',
            'suppress_filters' => true
        );

        $atts = wp_parse_args($args, $defaults);

        $posts = get_posts($atts);

        return $posts;
    }

    public static function get_post_types($exclude = true) {
        $args = array(
            'public' => true
        );

        $skip_post_types = ['attachment', 'elementor_library', 'oceanwp_library'];

        $post_types = get_post_types($args);
        if ($exclude) {
            $post_types = array_diff($post_types, $skip_post_types);
        }
        foreach ($post_types as $akey => $acpt) {
            $cpt = get_post_type_object($acpt);
            //var_dump($cpt); die();
            $post_types[$akey] = $cpt->label;
        }
        return $post_types;
    }

    public static function get_pages() {
        $args = array(
            'sort_order' => 'desc',
            'sort_column' => 'menu_order',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $listPage = [];
        foreach ($pages as $page) {
            //$option = '<option value="' . get_page_link( $page->ID ) . '">';
            //$option .= $page->post_title;
            //$option .= '</option>';
            //echo $option;
            $listPage[$page->ID] = $page->post_title;
        }

        return $listPage;
    }
    
    public static function get_post_terms( $post_id = 0, $taxonomy = null, $args = array() ) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        if ($taxonomy) {
            return wp_get_post_terms($post_id, $taxonomy, $args);
        }
        $post_terms = array();
        $post_taxonomies = get_taxonomies(array('public' => true));
        if (!empty($post_taxonomies)) {
            foreach ($post_taxonomies as $key => $atax) {
                $post_terms = array_merge($post_terms, wp_get_post_terms($post_id, $atax, $args));
            }
        }
        return $post_terms;
    }


    public static function get_taxonomies($dynamic = false, $cpt = '', $search = '') {
        $args = array(
                // 'public' => true,
                // '_builtin' => false
        );
        $output = 'objects'; // or objects
        $operator = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies($args, $output, $operator);
        $listTax = [];
        //$listTax[''] = 'None';
        if ($dynamic)
            $listTax['dynamic'] = 'Dynamic';
        if (!$cpt || $cpt == 'post') {
            $listTax['category'] = 'Categories posts (category)';
            $listTax['post_tag'] = 'Tags posts (post_tag)';
        }
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy->name == 'elementor_library_category'
                        || $taxonomy->name == 'elementor_font_type'
                        || $taxonomy->name == 'nav_menu'
                        || $taxonomy->name == 'link_category') {
                    continue;
                }
                if (!$cpt || in_array($cpt, $taxonomy->object_type)) {
                    //echo '<p>' . $taxonomy . '</p>';
                    $listTax[$taxonomy->name] = $taxonomy->label . ' (' . $taxonomy->name . ')';
                    //$listPage[$page->ID] = $page->post_title.$isparent;
                }
            }
        }
        
        if (!empty($search)) {
            $tmp = array();
            foreach ($listTax as $tkey => $atax) {
                $pos_key = stripos($tkey, $search);
                $pos_name = stripos($atax, $search);
                if ($pos_key !== false || $pos_name !== false) {
                    $tmp[$tkey] = $atax;
                }
            }
            $listTax = $tmp;
        }

        return $listTax;
    }

    public static function get_taxonomy_terms($taxonomy = null, $flat = false, $search = '') {
        $listTerms = [];
        $flatTerms = [];
        $listTerms[''] = 'None';
        $args = array('taxonomy' => $taxonomy,'hide_empty' => false);
        if ($search) {
            $args['name__like'] = $search;
        }
        if ($taxonomy) {            
            $terms = get_terms($args);
            if (!empty($terms)) {
                foreach ($terms as $aterm) {
                    $listTerms[$aterm->term_id] = $aterm->name . ' (' . $aterm->slug . ')';
                }
                $flatTerms = $listTerms;
            }
        } else {
            $taxonomies = self::get_taxonomies();
            foreach ($taxonomies as $tkey => $atax) {
                if ($tkey) {
                    $args['taxonomy'] = $tkey;
                    $terms = get_terms($args);
                    if (!empty($terms)) {//var_dump($terms); die();
                        $tmp = [];
                        $tmp['label'] = $atax;
                        //$listTerms[$tkey]['label'] = $atax;
                        foreach ($terms as $aterm) {
                            //$listTerms[$tkey]['options'][$aterm->term_id] = $aterm->name.' ('.$aterm->slug.')';
                            $tmp['options'][$aterm->term_id] = $aterm->name . ' (' . $aterm->slug . ')';
                            $flatTerms[$aterm->term_id] = $atax . ' > ' . $aterm->name . ' (' . $aterm->slug . ')';
                        }
                        $listTerms[] = $tmp;
                    }
                }
            }
        }
        if ($flat) {
            return $flatTerms;
        }
        //print_r($listTerms); die();
        return $listTerms;
    }

    public static function get_the_terms_ordered($post_id, $taxonomy) {
        //var_dump($post_id); var_dump($taxonomy);
        $terms = get_the_terms($post_id, $taxonomy);
        //var_dump($terms);
        $ret = array();
        if (!empty($terms)) {
            foreach ($terms as $term) {
                //$ret[$term->term_order] = (object)array(
                //var_dump($term);
                $ret[($term->term_order) ? $term->term_order : $term->slug] = (object) array(
                            "term_id" => $term->term_id,
                            "name" => $term->name,
                            "slug" => $term->slug,
                            "term_group" => $term->term_group,
                            "term_order" => $term->term_order,
                            "term_taxonomy_id" => $term->term_taxonomy_id,
                            "taxonomy" => $term->taxonomy,
                            "description" => $term->description,
                            "parent" => $term->parent,
                            "count" => $term->count,
                            "object_id" => $term->object_id
                );
            }
            ksort($ret);
            //$ret = (object) $ret;
            //var_dump($ret);
        } else {
            $ret = $terms;
        }
        return $ret;
    }
    public static function get_parentterms($tax) {
        $parentTerms = get_terms( $tax );
        $listTerm = [];
        $listTerm[0] = 'None';
        
        foreach ($parentTerms as $term_item) {
            $termChildren = get_term_children( $term_item->term_id, $tax );
            
            if (count($termChildren) > 0) $listTerm[$term_item->term_id] = $term_item->name;
        }
        return $listTerm;
    }
    public static function get_parentpages() {
        //
        $args = array(
            'sort_order' => 'DESC',
            'sort_column' => 'menu_order',
            'numberposts' => -1,
            // 'hierarchical' => 1,
            // 'exclude' => '',
            // 'include' => '',
            // 'meta_key' => '',
            // 'meta_value' => '',
            // 'authors' => '',
            // 'child_of' => 0,
            // 'parent' => -1,
            // 'exclude_tree' => '',
            // 'number' => '',
            // 'offset' => 0,
            'post_type' => self::get_types_registered(),
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $listPage = [];

        foreach ($pages as $page) {

            $children = get_children('post_parent=' . $page->ID);
            $parents = get_post_ancestors($page->ID);
            $isparent = '';
            // !$parents &&
            if (count($children) > 0) {
                $isparent = ' (Parent)';
            }
            $listPage[$page->ID] = $page->post_title . $isparent;
        }

        return $listPage;
    }

    public static function get_post_settings($settings) {
        $post_args['post_type'] = $settings['post_type'];

        if ($settings['post_type'] == 'post') {
            $post_args['category'] = $settings['category'];
        }

        $post_args['posts_per_page'] = $settings['num_posts'];
        $post_args['offset'] = $settings['post_offset'];
        $post_args['orderby'] = $settings['orderby'];
        $post_args['order'] = $settings['order'];

        return $post_args;
    }

    public static function get_excerpt_by_id($post_id, $excerpt_length) {
        $the_post = get_post($post_id); //Gets post ID

        $the_excerpt = null;
        if ($the_post) {
            $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
        }

        // $the_excerpt = ($the_post ? $the_post->post_content : null);//Gets post_content to be used as a basis for the excerpt
        //echo $the_excerpt;
        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length) :
            array_pop($words);
            //array_push($words, '…');
            $the_excerpt = implode(' ', $words);
            $the_excerpt .= '...';  // Don't put a space before
        endif;

        return $the_excerpt;
    }

// ************************************** ALL POST SINGLE IN ALL REGISTER TYPE ***************************/
    public static function get_all_posts($myself = null, $group = false, $orderBy = 'title') {
        $args = array(
            'public' => true,
                //'_builtin' => false,
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $posttype_all = get_post_types($args, $output, $operator);

        $type_excluded = array('elementor_library', 'oceanwp_library', 'ae_global_templates');
        $typesRegistered = array_diff($posttype_all, $type_excluded);
        // Return elementor templates array

        $templates[0] = 'None';

        $exclude_io = array();
        if (isset($myself) && $myself) {
            //echo 'ei: '.$settings['exclude_io'].' '.count($exclude_io);
            $exclude_io = array($myself);
        }

        $get_templates = get_posts(array('post_type' => $typesRegistered, 'numberposts' => -1, 'post__not_in' => $exclude_io, 'post_status' => 'publish', 'orderby' => $orderBy, 'order' => 'DESC'));

        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {

                if ($group) {
                    $templates[$template->post_type]['options'][$template->ID] = $template->post_title;
                    $templates[$template->post_type]['label'] = $template->post_type;
                } else {
                    $templates[$template->ID] = $template->post_title;
                }
            }
        }

        return $templates;
    }

    public static function get_posts_by_type($typeId, $myself = null, $group = false) {


        $exclude_io = array();
        if (isset($myself) && $myself) {
            //echo 'ei: '.$settings['exclude_io'].' '.count($exclude_io);
            $exclude_io = array($myself);
        }
        $templates = array();
        $get_templates = get_posts(array('post_type' => $typeId, 'numberposts' => -1, 'post__not_in' => $exclude_io, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'DESC', 'suppress_filters' => false));

        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {

                $templates[$template->ID] = $template->post_title;
            }
        }

        return $templates;
    }

    /**
     * Get Post object by post_meta query
     *
     * @use         $post = get_post_by_meta( array( meta_key = 'page_name', 'meta_value = 'contact' ) )
     * @since       1.0.4
     * @return      Object      WP post object
     */
    public static function get_post_by_meta($args = array()) {

        // Parse incoming $args into an array and merge it with $defaults - caste to object ##
        $args = (object) wp_parse_args($args);

        // grab page - polylang will take take or language selection ##
        $args = array(
            'meta_query' => array(
                array(
                    'key' => $args->meta_key,
                    'value' => $args->meta_value
                )
            ),
            'post_type' => $args->post_type, //'page',
            'posts_per_page' => '1'
        );
        //var_dump($args);
        // run query ##
        $posts = get_posts($args);

        // check results ##

        if (is_wp_error($posts)) {
            if (WP_DEBUG) {
                $error_string = $result->get_error_message();
                echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
            }
        }

        if (!$posts) {
            if (WP_DEBUG) {
                $error_string = _('No result founded');
                echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
            }
            return false;
        }

        // test it ##
        #pr( $posts[0] );
        // kick back results ##
        return reset($posts);
    }

    public static function get_types_registered() {
        $typesRegistered = get_post_types(array('public' => true), 'names', 'and');
        $type_esclusi = DCE_TemplateSystem::$supported_types;
        return array_diff($typesRegistered, $type_esclusi);
    }


    public static function get_roles($everyone = true) {
        $all_roles = wp_roles()->roles;
        //var_dump($all_roles); die();
        $ret = array();
        if ($everyone) {
            $ret['everyone'] = 'Everyone';
        }
        foreach ($all_roles as $key => $value) {
            $ret[$key] = $value['name'];
        }
        return $ret;
    }

    public static function get_current_user_role() {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $role = (array) $user->roles;
            return $role[0];
        } else {
            return false;
        }
    }
    
    public static function get_term_posts($term_id, $cpt = 'any') {
        $posts = array();
        $term = self::get_term_by('id', $term_id);
        //var_dump($term);
        if ($term) {
            /*
            $query = new \WP_Query( array(
                'post_type' => $cpt,  // Or your custom post type's slug
                'posts_per_page' => -1, // Do not paginate posts
                'tax_query' => array(
                    array(
                        'taxonomy' => $term->taxonomy,
                        'field' => 'term_id',
                        'value' => $term->term_id
                    )
                )
            ) );
            return $query->get_posts();             
            */
            
            $term_medias = get_posts(array(
                'post_type' => $cpt,
                'numberposts' => -1,
                'tax_query' => array(
                  array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'id',
                    'terms' => $term_id,
                    'include_children' => false
                  )
                )
            ));
            
            return $term_medias;
        }
        return $posts;        
    }

    public static function get_term_fields($meta = false, $group = false) {
        $termTmp = self::get_term_by('id', 1, 'category');
        if ($termTmp) {
            $termPropAll = get_object_vars($termTmp);
            if (!empty($meta) && is_string($meta)) {
                $termProp = array();
                foreach ($termPropAll as $key => $value) {
                    $pos_key = stripos($value, $meta);
                    $pos_name = stripos($key, $meta);
                    if ($pos_key === false && $pos_name === false) {
                        continue;
                    }
                    $termProp[$key] = $value;
                }
            } else {
                $termProp = $termPropAll;
            }

            if ($meta) {
                $metas = self::get_term_metas($group, (is_string($meta)) ? $meta : null);
                $termFieldsKey = $metas;
            }

            $termFields = array_keys($termProp);
            if (!empty($termFields)) {
                foreach ($termFields as $value) {
                    $name = str_replace('term_', '', $value);
                    $name = str_replace('_', ' ', $name);
                    $name = ucwords($name);
                    if ($group) {
                        $termFieldsKey['TERM'][$value] = $name;
                    } else {
                        $termFieldsKey[$value] = $name;
                    }
                }
            }

            if ($group) {
                $termFieldsKey = array_merge(['TERM' => $termFieldsKey['TERM']], $termFieldsKey); // in first position
            }
        }
        //var_dump($userFieldsKey); die();
        return $termFieldsKey;
    }
    
    public static function get_term_by($field = 'id', $value = 1, $taxonomy = '') {
        if ($field == 'id' || $field == 'term_id') {
            $term = get_term($value);
        } else {
            $term = get_term_by($field, $value, $taxonomy);
        }
        return $term;
    }

    public static function get_user_fields($meta = false, $group = false) {
        $userFieldsKey = array();
        $userTmp = wp_get_current_user();
        if ($userTmp) {
            $userProp = get_object_vars($userTmp);
            if (!empty($userProp['data'])) {
                $userPropAll = (array) $userProp['data'];
                $userProp = array();
                if (!empty($meta) && is_string($meta)) {
                    foreach ($userPropAll as $key => $value) {
                        $pos_key = stripos($value, $meta);
                        $pos_name = stripos($key, $meta);
                        if ($pos_key === false && $pos_name === false) {
                            continue;
                        }
                        $userProp[$key] = $value;
                    }
                } else {
                    $userProp = $userPropAll;
                }
            }
            //echo '<pre>';var_dump($userProp);echo '</pre>'; die();
            //$userMeta = get_registered_meta_keys('post');
            //$userFields = array_merge(array_keys($userProp), array_keys($userMeta));

            if ($meta) {
                $metas = self::get_user_metas($group, (is_string($meta)) ? $meta : null);
                $userFieldsKey = $metas;
            }

            $userFields = array_keys($userProp);
            if (!empty($userFields)) {
                foreach ($userFields as $value) {
                    $name = str_replace('user_', '', $value);
                    $name = str_replace('_', ' ', $name);
                    $name = ucwords($name);
                    if ($group) {
                        $userFieldsKey['USER'][$value] = $name;
                    } else {
                        $userFieldsKey[$value] = $name;
                    }
                }
            }

            $pos_key = is_string($meta) ? stripos('avatar', $meta) : false;
            if (empty($meta) || !is_string($meta) || $pos_key !== false) {
                if ($group) {
                    $userFieldsKey['USER']['avatar'] = 'Avatar';
                } else {
                    $userFieldsKey['avatar'] = 'Avatar';
                }
            }

            if ($group) {
                $userFieldsKey = array_merge(['USER' => $userFieldsKey['USER']], $userFieldsKey); // in first position
            }
        }
        //var_dump($userFieldsKey); die();
        return $userFieldsKey;
    }


    public static function get_adjacent_post_by_id($in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'category', $post_id = null) {
        global $wpdb;

        if ((!$post = get_post($post_id)))
            return null;
        //var_dump($post);

        $current_post_date = $post->post_date;

        $adjacent = $previous ? 'previous' : 'next';
        $op = $previous ? '<' : '>';
        $join = '';
        $order = $previous ? 'DESC' : 'ASC';

        $where = $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish'", $current_post_date, $post->post_type);
        $sort = "ORDER BY p.post_date $order LIMIT 1";

        $query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";

        //echo $query;

        $result = $wpdb->get_var($query);
        if (null === $result)
            $result = '';

        if ($result)
            $result = get_post($result);

        return $result;
    }

    


    

    public static function wooc_data($idprod = null) {
        global $product;

        if (function_exists('is_product')) {

            if (isset($idprod)) {
                $product = wc_get_product($idprod);
            } else {
                $product = wc_get_product();
            }
        }
        if (empty($product))
            return;

        return $product;
    }

    public static function get_rev_ID($revid, $revtype) {
        $rev_id = apply_filters('wpml_object_id', $revid, $revtype, true);
        if (!$rev_id)
            return $revid;
        return $rev_id;
    }

    /* public static function memo_globalid() {
      global $global_ID;
      global $original_global_ID;
      $original_global_ID = $global_ID;
      } */
    /* public static function reset_globalid() {
      global $global_ID;
      global $original_global_ID;
      $global_ID = $original_global_ID;
      } */


    public static function get_post_value($post_id = null, $field = 'ID', $single = null) {
        $postValue = null;

        if (!$post_id) {
            $post_id = get_the_ID();
        }

        if ($field == 'permalink' || $field == 'get_permalink') {
            $postValue = get_permalink($post_id);
        }

        if ($field == 'post_excerpt' || $field == 'excerpt') {
            //$postValue = get_the_excerpt($post_id);
            $post = get_post($post_id);
            $postValue = $post->post_excerpt;
        }

        if ($field == 'the_author' || $field == 'post_author' || $field == 'author') {
            $postValue = get_the_author();
        }

        if (in_array($field, array('thumbnail', 'post_thumbnail', 'thumb'))) {
            $postValue = get_the_post_thumbnail();
        }

        if (!$postValue) {
            if (property_exists('WP_Post', $field)) {
                $postTmp = get_post($post_id);
                $postValue = $postTmp->{$field};
            }
        }
        if (!$postValue) {
            if (property_exists('WP_Post', 'post_' . $field)) {
                $postTmp = get_post($post_id);
                if ($postTmp) {
                    $postValue = $postTmp->{'post_' . $field};
                }
            }
        }
        if (!$postValue || !$single) {
            if (metadata_exists('post', $post_id, $field)) {
                $postValue = get_post_meta($post_id, $field, $single);
            }
        }
        if (!$postValue) { // fot meta created with Toolset plugin
            if (metadata_exists('post', $post_id, 'wpcf-' . $field)) {
                $postValue = get_post_meta($post_id, 'wpcf-' . $field, $single);
            }
        }
        
        if (!$postValue) { // fot meta WooCoomerce plugin
            if (metadata_exists('post', $post_id, '_' . $field)) {
                $postValue = get_post_meta($post_id, '_' . $field, $single);
            }
        }
        
        if (!$postValue) {
            $postValue = array();
            $post_terms = get_the_terms($post_id, $field);
            if (!empty($post_terms) && !is_wp_error($post_terms)) {
                foreach ($post_terms as $key => $aterm) {
                    $postValue[$aterm->term_id] = $aterm->taxonomy;
                }
            } else {
                // woocommerce taxonomies (Attributes) begin with pa_
                $post_terms = get_the_terms($post_id, 'pa_'.$field);
                if (!empty($post_terms) && !is_wp_error($post_terms)) {
                    foreach ($post_terms as $key => $aterm) {
                        $postValue[$aterm->term_id] = $aterm->name;
                    }
                }
            }
        }
        
        if (is_array($postValue)) {
            if (empty($postValue)) return '';
            if ($single === true || count($postValue) == 1) return reset($postValue);
        }

        return $postValue;
    }

    public static function get_user_value($user_id = null, $field = 'display_name', $single = null) {
        $metaValue = '';
        if ($user_id) {            
            $userTmp = get_user_by('ID', $user_id);
            if ($userTmp) {
                //if (property_exists('WP_User', $metaKey[0])) {
                // campo nativo
                if (@$userTmp->data->{$field}) {
                    //$userTmp = get_user_by('ID', $user_id);
                    $metaValue = $userTmp->data->{$field};
                }                
                if (!$metaValue) {
                    if (@$userTmp->data->{'user_' . $field}) {
                        //if (property_exists('WP_User', 'user_'.$metaKey[0])) {
                        //$userTmp = get_user_by('ID', $user_id);
                        $metaValue = $userTmp->data->{'user_' . $field};
                    }
                }
                
                // altri campi nativi
                if (!$metaValue) {
                    $userInfo = get_userdata($user_id);
                    if (@$userInfo->{$field}) {
                        $metaValue = $userInfo->{$field};
                    }
                    if (!$metaValue) {
                        if (@$userInfo->{'user_' . $field}) {
                            $metaValue = $userInfo->{'user_' . $field};
                        }
                    }
                    
                }                
                // campo meta
                if (!$metaValue || !$single) {
                    if (metadata_exists('user', $user_id, $field)) {        
                        $metaValue = get_user_meta($user_id, $field, false);
                    }
                    if (!$metaValue) {
                        // meta from module user_registration
                        if (metadata_exists('user', $user_id, 'user_registration_' . $field)) {
                            $metaValue = get_user_meta($user_id, 'user_registration_' . $field, false);
                        }
                    }
                }
            }
        }
        if (is_array($metaValue)) {
            if (empty($metaValue)) return '';
            if ($single === true || count($metaValue) == 1) return reset($metaValue);
        }
        return $metaValue;
    }

    public static function get_term_value($term = null, $field = 'name', $single = null) {
        $termValue = null;

        if (!is_object($term)) {
            $term = self::get_term_by('id', $term);
        }

        if ($field == 'permalink' || $field == 'get_permalink' || $field == 'get_term_link' || $field == 'term_link') {
            $termValue = get_term_link($term);
        }

        if (!$termValue) {
            if (property_exists('WP_Term', $field)) {
                $termValue = $term->{$field};
            }
        }
        if (!$termValue) {
            if (property_exists('WP_Term', 'term_' . $field)) {
                $termValue = $term->{'term_' . $field};
            }
        }
        if (!$termValue) {
            if (metadata_exists('term', $term->term_id, $field)) {
                $termValue = get_term_meta($term->term_id, $field, false);
            }
        }
        if (!$termValue) { // fot meta created with Toolset plugin
            if (metadata_exists('term', $term->term_id, 'wpcf-' . $field)) {
                $termValue = get_term_meta($term->term_id, 'wpcf-' . $field, false);
            }
        }
        if (is_array($termValue)) {
            if (empty($termValue)) return '';
            if ($single === true || count($termValue) == 1) return reset($termValue);
        }
        return $termValue;
    }

    
    
    public static function get_post_link($post_id = null) {
        return get_permalink($post_id);
    }
    public static function get_user_link($user_id = null) {
        if (!$user_id) {
            $user_id = get_the_author_meta('ID');
        }
        return get_author_posts_url($user_id);
    }
    public static function get_term_link($term_id = null) {
        return get_term_link($term_id);
    }


    
    public static function get_options($like = '') {
        global $wpdb;
        $options = array();
        $query = 'SELECT option_name FROM ' . $wpdb->prefix . 'options';
        if ($like) {
            $query .= " WHERE option_name LIKE '%".$like."%'";
        }
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            foreach ($results as $key => $aopt) {
                $options[$aopt->option_name] = $aopt->option_name;
            }
            ksort($options);
        }
        return $options;
    }
    


    public static function in_the_loop() {
        global $in_the_loop;
        return in_the_loop() || $in_the_loop;
    }
    
    

    public static function get_dynamic_value($value, $fields = array(), $var = 'form') {
        if (is_array($value)) {
            if (!empty($value)) {
                foreach ($value as $key => $setting) {
                    if (is_string($setting)) {
                        $value[$key] = self::get_dynamic_value($setting, $fields);
                    }
                    // repeater
                    if (is_array($setting)) {
                        foreach ($setting as $akey => $avalue) {
                            if (is_array($avalue)) {
                                foreach ($avalue as $rkey => $rvalue) {
                                    $value[$key][$akey][$rkey] = self::get_dynamic_value($rvalue, $fields);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (is_string($value)) {
            $value = DCE_Tokens::do_tokens($value);
            $value = do_shortcode($value);
            if (!empty($fields)) {
                $value = self::replace_setting_shortcodes($value, $fields);
                $value = DCE_Tokens::replace_var_tokens($value, $var, $fields);
            }
        }
        return $value;
    }

    
    
    public static function get_post_css($post_id = null, $theme = false) {
        $upload = wp_upload_dir();
        $elementor_styles = array(
            'elementor-frontend-css' => ELEMENTOR_ASSETS_PATH . 'css/frontend.min.css',
            //'elementor-icons-css' => ELEMENTOR_ASSETS_PATH . 'lib/eicons/css/elementor-icons.min.css',
            'elementor-common-css' => ELEMENTOR_ASSETS_PATH . 'css/common.min.css',
            //'elementor-animations-css' => ELEMENTOR_ASSETS_PATH . 'lib/animations/animations.min.css',
            'dce-frontend-css' => DCE_PATH . DCE_Assets::$minifyCss,
            
        );
        if ($theme) {
            $elementor_styles['theme-style'] = get_stylesheet_directory().'/style.css';
            if (is_child_theme()) {
                $elementor_styles['theme-templatepath'] = TEMPLATEPATH.'/style.css';
                $elementor_styles['theme-templatepath'] = TEMPLATEPATH.'/assets/css/style.css';
                //$elementor_styles['theme-stylesheetpath'] = STYLESHEETPATH;
            }
        }
        //var_dump($elementor_styles); die();
        if (self::is_plugin_active('elementor-pro')) {
            $elementor_styles['elementor-pro-css'] = ELEMENTOR_PRO_ASSETS_PATH . 'css/frontend.min.css';
        }
        if ($post_id) {
            $elementor_styles['elementor-post-' . $post_id . '-css'] = $upload['basedir'] . '/elementor/css/post-' . $post_id . '.css';
        }
        $css = '';
        foreach ($elementor_styles as $key => $astyle) {
            //echo $astyle;
            $css .= self::get_style_embed($astyle);
        }
        //var_dump($css); die();
        return $css;
    }
    
    public static function get_style_embed($style) {
        $css = '';
        /* global $wp_styles;
          //$css = var_export($wp_styles->registered, true);
          if (!empty($wp_styles->registered[$style])) {
          $src = $wp_styles->registered[$style]->src;
          $css_file = get_stylesheet_directory_uri() . $src;
          $css .= $css_file;
          if (file_exists($css_file)) {
          $css = file_get_contents($css_file);
          }
          } */
        //$css = $style;
        if (file_exists($style)) {
            $css = file_get_contents($style);
        }
        return $css;
    }
    
    public static function auto_login($uid) {
            if (is_int($uid)) {
                $user = get_user_by( 'ID', $uid );
            } else {
                $user = get_user_by( 'login', $uid );
            }
            if ( ! $user instanceof WP_User ) {
                    return;
            }
            // login as this user
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID );
            do_action( 'wp_login', $user->user_login, $user );
    }
    
}
