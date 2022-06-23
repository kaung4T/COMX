<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elements Post Author
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Breadcrumbs extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-breadcrumbs';
    }
    
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Breadcrumbs', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Insert breadcrumbs and generate paths inside your page automatically', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/breadcrumbs/';
    }
    public function get_icon() {
        return 'icon-dyn-breadcrumbs';
    }

    static public function get_position() {
        return 4;
    }

    protected function _register_controls() {

       $this->start_controls_section(
            'section_options', [
                'label' => __('Options', 'dynamic-content-for-elementor'),
            ]
        );
       if( !function_exists('yoast_breadcrumb') || !$this->is_yoast_breadcrumbs()){
           $this->add_control(
                'enable_home_text', 
                [
                    'label'         => __( 'Enable Home text', 'dynamic-content-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'label_on'      => __( 'Yes', 'dynamic-content-for-elementor' ),
                    'label_off'     => __( 'No', 'dynamic-content-for-elementor' ),
                    'return_value'  => 'yes',
                    
                ]
            );

           $this->add_control(
              'home-text', [
                'label' => __('Home text', 'dynamic-content-for-elementor'),
                //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Homepage',
                'condition' => [
                        'enable_home_text' => 'yes',
                    ]
              ]
            );
            $this->add_control(
              'separator', [
                'label' => __('Separator', 'dynamic-content-for-elementor'),
                //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => ' > ',
              ]
            );
        }else{
            $this->add_control(
                'yoast_bc_alert',
                [
                    'raw' => __( 'Breadcrumbs Yoast SEO', 'dynamic-content-for-elementor' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ), __( 'Go settings Panel', 'dynamic-content-for-elementor' ) ),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => '',
                ]
            );
        }
        $this->add_responsive_control(
            'align', [
                'label' => __('Alignment', 'dynamic-content-for-elementor'),
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
                    'justify' => [
                        'title' => __('Justified', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'space', [
                'label' => __('Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-breadcrumbs .dce-separator, {{WRAPPER}} a, {{WRAPPER}} a + span' => 'padding: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------- STYLE
        $this->start_controls_section(
            'section_style_items', [
                'label' => __('Items', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'breadcrums_items',
            [
                'label' => __( 'Items', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-breadcrumbs li, {{WRAPPER}} .dce-breadcrumbs a, {{WRAPPER}} .dce-breadcrumbs span:not(.dce-separator)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
          'color_hover', [
            'label' => __('Text Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-breadcrumbs a:hover' => 'color: {{VALUE}};',
            ],
          ]
        );
        $this->add_control(
          'final_color', [
            'label' => __('Final text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-breadcrumbs .bread-current' => 'color: {{VALUE}};',
            ],
          ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-breadcrumbs',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_final',
                'label' => __('Typography of final', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-breadcrumbs .bread-current',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_home', [
                'label' => __('Home', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_home_text' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'home_color', [
                'label' => __('Home text color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-breadcrumbs li.item-home' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dce-breadcrumbs li.item-home a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_home_text' => 'yes',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_hometext',
                'label' => __('Typography home text', 'dynamic-content-for-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .dce-breadcrumbs li.item-home',
            ]
        );



        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_separator', [
                'label' => __('Separator', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_home_text' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'separator_color', [
                'label' => __('Separator Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-breadcrumbs .dce-separator' => 'color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_separator',
                'label' => __('Typography separator', 'dynamic-content-for-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .dce-breadcrumbs .dce-separator',
            ]
        );
        

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
        return;
        // Settings
        $separator = '';
        if( isset($settings['separator'] ) ) $separator = '<span class="dce-separator">'.$settings['separator'].'</span>';
        $id         = 'dce-breadcrumbs';
        $class      = 'dce-breadcrumbs';
        
        $home_title = '';
        if( isset($settings['home-text'] ) ) $home_title = $settings['home-text'];
        

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        // ------------------------------------------
        

        // Get the query & post information
        global $post,$wp_query;
        $category = get_the_category( $id_page );
        //var_dump($category); die();
        // Build the breadcrums
        
        

        $html_tag = 'div';

        if(function_exists('yoast_breadcrumb') && $this->is_yoast_breadcrumbs()) {
            yoast_breadcrumb( '<' . $html_tag . ' id="'.$id.'" class="'.$class.'">', '</' . $html_tag . '>' );
        }else{
            echo '<ul id="' . $id . '" class="' . $class . '">';

            // Do not display on the homepage
            if ( !is_front_page() ) {

                if( $settings['enable_home_text'] == 'yes' ){
                    // Home page
                    echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
                    echo '<li class="separator separator-home"> ' . $separator . ' </li>';
                }
                if ( is_single() ) {

                // Single post (Only display the first category)
                if( count($category) > 0 )echo '<li class="item-cat item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><a class="bread-cat bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '" href="' . get_category_link($category[0]->term_id ) . '" title="' . $category[0]->cat_name . '">' . $category[0]->cat_name . '</a></li>';
                if( count($category) > 0 ) echo '<li class="separator separator-' . $category[0]->term_id . '"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $id_page . '"><strong class="bread-current bread-' . $id_page . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                } else if ( is_category() ) {

                // Category page
                echo '<li class="item-current item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><strong class="bread-current bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '">' . $category[0]->cat_name . '</strong></li>';

            } else if ( is_page() ) {

                // Standard page
                if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $id_page );

                // Get parents in the right order
                $anc = array_reverse($anc);
                $parents = '';
                // Parent page loop
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-' . $id_page . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';

                } else {

                // Just display current page if not parents
                echo '<li class="item-current item-' . $id_page . '"><strong class="bread-current bread-' . $id_page . '"> ' . get_the_title() . '</strong></li>';

                }

            } else if ( is_tag() ) {

                // Tag page

                // Get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args ='include=' . $term_id;
                $terms = get_terms( $taxonomy, $args );

                // Display the tag name
                echo '<li class="item-current item-tag-' . $terms[0]->term_id . ' item-tag-' . $terms[0]->slug . '"><strong class="bread-current bread-tag-' . $terms[0]->term_id . ' bread-tag-' . $terms[0]->slug . '">' . $terms[0]->name . '</strong></li>';

            } elseif ( is_day() ) {

                // Day archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                // Month link
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

                // Day display
                echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

            } else if ( is_month() ) {

                // Month Archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                // Month display
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

                } else if ( is_year() ) {

                // Display year archive
                echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

                } else if ( is_author() ) {

                // Auhor archive

                // Get the author information
                global $author;
                $userdata = get_userdata( $author );

                // Display author name
                echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

                } else if ( get_query_var('paged') ) {

                // Paginated archives
                echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';

                } else if ( is_search() ) {

                // Search results page
                echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

                } elseif ( is_404() ) {

                // 404 page
                echo '<li>' . 'Error 404' . '</li>';
                }

            }

            echo '</ul>';
        }
    }

    protected function _content_template() {
        
    }

    private function is_yoast_breadcrumbs() {
        $breadcrumbs_yoast = current_theme_supports( 'yoast-seo-breadcrumbs' );
        if ( ! $breadcrumbs_yoast ) {
            $options_yoast = get_option( 'wpseo_internallinks' );
            $breadcrumbs_yoast = true === $options_yoast['breadcrumbs-enable'];
        }

        return $breadcrumbs_yoast;
    }

}
