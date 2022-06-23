<?php

namespace Elementor;

use \Elementor\Core\DynamicTags\Manager as Dynamic_Tags_Manager;

/**
 * Description of DCE_DynamicTags
 *
 * @author fra
 */
class DCE_Dynamic_Tags_Manager extends Dynamic_Tags_Manager {
    
    /**
    * Dynamic tags manager constructor.
    *
    * Initializing Elementor dynamic tags manager.
    *
    * @since 2.0.0
    * @access public
    */
    /*public function __construct() {
        var_dump(self::DYNAMIC_SETTING_KEY);
        parent::__construct();
    }*/
        
    /**
    * Parse dynamic tags text.
    *
    * Receives the dynamic tag text, and returns a single value or multiple values
    * from the tag callback function.
    *
    * @since 1.5.3
    * @access public
    *
    * @param string   $text           Dynamic tag text
    * @param array    $settings       The dynamic tag settings.
    * @param callable $parse_callback The functions that renders the dynamic tag.
    *
    * @return string|string[]|mixed A single string or an array of strings with
    *                               the return values from each tag callback
    *                               function.
    */
    public function parse_tags_text( $text, array $settings, callable $parse_callback ) {
        //var_dump($text);
        //var_dump($settings);
        //var_dump($parse_callback);
        
        // parse params
        $pezzi = explode('="',$text);
        $vkey = 'id';
        $parsed = array();
        foreach ($pezzi as $pkey => $apiece) {
            if ($pkey) {
                $tmp = explode('"', $apiece, 2);
                $parsed[$vkey] = reset($tmp);
            }
            $tmp = explode(' ', $apiece);
            $vkey = end($tmp);
        }
        if (isset($parsed['settings'])) {
            $parsed['settings'] = urldecode($parsed['settings']);
            $parsed['settings'] = json_decode($parsed['settings'], true);           
            //var_dump($parsed);
            if (isset($parsed['settings']['dynamic']) && $parsed['settings']['dynamic'] == 'ooo') {
                
                if (isset($parsed['settings']['value']) && $parsed['settings']['value']) {
                    $value = $parsed['settings']['value'];
                } else {
                    $postSettings = \DynamicContentForElementor\DCE_Helper::get_settings_by_id($parsed['settings']['eid']);
                    //var_dump($postSettings);
                    if (isset($parsed['settings']['name']) && $parsed['settings']['name']) {
                        $value = $postSettings[$parsed['settings']['name']];
                    } else {
                        $value = reset($postSettings);
                    }
                    if (is_array($value)) {
                        if (isset($parsed['settings']['sub']) && $parsed['settings']['sub']) {
                            $value = $value[$parsed['settings']['sub']];
                        } else {
                            $value = reset($value);
                        }
                    }
                    //var_dump($value);
                }
                
                return \DynamicContentForElementor\DCE_Tokens::do_tokens($value);
            }
        }
        
        return $value = parent::parse_tag_text($text, $settings, $parse_callback);
    }
}
