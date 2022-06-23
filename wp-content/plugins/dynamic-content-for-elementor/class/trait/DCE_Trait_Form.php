<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Form {
    
    public static function get_form_data($record) {
        // Get sumitetd Form data
        $raw_fields = $record->get('fields');
        // Normalize the Form Data
        $fields = [];
        foreach ($raw_fields as $id => $field) {
            $fields[$id] = $field['value'];
        }

        $extra_fields = self::get_form_extra_data($record, $fields);
        foreach ($extra_fields as $key => $value) {
            $fields[$key] = $value;
        }
        
        global $dce_form;
        if (!empty($dce_form) && is_array($dce_form)) {
            foreach ($fields as $key => $value) {
                $dce_form[$key] = $value;
            }
        } else {
            $dce_form = $fields; // for form tokens
        }
        
        if (!empty($fields['submitted_on_id'])) {
            global $post, $user;
            if (empty($post)) {
                $post = get_post($fields['submitted_on_id']);
            }
            /* if (empty($user)) {
              $user = get_user_by('id', $fields['submitted_by_id']);
              } */
        }
        
        if (!empty($fields['post_id'])) {
            global $post;
            $post = get_post($fields['post_id']);
        }
        
        
        return $fields;
    }
    
    public static function get_form_extra_data($record, $fields = null, $settings = null) {

        $referrer = isset($_POST['referrer']) ? $_POST['referrer'] : '';
        
        if (is_object($record)) {
            $form_name = $record->get_form_settings('form_name');
        } else {
            if (!empty($settings['form_name'])) {
                $form_name = $settings['form_name'];
            }
        }

        // get current page
        $this_post = get_queried_object();
        if ($this_post && get_class($this_post) == 'WP_Post') {
            $this_page = $this_post;
        } else if ($referrer) {
            $post_id = url_to_postid($referrer);
            if ($post_id) {
                $this_post = $this_page = get_post($post_id);
            }
        } else {
            $this_post = $this_page = get_post($_POST['post_id']);
        }

        // get current user
        $this_user_id = get_current_user_id();

        // Elementor DB
        $data = array();
        $email = false;
        $this_user = false;
        foreach ($fields as $label => $value) {
            if (stripos($label, 'email') !== false) {
                $email = $value;
            }
            $data[] = array('label' => $label, 'value' => sanitize_text_field($value));
        }
        if ($this_user_id) {
            if ($this_user = get_userdata($this_user_id)) {
                $this_user = $this_user->display_name;
            }
        }
        $extra = array(
            'submitted_on' => $this_page->post_title,
            'submitted_on_id' => $this_page->ID,
            'submitted_by' => $this_user,
            'submitted_by_id' => $this_user_id
        );

        return [
            'submitted_on_id' => $this_page->ID,
            'submitted_by_id' => $this_user_id,
            'ip_address' => \ElementorPro\Classes\Utils::get_client_ip(),
            'referrer' => $referrer,
            'form_name' => $form_name,
                /*
                  // Elementor DB
                  'sb_elem_cfd' => array(
                  'data'     => $data,
                  'extra'    => $extra,
                  'post'     => array_map( 'sanitize_text_field', $_POST ),
                  'server'   => $_SERVER,
                  'fields_original' => $fields, //array( 'form_fields' => $record->get_form_settings( 'form_fields' ) ),
                  'record_original' => $record,
                  ),
                  'sb_elem_cfd_read' => 0,
                  'sb_elem_cfd_email' => $email,
                  'sb_elem_cfd_form_id' => $fields['form_name'],
                 */
        ];
    }
    
    public static function replace_setting_shortcodes($setting, $fields = array(), $urlencode = false) {
        // Shortcode can be `[field id="fds21fd"]` or `[field title="Email" id="fds21fd"]`, multiple shortcodes are allowed
        return preg_replace_callback('/(\[field[^]]*id="(\w+)"[^]]*\])/', function( $matches ) use ( $urlencode, $fields ) {
            $value = '';
            if (isset($fields[$matches[2]])) {
                $value = $fields[$matches[2]];
            }
            if ($urlencode) {
                $value = urlencode($value);
            }
            return $value;
        }, $setting);
    }
    
    // convert an array to a options list compatible with Elementor PRO Form
    public static function array_options($arr = array(), $val = 'keys') {        
        $str = '';
        if (is_string($arr)) $arr = DCE_Helper::str_to_array($arr);
        if (is_object($arr)) $arr = (array)$arr;
        if (!empty($arr) && is_array($arr)) { 
            $i = 0;
            foreach ($arr as $key => $value) {
                if ($val == 'keys') {
                    $str .= $key.'|'.$value;
                } else {
                    $str .= $value;
                }
                if ($i < count($arr)-1) {
                    $str .= PHP_EOL;
                }
                $i++;
            }        
        }
        return $str;
    }
    
}
