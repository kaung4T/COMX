<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Meta {
    
    public static $meta_fields = [];
    
    public static function get_acf_types() {
        return array("text", "textarea", "number", "range", "email", "url", "password", "image", "file", "wysiwyg", "oembed", "gallery", "select", "checkbox", "radio", "button_group", "true_false", "link", "post_object", "page_link", "relationship", "taxonomy", "user", "google_map", "date_picker", "date_time_picker", "time_picker", "color_picker", "message", "accordion", "tab", "group", "repeater", "flexible_content", "clone");
    }

    public static function get_pods_types() {
        return array("text", "website", "phone", "email", "password", "paragraph", "wysiwyg", "code", "datetime", "date", "time", "number", "currency", "file", "oembed", "pick", "boolean", "color");
    }

    public static function get_toolset_types() {
        return array("audio", "colorpicker", "email", "embed", "file", "image", "numeric", "phone", "textarea", "textfield", "url", "video", "checkboxes", "checkbox", "date", "radio", "select", "skype", "wysiwyg", "multiple");
    }
    
    public static function get_user_metas($grouped = false, $like = '') {
        global $wp_meta_keys;

        $userMetas = $userMetasGrouped = array();

        // ACF
        $acf_groups = get_posts(array('post_type' => 'acf-field-group', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
        if (!empty($acf_groups)) {
            foreach ($acf_groups as $aacf_group) {
                $is_user_group = false;
                $aacf_meta = maybe_unserialize($aacf_group->post_content);
                if (!empty($aacf_meta['location'])) {
                    foreach ($aacf_meta['location'] as $gkey => $gvalue) {
                        foreach ($gvalue as $rkey => $rvalue) {
                            if (substr($rvalue['param'], 0, 5) == 'user_') {
                                $is_user_group = true;
                            }
                        }
                    }
                }
                if ($is_user_group) {
                    $acf = get_posts(array('post_type' => 'acf-field', 'numberposts' => -1, 'post_status' => 'publish', 'post_parent' => $aacf_group->ID, 'suppress_filters' => false));
                    if (!empty($acf)) {
                        foreach ($acf as $aacf) {
                            $aacf_meta = maybe_unserialize($aacf->post_content);
                            if ($like) {
                                $pos_key = stripos($aacf->post_excerpt, $like);
                                $pos_name = stripos($aacf->post_title, $like);
                                if ($pos_key === false && $pos_name === false) {
                                    continue;
                                }
                            }
                            $userMetas[$aacf->post_excerpt] = $aacf->post_title . ' [' . $aacf_meta['type'] . ']';
                            $userMetasGrouped['ACF'][$aacf->post_excerpt] = $userMetas[$aacf->post_excerpt];
                        }
                    }
                    //echo '<pre>';var_dump($aacf_meta);echo '</pre>';
                }
            }
        }

        // PODS
        /* $pods = get_posts(array('post_type' => '_pods_field', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
          if (!empty($pods)) {
          foreach ($pods as $apod) {
          $type = get_post_meta($apod->ID, 'type', true);
          $userMetas[$apod->post_name] = $apod->post_title.' ['.$type.']';
          $userMetasGrouped['PODS'][$apod->post_name] = $userMetas[$apod->post_name];
          }
          } */

        // TOOLSET
        /* $toolset = get_option('wpcf-fields', false);
          if ($toolset) {
          $toolfields = maybe_unserialize($toolset);
          if (!empty($toolfields)) {
          foreach ($toolfields as $atool) {
          $userMetas[$atool['meta_key']] = $atool['name'].' ['.$atool['type'].']';
          $userMetasGrouped['TOOLSET'][$atool['meta_key']] = $userMetas[$atool['meta_key']];
          }
          }
          } */

        // MANUAL
        global $wpdb;
        $table = $wpdb->prefix . 'usermeta';
        //defined( 'CUSTOM_USER_TABLE' );
        if (defined( 'CUSTOM_USER_META_TABLE')) {
            $table = CUSTOM_USER_META_TABLE;
        }
        $query = 'SELECT DISTINCT meta_key FROM ' . $table;
        if ($like) {
            $query .= " WHERE meta_key LIKE '%".$like."%'";
        }
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            $metas = array();
            foreach ($results as $key => $auser) {
                $metas[$auser->meta_key] = $auser->meta_key;
            }
            ksort($metas);
            //$manual_metas = array_diff_key($metas, $userMetas);
            $manual_metas = $metas;
            foreach ($manual_metas as $ameta) {
                if (substr($ameta, 0, 1) == '_') {
                    $ameta = $tmp = substr($ameta, 1);
                    if (in_array($tmp, $manual_metas)) {
                        continue;
                    }
                }
                if (!isset($postMetas[$ameta])) {
                    $userMetas[$ameta] = $ameta;
                    $userMetasGrouped['META'][$ameta] = $ameta;
                }
            }
        }

        //$userMetas = get_user_meta();
        //var_dump($userMetas); die();
        //$userMetasGrouped['NATIVE'] = $userMetas;

        if ($grouped) {
            return $userMetasGrouped;
        }

        return $userMetas;
    }
    
    public static function get_term_metas($grouped = false, $like = '') {
        global $wp_meta_keys;

        $termMetas = $termMetasGrouped = array();

        // ACF
        $acf_groups = get_posts(array('post_type' => 'acf-field-group', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
        if (!empty($acf_groups)) {
            foreach ($acf_groups as $aacf_group) {
                $is_term_group = false;
                $aacf_meta = maybe_unserialize($aacf_group->post_content);
                if (!empty($aacf_meta['location'])) {
                    foreach ($aacf_meta['location'] as $gkey => $gvalue) {
                        foreach ($gvalue as $rkey => $rvalue) {
                            if (substr($rvalue['param'], 0, 5) == 'term_') {
                                $is_term_group = true;
                            }
                        }
                    }
                }
                if ($is_term_group) {
                    $acf = get_posts(array('post_type' => 'acf-field', 'numberposts' => -1, 'post_status' => 'publish', 'post_parent' => $aacf_group->ID, 'suppress_filters' => false));
                    if (!empty($acf)) {
                        foreach ($acf as $aacf) {
                            $aacf_meta = maybe_unserialize($aacf->post_content);
                            if ($like) {
                                $pos_key = stripos($aacf->post_excerpt, $like);
                                $pos_name = stripos($aacf->post_title, $like);
                                if ($pos_key === false && $pos_name === false) {
                                    continue;
                                }
                            }
                            $termMetas[$aacf->post_excerpt] = $aacf->post_title . ' [' . $aacf_meta['type'] . ']';
                            $termMetasGrouped['ACF'][$aacf->post_excerpt] = $userMetas[$aacf->post_excerpt];
                        }
                    }
                    //echo '<pre>';var_dump($aacf_meta);echo '</pre>';
                }
            }
        }

        // PODS
        /* $pods = get_posts(array('post_type' => '_pods_field', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
          if (!empty($pods)) {
          foreach ($pods as $apod) {
          $type = get_post_meta($apod->ID, 'type', true);
          $userMetas[$apod->post_name] = $apod->post_title.' ['.$type.']';
          $userMetasGrouped['PODS'][$apod->post_name] = $userMetas[$apod->post_name];
          }
          } */

        // TOOLSET
        /* $toolset = get_option('wpcf-fields', false);
          if ($toolset) {
          $toolfields = maybe_unserialize($toolset);
          if (!empty($toolfields)) {
          foreach ($toolfields as $atool) {
          $userMetas[$atool['meta_key']] = $atool['name'].' ['.$atool['type'].']';
          $userMetasGrouped['TOOLSET'][$atool['meta_key']] = $userMetas[$atool['meta_key']];
          }
          }
          } */

        // MANUAL
        global $wpdb;
        $query = 'SELECT DISTINCT meta_key FROM ' . $wpdb->prefix . 'termmeta';
        if ($like) {
            $query .= " WHERE meta_key LIKE '%".$like."%'";
        }
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            $metas = array();
            foreach ($results as $key => $aterm) {
                $metas[$aterm->meta_key] = $aterm->meta_key;
            }
            ksort($metas);
            //$manual_metas = array_diff_key($metas, $userMetas);
            $manual_metas = $metas;
            foreach ($manual_metas as $ameta) {
                if (substr($ameta, 0, 1) == '_') {
                    $ameta = $tmp = substr($ameta, 1);
                    if (in_array($tmp, $manual_metas)) {
                        continue;
                    }
                }
                if (!isset($postMetas[$ameta])) {
                    $termMetas[$ameta] = $ameta;
                    $termMetasGrouped['META'][$ameta] = $ameta;
                }
            }
        }

        if ($grouped) {
            return $termMetasGrouped;
        }

        return $termMetas;
    }

    

    public static function get_post_meta($post_id, $meta_key, $fallback = true, $single = false) {
        $meta_value = false;

        if ($fallback) {
            $meta_value = get_post_meta($post_id, $meta_key, $single);
        }

        // https://docs.elementor.com/article/381-elementor-integration-with-acf
        if (DCE_Helper::is_plugin_active('acf')) { // && in_array($original_type, $acf_types)) {
            //var_dump(array_keys(self::get_acf_fields(false, array('repeater', 'group'))));
            $acf_fields = self::get_acf_fields();
            //echo $meta_key; var_dump($acf_fields);
            if (!empty($acf_fields) && array_key_exists($meta_key, $acf_fields)) {
                // https://www.advancedcustomfields.com/resources/
                $meta_value = get_field($meta_key, $post_id);
                //var_dump($meta_value);
                //$render_type = 'plugin';
            }
        }

        // https://docs.elementor.com/article/385-elementor-integration-with-pods
        if (DCE_Helper::is_plugin_active('pods')) { // && in_array($original_type, $pods_types)) {
            $pods_fields = array_keys(self::get_pods_fields());
            //var_dump($pods_fields);
            //var_dump(in_array($meta_key, $pods_fields));
            if (!empty($pods_fields) && in_array($meta_key, $pods_fields, true)) {
                $meta_value = pods_field_display($meta_key, $post_id);
                //$render_type = 'plugin';
            }
        }

        // ToolSet
        if (DCE_Helper::is_plugin_active('wpcf')) { // && in_array($original_type, $toolset_types)) {
            $toolset_fields = array_keys(self::get_toolset_fields());
            if (!empty($toolset_fields) && in_array($meta_key, $toolset_fields, true)) {
                //$meta_value = types_render_field($meta_key, array('post_id' => $post_id));
                //$render_type = 'plugin';
            }
        }

        //var_dump($meta_value);
        return $meta_value;
    }

    public static function get_post_meta_name($meta_key) {

      // ACF
      if (self::is_plugin_active('acf')) {
          $acf = get_field_object($meta_key);
          if ($acf) {
              return $acf['label'];
          }
      }


      // PODS
      if (self::is_plugin_active('pods')) {
          //$pods = PODS::label($meta_key);
          $pods = get_page_by_path($meta_key, OBJECT, '_pods_field');
          if ($pods) {
              return $pods->post_title;
          }
      }

      /*
      // TOOLSET
      $toolset = get_option('wpcf-fields', false);
      if ($toolset) {
          $toolfields = maybe_unserialize($toolset);
          if (!empty($toolfields)) {
              foreach ($toolfields as $atool) {
                  $postMetas[$atool['meta_key']] = $atool['name'].' ['.$atool['type'].']';
                  $postMetasGrouped['TOOLSET'][$atool['meta_key']] = $postMetas[$atool['meta_key']];
              }
          }
      }*/

      return $meta_key;
    }

    public static function get_meta_type($meta_key, $meta_value = null) {

        $meta_type = 'text';

        // ACF
        if (self::is_plugin_active('acf')) {
            global $wpdb;
            $sql = "SELECT post_content FROM " . $wpdb->prefix . "posts WHERE post_excerpt = '" . $meta_key . "' AND post_type = 'acf-field';";
            $acf_result = $wpdb->get_col($sql);
            if (!empty($acf_result)) {
                $acf_content = reset($acf_result);
                $acf_field_object = maybe_unserialize($acf_content);
                //$acf = get_field_object($meta_key);
                if ($acf_field_object && is_array($acf_field_object) && isset($acf_field_object['type'])) {
                    $meta_type = $acf_field_object['type'];
                    //return $meta_type;
                }
            }
        }

        // PODS
        if (self::is_plugin_active('pods')) {
            //$pods = PODS::label($meta_key);
            $pods = get_page_by_path($meta_key, OBJECT, '_pods_field');
            if ($pods) {
                $meta_type = get_post_meta($apod->ID, 'type', true);
                //return $meta_type;
            }
        }

        /*
          // TOOLSET
          $toolset = get_option('wpcf-fields', false);
          if ($toolset) {
          $toolfields = maybe_unserialize($toolset);
          if (!empty($toolfields)) {
          foreach ($toolfields as $atool) {
          $postMetas[$atool['meta_key']] = $atool['name'].' ['.$atool['type'].']';
          $postMetasGrouped['TOOLSET'][$atool['meta_key']] = $postMetas[$atool['meta_key']];
          }
          }
          } */

        if ($meta_value) {
            if ($meta_type != 'text') {
                switch ($meta_type) {
                    case 'gallery':
                        return 'image';

                    case 'embed':
                        if (strpos($meta_value, 'https://www.youtube.com/') !== false || strpos($meta_value, 'https://youtu.be/') !== false) {
                            return 'youtube';
                        }

                    default:
                        return $meta_type;
                }
            } else {
                if ($meta_key == 'avatar') {
                    return 'image';
                }

                if (is_numeric($meta_value)) {
                    return 'number';
                }
                // Validate e-mail
                if (filter_var($meta_value, FILTER_VALIDATE_EMAIL) !== false) {
                    return 'email';
                }

                // Youtube url
                if (is_string($meta_value)) {
                    if (strpos($meta_value, 'https://www.youtube.com/') !== false || strpos($meta_value, 'https://youtu.be/') !== false) {
                        return 'youtube';
                    }
                    $ext = pathinfo($meta_value, PATHINFO_EXTENSION);
                    if (in_array($ext, array('mp3', 'm4a', 'ogg', 'wav', 'wma')) === true) {
                        return 'audio';
                    }
                    if (in_array($ext, array('mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv')) === true) {
                        return 'video';
                    }

                    // Validate url
                    if (filter_var($meta_value, FILTER_SANITIZE_URL) !== false) {
                        //return 'url';
                    }
                    if (substr($meta_value, 0, 7) == 'http://' || substr($meta_value, 0, 8) == 'https://') {
                        return 'url';
                    }
                }
            }
        }

        return $meta_type;
    }

    public static function get_user_meta($user_id, $meta_key, $fallback = true, $single = false) {
        $meta_value = false;

        if ($fallback) {
            $meta_value = get_post_meta($user_id, $meta_key, $single);
        }

        // https://docs.elementor.com/article/381-elementor-integration-with-acf
        if (DCE_Helper::is_plugin_active('acf')) { // && in_array($original_type, $acf_types)) {
            //var_dump(array_keys(self::get_acf_fields(array('repeater', 'group'))));
            $acf_fields = array_keys(self::get_acf_fields());
            //var_dump($acf_fields);
            if (!empty($acf_fields) && in_array($meta_key, $acf_fields, true)) {
                // https://www.advancedcustomfields.com/resources/
                $meta_value = get_field($meta_key, 'user_' . $user_id);
                //var_dump($meta_value);
                //$render_type = 'plugin';
            }
        }

        // https://docs.elementor.com/article/385-elementor-integration-with-pods
        if (DCE_Helper::is_plugin_active('pods')) { // && in_array($original_type, $pods_types)) {
            $pods_fields = array_keys(self::get_pods_fields());
            //var_dump($pods_fields);
            //var_dump(in_array($meta_key, $pods_fields));
            if (!empty($pods_fields) && in_array($meta_key, $pods_fields, true)) {
                $meta_value = pods_field_display($meta_key, $user_id);
                //$render_type = 'plugin';
            }
        }

        // ToolSet
        if (DCE_Helper::is_plugin_active('wpcf')) { // && in_array($original_type, $toolset_types)) {
            $toolset_fields = array_keys(self::get_toolset_fields());
            if (!empty($toolset_fields) && in_array($meta_key, $toolset_fields, true)) {
                //$meta_value = types_render_field($meta_key, array('user_id' => $user_id));
                //$render_type = 'plugin';
            }
        }

        //var_dump($meta_value);
        return $meta_value;
    }

    public static function get_post_metas($grouped = false, $like = '') {
        global $wp_meta_keys;

        $postMetas = $postMetasGrouped = array();

        // REGISTERED in FUNCTION
        $cpts = self::get_post_types();
        foreach ($cpts as $ckey => $cvalue) {
            $cpt_metas = get_registered_meta_keys($ckey);
            if (!empty($cpt_metas)) {
                foreach ($cpt_metas as $fkey => $actpmeta) {
                    if ($like) {
                        $pos_key = stripos($fkey, $like);
                        if ($pos_key === false) {
                            continue;
                        }
                    }
                    $postMetas[$fkey] = $fkey . ' [' . $actpmeta['type'] . ']';
                    $postMetasGrouped['CPT_' . $ckey][$fkey] = $fkey . ' [' . $actpmeta['type'] . ']';
                    //$postMetasGrouped['CPT_'.$ckey]['label'] = strtoupper($ckey);
                    //$postMetasGrouped['CPT_'.$ckey]['options'][$fkey] = $fkey.' ['.$actpmeta['type'].']';
                }
            }
        }

        // ACF
        if (self::is_plugin_active('acf')) {
            $acf = self::get_acf_fields(); //$grouped);
            // $acf = get_posts(array('post_type' => 'acf-field', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
            if (!empty($acf)) {
                foreach ($acf as $acfkey => $aacf) {
                    //$aacf_meta = maybe_unserialize($aacf->post_content);
                    //$postMetas[$aacf->post_excerpt] = $aacf->post_title.' ['.$aacf_meta['type'].']';
                    //$postMetasGrouped['ACF'][$aacf->post_excerpt] = $postMetas[$aacf->post_excerpt];
                    if ($acfkey) {
                        if ($like) {
                            $pos_key = stripos($acfkey, $like);
                            $pos_name = stripos($aacf, $like);
                            if ($pos_key === false && $pos_name === false) {
                                continue;
                            }
                        }
                        $postMetas[$acfkey] = $aacf;
                        $postMetasGrouped['ACF'][$acfkey] = $aacf;
                    }
                }
            }
        }

        // PODS
        if (self::is_plugin_active('pods')) {
            $pods = get_posts(array('post_type' => '_pods_field', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
            if (!empty($pods)) {
                foreach ($pods as $apod) {
                    $type = get_post_meta($apod->ID, 'type', true);
                    $postMetas[$apod->post_name] = $apod->post_title . ' [' . $type . ']';
                    $postMetasGrouped['PODS'][$apod->post_name] = $postMetas[$apod->post_name];
                }
            }
        }

        // TOOLSET
        if (self::is_plugin_active('wpcf')) {
            $toolset = get_option('wpcf-fields', false);
            if ($toolset) {
                $toolfields = maybe_unserialize($toolset);
                if (!empty($toolfields)) {
                    foreach ($toolfields as $atool) {
                        $postMetas[$atool['meta_key']] = $atool['name'] . ' [' . $atool['type'] . ']';
                        $postMetasGrouped['TOOLSET'][$atool['meta_key']] = $postMetas[$atool['meta_key']];
                    }
                }
            }
        }

        // MANUAL
        global $wpdb;
        $query = 'SELECT DISTINCT meta_key FROM ' . $wpdb->prefix . 'postmeta';
        if ($like) {
            $query .= " WHERE meta_key LIKE '%".$like."%'";
        }
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            $metas = array();
            foreach ($results as $key => $apost) {
                $metas[$apost->meta_key] = $apost->meta_key;
            }
            ksort($metas);
            $manual_metas = array_diff_key($metas, $postMetas);
            foreach ($manual_metas as $ameta) {
                if (substr($ameta, 0, 8) == '_oembed_') {
                    continue;
                }
                /* if (substr($ameta, 0, 1) == '_') {
                  $ameta = $tmp = substr($ameta, 1);
                  if (in_array($tmp, $manual_metas)) {
                  continue;
                  }
                  } */
                if (!isset($postMetas[$ameta])) {
                    $postMetas[$ameta] = $ameta;
                    $postMetasGrouped['NATIVE'][$ameta] = $ameta;
                }
            }
        }

        if ($grouped) {
            return $postMetasGrouped;
        }

        return $postMetas;
    }
    
    public static function is_post_meta($meta_name = null) {
        $post_fields = array(
            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_title',
            'post_excerpt',
            'post_status',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'pinged',
            'post_modified',
            'post_modified_gmt',
            'post_content_filtered',
            'post_parent',
            'guid',
            'menu_order',
            'post_type',
            'post_mime_type',
            'comment_count',
        );

        if ($meta_name) {
            //$post_fields = self::get_post_fields();
            //var_dump($post_fields);
            if (in_array($meta_name, $post_fields)) { // || isset($post_fields[$meta_name])) {
                return false;
            }
        }
        return true;
    }
    
    public static function is_userdata($field_name = null) {
        $user_fields = array(
            'locale',
            'syntax_highlighting',
            'avatar',
            'nickname',
            'first_name',
            'last_name',
            'description',
            'rich_editing',
            'role',
            'jabber',
            'aim',
            'yim',
            'show_admin_bar_front',
        );
        if ($field_name) {
            if (in_array($field_name, $user_fields) || !self::is_user_meta($field_name)) {
                return true;
            }
        }
        return false;
    }
    
    public static function is_user_meta($meta_name = null) {
        $user_fields = array(
            'ID',
            'user_login',
            'user_pass',
            'user_nicename',
            'user_email',
            'user_url',
            'user_registered',
            'user_activation_key',
            'user_status',
            'display_name',
        );
        if ($meta_name) {
            //$post_fields = self::get_post_fields();
            //var_dump($post_fields);
            if (in_array($meta_name, $user_fields)) { // || isset($post_fields[$meta_name])) {
                return false;
            }
        }
        return true;
    }
    
    public static function is_term_meta($meta_name = null) {
        $term_fields = array(
            'term_id',
            'name',
            'slug',
            'term_group',
            'term_order',
        );
        if ($meta_name) {
            //$post_fields = self::get_post_fields();
            //var_dump($post_fields);
            if (in_array($meta_name, $term_fields)) { // || isset($post_fields[$meta_name])) {
                return false;
            }
        }
        return true;
    }
    
    public static function get_acf_fields($types = array(), $group = false, $select = true) {

        $acfList = [];

        if (is_string($types)) {
            if ($types == 'dyncontel-acf') {
                $types = array(
                    'text',
                    'textarea',
                    'select',
                    'number',
                    'date_time_picker',
                    'date_picker',
                    'time_picker',
                    'oembed',
                    'file',
                    'url',
                    'image',
                    'wysiwyg',
                    'true_false',
                );
            } else {
                $types = array($types);
            }
        }
        if ($select) {
            $acfList[0] = 'Select the Field';
        }

        $tipo = 'acf-field';
        $get_templates = get_posts(array('post_type' => $tipo, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'suppress_filters' => false));

        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {
                $gruppoAppartenenza = false;
                if ($template->post_parent) {
                    if ($gruppoAppartenenza = get_post($template->post_parent)) {
                        $gruppoAppartenenzaField = maybe_unserialize($gruppoAppartenenza->post_content);
                    }
                }
                $arrayField = maybe_unserialize($template->post_content);
                if (isset($arrayField['type']) && (empty($types) || in_array($arrayField['type'], $types))) {

                    if ($group && $gruppoAppartenenza) {

                        if (isset($gruppoAppartenenzaField['type']) && $gruppoAppartenenzaField['type'] == 'group') {
                            $acfList[$gruppoAppartenenza->post_excerpt]['options'][$gruppoAppartenenza->post_excerpt . '_' . $template->post_excerpt] = $template->post_title . ' [' . $template->post_excerpt . '] (' . $arrayField['type'] . ')';
                        } else {
                            $acfList[$gruppoAppartenenza->post_excerpt]['options'][$template->post_excerpt] = $template->post_title . ' [' . $template->post_excerpt . '] (' . $arrayField['type'] . ')';
                        }
                        $acfList[$gruppoAppartenenza->post_excerpt]['label'] = $gruppoAppartenenza->post_title;
                    } else {
                        if ($gruppoAppartenenza && isset($gruppoAppartenenzaField['type']) && $gruppoAppartenenzaField['type'] == 'group') {
                            $acfList[$gruppoAppartenenza->post_excerpt . '_' . $template->post_excerpt] = $template->post_title . ' [' . $template->post_excerpt . '] (' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
                        } else {
                            $acfList[$template->post_excerpt] = $template->post_title . ' [' . $template->post_excerpt . '] (' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
                        }
                    }
                }
            }
        }
        return $acfList;
    }

    public static function get_acf_field_urlfile($group = false) {
        return self::get_acf_fields(array('file', 'url'), $group);
    }

    public static function get_acf_field_relations() {
        return self::get_acf_fields('relationship', $group);
    }

    /* public static function get_acf_field_relational_post() {
      $acfList = [];
      $relational = array("post_object", "relationship"); //,"taxonomy","user");
      $acfList[0] = __('Select the Field', 'dynamic-content-for-elementor');
      $get_templates = get_posts(array('post_type' => 'acf-field', 'numberposts' => -1));
      if (!empty($get_templates)) {
      foreach ($get_templates as $template) {
      $gruppoAppartenenza = $template->post_parent;
      $arrayField = maybe_unserialize($template->post_content);
      if (in_array($arrayField['type'], $relational)) {
      $acfList[$template->post_excerpt] = $template->post_title . ' (' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
      }
      }
      }
      return $acfList;
      } */

    public static function get_acf_field_relational_post() {
        return self::get_acf_fields(array("post_object", "relationship"));
    }

    public static function get_acf_repeater_fields($repeater_name) {
        $sub_fields = array();
        if (self::is_plugin_active('acf')) {
            $repeater_id = self::get_acf_field_id($repeater_name);
            $fields = get_posts(array('post_type' => "acf-field", 'numberposts' => -1, 'post_status' => 'publish', 'post_parent' => $repeater_id));
            if (!empty($fields)) {
                foreach ($fields as $key => $afield) {
                    $settings = maybe_unserialize($afield->post_content);
                    $settings['title'] = $afield->post_title;
                    $sub_fields[$afield->post_excerpt] = $settings;
                }
                
            }
        }
        return $sub_fields;
    }
    
    public static function get_acf_field_id($key) {
        global $wpdb;
        if (isset(self::$meta_fields[$key])) {
            return self::$meta_fields[$key];
        }
        $query = 'SELECT ID FROM '. $wpdb->posts .' WHERE post_type LIKE "acf-field" AND post_excerpt LIKE "'.$key.'"';
        $result = $wpdb->get_var($query);
        if ($result) {
            self::$meta_fields[$key] = $result;
            return $result;
        }
        return false;
    }
    public static function get_acf_field_settings($key) {
        $field = self::get_acf_field_post($key);
        if ($field) {
            //var_dump($field);
            $settings = maybe_unserialize($field->post_content);
            return $settings;
        }
        return false;
    }
    public static function get_acf_field_post($key) {
        if(is_int($key)) return get_post($key);
        $field_id = self::get_acf_field_id($key);
        if ($field_id) {
            $field = get_post($field_id);
            //var_dump($field);
            return $field;
        }
        return false;
    }
    
    public static function get_acffield_filtred( $idField, $id_page = null, $format = true) {
        
        // TODO
        // https://www.advancedcustomfields.com/resources/options-page/
        //get_field($idField, 'option');
        
        //
        $dataACFieldPost = self::get_acf_field_post($idField);
        //$field_type = $field_settings['type'];
        //var_dump(self::get_acf_field_post($idField));
        //var_dump($idField);
        //var_dump($id_page);
        //var_dump($dataACFieldPost);

        // VOGLIO CAPIRE IL TIPO DEL GENITORE

        if($dataACFieldPost){ 
            $parentID = $dataACFieldPost->post_parent;
            $parent_settings = self::get_acf_field_settings($parentID);
            
            if( isset($parent_settings['type']) && $parent_settings['type'] == 'repeater' ){
                $parent_post = get_post($parentID);
                $row = acf_get_loop('active');               
                if (!$row) {
                    if (have_rows($parent_post->post_excerpt, $id_page)) {
                        the_row();
                    }
                }
                return get_sub_field($idField); 
            }
        }

        $theField = get_field( $idField, $id_page, $format);
        
        if (is_post_type_archive() || is_tax() || is_category() || is_tag() ) {
                
            $term = get_queried_object();
            $theField = get_field($idField, $term, $format);

        }else if( is_author() ){

            $author_id = get_the_author_meta('ID');
            $theField = get_field($idField, 'user_'. $author_id, $format);

        }

        if(DCE_Helper::in_the_loop()) $theField = get_field($idField , $id_page, $format);
        return $theField;
    }
    
    public static function get_pods_fields($t = null) {
        $podsList = [];
        $podsList[0] = __('Select the Field', 'dynamic-content-for-elementor');
        $pods = get_posts(array('post_type' => '_pods_field', 'numberposts' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
        if (!empty($pods)) {
            foreach ($pods as $apod) {
                $type = get_post_meta($apod->ID, 'type', true);
                if (!$t || $type == $t) {
                    $title = $apod->post_title;
                    if (!$t) {
                        $title .= ' [' . $type . ']';
                    }
                    $podsList[$apod->post_name] = $title;
                }
            }
        }
        return $podsList;
    }

    public static function get_toolset_fields($t = null) {
        $toolsetList = [];
        $toolsetList[0] = __('Select the Field', 'dynamic-content-for-elementor');
        $toolset = get_option('wpcf-fields', false);
        if ($toolset) {
            $toolfields = maybe_unserialize($toolset);
            if (!empty($toolfields)) {
                foreach ($toolfields as $atool) {
                    $type = $atool['type'];
                    if (!$t || $type == $t) {
                        $title = $atool['name'];
                        if (!$t) {
                            $title .= ' [' . $type . ']';
                        }
                        $toolsetList[$atool['meta_key']] = $title;
                    }
                }
            }
        }
        return $toolsetList;
    }
    
        /* public static function get_user_fields($idUser = 1) {
      $userTmp = wp_get_current_user();
      //var_dump($userTmp);
      $userProp = get_object_vars($userTmp);
      $userMeta = get_registered_meta_keys('user');
      //var_dump($userMeta);
      $userFields = array_merge(array_keys($userProp), array_keys($userMeta));
      return $userFields;
      } */

    

    /* public static function get_user_meta($idUser = 1) {
      $all_userMeta = get_user_meta($idUser);
      //$all_userMeta = get_metadata('user',1);
      //var_dump($all_userMeta); die();
      $ret['none'] = 'None';
      foreach ($all_userMeta as $key => $value) {
      $ret[$key] = $key; //$value;
      }
      return $ret;
      } */
    
}
