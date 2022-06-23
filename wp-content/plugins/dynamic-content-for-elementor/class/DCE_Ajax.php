<?php
namespace DynamicContentForElementor;

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Widget_Base;
use Elementor\Core\Base\Module;
use Elementor\TemplateLibrary\Source_Local;

class DCE_Ajax {
    
    public $query_control;
    
    public function __construct() {
        $this->init();
    }
    
    function init() {
        
        add_action( 'wp_ajax_wpa_update_postmetas', array($this, 'wpa_update_postmetas') );
        add_action( 'wp_ajax_wpa_update_options', array($this, 'wpa_update_options') );
        
        add_action( 'wp_ajax_dce_file_browser_hits', array($this, 'dce_file_browser_hits') );
        add_action( 'wp_ajax_nopriv_dce_file_browser_hits', array($this, 'dce_file_browser_hits') );
        
        add_action( 'wp_ajax_dce_get_next_post', array($this, 'dce_get_next_post') );
        add_action( 'wp_ajax_nopriv_dce_get_next_post', array($this, 'dce_get_next_post') );
        
        add_action('wp_ajax_dce_visibility_is_hidden', array($this, 'dce_visibility_is_hidden'));
        add_action('wp_ajax_dce_visibility_toggle', array($this, 'dce_visibility_toggle'));
        
        // Ajax page open Actions
        add_action('wp_ajax_modale_action', array($this, 'dce_ajax_action'));
        add_action('wp_ajax_nopriv_modale_action', array($this, 'dce_ajax_action'));
        
        add_action('wp_ajax_dualview_action', array($this, 'dce_dualView_ajax_action'));
        add_action('wp_ajax_nopriv_dualview_action', array($this, 'dce_dualView_ajax_action'));
        
        add_action('wp_ajax_dce_finder_search', array($this, 'dce_finder_search'));
        add_action('wp_ajax_nopriv_dce_finder_search', array($this, 'dce_finder_search'));
        
        // Ajax Select2 autocomplete
        include_once( DCE_PATH.'/modules/query-control/DCE_QueryControl.php' );
        $this->query_control = new \DynamicContentForElementor\Modules\QueryControl\DCE_QueryControl();
        
    }
    
    function wpa_update_postmetas() {
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            $post_id = 0;
            if (isset($_REQUEST['post_id'])) {
                $post_id = $_REQUEST['post_id'];
            }
            if ($post_id) {

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'action' && $key != 'post_id') {
                        if ($value) {
                            $tmp = get_post_meta($post_id, $key, true);
                            if (is_array($value)) {
                                if (!empty($tmp)) {
                                    $value = array_merge($tmp, $value);
                                }
                            }
                            update_post_meta($post_id, $key, $value);
                        } else {
                            delete_post_meta($post_id, $key);
                        }
                    }
                }
            } else {
                return false;
            }
        }
        echo json_encode($_REQUEST);
        // Always die in functions echoing ajax content
       wp_die(); // this is required to terminate immediately and return a proper response
    }
    
    function wpa_update_options() {
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            foreach ($_REQUEST as $key => $value) {
                if ($key != 'action') {
                    if ($value) {
                        if (is_array($value)) {
                            $tmp = get_option($key);
                            if (!empty($tmp)) {
                                $value = array_merge($tmp, $value);
                            }
                        }
                        update_option($key, $value);
                    } else {
                        delete_option($key);
                    }
                }
            }

        }
        echo json_encode($_REQUEST);
        // Always die in functions echoing ajax content
       wp_die(); // this is required to terminate immediately and return a proper response
    }
    
    function dce_file_browser_hits() {
        // The $_REQUEST contains all the data sent via ajax
        //var_dump($_REQUEST);
        if ( isset($_REQUEST) ) {
            if (isset($_REQUEST['post_id'])) {
                $post_id = $_REQUEST['post_id'];
                $key = 'dce-file';
                $tmp = get_post_meta($post_id, $key, true);
                $value = array('hits' => 1);
                if (!empty($tmp)) {
                    if (is_array($tmp)) {
                        if (isset($tmp['hits'])) {
                            $tmp['hits'] = intval($tmp['hits']) + 1;
                        } else {
                            $tmp['hits'] = 1;
                        }
                    }
                    $value = $tmp;
                }
                update_post_meta($post_id, $key, $value);
            } else if (isset($_REQUEST['md5'])) {
                $md5 = $_REQUEST['md5'];
                $key = 'dce-file-'.$md5;
                $tmp = get_option($key);                
                $value = array('hits' => 1);
                if (!empty($tmp)) {
                    if (is_array($tmp)) {
                        if (isset($tmp['hits'])) {
                            $tmp['hits'] = intval($tmp['hits']) + 1;
                        } else {
                            $tmp['hits'] = 1;
                        }
                    }
                    $value = $tmp;
                }
                update_option($key, $value);
            }
        }
        echo json_encode($_REQUEST);
        // Always die in functions echoing ajax content
       wp_die(); // this is required to terminate immediately and return a proper response
    }
    
    function dce_visibility_toggle() {
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            if (isset($_REQUEST['element_id']) && isset($_REQUEST['post_id'])) {
                $element_id = $_REQUEST['element_id'];
                $post_id = $_REQUEST['post_id'];
                if ($post_id) {
                    $settings = DCE_Helper::get_settings_by_id($element_id, $post_id);
                    if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                        if (Extensions\DCE_Extension_Visibility::is_hidden($settings)) {
                            DCE_Helper::set_settings_by_id($element_id, 'enabled_visibility', null, $post_id);
                        } else {
                            DCE_Helper::set_settings_by_id($element_id, 'dce_visibility_hidden', 'yes', $post_id);
                        }
                    } else {
                        DCE_Helper::set_settings_by_id($element_id, 'enabled_visibility', 'yes', $post_id);
                        DCE_Helper::set_settings_by_id($element_id, 'dce_visibility_hidden', 'yes', $post_id);
                    }
                }
            }
        }
        echo $_REQUEST['element_id'];
        // Always die in functions echoing ajax content
        wp_die(); // this is required to terminate immediately and return a proper response
    }
    function dce_visibility_is_hidden() {
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            if (isset($_REQUEST['element_id']) && isset($_REQUEST['post_id'])) {
                $element_id = $_REQUEST['element_id'];
                $post_id = $_REQUEST['post_id'];
                $settings = DCE_Helper::get_settings_by_id($element_id, $post_id);
                if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                    if (Extensions\DCE_Extension_Visibility::is_hidden($settings)) {
                        echo $element_id;
                        wp_die();
                    }
                }
            }
        }
        echo '0';
        // Always die in functions echoing ajax content
        wp_die(); // this is required to terminate immediately and return a proper response
    }
    
    
    function wpa_dce_get_next_post($id = null) {
        $ret = array();
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            //var_dump($_REQUEST);
            //get_adjacent_post();
            $next = DynamicContentForElementor\DCE_Helper::get_adjacent_post_by_id(null, null, true, null, $_REQUEST['post_id']);
            //var_dump($next);
            $next_url = get_permalink($next->ID);
            $ret['ID'] = $next->ID;
            $ret['permalink'] = $next_url;
            $ret['title'] = get_the_title($next->ID);
            $ret['thumbnail'] = get_the_post_thumbnail($next->ID);
        }
        echo json_encode($ret);
        // Always die in functions echoing ajax content
       wp_die(); // this is required to terminate immediately and return a proper response
    }
    
    public static function dce_ajax_action() {
        //wp_head();
        $postid = url_to_postid($_POST['post_href']);
        $template_id = $_POST['template_id'];

        //enqueue_scripts('dce-acf_posts');
        global $global_ID;
        $global_ID = $postid;
        //
        $titolo_seo = get_post_meta($postid, '_yoast_wpseo_title', true); //get_post_meta($postid, '_yoast_wpseo_metadesc', true);
        $titolo_nativo = get_the_title($postid) . ' - ' . get_bloginfo('name');
        if (!empty($titolo_seo)) {
            $titolo_nativo = $titolo_seo;
        }

        echo '<div class="content-p">';

        self::get_template_for_ajax($template_id, $postid);

        echo '<div class="titolo-nativo">' . $titolo_nativo . '</div>';
        //wp_footer();
        echo '</div>';

        wp_die();
    }
    
    public static function dce_dualView_ajax_action() {

        //wp_head();
        $postid = url_to_postid($_POST['post_href']);
        $template_id = $_POST['template_id'];
        $featuredImage = get_the_post_thumbnail($postid);
        //
        //enqueue_scripts('dce-acf_posts');
        global $global_ID;
        $global_ID = $postid;
        //
        ?>
        <div class="cd-contenuto">
            <div class="cd-slider-wrapper">
                <ul class="cd-slider">
                    <li class="selected"><?php echo $featuredImage; ?></li>
                </ul> <!-- cd-slider -->

                <ul class="cd-slider-navigation">
                    <li><a class="cd-next" href="#0">Prev</a></li>
                    <li><a class="cd-prev" href="#0">Next</a></li>
                </ul> <!-- cd-slider-navigation -->
            </div> <!-- cd-slider-wrapper -->

            <div class="cd-item-info">
        <?php
        //echo $postid;
        self::get_template_for_ajax($template_id, $postid);
        ?>
            </div> <!-- cd-item-info -->
            <a href="#0" class="cd-close">Close</a>
        </div>
        <?php
    }

    public static function get_template_for_ajax($t_id, $p_id) {
        if ($t_id > 0) {
            echo do_shortcode('[dce-elementor-template id="' . $t_id . '"]');
        } else {
            // Check if the template is created with Elementor
            $elementor = get_post_meta($p_id, '_elementor_edit_mode', true);
            if ($elementor) {
                echo do_shortcode('[dce-elementor-template id="' . $p_id . '"]');
            } else {
                $post_template = get_post($p_id);
                $contenuto = $post_template->post_content;
                echo $contenuto;
            }
        }
    }

    
    //https://premium.wpmudev.org/blog/how-to-use-ajax-in-wordpress-to-load-search-results/
    public function dce_finder_search() {
        $ret = array();
        // The $_REQUEST contains all the data sent via ajax
        if ( isset($_REQUEST) ) {
            //var_dump($_REQUEST);
            $eid = $_REQUEST['eid'];
            $pid = $_REQUEST['post_id'];
            $search = $_REQUEST['search'];
            
            $settings = DCE_Helper::get_settings_by_id($eid, $pid);
            //var_dump($settings); die();
            
            $posts_id = array();
            global $wpdb;
            
            $title_results = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_status LIKE 'publish' AND post_title LIKE '%".$search."%'", OBJECT );            
            if (!empty($title_results)) {
                foreach ($title_results as $key => $title_result) {
                    $posts_id[] = $title_result->ID;
                }
            }
            
            $taxonomies = array();
            if (!empty($settings)) {
                if ($settings['taxonomies']) {
                    $taxonomies = get_taxonomies();
                    $taxonomies_terms = DCE_Helper::get_taxonomies_terms();
                }
            }
            
            $taxonomies_html = '';
            /*foreach ($taxonomies_terms as $key => $atax) {
                $taxonomies_html .= '<ul>';
                $taxonomies_html .= '<li>'.$atax['title'].'</li>';
                $taxonomies_html .= '</ul>';
            }*/
            $taxonomies_html = var_export($settings, true);
            
            
            $args = array(
                'post__in' => $posts_id
            );
            //$posts = get_posts($args);
            
            $posts = array();
            foreach ($posts_id as $pid) {
                $posts[$pid] = array(
                    'title' => get_the_title($pid),
                    'thumb' => get_the_post_thumbnail($pid),
                    'excerpt' => get_the_excerpt($pid),
                    'permalink' => get_permalink($pid),
                );
            }
            
            $posts_html = '';
            foreach ($posts as $post_id => $apost) {
                
                $featuredImageID = get_post_thumbnail_id($post_id);
                if ($featuredImageID) {
                    $featured_img_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $featuredImageID, 'size', $settings );
                } else {
                    $featured_img_url = $settings['custom_placeholder_image']['url'];
                }
                
                $posts_html .= '<div class="dce-finder-result-post dce-finder-result-post-card col">'
                        . '<a href="'.$apost['permalink'].'"><img src="'.$featured_img_url.'"></a>'
                        . '<a href="'.$apost['permalink'].'"><h4>'.$apost['title'].'</h4></a>'
                        . '<p>'.$apost['excerpt'].'</p>'
                        . '<a href="'.$apost['permalink'].'" class="btn button">'. __('Read more', 'dynamic-content-for-elementor').'</a>'
                        . '</div>';
            }
            
            
            $ret = array(
                'totals' => count($posts),
                'posts' => $posts_html,
                'taxonomies' => $taxonomies_html,
            );
        }
        echo json_encode($ret);
        // Always die in functions echoing ajax content
        wp_die(); // this is required to terminate immediately and return a proper response
    }
}