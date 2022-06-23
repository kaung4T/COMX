<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Image {
    
    public static function get_thumbnail_sizes() {
        $sizes = get_intermediate_image_sizes();
        foreach ($sizes as $s) {
            $ret[$s] = $s;
        }

        return $ret;
    }
    
    public static function is_resized_image($imagePath) {
        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
        $pezzi = explode('-', substr($imagePath, 0, -(strlen($ext) + 1)));
        //var_dump($pezzi);
        if (count($pezzi) > 1) {
            $misures = array_pop($pezzi);
            $fullsize = implode('-', $pezzi) . '.' . $ext;
            //echo $fullsize;
            $pezzi = explode('x', $misures);
            if (count($pezzi) == 2) {
                //var_dump($pezzi);
                if (is_numeric($pezzi[0]) && is_numeric($pezzi[1])) {
                    return $fullsize; // return original value
                }
            }
        }
        return false;
    }
    
    public static function get_placeholder_image_src($size = null) {
        $placeholder_image = DCE_URL . 'assets/img/placeholder.jpg';
        return $placeholder_image;
    }
    
    
    public static function get_image_id($image_url) {
        global $wpdb;
        $sql = "SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid LIKE '%" . esc_sql($image_url) . "';";
        $attachment = $wpdb->get_col($sql);
        return reset($attachment);
    }

    /**
     * Get size information for all currently-registered image sizes.
     *
     * @global $_wp_additional_image_sizes
     * @uses   get_intermediate_image_sizes()
     * @return array $sizes Data for all currently-registered image sizes.
     */
    public static function get_image_sizes() {
        global $_wp_additional_image_sizes;

        $sizes = array();

        foreach (get_intermediate_image_sizes() as $_size) {
            if (in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
                $sizes[$_size]['width'] = get_option("{$_size}_size_w");
                $sizes[$_size]['height'] = get_option("{$_size}_size_h");
                $sizes[$_size]['crop'] = (bool) get_option("{$_size}_crop");
            } elseif (isset($_wp_additional_image_sizes[$_size])) {
                $sizes[$_size] = array(
                    'width' => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop' => $_wp_additional_image_sizes[$_size]['crop'],
                );
            }
        }

        return $sizes;
    }

    /**
     * Get size information for a specific image size.
     *
     * @uses   get_image_sizes()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|array $size Size data about an image size or false if the size doesn't exist.
     */
    public static function get_image_size($size) {
        $sizes = self::get_image_sizes();

        if (isset($sizes[$size])) {
            return $sizes[$size];
        }

        return false;
    }

    /**
     * Get the width of a specific image size.
     *
     * @uses   get_image_size()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Width of an image size or false if the size doesn't exist.
     */
    public static function get_image_width($size) {
        if (!$size = self::get_image_size($size)) {
            return false;
        }

        if (isset($size['width'])) {
            return $size['width'];
        }

        return false;
    }

    /**
     * Get the height of a specific image size.
     *
     * @uses   get_image_size()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Height of an image size or false if the size doesn't exist.
     */
    public static function get_image_height($size) {
        if (!$size = get_image_size($size)) {
            return false;
        }

        if (isset($size['height'])) {
            return $size['height'];
        }

        return false;
    }
    
}
