<?php
// safe check for visibility condition

/** Loads the WordPress Environment and Template */
define('WP_USE_THEMES', false);
require ('../../../../wp-blog-header.php');

$element_id = $_GET['eid'];
//var_dump($element_id);
$post_id = intval($_GET['pid']);
//var_dump($post_id);
if ($element_id && $post_id) {

    $php_code = get_post_meta($post_id);
    $settings = \DynamicContentForElementor\DCE_Helper::get_settings_by_id($element_id, $post_id);
    //var_dump($settings);
    
    if (isset($settings['dce_visibility_custom_condition_php'])) {
        $php_code = $settings['dce_visibility_custom_condition_php'];
        if ($php_code) {
            //var_dump($php_code);
            if (strpos($php_code, 'return ') !== false) {
                $return = eval($php_code);
                if ($return) {
                    echo '1';
                    die();
                }
            }
        }
    }
    //eval($php_code);
    
}

echo '0';