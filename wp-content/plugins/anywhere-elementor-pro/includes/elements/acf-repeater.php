<?php

namespace Aepro;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Aepro_ACF_Repeater extends Widget_Base {

    public function get_name() {

        return 'ae-acf-repeater';
    }

    public function get_title() {

        return __('AE - ACF Repeater', 'ae-pro');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_script_depends() {

        return [ 'jquery-masonry', 'ae-swiper' ];

    }

    //public function get_custom_help_url() {
    //    $helper = new Helper();
    //    return $helper->get_help_url_prefix() . $this->get_name();
    //}

    protected function _register_controls()
    {

        $helper = new Helper();

        // Register Controls

        $this->setting_controls();

        $this->layout_controls();

        $this->carousel_controls();

        // Register Styles

        $this->layout_styles();

        $this->carousel_styles();


    }

    protected function render() {

        $settings = $this->get_settings();
        if(!isset($settings['template']) || empty($settings['template'])){
            echo __('Please select a template first','ae-pro');
        }else{
            $this->generate_output($settings);
        }
    }

    function generate_output($settings,$with_wrapper = true){

        $settings['template'] = apply_filters( 'wpml_object_id', $settings['template'], 'ae_global_templates' );
        $masonry = $settings['masonry_grid'];

        if($settings['layout_mode'] == 'carousel') {
            $settings['direction'] = 'horizontal';
            /*-- Carousel */
	        $slide_per_view['desktop'] = isset($settings['slide_per_view']) ? $settings['slide_per_view'] : 1;
	        $slide_per_view['tablet'] = isset($settings['slide_per_view_tablet']) ? $settings['slide_per_view_tablet'] : 1;
	        $slide_per_view['mobile'] = isset($settings['slide_per_view_mobile']) ? $settings['slide_per_view_mobile'] : 1;

            $slides_per_group['desktop'] = isset($settings['slides_per_group']) ? $settings['slides_per_group'] : 1;
            $slides_per_group['tablet'] = isset($settings['slides_per_group_tablet']) ? $settings['slides_per_group_tablet'] : 1;
            $slides_per_group['mobile'] = isset($settings['slides_per_group_mobile']) ? $settings['slides_per_group_mobile'] : 1;
            //echo '<pre>';print_r($slide_per_view);'</pre>';

            $direction = $settings['direction'];
            $speed = $settings['speed'];
            $autoplay = $settings['autoplay'];
            $duration = $settings['duration'];
            $effect = $settings['effect'];
            $space['desktop'] = $settings['space']['size'];
            $space['tablet'] = $settings['space_tablet']['size'];
            $space['mobile'] = $settings['space_mobile']['size'];
            //print_r(json_encode($space));
            $loop = $settings['loop'];
	        $auto_height = $settings['auto_height'];
            $zoom = 'no'; //$settings['zoom'];
            $pagination_type = $settings['ptype'];
            $navigation_button = $settings['navigation_button'];
            $clickable = $settings['clickable'];
            $keyboard = $settings['keyboard'];
            $scrollbar = $settings['scrollbar'];
            $ptype = $settings['ptype'];

            $this->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper');

            $this->add_render_attribute('outer-wrapper', 'data-speed', $speed['size']);
            $this->add_render_attribute('outer-wrapper', 'data-direction', $direction);
            if ($autoplay == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-autoplay', $autoplay);
            }
            if ($autoplay == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-duration', $duration['size']);
            }
            $this->add_render_attribute('outer-wrapper', 'data-effect', $effect);
            $this->add_render_attribute('outer-wrapper', 'data-space', json_encode($space, JSON_NUMERIC_CHECK));
            if ($loop == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-loop', $loop);
            } else {
                autoplayStopOnLast:
                true;
            }

	        if ($auto_height == 'yes') {
		        $this->add_render_attribute('outer-wrapper', 'data-auto-height', 'true');
	        } else {
		        $this->add_render_attribute('outer-wrapper', 'data-auto-height', 'false');
	        }
	        
            if ($zoom == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-zoom', $zoom);
            }

            if (!empty($slide_per_view)) {
                $this->add_render_attribute('outer-wrapper', 'data-slides-per-view', json_encode($slide_per_view, JSON_NUMERIC_CHECK));
            }

            if (!empty($slides_per_group)) {
                $this->add_render_attribute('outer-wrapper', 'data-slides-per-group', json_encode($slides_per_group, JSON_NUMERIC_CHECK));
            }


            if ($ptype != '') {
                $this->add_render_attribute('outer-wrapper', 'data-ptype', $ptype);
            }
            if ($pagination_type == 'bullets' && $clickable == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-clickable', $clickable);
            }
            if ($navigation_button == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
            }
            if ($keyboard == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-keyboard', $keyboard);
            }
            if ($scrollbar == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-scrollbar', $scrollbar);
            }

            /*-- Carousel */
        }

        $this->add_render_attribute( 'acf-repeater-wrapper', 'class', 'ae-acf-repeater-wrapper' );
        $this->add_render_attribute( 'acf-repeater-inner', 'class', 'ae-acf-repeater-inner' );
        $this->add_render_attribute( 'acf-repeater-widget-wrapper', 'data-pid', get_the_ID() );
        $this->add_render_attribute( 'acf-repeater-widget-wrapper', 'data-wid', $this->get_id() );
        $this->add_render_attribute( 'acf-repeater-widget-wrapper', 'class', 'ae-acf-repeater-widget-wrapper' );

        if($settings['layout_mode'] == 'carousel'){
            $this->add_render_attribute( 'acf-repeater-widget-wrapper', 'class', 'ae-carousel-yes');
        }else{
            $this->add_render_attribute( 'acf-repeater-widget-wrapper', 'class', 'ae-masonry-'.$masonry);

        }

        $this->add_render_attribute( 'acf-repeater-item', 'class', 'ae-acf-repeater-item' );


        $with_css = false;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            $with_css = true;
        }

        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();

        if($with_wrapper){ ?>
            <div <?php echo $this->get_render_attribute_string('acf-repeater-widget-wrapper'); ?>>
        <?php } ?>

        <?php if($settings['layout_mode'] == 'carousel'){ ?>
            <div <?php echo $this->get_render_attribute_string('outer-wrapper'); ?> >
            <?php $this->add_render_attribute('swiper-container', 'class', ['ae-swiper-container', 'swiper-container']); ?>
            <div <?php echo $this->get_render_attribute_string('swiper-container'); ?> >
            <?php $this->add_render_attribute('acf-repeater-wrapper', 'class', ['ae-swiper-wrapper', 'swiper-wrapper']); ?>
            <?php $this->add_render_attribute('acf-repeater-item', 'class', ['ae-swiper-slide', 'swiper-slide']); ?>
            <?php $this->add_render_attribute('acf-repeater-inner', 'class', ['ae-swiper-slide-wrapper', 'swiper-slide-wrapper']); ?>

        <?php  } ?>

        <?php if( have_rows($settings['acf_repeater_field_name'], $post_data->ID) ){ ?>
            <div <?php echo $this->get_render_attribute_string('acf-repeater-wrapper'); ?>>
                <?php while( have_rows($settings['acf_repeater_field_name'], $post_data->ID) ) {  the_row();
                    ?>
                    <div <?php echo $this->get_render_attribute_string('acf-repeater-item'); ?>>
                        <div <?php echo $this->get_render_attribute_string('acf-repeater-inner'); ?>>
                            <div class="ae_data elementor elementor-<?php echo $settings['template']; ?>">
                                <?php echo Plugin::instance()->frontend->get_builder_content( $settings['template'],$with_css ); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <div class="ae-no-posts">
                <?php echo do_shortcode($settings['no_posts_message']);
                $settings['layout_mode'] = '';
                ?>
            </div>
        <?php } ?>

        <?php if($settings['layout_mode'] == 'carousel'){ ?>
            <?php if($pagination_type != ''){ ?>
                <div class = "ae-swiper-pagination swiper-pagination"></div>
            <?php } ?>
            <?php if($navigation_button == 'yes'){ ?>
                <?php if($settings['arrow_horizontal_position'] != 'center'){;?>
                    <div class="ae-swiper-button-wrapper swiper-button-wrapper">
                <?php } ?>
                <div class = "ae-swiper-button-prev swiper-button-prev">
                    <?php if($settings['direction'] == 'vertical') { ?>
                        <i class="fa fa-angle-up"></i>
                    <?php } else { ?>
                        <?php if(is_rtl()){ ?>
                            <i class="fa fa-angle-right"></i>
                        <?php }else{ ?>
                            <i class="fa fa-angle-left"></i>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class = "ae-swiper-button-next swiper-button-next">
                    <?php if($settings['direction'] == 'vertical') { ?>
                        <i class="fa fa-angle-down"></i>
                    <?php } else { ?>
	                    <?php if(is_rtl()){ ?>
                            <i class="fa fa-angle-left"></i>
	                    <?php }else{ ?>
                            <i class="fa fa-angle-right"></i>
	                    <?php } ?>
                    <?php } ?>
                </div>
                <?php if($settings['arrow_horizontal_position'] != 'center'){;?>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if($scrollbar == 'yes'){ ?>
                <div class = "ae-swiper-scrollbar swiper-scrollbar"></div>

            <?php } ?>
            </div>
            </div>
        <?php  } ?>
        <?php if($with_wrapper){ ?>
            </div>
        <?php } ?>

        <?php
    }

    function setting_controls(){
        $helper = new Helper();
        $this->start_controls_section(
            'repeater_section',
            [
                'label' => __( 'Repeater', 'ae-pro' ),
            ]
        );

        $block_layouts[''] = 'Select Template';
        $block_layouts = $block_layouts + $helper->ae_acf_repeater_layouts();


        $this->add_control(
            'template',
            [
                'label'     =>  __('Template','ae-pro'),
                'type'      =>  Controls_Manager::SELECT,
                'options'   =>  $block_layouts,
                'description' => __('Know more about templates <a href="http://aedocs.webtechstreet.com/article/9-creating-block-layout-in-anywhere-elementor-pro" target="_blank">Click Here</a>','ae-pro')
            ]
        );

        $repeater_fields[''] = 'Select Field';
        $repeater_fields = $repeater_fields + $helper->get_ae_acf_repeater_fields();

        $this->add_control(
            'acf_repeater_field_name',
            [
                'label' => __('Repeater Field (ACF)','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options'   =>  $repeater_fields,
                'placeholder' => __('Repeater Field Name','ae-pro'),
            ]
        );

        $this->end_controls_section();
    }

    function layout_controls(){
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'layout_mode',
            [
                'label' => __('Layout Mode','ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grid'  => __('Grid', 'ae-pro'),
                    'carousel' => __('Carousel', 'ae-pro')
                ],
                'default' => 'grid',
                'prefix_class' => 'ae-acf-repeater-layout-'
            ]
        );


        $this->add_control(
            'masonry_grid',
            [
                'label' => __( 'Masonry', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'masonry_on' => __( 'On', 'ae-pro' ),
                'masonry_off' => __( 'Off', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'ae-pro'),
                'type'  => Controls_Manager::NUMBER,
                'desktop_default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'min' => 1,
                'max' => 12,
                'condition' => [
                    'layout_mode' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-repeater-item' => 'width: calc(100%/{{VALUE}})',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_col_gap',
            [
                'label' => __('Column Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'layout_mode' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-acf-repeater-layout-grid .ae-acf-repeater-item' => 'padding-left:{{SIZE}}{{UNIT}}; padding-right:{{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-repeater-item' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_mode' => 'grid'
                ]
            ]
        );

        $this->add_responsive_control(
            'carousel_item_row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-repeater-wrapper' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );

        $this->add_control(
            'no_posts_message',
            [
                'label' => __('No Posts Message', 'ae-pro'),
                'type'  => Controls_Manager::TEXTAREA,
                'separator' => 'before',
                'description'   => __('', 'ae-pro')
            ]
        );

        $this->end_controls_section();
    }

    function carousel_controls()
    {

        $this->start_controls_section(
            'carousel_control',
            [
                'label' => __( 'Carousel', 'ae-pro' ),
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );

        $this->add_control(
            'image_carousel',
            [
                'label' => __('Carousel', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        // Todo:: different effects management
        $this->add_control(
            'effect',
            [
                'label' => __('Effects', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'ae-pro'),
                    'slide' => __('Slide', 'ae-pro'),
                    //'cube' => __('Cube', 'ae-pro'),
                    'coverflow' => __('Coverflow', 'ae-pro'),
                    'flip' => __('Flip', 'ae-pro'),
                ],
                'default'=>'slide',
            ]
        );

        /*$this->add_control(
            'direction',
            [
                'label' => __('Direction', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __('Horizontal', 'ae-pro'),
                    'vertical' => __('Vertical', 'ae-pro')
                ],
                'default'=>'horizontal',
            ]
        );*/

        $this->add_responsive_control(
            'slide_per_view',
            [
                'label' => __( 'Slides Per View', 'ae-pro' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'effect',
                            'operator' => '==',
                            'value' => 'slide',
                        ], [
                            'name' => 'effect',
                            'operator' => '==',
                            'value' => 'coverflow',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_per_group',
            [
                'label' => __( 'Slides Per Group', 'ae-pro' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );

        $this->add_control(
            'carousel_settings_heading',
            [
                'label' => __('Setting', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5000,
                ],
                'description' => __('Duration of transition between slides (in ms)', 'ae-pro'),
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1000
                    ]
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
            ]

        );

        $this->add_control(
            'duration',
            [
                'label' => __('Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 900,
                ],
                'description' => __('Delay between transitions (in ms)', 'ae-pro'),
                'range' => [
                    'px' =>[
                        'min' => 300,
                        'max' => 3000,
                        'step' => 300,
                    ]
                ],
                'condition' => [
                    'autoplay' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Space Between Slides', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>[
                    'size' => 15,
                ],
                'tablet_default' =>[
                    'size' => 10,
                ],
                'mobile_default' =>[
                    'size' => 5,
                ],
                'range' => [
                    'px'=>[
                        'min'=> 0,
                        'max'=> 50,
                        'step'=> 5,
                    ]
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

	    $this->add_control(
		    'auto_height',
		    [
			    'label' => __('Auto Height', 'ae-pro'),
			    'type' => Controls_Manager::SWITCHER,
			    'default' => '',
			    'label_on' => __('Yes', 'ae-pro'),
			    'label_off' => __('No', 'ae-pro'),
			    'return_value' => 'yes',
			    'condition' => [
				    'layout_mode' => 'carousel'
			    ]
		    ]
	    );

        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Pagination', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );



        $this -> add_control(
            'ptype',
            [
                'label' => __(' Pagination Type' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        ''        => __('None', 'ae-pro'),
                        'bullets' => __( 'Bullets' , 'ae-pro'),
                        'fraction' =>__( 'Fraction' , 'ae-pro'),
                        'progress' =>__('Progress' , 'ae-pro'),
                    ],
                'default'=>'bullets'
            ]
        );

        $this->add_control(
            'clickable',
            [
                'label' =>__('Clickable' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'condition'=> [
                    'ptype' => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'navigation_button',
            [
                'label' => __('Previous/Next Button' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes' , 'ae-pro'),
                'label_off' => __('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => __('Keyboard Control' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' =>__('Scroll bar', 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=>'yes',
                'label_on' =>__('Yes' , 'ae-pro'),
                'label_off'=>__('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

    }

    function layout_styles(){
        $this->start_controls_section(
            'layout_style',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'label' => __( 'Item Background', 'ae-pro' ),
                'types' => [ 'none','classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-acf-repeater-inner',
                'default' => '#fff'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-acf-repeater-inner',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-acf-repeater-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-acf-repeater-inner',
            ]
        );

        $this->end_controls_section();
    }

    function carousel_styles(){
        $this->start_controls_section(
            'carousel_style',
            [
                'label' => __( 'Carousel', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_mode' => 'carousel',
                ]
            ]
        );

        $this->add_control(
            'heading_style_arrow',
            [
                'label' => __('Arrow', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );
        $this->start_controls_tabs( 'tabs_arrow_styles' );

        $this->start_controls_tab(
            'tab_arrow_normal',
            [
                'label' => __( 'Normal', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'color:{{VAlUE}};'
                ],
                'default' => '#444',
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __(' Background Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'background-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev, {{WRAPPER}} .ae-swiper-container .ae-swiper-button-next',
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrow_hover',
            [
                'label' => __( 'Hover', 'ae-pro' ),
            ]
        );
        $this->add_control(
            'arrow_color_hover',
            [
                'label' => __('Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover i' => 'color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover i' => 'color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_bg_color_hover',
            [
                'label' => __(' Background Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover' => 'background-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_color_hover',
            [
                'label' => __(' Border Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover' => 'border-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover' => 'border-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_radius_hover',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 25
                    ],
                'range' =>
                    [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'font-size:{{SIZE}}px;',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'font-size:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );


        $this->add_responsive_control(
            'arrow_horizontal_position',
            [
                'label' => __( 'Horizontal Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                /*'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-wrapper' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; height: 100%; top: 0; width: 100px; left: 0;',
                    'center' => '',
                    'right' => '',
                ],*/
                'default' => 'center',
                'condition' => [
                    'navigation_button' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'arrow_vertical_position',
            [
                'label' => __( 'Vertical Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'ae-pro' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'ae-pro' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'ae-pro' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                /*'selectors_dictionary' => [
                    'top' => 'top: 0; bottom: auto; transform: unset;',
                    'middle' => 'translate(-50%);',
                    'bottom' => 'top: auto; bottom: 0; transform: unset;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => '{{VALUE}}',
                    '{{WRAPPER}} .ae-swiper-button-next' => '{{VALUE}}',

                ],*/
                'default' => 'center',
                'condition' => [
                    'navigation_button' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'horizontal_arrow_offset',
            [
                'label' => __('Horizontal Offset', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-hpos-left .ae-swiper-button-wrapper' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-right .ae-swiper-button-wrapper' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-center .ae-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-center .ae-swiper-button-next' => 'right: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'navigation_button' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'vertical_arrow_offset',
            [
                'label' => __('Vertical Offset', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-vpos-top .ae-swiper-button-wrapper' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-bottom .ae-swiper-button-wrapper' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-middle .ae-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-middle .ae-swiper-button-next' => 'top: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'navigation_button' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_style_dots',
            [
                'label' => __('Dots', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width:{{SIZE}}px; height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Active Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color:{{VAlUE}} !important;',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'inactive_dots_color',
            [
                'label' => __('Inactive Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_scroll',
            [
                'label' => __('Scrollbar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'scroll_size',
            [
                'label' => __('Scrollbar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-vertical .ae-swiper-scrollbar' => 'width:{{SIZE}}px;',
                    '{{WRAPPER}} .swiper-container-horizontal .ae-swiper-scrollbar' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => __('Scrollbar Drag Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scroll_color',
            [
                'label' => __('Scrollbar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_progress',
            [
                'label' => __('Progress Bar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );
        $this->add_control(
            'progressbar_color',
            [
                'label' => __('Prgress Bar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progress_color',
            [
                'label' => __('Prgress Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );



        $this->add_control(
            'progressbar_size',
            [
                'label' => __('Prgress Bar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progress' => 'height:{{SIZE}}px;',
                    '{{WRAPPER}} .swiper-container-vertical .swiper-pagination-progress' => 'width:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->end_controls_section();
    }
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_ACF_Repeater() );