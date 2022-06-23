<?php
namespace DynamicContentForElementor;

/**
 * DCE Tokens Class
 *
 * @since 0.1.0
 */
class DCE_Tokens {
    
    // List of Drupal Tokens: https://www.drupal.org/node/390482
    static public function do_tokens($text = '') {
        if (!is_string($text)) {
            return $text;
        }
        return self::replace_all_tokens($text);
    }

    static public function replace_all_tokens($text) {
        //var_dump(get_class(get_queried_object()));
        $text = self::replace_date_tokens($text);
        $text = self::replace_author_tokens($text);
        $text = self::replace_user_tokens($text);
        $text = self::replace_post_tokens($text);
        
        $text = self::replace_form_tokens($text);
        $text = self::replace_wp_query_tokens($text);
        //$text = self::replace_var_tokens($text);
        $text = self::replace_term_tokens($text);
        $text = self::replace_option_tokens($text);
        $text = self::replace_system_tokens($text);
        $text = self::replace_comment_tokens($text);
        
        if (DCE_Helper::is_plugin_active('woocommerce')) {
            $text = self::replace_product_tokens($text);
        }
        
        return $text;
    }
    
    static public function replace_tokens_with_his_name($text, $var_name) {
        $pezzi = explode('['.$var_name, $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {                
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);
                    $subfield = '';
                    if (substr($metaParams, 0, 1) == ':') {
                        $metaParams = substr($metaParams, 1);
                        $subfield = ':';
                    }
                    $morePezzi = explode('?', $metaParams, 2);
                    $pezzoTmp = reset($morePezzi);
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    $metaName = reset($altriPezzi);
                    $metaKey = explode(':', $metaName);
                    $field = reset($metaKey);

                    $replaceValue = $field;
                    
                    $text = str_replace('['.$var_name.$subfield . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    static public function replace_form_tokens($text) {
        global $dce_form;
        
        $text = str_replace('[form:pdf]', '<!--[dce_form_pdf:attachment]-->', $text); // pdf mail attachment
        
        if (is_null($dce_form)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $text = self::replace_tokens_with_his_name($text, 'form');
            }
        } else {
            $text = self::replace_content_shortcodes($text, '[form:all-fields]', $dce_form);
            $text = self::replace_var_tokens($text, 'form', $dce_form);
        }
        return $text;
    }
    
    static public function replace_content_shortcodes($text, $all_fields_shortcode, $record, $line_break = '<br>') {
        if (false !== strpos($text, $all_fields_shortcode)) {
            $fields = '';
            foreach ($record as $fkey => $fvalue) {
                if (is_array($fvalue)) {
                    switch ($fkey) {
                        case 'pdf':
                            $fvalue = $fvalue['url'];
                            break;
                        default: 
                            $fvalue = var_export($fvalue, 1);
                    }
                }
                $formatted = '';
                if (!empty($fkey)) {
                    $formatted = sprintf('<b>%s:</b> %s', $fkey, $fvalue);
                } elseif (!empty($fvalue)) {
                    $formatted = sprintf('%s', $fvalue);
                }
                $fields .= $formatted . $line_break;
            }
            $text = str_replace($all_fields_shortcode, $fields, $text);
        }
        return $text;
    }
    
    static public function replace_comment_tokens($text) {
        // TODO
        return $text;
    }
    
    static public function replace_author_tokens($text) {
        $pezzi = explode('[author', $text);
        $author_ID = 0;
        if (count($pezzi) > 1) {
            global $authordata;
            //$author = get_the_author();
            //$user = get_user_by('display_name', $author);
            $author_ID = get_the_author_meta('ID');
            /*if ($authordata) {
                $author_ID = $authordata->ID;
            }
            if (!$author_ID) {
                $post = get_post();
                if ($post) {
                    $author_ID = $post->post_author;
                }
            }*/
            if ($author_ID) {
                foreach ($pezzi as $key => $avalue) {
                    if ($key) {
                        $metaTmp = explode(']', $avalue);
                        $metaParams = reset($metaTmp);
                        $metaParamsAuthor =  $metaParams.'|'.$author_ID;
                        $text = str_replace('[author'.$metaParams.']', '[author'.$metaParamsAuthor.']', $text);
                    }
                }
            }
            $text = str_replace('[author', '[user', $text);
            $text = self::replace_user_tokens($text);
        }
        return $text;
    }
    static public function replace_user_tokens($text) {
        //$current_user = wp_get_current_user();
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            //$current_user_id = get_the_author_meta('ID');
        }
        $user_id = $current_user_id;
        // user field
        $pezzi = explode('[user:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    $single = null;
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        foreach ($filtersTmp as $afilter) {
                            if (is_numeric($afilter) && intval($afilter) > 0) {
                                $user_id = intval($afilter);
                            }
                            if ($afilter == 'author') {
                                $user_id = get_the_author_meta('ID');
                            }
                            if ($afilter == 'single') {
                                $single = true;
                            }
                            if ($afilter == 'multiple') {
                                $single = false;
                            }
                        }
                    }
                    $metaName = reset($altriPezzi);
                    $metaKey = explode(':', $metaName);
                    $field = array_shift($metaKey);
                    $metaValue = DCE_Helper::get_user_value($user_id, $field, $single);
                    $replaceValue = self::check_array_value($metaValue, $metaKey);
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi), $user_id, $field);
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('[user:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    static public function replace_post_tokens($text) {
        $current_post_id = $post_id = get_the_ID();
        $current_post = get_post();
        if ($current_post) {
            $current_post_id = $post_id = $current_post->ID;
        }
        // post field
        $pezzi = explode('[post:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $single = null;
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        foreach ($filtersTmp as $afilter) {
                            if (is_numeric($afilter) && intval($afilter) > 0) {
                                $post_id = intval($afilter);
                            }
                            if ($afilter == 'single') {
                                $single = true;
                            }
                            if ($afilter == 'multiple') {
                                $single = false;
                            }
                        }
                    }

                    $metaName = reset($altriPezzi);

                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $field = array_shift($metaKey);
                    $metaValue = '';
                    if ($post_id) {                        
                        $metaValue = DCE_Helper::get_post_value($post_id, $field, $single);
                        /*if (!$metaValue) {
                            $metaValue = self::check_array_value($current_post, $field);
                        }*/
                    }
                    
                    $replaceValue = self::check_array_value($metaValue, $metaKey);
                    //$checkValue = eval($replaceValue);
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi), $post_id, $field);
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('[post:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }
    
    static public function replace_product_tokens($text) {
        global $product;
        $current_post_id = $post_id = get_the_ID();
        $current_post = wc_get_product();
        //var_dump($current_post); die();
        /*if ($current_post) {
            $current_post_id = $post_id = $current_post->ID;
        }*/
        // post field
        $pezzi = explode('[product:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $single = null;
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        foreach ($filtersTmp as $afilter) {
                            if (is_numeric($afilter) && intval($afilter) > 0) {
                                $post_id = intval($afilter);
                            }
                            if ($afilter == 'single') {
                                $single = true;
                            }
                            if ($afilter == 'multiple') {
                                $single = false;
                            }
                        }
                    }

                    $metaName = reset($altriPezzi);

                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $field = array_shift($metaKey);
                    
                    $metaValue = '';
                    if ($post_id) {      
                        //var_dump($field);
                        switch ($field) {
                            case 'variation_attributes':
                                //var_dump(get_class($current_post));
                                if (get_class($current_post) == 'WC_Product_Variable') {
                                    $metaValue = $current_post->get_variation_attributes();
                                    break;
                                }
                                
                            default:  
                                $metaValue = DCE_Helper::get_post_value($post_id, $field, $single);
                                /*if (!$metaValue) {
                                    $metaValue = self::check_array_value($current_post, $field);
                                }*/
                        }
                    }
                    
                    $replaceValue = self::check_array_value($metaValue, $metaKey);
                    //$checkValue = eval($replaceValue);
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi), $post_id, $field);
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('[product:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }
    
    static public function replace_system_tokens($text) {
        $pezzi = explode('[system:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $altriPezzi = explode('|', $pezzoTmp, 2);

                    $metaName = reset($altriPezzi);

                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $metaKeyName = array_shift($metaKey);
                    $metaValue = array();
                    switch($metaKeyName) {
                        case 'get':
                        case '_get':
                        case '_GET':
                            $metaValue = $_GET;
                            break;
                        case 'post':
                        case '_post':
                        case '_POST':
                            $metaValue = $_POST;
                            break;
                        case '_reuqest':
                        case '_REQUEST':
                        case 'request':
                            $metaValue = $_REQUEST;
                            break;
                        case 'cookie':
                        case '_cookie':
                        case '_COOKIE':
                            $metaValue = $_COOKIE;
                            break;
                        case 'server':
                        case '_server':
                        case '_SERVER':
                            $metaValue = $_SERVER;
                            break;
                        default:
                            if (defined($metaKeyName)) {
                                $metaValue = constant($metaKeyName);
                            } else if (defined(strtoupper($metaKeyName))) {
                                $metaValue = constant(strtoupper($metaKeyName));
                            }
                    }
                    $replaceValue = self::check_array_value($metaValue, $metaKey);
                    
                    //$checkValue = eval($replaceValue);
                    //var_dump($altriPezzi); die();
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi));
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    //var_dump($replaceValue);
                    $text = str_replace('[system:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }
    
    static public function replace_wp_query_tokens($text) {
        global $wp_query;
        //var_dump($wp_query);
        $pezzi = explode('[wp_query:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $altriPezzi = explode('|', $pezzoTmp, 2);

                    $metaName = reset($altriPezzi);

                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $metaValue = $wp_query;
                    switch ($metaValue) { 
                        case 'referer':
                            $replaceValue = wp_get_referer();
                            break;
                        default:
                            $replaceValue = self::check_array_value($metaValue, $metaKey);
                    }
                    //$checkValue = eval($replaceValue);
                    //var_dump($altriPezzi); die();
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi));
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    //var_dump($replaceValue);
                    $text = str_replace('[wp_query:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    static public function replace_var_tokens($text, $var_name, $var_value) {
        $current_post_id = $post_id = get_the_ID();
        $var_value_original = $var_value;
        if (is_object($var_value)) {
            $var_value = get_object_vars($var_value);
        } else {
            $var_value = maybe_unserialize($var_value);
        }
        //if (trim($text) == '['.$var_name.']') {
            $text = str_replace('['.$var_name.']', DCE_Helper::to_string($var_value), $text); // simple
        //}
        // var field
        //$pezzi = explode('['.$var_name.':', $text);
        $pezzi = explode('['.$var_name, $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    $subfield = '';
                    if (substr($metaParams, 0, 1) == ':') {
                        if (is_object($var_value_original)) {
                            //var_dump(get_class($var_value_original));
                            switch(get_class($var_value_original)) {
                                case 'WP_User':
                                    if ($var_name == 'user' || $var_name == 'object') {
                                        $text = str_replace('['.$var_name . $metaParams . ']', '[user' . $metaParams . ']', $text);
                                        return self::replace_user_tokens($text);
                                    }
                                    break;
                                case 'WP_Post':
                                    if ($var_name == 'post' || $var_name == 'object') {
                                        $text = str_replace('['.$var_name . $metaParams . ']', '[post' . $metaParams . ']', $text);
                                        return self::replace_post_tokens($text);    
                                    }
                                    break;
                                case 'WP_Term':
                                    if ($var_name == 'term' || $var_name == 'object') {
                                        $text = str_replace('['.$var_name . $metaParams . ']', '[term' . $metaParams . ']', $text);
                                        //return self::replace_term_tokens($text);    
                                    }
                                    break;
                            }
                        }
                        $metaParams = substr($metaParams, 1);
                        $subfield = ':';
                    }

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    $post_id = get_the_ID();
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        if (is_numeric(reset($filtersTmp)) && intval(reset($filtersTmp)) > 0) {
                            $post_id = reset($filtersTmp);
                        }
                    }

                    $metaName = reset($altriPezzi);

                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $field = reset($metaKey);

                    $replaceValue = self::check_array_value($var_value, $metaKey);
                    if (count($altriPezzi) == 2) {
                        // APPLY FILTERS
                        $replaceValue = self::apply_filters($replaceValue, end($altriPezzi), $post_id, $field);
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('['.$var_name.$subfield . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    static public function replace_term_tokens($text) {
        $pezzi = explode('[term:', $text);
        if (count($pezzi) > 1) {
            global $wp_query;
            $terms = array();
            $queried_object = $wp_query->queried_object; //get_queried_object();
            //echo '<pre>';var_dump( $queried_object );echo '</pre>';
            if ($queried_object) {
                if (get_class($queried_object) == 'WP_Term') {
                    $terms[] = $queried_object;
                }
                if (get_class($queried_object) == 'WP_Post' || DCE_Helper::in_the_loop()) {
                    $terms = DCE_Helper::get_post_terms();
                } 
            }
            $taxonomies = DCE_Helper::get_taxonomies();
            foreach ($pezzi as $key => $avalue) {
                $filters = array();
                if ($key) {
                    $metaTmp = explode(']', $avalue);
                    $metaParams = reset($metaTmp);

                    // GET FALLBACK
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);

                    // GET FILTERS or ID
                    $tterms = $terms;
                    $single = null;
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        foreach ($filtersTmp as $afilter) {
                            if ($afilter == 'single') {
                                $single = true;
                            }
                            if ($afilter == 'multiple') {
                                $single = false;
                            }
                            if (is_numeric($afilter) && intval($afilter) > 0) {
                                $term_id = $afilter;
                                if ($term_id) {
                                    $my_term = get_term_by('term_taxonomy_id', $term_id);
                                    $tterms = array($my_term);
                                }
                            } else {
                                if (isset($taxonomies[$afilter])) {
                                    $terms_tmp = array();
                                    if (!empty($tterms)) {
                                        foreach ($tterms as $aterm) {
                                            if ($aterm->taxonomy == $afilter) {
                                                $terms_tmp[] = $aterm;
                                            }
                                        }
                                        $tterms = $terms_tmp;
                                    }
                                } else {
                                    if ($afilter == 'first' || $afilter == 'reset') {
                                        $tterms = array(reset($tterms));
                                    }
                                    if ($afilter == 'last' || $afilter == 'end') {
                                        $tterms = array(end($tterms));
                                    }
                                }
                            }
                        }
                    }

                    $metaName = reset($altriPezzi);
                    //var_dump($metaName);
                    // GET SUB ARRAY
                    $metaKey = explode(':', $metaName);
                    $field = array_shift($metaKey);
                    $replaceValue = array();
                    if (!empty($tterms)) {
                        foreach ($tterms as $key => $aterm) {
                            $metaValue = DCE_Helper::get_term_value($aterm, $field, $single);
                            $metaValue = self::check_array_value($metaValue, $metaKey);
                            if (count($altriPezzi) == 2) {
                                // APPLY FILTERS
                                $metaValue = self::apply_filters($metaValue, end($altriPezzi), $aterm->term_id, $field);
                            }
                            $replaceValue[] = $metaValue;
                        }
                    }                    
                    
                    $replaceValue = array_filter($replaceValue);
                    if (!empty($replaceValue)) {
                        $replaceValue = implode(', ', $replaceValue);
                    }
                    //$checkValue = eval($replaceValue);
                    
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('[term:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    static public function replace_option_tokens($text) {
        // /wp-admin/options.php
        $pezzi = explode('[option:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                if ($key) {
                    $pezzo = explode(']', $avalue);
                    $metaParams = reset($pezzo);
                    $morePezzi = explode('?', $metaParams, 2);
                    $fallback = '';
                    if (count($morePezzi) == 2) {
                        $fallback = end($morePezzi);
                    }
                    $pezzoTmp = reset($morePezzi);
                    
                    // GET FILTERS or ID
                    $altriPezzi = explode('|', $pezzoTmp, 2);
                    
                    $optionParams = explode(':', reset($altriPezzi));
                    $optionName = array_shift($optionParams);
                    $optionValue = get_option($optionName);
                    $replaceValue = self::check_array_value($optionValue, $optionParams);
                    if (count($altriPezzi) == 2) {
                        $filtersTmp = explode('|', end($altriPezzi));
                        $replaceValue = self::apply_filters($replaceValue, $filtersTmp);
                    }
                    $replaceValue = self::value_or_fallback($replaceValue, $fallback);
                    
                    $text = str_replace('[option:' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }
    
    static public function replace_date_tokens($text) {
        $text = str_replace('[date]', '[date:now]', $text);
        $text = str_replace('[date|', '[date:now|', $text);
        // /wp-admin/options.php
        //var_dump($text);
        $pezzi = explode('[date:', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                if ($key) {
                    $pezzo = explode(']', $avalue);
                    $metaParams = reset($pezzo);
                    //if (in_array(substr($metaParams, 0,1), array(':','|'))) {
                        //$metaParams = substr($metaParams, 1);
                        $altriPezzi = explode('|', $metaParams,2);
                        $filtersTmp = array();
                        if (count($altriPezzi) == 2) {
                            $filtersTmp = explode('|', end($altriPezzi));
                        }
                        // GET TIMESTAMP
                        $timestamp = '';

                        // date format
                        $dateFormat = get_option( 'date_format' );
                        if (!empty($filtersTmp)) {
                            foreach ($filtersTmp as $pkey => $pvalue) {
                                if (!$pkey) {
                                    if ($pvalue && !is_callable($pvalue) && $pvalue != 'IT') {
                                        $dateFormat = $pvalue;
                                    }
                                }
                            }
                        }

                        $pezzoTmp = reset($altriPezzi);
                        //var_dump($pezzoTmp);
                        $dateParams = explode(':', $pezzoTmp);

                        $altTime = reset($dateParams);
                        if ($altTime == 'post' || $altTime == 'user' ) { // from post field
                            $lastParam = end($dateParams);
                            $timeMod = false;
                            if (in_array(substr(trim($lastParam),0,1), array('-', '+'))) {
                                $timeMod = array_pop($dateParams);
                                $pezzoTmp = implode(':', $dateParams);
                            }
                            $altTime = self::do_tokens('['.$pezzoTmp.']');
                            $altTime = strtotime($altTime);
                            if ($timeMod) {
                                $altTime = strtotime($timeMod, $altTime);
                            }
                        }
                        if (is_numeric($altTime)) {
                            $timestamp = $altTime;
                        } else {
                            $timestamp = strtotime($pezzoTmp);
                        }
                        //$dateFormat = $pezzoTmp; //$dateParams[0];
                        $replaceValue = date_i18n($dateFormat, $timestamp);

                        // translate
                        if (!empty($filtersTmp)) {
                            /*foreach ($filtersTmp as $pkey => $pvalue) {
                                $replaceValue = self::str_translate($replaceValue, $pvalue);
                            }*/
                            $replaceValue = self::apply_filters($replaceValue, $filtersTmp);
                        }

                        //$replaceValue = self::check_array_value($dateValue, $dateFormat); //$dateParams);
                        //$text = str_replace('[date|' . $metaParams . ']', $replaceValue, $text); // now
                        $text = str_replace('[date:' . $metaParams . ']', $replaceValue, $text); // custom date
                    //}
                }
            }
        }
        return $text;
    }
    
    static public function value_or_fallback($replaceValue, $fallback = '') {
        if (is_array($replaceValue) || is_object($replaceValue)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() && false) {
                $replaceValue = '<pre>'.var_export($replaceValue, true).'</pre>';
            } else {
                // FALLBACK
                //$replaceValue = $fallback;
                $post_ids = array();
                if (is_array(reset($replaceValue))) {
                    $first = reset($replaceValue);
                    if (isset($first['ID'])) {
                        foreach ($replaceValue as $apost) {
                            if (isset($apost['ID'])) {
                                $post_ids[] = $apost['ID'];
                            }
                        }
                        $replaceValue = $post_ids;
                    }
                }
                if (is_object(reset($replaceValue))) {
                    if (get_class(reset($replaceValue)) == 'WP_Post') {
                        foreach ($replaceValue as $apost) {
                            $post_ids[] = $apost->ID;
                        }
                        $replaceValue = $post_ids;
                    }
                    $replaceValue = (array)$replaceValue;                
                }
                
                $replaceValue = implode(', ', $replaceValue);
            }
            
            /*if (is_array($replaceValue)) {
                if (count($replaceValue) == 1) {
                    $tmpValue = reset($replaceValue);
                    if (!is_array($tmpValue)) {
                        $replaceValue = $tmpValue;
                    }
                }
            }*/
        }
        if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if ($replaceValue == '' 
                || substr($replaceValue,0,12) == '<pre>array ('
                || substr($replaceValue,0,12) == '<pre>object(') {
                // FALLBACK
                $replaceValue = $fallback;
            }
        }
        
        return $replaceValue;
    }

    static public function check_array_value($optionValue = array(), $optionParams = array()) {
        if (!is_array($optionParams)) {
            $optionParams = array($optionParams);
        }
        //var_dump($optionParams);
        
        $val = $optionValue;
        if (!empty($optionParams)) {
            foreach ($optionParams as $key => $value) {
                if (is_array($val)) {
                    /*if (count($val) == 1) {
                        $tmpValue = reset($val);
                        if (!is_array($tmpValue)) {
                            $val = $tmpValue;
                        }
                    }*/
                    if ($value == 'permalink') {
                        if (isset($val['post_author'])) {
                            return \DynamicContentForElementor\DCE_Helper::get_post_link($val['ID']);
                        }
                        if (isset($val['term_id'])) {
                            return \DynamicContentForElementor\DCE_Helper::get_term_link($val['term_id']);
                        }
                        if (isset($val['user_login'])) {
                            return \DynamicContentForElementor\DCE_Helper::get_user_link($val['ID']);
                        }
                        return '#';
                    }elseif (array_key_exists($value, $val)) {
                        $val = $val[$value];
                    } else {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            return '<pre>'.var_export($val, true).'</pre>';
                        } else {
                            return false;
                        }
                    } 

                } else if (is_object($val)) {
                    $val_class = get_class($val);
                    
                    if ($value == 'permalink') {
                        if ($val_class == 'WP_Post') {
                            return \DynamicContentForElementor\DCE_Helper::get_post_link($val->ID);
                        }
                        if ($val_class == 'WP_Term') {
                            return \DynamicContentForElementor\DCE_Helper::get_term_link($val->term_id);
                        }
                        if ($val_class == 'WP_User') {
                            return \DynamicContentForElementor\DCE_Helper::get_user_link($val->ID);
                        }
                        return '#';
                    }elseif (property_exists($val_class, $value)) {
                        $val = $val->{$value};
                    } elseif (method_exists($val_class, $value)) {
                        $val = $val->{$value}();
                    } else {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            return '<pre>'.var_export($val, true).'</pre>';
                        } else {
                            return false;
                        }
                    }
                }

            }
        }
        
        if (is_array($val) || is_object($val)) {
            //return '<pre>'.var_export($val, true).'</pre>';
        }
        
        return $val;
    }
    
    public static function remove_quote($parameters = array()) {
        if (!empty($parameters)) {
            foreach ($parameters as $pkey => $pvalue) {
                $parameters[$pkey] = trim($pvalue);
                if ((substr($pvalue,0,1) == '"' && substr($pvalue,-1) == '"')
                    || (substr($pvalue,0,1) == "'" && substr($pvalue,-1) == "'")) {
                    $parameters[$pkey] = substr($pvalue,1,-1); // remove quote
                }
            }
        }
        return $parameters;
    }
    
    public static function apply_filters($replaceValue = false, $altriPezzi = '', $post_id = 0, $field = '') {
        if (is_string($altriPezzi)) {
            $filtersTmp = explode('|', $altriPezzi);
        }
        if (is_array($altriPezzi)) {
            $filtersTmp = $altriPezzi;
        }
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        $filters = array();
        foreach ($filtersTmp as $afilter) {
            if (!is_numeric($afilter) && !intval($afilter) > 0) {
                $afilterTmp = explode('(', $afilter,2);
                if (count($afilterTmp) == 2) {
                    $parameter_string = substr(end($afilterTmp),0,-1);
                    $parameters = explode(',', $parameter_string);
                    $parameters = self::remove_quote($parameters);
                    $parameters = array_filter($parameters); // TO FIX remove also "0"
                    if (empty($parameters)) {
                        $parameters[] = $parameter_string;
                        $parameters = self::remove_quote($parameters);
                    }
                    $kfilter = reset($afilterTmp);
                    $filters[$kfilter] = $parameters;
                } else {
                    $filters[$afilter] = array(); // no params
                }
            }
        }
        //var_dump($filters);
        // APPLY FILTERS
        if (!empty($filters)) {
            // https://www.w3schools.com/Php/php_ref_string.asp
            // https://www.php.net/manual/en/ref.strings.php
            foreach ($filters as $afilter => $parameters) {
                
                if ($afilter == 'concatenate' || $afilter == 'concat') {
                    $string2 = reset($parameters);
                    $replaceValue = self::concatenate($replaceValue, $string2, count($parameters) > 1);
                    continue;
                }
                
                if ($afilter == 'add' || $afilter == 'sum') {
                    $string2 = reset($parameters);
                    $replaceValue = self::sum($replaceValue, $string2);
                    continue;
                }
                
                if ($afilter == 'opts' || $afilter == 'options') {
                    $string2 = reset($parameters);
                    $replaceValue = DCE_Helper::array_options($replaceValue, $string2);
                    continue;
                }

                // THUMB Custom Size
                if (in_array($field, array('thumbnail','post_thumbnail','thumb','guid'))) {
                    if (strpos($afilter, 'x') !== false) {
                        list($h,$w) = explode('x', $afilter, 2);
                        if (is_numeric($h) && is_numeric($w)) {
                            //$h = intval($h);
                            //$w = intval($w);
                            $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                            $replaceValueThumb = wp_get_attachment_image_src( $post_thumbnail_id, array($w,$h));
                            if ($replaceValueThumb) {
                                $replaceValue = reset($replaceValueThumb);
                            } else {
                                $replaceValue = '';
                            }
                            continue;
                        }
                    }
                }
                if ($afilter == 'eval') {
                    echo 'EVAL is not a secure function, please do NOT use it.';
                    continue;
                }
                
                if ($afilter && is_callable($afilter) && $replaceValue != '') {
                    if (empty($parameters)) {                        
                        $replaceValue = $afilter($replaceValue);
                        //$replaceValue = call_user_func_array($afilter, $replaceValue);
                    } else {
                        /*if (in_array($afilter, array('substr'))) {
                            array_unshift($parameters, $replaceValue);
                        }*/
                        if ($afilter == 'date') {
                            $afilter = 'date_i18n';
                        }
                        
                        if (in_array($afilter, array('implode', 'explode', 'date', 'date_i18n', 'get_the_author_meta'))) {
                            $parameters[] = $replaceValue;
                        } else {
                            array_unshift($parameters, $replaceValue);
                        }
                        $replaceValue = call_user_func_array($afilter, $parameters);
                    }
                }
            }
        }
        return $replaceValue;
    }
    
    public static function user_to_author($txt) {
        return str_replace('[user', '[author', $txt);
    }
        
    public static function concatenate($string1 = '', $string2 = '', $before = false) {
        if ($before) {
            return $string2.$string1;
        }
        return $string1.$string2;
    }
    
    public static function sum($num1 , $num2 = 0) {
        return floatval($num1) + floatval($num2);
    }

}