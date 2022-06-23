<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Elementor {
    
    // ************************************** ELEMENTOR ***************************/
    public static function get_all_template($def = null) {

        $type_template = array('elementor_library', 'oceanwp_library');

        // Return elementor templates array

        if ($def) {
            $templates[0] = 'Default';
            $templates[1] = 'NO';
        } else {
            $templates[0] = 'NO';
        }

        $get_templates = self::get_templates(); //get_posts(array('post_type' => $type_template, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'DESC', 'suppress_filters' => false ));
        //print_r($get_templates);
        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {
                $templates[$template['template_id']] = $template['title'] . ' (' . $template['type'] . ')';
                //$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
                //$types[ $template['template_id'] ] = $template['type'];
            }
        }

        return $templates;
    }
    
    public static function get_templates() {
        return \Elementor\Plugin::instance()->templates_manager->get_source('local')->get_items([
                    'type' => ['section', 'archive', 'page', 'single'],
        ]);
    }
    
    public static function get_settings_by_id($element_id = null, $post_id = null) {
        $settings = array();
        if (!$post_id) {
            $post_id = get_the_ID();
            if (!$post_id) {
                $post_id = $_GET['post'];
            }
        }
        
        // find element settings (because it may not be on post, but in a template)
        global $wpdb;
        $table = $wpdb->prefix . 'postmeta';
        $query = "SELECT post_id FROM " . $table . " WHERE meta_value LIKE '%[{\"id\":\"".$element_id."\",%'";
        //echo $query;
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            $result = reset($results);
            $post_id = reset($result);
            //var_dump($post_id);
        }
        
        $post_meta = json_decode(get_post_meta($post_id, '_elementor_data', true), true);
        //var_dump($post_meta);
        if (!$element_id) {
            return $post_meta;
        }
        $keys_array = self::array_find_deep_value($post_meta, $element_id, 'id');
        if (isset($keys_array['settings'])) {
            return $keys_array['settings'];
        }
        return false;
        /*var_dump($keys_array);
        $keys = '["' . implode('"]["', $keys_array) . '"]';
        $keys = str_replace('["id"]', '["settings"]', $keys);
        eval("\$settings = \$post_meta" . $keys . ";");
        return $settings;*/
    }

    public static function set_all_settings_by_id($element_id = null, $settings = array(), $post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
            if (!$post_id) {
                $post_id = $_GET['post'];
            }
        }
        $post_meta = self::get_settings_by_id(null, $post_id);
        if ($element_id) {
            $keys_array = self::array_find_deep($post_meta, $element_id);
            $keys = '["' . implode('"]["', $keys_array) . '"]';
            $keys = str_replace('["id"]', '["settings"]', $keys);
            eval("\$post_meta" . $keys . " = \$settings;");
            array_walk_recursive($post_meta, function($v, $k) {
                $v = self::escape_json_string($v);
            });
        }
        $post_meta_prepared = json_encode($post_meta);
        $post_meta_prepared = wp_slash($post_meta_prepared);
        update_metadata('post', $post_id, '_elementor_data', $post_meta_prepared);
    }

    public static function set_settings_by_id($element_id, $key, $value = null, $post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
            if (!$post_id) {
                $post_id = $_GET['post'];
            }
        }
        $post_meta = self::get_settings_by_id(null, $post_id);
        $keys_array = self::array_find_deep($post_meta, $element_id);
        $keys = '["' . implode('"]["', $keys_array) . '"]';
        $keys = str_replace('["id"]', '["settings"]', $keys);
        if (is_null($value)) {
            eval("unset(\$post_meta" . $keys . "[\$key]);");
        } else {
            eval("\$post_meta" . $keys . "[\$key] = \$value;");
        }
        array_walk_recursive($post_meta, function($v, $k) {
            $v = self::escape_json_string($v);
        });
        $post_meta_prepared = json_encode($post_meta);
        $post_meta_prepared = wp_slash($post_meta_prepared);
        update_metadata('post', $post_id, '_elementor_data', $post_meta_prepared);
        return $post_id;
    }

    public static function set_dynamic_tag($editor_data) {
        if (is_array($editor_data)) {
            foreach ($editor_data as $key => $avalue) {
                $editor_data[$key] = self::set_dynamic_tag($avalue);
            }
            if (isset($editor_data['elType'])) {
                foreach ($editor_data['settings'] as $skey => $avalue) {
                    //if ($editor_data['type'] == 'text' || $editor_data['type'] == 'textarea') {
                    $editor_data['settings'][\Elementor\Core\DynamicTags\Manager::DYNAMIC_SETTING_KEY][$skey] = 'token';
                }
            }
        }
        return $editor_data;
    }
    
    public static function recursive_array_search($needle, $haystack, $currentKey = '') {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $nextKey = self::recursive_array_search($needle, $value, is_numeric($key) ? $currentKey . '[' . $key . ']' : $currentKey . '["' . $key . '"]');
                if ($nextKey) {
                    return $nextKey;
                }
            } else if ($value == $needle) {
                return is_numeric($key) ? $currentKey . '[' . $key . ']' : $currentKey . '["' . $key . '"]';
            }
        }
        return false;
    }

    public static function array_find_deep($array, $search, $keys = array()) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sub = self::array_find_deep($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($value === $search) {
                return array_merge($keys, array($key));
            }
        }
        return array();
    }
    public static function array_find_deep_value($array, $value, $key) {
        if (is_array($array)) {
            foreach ($array as $akey => $avalue) {
                if (is_array($avalue)) {
                    if (isset($avalue[$key]) && $value == $avalue[$key]) {
                        return $avalue;
                    }
                    $sub = self::array_find_deep_value($avalue, $value, $key);
                    if (!empty($sub)) {
                        return $sub;
                    }
                }
            }
        }
        return false;
    }
    
    // @P mod
    public static function dce_dynamic_data($datasource = false, $fromparent = false) {

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        global $product;
        global $post;
        //
        global $paged;

        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';
        //
        $original_global_ID = $global_ID; // <-----------------------------
        $original_post = $post; // <-----------------------------
        $original_product = $product;
        $original_paged = $paged;

        //
        // 1) ME-STESSO (naturale) - - - - - - - - - - - - - - - - - - - -
        $type_page = get_post_type();
        $id_page = self::get_rev_ID(get_the_ID(), $type_page);

        // ************************************
        $product = self::wooc_data(); //wc_get_product();
        //echo 'natural ...';

        if ($demoPage) {

            // 2) LA-DEMO  - - - - - - - - - - - - - - - - - - - -

            $type_page = get_post_type($demoPage);
            $id_page = $demoPage;
            // ************************************
            $product = self::wooc_data($id_page); //wc_get_product( $id_page );

            $post = get_post($id_page);
            //echo 'DEMO ...'.$id_page.' - '.$type_page;
        }
        if ($global_ID) {

            // 3) ME-STESSO (se in un template) - - - - - - - - - - - - - - - - - - - -

            $type_page = get_post_type($global_ID); //$global_TYPE;
            $id_page = self::get_rev_ID($global_ID, $type_page);
            // ************************************
            // if product noot exist $product

            $product = self::wooc_data($id_page); //wc_get_product( $id_page );
            $post = get_post($id_page);
            //echo 'global ... '.$id_page.' - '.$type_page;
        }
        if ($datasource) {

            // 4) UN'ALTRO-POST (other) - - - - - - - - - - - - - - - - - - -
            //$original_global_ID = $global_ID;

            $type_page = get_post_type($datasource);
            $id_page = self::get_rev_ID($datasource, $type_page);
            //
            $product = self::wooc_data($id_page); //wc_get_product( $id_page );
            $post = get_post($id_page);
            //
            //echo 'data source.. '.$id_page;
        }
        if ($fromparent) {
            // 5) PARENT (of current)  - - - - - - - - - - - - - - - - - - - -
            $type_page = $global_TYPE;
            $id_page = self::get_rev_ID($global_ID, $type_page);

            $the_parent = wp_get_post_parent_id($id_page);
            if ($the_parent != 0) {
                $type_page = get_post_type($the_parent);
                $id_page = self::get_rev_ID($the_parent, $type_page);
            } /* else {
              // the parent not exist
              $id_page = 0;
              $type_page = get_post_type($id_page);
              } */

            $product = self::wooc_data($id_page); //wc_get_product( $id_page );
            $post = get_post($id_page);
            //echo 'parent.. ('.$id_page.') ';
        }
        //echo $type_page;
        //
        //$global_ID = $id_page; // <-----------------------------


        $data = [
            'id' => $id_page, //number
            'global_id' => $original_global_ID,
            'type' => $type_page, //string
            'is' => $global_is, //string
            'block' => $in_the_loop   //boolean
        ];

        $global_ID = $original_global_ID; // <-----------------------------
        //if ($datasource) {
        $post = $original_post;
        if ($type_page != 'product')
            $product = $original_product;
        $paged = $original_paged;
        //}
        //
        return $data;
    }
    
    public static function is_edit_mode() {
        return \Elementor\Plugin::$instance->editor->is_edit_mode() || isset($_GET['elementor-preview']);
    }
    
}
