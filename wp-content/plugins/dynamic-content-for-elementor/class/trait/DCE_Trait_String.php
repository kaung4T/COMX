<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_String {
    
    /**
    * Custom Function for Remove Specific Tag in the string.
    */
    public static function strip_tag($string, $tag) {
        $string =  preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
        $string = preg_replace('/<\/'.$tag.'>/i', '', $string);
        return $string;
    }

    public static function remove_empty_p($content) {
        //$content = force_balance_tags( $content );
        //$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
        //$content = preg_replace( '~\s?<p>(\s| )+</p>\s?~', '', $content );
        $content = str_replace("<p></p>", "", $content);
        return $content;
    }
    
    

    public static function escape_json_string($value) {
        // # list from www.json.org: (\b backspace, \f formfeed)
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }
    
    public static function str_to_array($delimiter = ',', $string = '', $format = null) {
        $pieces = explode($delimiter, $string);
        $pieces = array_map('trim', $pieces);
        //$pieces = array_filter($pieces);
        $tmp = array();
        foreach ($pieces as $value) {
            if ($value != '') {
                $tmp[] = $value;
            }
        }
        $pieces = $tmp;
        if ($format) {
            $pieces = array_map($format, $pieces);
        }
        return $pieces;
    }
    
    public static function to_string($avalue) {
        if (!is_array($avalue) && !is_object($avalue)) {
            return $avalue;
        }
        if (is_object($avalue) && get_class($avalue) == 'WP_Term') {
            return $avalue->name;
        }
        if (is_object($avalue) && get_class($avalue) == 'WP_Post') {
            return $avalue->post_title;
        }
        if (is_object($avalue) && get_class($avalue) == 'WP_User') {
            return $avalue->display_name;
        }
        if (is_array($avalue)) {

            if (isset($avalue['post_title'])) {
                return $avalue['post_title'];
            }
            if (isset($avalue['display_name'])) {
                return $avalue['display_name'];
            }
            if (isset($avalue['name'])) {
                return $avalue['name'];
            }
            if (count($avalue) == 1) {
                return reset($avalue);
            }
            return print_r($avalue, true);
        }
        return '';
    }
    
    public static function vc_strip_shortcodes($content) {
        //return $content;
        $tmp = $content;
        $tags = array('[/vc_', '[vc_', '[dt_', '[interactive_banner_2');
        foreach ($tags as $atag) {
            $pezzi = explode($atag, $tmp);
            if (count($pezzi) > 1) {
                $content_mod = '';
                foreach ($pezzi as $key => $value) {
                    $altro = explode(']', $value, 2);
                    $content_mod .= end($altro);
                }
                $tmp = $content_mod;
            }
        }
        return $tmp;
    }
    
    public static function text_reduce($text, $length, $length_type, $finish) {
        $tokens = array();
        $out = '';
        $w = 0;

        // Divide the string into tokens; HTML tags, or words, followed by any whitespace
        // (<[^>]+>|[^<>\s]+\s*)
        preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $text, $tokens);
        foreach ($tokens[0] as $t) { // Parse each token
            if ($w >= $length && 'sentence' != $finish) { // Limit reached
                break;
            }
            if ($t[0] != '<') { // Token is not a tag
                if ($w >= $length && 'sentence' == $finish && preg_match('/[\?\.\!]\s*$/uS', $t) == 1) { // Limit reached, continue until ? . or ! occur at the end
                    $out .= trim($t);
                    break;
                }
                if ('words' == $length_type) { // Count words
                    $w++;
                } else { // Count/trim characters
                    if ($finish == 'exact_w_spaces') {
                        $chars = $t;
                    } else {
                        $chars = trim($t);
                    }
                    $c = mb_strlen($chars);
                    if ($c + $w > $length && 'sentence' != $finish) { // Token is too long
                        $c = ( 'word' == $finish ) ? $c : $length - $w; // Keep token to finish word
                        $t = substr($t, 0, $c);
                    }
                    $w += $c;
                }
            }
            // Append what's left of the token
            $out .= $t;
        }

        return trim(force_balance_tags($out));
    }
    
    /**
    * Maybe JSON decode the given string.
    *
    * @param  string $str
    * @param  bool   $assoc
    *
    * @return mixed
    */
    public static function maybe_json_decode( $str, $assoc = false ) {
        return self::is_json( $str ) ? json_decode( $str, $assoc ) : $str;
    }
    /**
    * Test if given object is a JSON string or not.
    *
    * @param  mixed  $obj
    *
    * @return bool
    */
   function is_json( $obj ) {
       return is_string( $obj )
           && is_array( json_decode( $obj, true ) )
           && json_last_error() === JSON_ERROR_NONE;
   }
   
   public static function path_to_url($dir) {
        $dirs = wp_upload_dir();
        $url = str_replace($dirs["basedir"], $dirs["baseurl"], $dir);
        $url = str_replace(ABSPATH, get_home_url(null, '/'), $url);
        //$url = urlencode($url);
        return $url;
    }
    
    public static function array_to_groups($myarray) {
        $ret = array();
        if (!empty($myarray)) {
            foreach ($myarray as $mkey => $avalue) {
                ksort($avalue);
                $ret[$mkey]['label'] = $mkey;
                $ret[$mkey]['options'] = $avalue;
            }
        }
        return $ret;
    }
    
    public static function tablefy($html = '') {
        $table_replaces = array(
            'table' => '.elementor-container',
            'tr' => '.elementor-row',
            'td' => '.elementor-column',
        );
        $dom = new \PHPHtmlParser\Dom;
        $dom->load($html);
        foreach ($dom->find('.elementor-container') as $tag) {
            $changeTagTable = function() {
                $this->name = 'table';
            };
            $changeTagTable->call($tag->tag);
        }
        foreach ($dom->find('.elementor-row') as $tag) {
            $changeTagTr = function() {
                $this->name = 'tr';
            };
            $changeTagTr->call($tag->tag);
        }
        foreach ($dom->find('.elementor-column') as $tag) {
            $changeTagTd = function() {
                $this->name = 'td';
            };
            $changeTagTd->call($tag->tag);
        }
        $html_table = (string) $dom;
        return $html_table;
    }
    
}
