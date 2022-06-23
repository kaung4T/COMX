<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Post NextPrev
 *
 * Single post/page NextPrev for Dynamic Content for Elementor
 *
 */
class DCE_Widget_InfinitePosts extends DCE_Widget_Prototype {
    
    public static $alreadyEnqueued = false;

    public function get_name() {
        return 'dyncontel-infinite-posts';
    }
    static public function is_enabled() {
        return false;
    }
    public function get_title() {
        return __('Infinite Posts', 'dynamic-content-for-elementor');
    }
    public function get_icon() {
        return 'icon-dyn-infinite_posts todo';
    }
    /*public function get_style_depends() {
        return [ 'dce-nextPrev' ];
    }*/
    
    public function get_script_depends() {
        return [ 'dce-visible' ];
    }
    static public function get_position() {
        return 6;
    }
    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_content', [
                'label' => __('Infinite Posts', 'dynamic-content-for-elementor')
            ]
        );
        
        $this->add_control(
            'article_selector', [
                'label' => __('Main content selector', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'article',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'next_widget_id', [
            'label' => __('Automatic', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'default' => $this->get_id(),
            'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'next_on_scroll', [
            'label' => __('Automatic', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'description' => 'Append automatically next post on scrolling to the end of page',
            'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'next_button_label', [
                'label' => __('Next button label', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'See next post',
                'condition' => [
                    'next_on_scroll' => ''
                ]
            ]
        );
        
        $this->add_control(
            'next_block', [
                'label' => __('Next block show', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    /*'noshow' => [
                        'title' => __('No show', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-ban',
                    ],*/
                    'button' => [
                        'title' => __('Only button', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-hand-pointer-o',
                    ],
                    'widget' => [
                        'title' => __('In widget position', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-cube',
                    ],
                    'fixed' => [
                        'title' => __('Fixed', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-bookmark',
                    ],
                ],
                'default' => 'widget',
                'frontend_available' => true,
                'condition' => [
                    'next_on_scroll' => ''
                ]
            ]
        );
        
        
        
        $this->add_control(
            'next_block_align', [
                'label' => __('Next block alignment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'right',
                'condition' => [
                    'next_block' => array('fixed', 'widget', 'button'),
                    'next_on_scroll' => ''
                ]
            ]
        );
        
        $this->add_control(
            'next_block_valign', [
                'label' => __('Next block vertical alignment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'bottom' => [
                        'title' => __('Bottom', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-angle-down',
                    ],
                    'middle' => [
                        'title' => __('Middle', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-arrows-v',
                    ],
                    'top' => [
                        'title' => __('Top', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-angle-up',
                    ],
                ],
                'default' => 'bottom',
                'condition' => [
                    'next_block' => 'fixed',
                    'next_on_scroll' => ''
                ]
            ]
        );
        
        
        /*$this->add_control(
            'wp_next', [
                'label' => __('Use default WP Next', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                'description' => 'Use native Wordpress function to detect next post, otherwise you can filter and decide what posts show.'
            ]
        );
        
        $this->add_control(
            'taxonomy_type', [
                'label' => __('Taxonomy Type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => DCE_Helper::get_taxonomies(),
                'default' => '',
                'description' => 'If want to show "Same term" of the current post.',
                'condition' => [
                    'wp_next' => '',
                ]
            ]
        );*/
       
       
        $this->end_controls_section();
    }

    protected function render() {
        global $global_ID;
        if (!$global_ID) {
            $global_ID = get_the_ID();
        }
        
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;
        
        // ------------------------------------------
        
        if (is_single() || true) {
            //echo 'SINGLE';
            //$taxonomy_type = $settings['taxonomy_type'];
            //$same_term = $settings['same_term'];
            
            //$next = get_adjacent_post();
            $next = \DynamicContentForElementor\DCE_Helper::get_adjacent_post_by_id(null, null, true, null, get_the_ID());
            //var_dump($next);
            $next_url = get_permalink($next->ID);
            
            if (!$settings['next_on_scroll'] && $settings['next_block']) {
                switch ($settings['next_block']) {
                    
                    case 'button': 
                        echo '<div class="infinite-button-next" style="text-align: '.$settings['next_block_align'].';"><button class="btn">'.$settings['next_button_label'].'</button></div>';
                        break;
                    
                    case 'widget': 
                    case 'fixed': 
                        ?>
                        <div id="infinite-box-next-<?php echo $global_ID; ?>" 
                             class="infinite-box-next infinite-box-next-<?php echo $settings['next_block']; ?> infinite-box-next-editor infinite-box-next-todo infinite-box-next-<?php echo $settings['next_block_align']; ?> infinite-box-next-<?php echo $settings['next_block_valign']; ?><?php if ($settings['next_block'] == 'fixed' && !\Elementor\Plugin::$instance->editor->is_edit_mode()) { ?> infinite-box-next-hidden<?php } ?>" 
                             data-post-id="<?php echo $global_ID; ?>" 
                             data-next-id="<?php echo $next->ID; ?>" 
                             data-article="#infinite-post-next-<?php echo $global_ID; ?>"
                        >
                            <?php if( $settings['next_block'] == 'fixed' ) { ?><a href="#" class="infinite-box-next-close">x</a><?php } ?>
                            <div class="infinite-box-next-thumbnail">
                                <?php echo get_the_post_thumbnail($next->ID); ?>
                            </div>
                            <h4 class="infinite-box-next-title"><?php echo get_the_title($next->ID); ?></h4>
                            <?php if ($settings['next_button_label']) { ?>
                            <a class="infinite-box-next-cta" href="<?php echo get_permalink($next->ID); ?>">
                                    <?php echo $settings['next_button_label']; ?>
                                </a>
                            <?php } ?>
                        </div>   
                        <?php
                        break;
                    
                    case 'noshow': 
                    default:
                        break;
                }
            }
            
            
            add_action('wp_footer', [$this, 'add_next_script'], 30);
        }
        
        if (is_archive()) {
            echo 'ARCHIVE';
        ?>
            <script>

            </script>
        <?php
        } 
        // ------------------------------------------
        
        ?>
            <style>
                .infinite-box-next-fixed {
                    position: fixed;
                    border: 1px solid;
                    width: 200px;
                    padding: 15px;
                    transition: all 1s;
                    background-color: white;
                    z-index: 10;
                }
                
                .infinite-box-next-fixed.infinite-box-next-bottom {
                    bottom: 20px;
                }
                .infinite-box-next-fixed.infinite-box-next-top {
                    top: 20px;
                }
                .infinite-box-next-fixed.infinite-box-next-middle {
                    top: 50%;
                }
                
                .infinite-box-next-fixed.infinite-box-next-left {
                    left: 0;
                    border-left: none;
                }
                .infinite-box-next-fixed.infinite-box-next-right {
                    right: 0;
                    border-right: none;
                }
                
                .infinite-box-next-fixed.infinite-box-next-left.infinite-box-next-hidden {
                    left: -200px;
                }
                .infinite-box-next-fixed.infinite-box-next-right.infinite-box-next-hidden {
                    right: -200px;
                }
                
                .infinite-box-next-fixed.infinite-box-next-center {
                    left: 50%;
                    margin-left: -100px;
                }
                
                .infinite-box-next-fixed.infinite-box-next-center.infinite-box-next-middle.infinite-box-next-hidden {
                    display: none !important;
                }
                .infinite-box-next-fixed.infinite-box-next-center.infinite-box-next-top.infinite-box-next-hidden {
                    top: -100%;
                }
                .infinite-box-next-fixed.infinite-box-next-center.infinite-box-next-bottom.infinite-box-next-hidden {
                    bottom: -100%;
                }
                
                .infinite-box-next-fixed > .infinite-box-next-close {
                    position: absolute;
                    top: 0;
                    right: 0;
                    width: 15px;
                    height: 15px;
                    line-height: 15px;
                    font-size: 10px;
                    text-align: center;
                    background-color: #333;
                    color: white;
                }
                
                .infinite-box-next-widget.infinite-box-next-left {
                    text-align: left;
                }
                .infinite-box-next-widget.infinite-box-next-right {
                     text-align: right;
                }
                .infinite-box-next-widget.infinite-box-next-center {
                     text-align: center;
                }
                .infinite-box-next-widget.infinite-box-next-hidden {
                    display: none !important;
                }
                
                .infinite-box-next img {
                    max-width: 100%;
                    height: auto;
                }
            </style>
        <?php
        
    }
    
    
    public function add_next_script() {
        if ( ! self::$alreadyEnqueued ) {
        $next = \DynamicContentForElementor\DCE_Helper::get_adjacent_post_by_id(null, null, true, null, get_the_ID());
        ?>
        <script>
            var initial_post_id = <?php the_ID(); ?>;
            var next_post_id = <?php echo $next->ID; ?>;
            
            
            var dce_next_settings = jQuery('.elementor-widget-dyncontel-infinite-posts').data('settings');
            console.log(dce_next_settings);
            //alert(dce_next_settings.next_on_scroll);
            
            var next_container = jQuery( dce_next_settings.article_selector ).parent();
            next_container.addClass('infinite-post-container');
            
            function dce_load_next(post_id, next, scrollto = false) {
                
                //alert('add next');
                /*var data = {};
                data['action'] = "get_post_action";
                data['post_id'] = post_id;
                console.log(data);
                jQuery.post(ajaxurl, data, function(response) {
                    //alert("Got this from the server: " + response);
                    console.log(response);
                    //alert(response.permalink);
                    jQuery( '#infinite-post-next-'+post_id ).html(response);
                });*/
                
                jQuery( '#infinite-post-next-'+post_id ).load( next.permalink+' '+dce_next_settings.article_selector, function() {
                    jQuery(this).hide().fadeIn();
                    jQuery(this).find('.infinite-box-next-editor').removeClass('infinite-box-next-editor');
                    jQuery('#infinite-post-next-'+post_id).attr('data-post-id', post_id);
                    jQuery('#infinite-post-next-'+post_id).attr('data-next-id', next.ID);
                    //alert(post_id + ' -- ' +next.ID);
                    if (!jQuery('#infinite-post-next-'+next.ID).length) {
                        jQuery( '.infinite-post-container' ).append( '<div id="infinite-post-next-'+next.ID+'" class="infinite-post-next infinite-post-next-todo" data-post-id="'+next.ID+'"></div>' );
                        
                        var element_el = jQuery('#infinite-post-next-'+next.ID).find('.elementor-element');
                        element_el.each(function(i) {
                            //var el = jQuery(this).data('element_type');
                            elementorFrontend.elementsHandler.runReadyTrigger( jQuery(this) );
                        });
                        
                        if (scrollto) {
                            var target = '#infinite-post-next-'+post_id;
                            console.log('scrollto: '+target);
                            jQuery('html, body').stop().animate({
                                    'scrollTop': jQuery(target).offset().top
                            }, 500, 'swing', function() {
                                    window.location.hash = target;
                            });
                        }
                    }
                });
                
            }
            
            function dce_box_next(post_id, next) {
                var box_next_id = 'infinite-box-next-'+post_id; //next.ID;
                console.log('creo il box: '+box_next_id);
                if (jQuery('#'+box_next_id).length) {
                    jQuery('#'+box_next_id).show();
                } else {
                    var box_next = jQuery('.infinite-box-next-editor').clone().attr('id', box_next_id);
                    jQuery( '.infinite-post-container' ).append(box_next);
                    jQuery('#'+box_next_id).attr('data-post-id', post_id);
                    jQuery('#'+box_next_id).attr('data-next-id', next.ID); //post_id);
                    jQuery('#'+box_next_id).removeClass('infinite-box-next-editor');
                    jQuery('#'+box_next_id).attr('data-article', '#infinite-post-next-'+post_id);
                    jQuery('#'+box_next_id).find('.infinite-box-next-thumbnail').html(next.thumbnail);
                    jQuery('#'+box_next_id).find('.infinite-box-next-title').text(next.title);
                    jQuery('#'+box_next_id).find('.infinite-box-next-cta').attr('href', next.permalink);
                    //jQuery('#'+box_next_id).append( '<div id="infinite-box-next-'+next.ID+'" class="infinite-box-next infinite-box-next-todo" data-post-id="'+post_id+'">'+next.thumbnail+'<h4>'+next.title+'</h4><a href="#infinite-post-next-'+post_id+'">Read next post</a></div>' );
                    jQuery('#'+box_next_id).addClass('infinite-box-next-todo');
                    jQuery('#'+box_next_id).addClass('infinite-box-next-hidden');
                    //jQuery('body').append(box_next);
                    jQuery('#'+box_next_id).show();
                }
                jQuery('#'+box_next_id).removeClass('infinite-box-next-hidden');
            }
            
            function dce_preloader(post_id) {
                jQuery( '#infinite-post-next-'+post_id ).html('<div class="text-center" style="text-align:center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
            }

            jQuery(document).ready(function(){
                if ( typeof ajaxurl === 'undefined') {
                    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>";
                }
                
                
                jQuery('.infinite-post-container').append( '<div id="infinite-post-next-'+initial_post_id+'" class="infinite-post-next infinite-post-next-todo" data-post-id="'+initial_post_id+'" data-next-id="'+next_post_id+'"></div>' );
                //var next_url = jQuery('.infinite-post-next').attr('data-url');
                
                jQuery(window).on('load scroll resize', function(){
                    
                    // TODO: use Waypoint instead of Visible
                    /*var waypoint = new Waypoint({
                        element: document.getElementById('waypoint'),
                        handler: function(direction) {
                          console.log('Scrolled to waypoint!')
                        }
                    });*/
                    
                    //alert('visible');
                    jQuery('.infinite-post-next-todo').each(function(){
                        if (jQuery(this).visible()) {
                           //alert('visible');
                           console.log('visible: '+jQuery(this).attr('id'));
                           jQuery(this).click();
                        }
                    });
                });
                
                
                jQuery(document).on("click", ".infinite-post-next-todo", function(event){
                    jQuery(this).removeClass('infinite-post-next-todo');
                    
                    
                    
                    var post_id = jQuery(this).attr("data-post-id");
                    console.log('click next: '+post_id);
                    /*if (jQuery('.infinite-box-next-editor').length) {
                        jQuery('.infinite-box-next-editor').show(); 
                        return;
                    }*/
        
                    if (dce_next_settings.next_on_scroll) {
                        dce_preloader(post_id);
                    }
        
                    //alert('add next');
                    var data = {};
                    data['action'] = "dce_get_next_post";
                    if (jQuery(this).attr("data-post-id")) {
                        data['post_id'] = post_id;
                    }
                    console.log(data);
                    jQuery.post(ajaxurl, data, function(response) {
                        //alert("Got this from the server: " + response);
                        console.log(response);
                        //alert(response.permalink);
                        if (response.permalink) {
                            if (dce_next_settings.next_on_scroll) {
                                dce_load_next(post_id, response);
                            } else {
                                dce_box_next(post_id, response);
                            }
                        } else {
                            jQuery( '#infinite-post-next-'+post_id ).remove();
                        }
                    }, "json");
                });
                
                jQuery(document).on("click", ".infinite-box-next-todo", function(event){
                    jQuery(this).removeClass('infinite-box-next-todo');
                    var post_id = jQuery(this).attr("data-post-id");
                    
                    dce_preloader(post_id);
                    
                    //alert('add next');
                    var data = {};
                    data['action'] = "dce_get_next_post";
                    if (post_id) {
                        data['post_id'] = post_id;
                        /*if (jQuery('.infinite-box-next-editor').length) {                          
                            var next_id = jQuery(this).attr("data-next-id");
                            data['post_id'] = next_id;
                        }*/
                    }
                    console.log(data);
                    jQuery.post(ajaxurl, data, function(response) {
                        //alert("Got this from the server: " + response);
                        console.log(response);
                        //alert(response.permalink);
                        if (response.permalink) {
                            dce_load_next(post_id, response, true);
                        }
                    }, "json");
                    
                    jQuery(this).addClass('infinite-box-next-hidden');
                    
                    
                    //if (dce_next_settings.next_block != 'widget') {
                        //jQuery(this).remove();    
                    //}
                    
                    return false;
                });
                
                jQuery(document).on("click", ".infinite-box-next-close", function(event){
                    jQuery(this).closest('.infinite-box-next').addClass('infinite-box-next-hidden');
                    return false;
                });
                //jQuery( '.infinite-post-next' ).load( next_url+' #main' );
            });
        </script>
        <?php
        }
        self::$alreadyEnqueued = true;
    }

}
