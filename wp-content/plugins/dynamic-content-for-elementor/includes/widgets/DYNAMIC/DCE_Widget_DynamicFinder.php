<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Dynamic Finder
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */

class DCE_Widget_DynamicFinder extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-dynamic-finder';
    }

    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Dynamic Finder', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Dynamic Finder', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo';
    }

    public function get_icon() {
        return 'icon-dynamic_posts';
    }

    public function get_script_depends() {
        return [ 'dce-finder'];
    }

    public function get_style_depends() {
        return [ 'dce-finder'];
    }

    protected function _register_controls() {
        /*
          SELECT
          title, thumb, excerpt, meta
          FROM
          posts, tax
          WHERE
          cpt, taxonomy
          ORDER BY
          title, content, meta
          menu, date, relevance
          LIMIT
         * 
         *
          OVERRIDE native search
         */

        // ------------------------------------------------------------------------------------ [SECTION]
        $this->start_controls_section(
                'section_query', [
            'label' => __('Search Query', 'dynamic-content-for-elementor'),
                ]
        );
        
        $this->add_control(
            'search_engine', [
                'label' => __('Search Engine', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'native' => [
                        'title' => __('Native', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'custom' => [
                        'title' => __('Custom', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ]
                ],
                'default' => 'native',
                'frontend_available' => true,
            ]
        );
        
        

        // --------------------------------- [ Custom Post Type ]
        $this->add_control(
                'post_type', [
            'label' => __('Post Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => DCE_Helper::get_post_types(),
            'multiple' => true,
            'label_block' => true,
            'default' => 'post',
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );

        $this->add_control(
                'taxonomy', [
            'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => DCE_Helper::get_taxonomy_terms(),
            'default' => '',
            'multiple' => true,
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );
        
        $this->add_control(
                'metas', [
            'label' => __('Search in post metas', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => '',
            'description' => __('Search also in Posts Meta fields.<br>Warning: using post meta will increase time necessary to obtain results.', 'dynamic-content-for-elementor'),
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );
        $this->add_control(
                'post_metas', [
            'label' => __('Post Metas', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => DCE_Helper::get_post_metas(),
            'default' => '',
            'multiple' => true,
            'description' => __('Search only in selected Posts Meta fields. If empty all fields will be utilized during search operation.', 'dynamic-content-for-elementor'),
            'condition' => [
                'search_engine' => 'custom',
                'metas' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'num_posts', [
            'label' => __('Number of Post', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '-1',
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'view_more_btn', [
            'label' => __('Show "View more results" button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            /*'condition' => [
                'search_engine' => 'custom',
            ],*/
                ]
        );
        
        $this->add_control(
          'view_more_btn_text',
          [
             'label' => __( 'View more results button text', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::TEXT,
             'default' => __('View more results', 'dynamic-content-for-elementor'),
             'condition' => [
                    'view_more_btn' => 'yes',
                ],
          ]
        );
        $this->add_control(
                'load_more_scroll', [
            'label' => __('Automatic load more result', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );

        $this->add_control(
                'orderby', [
            'label' => __('Order By', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => array('recommended'=> 'Recommended') + DCE_Helper::get_post_orderby_options(),
            'default' => 'recommended',
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );
        
        $this->add_control(
                'taxonomies', [
            'label' => __('Use taxonomy filter', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'description' => __('Use advanced taxonomy filter in result panel', 'dynamic-content-for-elementor'),
            'condition' => [
                'search_engine' => 'custom',
            ],
                ]
        );
        
        $this->add_control(
                'position', [
            'label' => __('Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => array(
                'below' => 'Below the search input',
                ' fullscreen' => 'Fullscreen',
            ),
            'default' => 'below',
                ]
        );
        
        
        
        
        $this->add_control(
            'post_display', [
                'label' => __('Search Engine', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'native' => [
                        'title' => __('Native', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'custom' => [
                        'title' => __('Custom', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ],
                    'template' => [
                        'title' => __('Template', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ]
                ],
                'default' => 'native',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
                'show_title', [
            'label' => __('Show post Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'search_engine' => 'custom',
                'post_display' => 'custom',
            ],
                ]
        );
        $this->add_control(
                'show_thumb', [
            'label' => __('Show post Featured image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'search_engine' => 'custom',
                'post_display' => 'custom',
            ],
                ]
        );
        $this->add_control(
          'custom_placeholder_thumb',
          [
             'label' => __( 'Placeholder Image', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::MEDIA,
             'description' => 'Use another image if the featured one does not exist.',
             'default' => [
                'url' => DCE_Helper::get_placeholder_image_src(),
             ],
             'condition' => [
                    'search_engine' => 'custom',
                    'show_thumb' => 'yes',
                    'post_display' => 'custom',
                ],
          ]
        );
        $this->add_control(
                'show_excerpt', [
            'label' => __('Show post Excerpt', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => '',
            'condition' => [
                'search_engine' => 'custom',
                'post_display' => 'custom',
            ],
                ]
        );
        $this->add_control(
                'show_cta', [
            'label' => __('Show post Read more button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'default' => '',
            'condition' => [
                'search_engine' => 'custom',
                'post_display' => 'custom',
            ],
                ]
        );
        $this->add_control(
          'custom_cta_button',
          [
             'label' => __( 'Placeholder Image', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::TEXT,
             'default' => __('Read more', 'dynamic-content-for-elementor'),
             'condition' => [
                    'search_engine' => 'custom',
                    'show_cta' => 'yes',
                    'post_display' => 'custom',
                ],
          ]
        );
        
        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        
        ?>
<form id="dce-finder-form-<?php echo $this->get_id(); ?>" action="<?php get_search_link(); ?>" method="get" data-pid="<?php echo get_the_ID(); ?>">
                <input type="text" value="" id="dce-finder-<?php echo $this->get_id(); ?>" class="dce-finder-input form-control" data-eid="<?php echo $this->get_id(); ?>">
                <div class="dce-finder-result-wrapper dce-finder-position-<?php echo $settings['position']; ?>">
                    <div class="dce-finder-result-searching">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div class="dce-finder-result-container">
                        <input type="text" value="" class="dce-finder-result-input">
                        <div class="dce-finder-result-taxonomies">
                            <ul>
                                
                            </ul>
                        </div>
                        <div class="dce-finder-result-posts">
                            <div class="dce-finder-result-posts-header">
                                <?php _e('Results found', 'dynamic-content-for-elementor'); ?>: <span class="dce-finder-result-number"></span>
                            </div>
                            <div class="dce-finder-result-posts-grid">
                                
                            </div>
                            <div class="dce-finder-result-posts-footer">
                                <a href="<?php echo get_search_link('dynamic.ooo'); ?>" class="btn button btn-primary">
                                    <?php _e('View more results', 'dynamic-content-for-elementor'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="dce-finder-result-close"><i class="fa fa-close"></i></a>
                </div>
            </form>
            <script>
                
                function dce_search_result_show(results, wrapper) {
                    console.log(wrapper);
                    wrapper.find('.dce-finder-result-posts-grid').html(results.posts);
                    if (results.taxonomies) {
                        wrapper.find('.dce-finder-result-taxonomies').html(results.taxonomies);
                    }
                    /*jQuery.each(results.posts, function (index, value) {
                        //wrapper.append('<div class="dce-finder-result-post col">'+value.post_title+'</div>');
                    });*/
                }
                
                function dce_search_result(search, eid, rand) {
                    var dce_finder_settings = jQuery('.elementor-element-'+eid).data('settings');
                    var wrapper = jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-wrapper');
                    if (dce_finder_settings.search_engine == 'native') {
                        jQuery.get('?s='+encodeURI(search), function(response) {
                            if (rand == jQuery('#dce-finder-'+eid).data('rand')) {
                                //alert("Got this from the server: " + response);
                                wrapper.find('.dce-finder-result-posts-grid').html('');
                                console.log(response);
                                //wrapper.html('keydown: '+response);
                                jQuery.each(response.data, function (index, value) {
                                    wrapper.append('<div class="dce-finder-result-post">'+value.post_title+'</div>');
                                })
                            }
                        });
                    } else {
                        var data = {};
                        data['action'] = "dce_finder_search";
                        data['search'] = search;
                        data['eid'] = eid;
                        data['post_id'] = jQuery('#dce-finder-form-'+eid).data('pid');
                        jQuery.post(ajaxurl, data, function(response) {
                            if (rand == jQuery('#dce-finder-'+eid).data('rand')) {
                                //alert("Got this from the server: " + response);
                                jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-posts-grid').html('');
                                jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-taxonomies > ul').html('');
                                console.log(response);
                                //wrapper.html('keydown: '+response);
                                var results = jQuery.parseJSON(response);
                                dce_search_result_show(results, wrapper);
                                // show results
                                jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-searching').hide();
                                jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-container').show();
                            }
                        });
                    }
                    jQuery('body').append('');
                }
                
                jQuery(document).ready(function(){
                    
                    jQuery(document).on('keydown', '.dce-finder-input', function() {
                        var eid = jQuery(this).data('eid');
                        var search = jQuery(this).val();
                        if (search.length > 3) {
                            console.log(search);
                            var rand = Math.random();
                            jQuery(this).data('rand', rand);
                            jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-wrapper').show();
                            jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-searching').show();
                            jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-container').hide();
                            dce_search_result(search, eid, rand);
                        }
                        jQuery('#dce-finder-form-'+eid).find('.dce-finder-result-input').val(search);
                    });
                    
                    jQuery(document).on('click', '.dce-finder-result-close', function() {
                        jQuery(this).closest('.dce-finder-result-wrapper').hide();
                    });
                    
                });
            </script>
            
            <style>
                .text-center {
                    text-align: center;
                }
                .max-width-100p {
                    max-width: 100%;
                }
                .absolute {
                    position: absolute;
                }
                .block {
                    display: block;
                }
                .top {
                    top: 0;
                }
                .right {
                    right: 0;
                }
                
                .dce-finder-result-wrapper {
                    font-family: sans-serif;
                    font-size: 14px;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                    box-sizing: border-box;
                    border-radius: 4px;
                    color: #333;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    min-width: 300px;
                    z-index: 2147483640;
                    overflow: hidden;
                    display: -webkit-box;
                    display: -webkit-flex;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -webkit-flex-flow: column nowrap;
                    -ms-flex-flow: column nowrap;
                    flex-flow: column nowrap;
                }
                .dce-finder-result-wrapper {
                    display: none;
                    /*position: absolute;*/
                    border: 1px solid;
                    z-index: 1000;
                    background-color: white;
                    width: calc(100% - 30px);
                    padding: 10px;
                    margin: 0 15px;
                }
                .dce-finder-result-wrapper {
                    position: fixed;
                    left: 0;
                    /*min-height: 400px;*/
                }
                
                .dce-finder-result-input {
                    position: absolute;
                    display: block;
                    padding: 4px;
                    left: 0;
                    top: 0;
                }
                .dce-finder-position-below .dce-finder-result-input {
                    display: none;
                }
                .dce-finder-result-close {
                    position: absolute;
                    display: block;
                    padding: 4px;
                    right: 0;
                    top: 0;
                }
                .dce-finder-result-close:hover {
                    color: red;
                }
                .dce-finder-result-searching {
                    padding: 15px;
                    font-size: 30px;
                    text-align: center;
                }
                
                .dce-finder-result-taxonomies {
                    float: right;
                    width: 280px;
                    max-width: 100%;
                    border-left: 1px solid #eee;
                    background-color: #fafafa;
                    padding: 15px;
                }
                .dce-finder-result-posts {
                    -webkit-box-flex: 1;
                    -webkit-flex: 1 0 280px;
                    -ms-flex: 1 0 280px;
                    flex: 1 0 280px;
                    display: -webkit-box;
                    display: -webkit-flex;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -webkit-flex-flow: column nowrap;
                    -ms-flex-flow: column nowrap;
                    flex-flow: column nowrap;
                    position: relative;
                    overflow-x: hidden;
                    background-color: inherit;
                }
                .dce-finder-result-posts-header {
                    font-weight: bold;
                    text-align: center;
                }
                
                
                
                .dce-finder-result-posts-grid {
                    display: -webkit-box;
                    display: -webkit-flex;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: horizontal;
                    -webkit-box-direction: normal;
                    -webkit-flex-flow: row wrap;
                    -ms-flex-flow: row wrap;
                    flex-flow: row wrap;
                    -webkit-box-pack: start;
                    -webkit-justify-content: flex-start;
                    -ms-flex-pack: start;
                    justify-content: flex-start;
                    -webkit-box-align: start;
                    -webkit-align-items: flex-start;
                    -ms-flex-align: start;
                    align-items: flex-start;
                }
                .dce-finder-result-posts-grid {
                    margin-right: -2px;
                    padding: 1px 0 0;
                }
                .dce-finder-result-posts-grid {
                    display: -ms-grid;
                    display: grid;
                    min-width: 180px;
                    /*-ms-grid-columns: (minmax(180px,1fr)) [auto-fill];*/
                    grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
                    grid-column-gap: 1px;
                    grid-row-gap: 1px;
                    grid-auto-rows: min-content;
                }
                .dce-finder-result-posts-grid {
                    -webkit-overflow-scrolling: touch;
                    overflow-x: hidden;
                    overflow-y: auto;
                    -webkit-box-flex: 1;
                    -webkit-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    padding: 0;
                    background-color: inherit;
                }
                
                .dce-finder-result-post-card {
                    width: auto !important;
                    margin: 0 !important;
                    border: 0 !important;
                }
                .dce-finder-result-post-card {
                    background: #fff;
                    border: 1px solid #eee;
                    font-size: 14px;
                    line-height: 1.285714286;
                }
                .dce-finder-result-post-card {
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -webkit-flex-flow: column nowrap;
                    -ms-flex-flow: column nowrap;
                    flex-flow: column nowrap;
                }
                .dce-finder-result-post-card {
                    width: 180px;
                    -webkit-box-flex: 1;
                    -webkit-flex: 1 1 auto;
                    -ms-flex: 1 1 auto;
                    flex: 1 1 auto;
                }
                .dce-finder-result-post-card {
                    display: -webkit-box;
                    display: -webkit-flex;
                    display: -ms-flexbox;
                    display: flex;
                    padding: 0;
                    margin: 0;
                }
                .dce-finder-result-post-card {
                    margin: -1px 0 0 -1px;
                }
                
            </style>
        <?php
        
    }

}
