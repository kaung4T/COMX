<?php
namespace Aepro;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Aepro\Aepro_Control_Manager;
class FeaturedBG{
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('elementor/element/before_section_start',[ $this, 'add_fields'],10,3);
        add_action('elementor/frontend/element/before_render',[ $this, 'before_section_render'],10,1);

        add_action('elementor/frontend/section/before_render',[ $this, 'before_section_render'],10,1);
        add_action('elementor/frontend/column/before_render',[ $this, 'before_section_render'],10,1);

    }

    public function add_fields($element,$section_id, $args){
        $helper = new Helper();
        if ( ('section' === $element->get_name() && 'section_background' === $section_id) || ('column' === $element->get_name() && 'section_style' === $section_id)) {

            $element->start_controls_section(
                'post_featured_bg',
                [
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO,
                    'label' => __( 'Dynamic Background', 'ae-pro' ),
                ]
            );

            $element->add_control(
                'show_featured_bg',
                [
                    'label' => __( 'Show Dynamic Background Image', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Show', 'ae-pro' ),
                    'label_off' => __( 'Hide', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-featured-bg-'
                ]
            );
            $ae_featured_bg_source['post'] = __('Post','ae-pro');
            if(class_exists('ACF') || class_exists('acf')){
                $ae_featured_bg_source['custom_field'] = __('Post Custom Field','ae-pro');
                $ae_featured_bg_source['term_custom_field'] = __('Term Custom Field', 'ae-pro');
            }

            $element->add_control(
                'ae_featured_bg_source',
                [
                    'label'         => __('Source','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $ae_featured_bg_source,
                    'default'       => 'post',
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                    ],
                    'prefix_class'  => 'ae-featured-bg-source-',
                    'condition'     => [
                        'show_featured_bg' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_cf_field_key',
                [
                    'label' => __( 'Custom Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Custom Field Key', 'ae-pro' ),
                    'default' => '',
                    'prefix_class' => 'ae-feature-bg-custom-field-',
                    'condition' => [
                        'ae_featured_bg_source!' => 'post',
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );


            $element->add_control(
                'ae_featured_image_size',
                [
                    'label'         => __('Image Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $helper->ae_get_intermediate_image_sizes(),
                    'default'       => 'large',
                    'prefix_class'  => 'ae-featured-img-size-',
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_size',
                [
                    'label'         => __('Background Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => array(
                        'auto'   => __('Auto','ae-pro'),
                        'cover'   => __('Cover','ae-pro'),
                        'contain'   => __('Contain','ae-pro')
                    ),
                    'default'       => 'cover',
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_position',
                [
                    'label'         => __('Background Position','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'top left' => __( 'Top Left', 'ae-pro' ),
                        'top center' => __( 'Top Center', 'ae-pro' ),
                        'top right' => __( 'Top Right', 'ae-pro' ),
                        'center left' => __( 'Center Left', 'ae-pro' ),
                        'center center' => __( 'Center Center', 'ae-pro' ),
                        'center right' => __( 'Center Right', 'ae-pro' ),
                        'bottom left' => __( 'Bottom Left', 'ae-pro' ),
                        'bottom center' => __( 'Bottom Center', 'ae-pro' ),
                        'bottom right' => __( 'Bottom Right', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-position: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_attachment',
                [
                    'label'         => __('Background Attachment','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'scroll' => __( 'Scroll', 'ae-pro' ),
                        'fixed' => __( 'Fixed', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-attachment: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_repeatae_featured_bg_repeat',
                [
                    'label'         => __('Background Repeat','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'no-repeat' => __( 'No-repeat', 'ae-pro' ),
                        'repeat' => __( 'Repeat', 'ae-pro' ),
                        'repeat-x' => __( 'Repeat-x', 'ae-pro' ),
                        'repeat-y' => __( 'Repeat-y', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-repeat: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_section_column_background_alert',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'ae_pro_alert',
                    'raw' => __( $helper->get_widget_admin_note_html( "Know more about Section/Column Backgrounds", "https://wpvibes.link/go/dynamic-section-column-backgrounds" ) , 'ae-pro' ),
                    'separator' => 'none',
                ]
            );

            $element->end_controls_section();

            $element->start_controls_section(
                'dynamic_link_section',
                [
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO,
                    'label' => __( 'Dynamic Link', 'ae-pro' ),
                ]
            );


            $element->add_control(
                'enable_link',
                [
                    'label' => __( 'Enable Link', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-link-',
                ]
            );


            $element->add_control(
                'dynamic_link_source',
                [
                    'label'         => __('Links To','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'post'              => __('Post', 'ae-pro'),
                        'custom_field_url'  => __('Custom Field (URL)', 'ae-pro'),
                        'static_url'        => __('Static Url', 'ae-pro')
                    ],
                    'default'       => 'post',
                    'condition'     => [
                        'enable_link' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'dynamic_link_custom_field',
                [
                    'label' => __( 'Custom Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Custom Field Key', 'ae-pro' ),
                    'default' => '',
                    'condition' => [
                        'dynamic_link_source' => 'custom_field_url',
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'static_url',
                [
                    'label'         => __('Url', 'ae-pro'),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __('https://example.com', 'ae-pro'),
                    'default'       => '',
                    'condition'     => [
                        'dynamic_link_source' => 'static_url',
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'enable_open_in_new_window',
                [
                    'label' => __( 'Enable Open In New Window', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-new-window-',
                    'condition'     => [
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_section_column_clickable_alert',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'ae_pro_alert',
                    'raw' => __( $helper->get_widget_admin_note_html( "Know more about Section/Column Clickable", "https://wpvibes.link/go/section-column-clickable" ) , 'ae-pro' ),
                    'separator' => 'none',
                ]
            );

            $element->end_controls_section();


            /**
            $element->start_controls_section(
                'ae_box_click',
                [
                    'label' => __('Box Click','ae-pro'),
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO
                ]
            );

            $element->add_control(
                '(',
                [
                    'label' => __('Link', 'ae-pro'),
                    'type'  => Controls_Manager::URL
                ]
            );

            $element->end_controls_section();
            **/
        }
    }

    function before_section_render($element){



        if($element->get_settings( 'show_featured_bg' ) == 'yes') {

            $img_size = $element->get_settings('ae_featured_image_size');
            $img_source = $element->get_settings('ae_featured_bg_source');
            $helper = new Helper();

            switch ($img_source) {
                case 'post'         :
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size);
                    $image = $img[0];

                    break;

                case 'custom_field' :
                    if (!class_exists('ACF') && !class_exists('acf')) {
                        $image = '';
                        break;
                    }
                    $img = wp_get_attachment_image_src(get_field($element->get_settings('ae_featured_bg_cf_field_key'), get_the_ID()), $img_size);


	                $repeater = $helper->is_repeater_block_layout();
	                if($repeater['is_repeater']){
		                if(isset($repeater['field'])){
			                $repeater_field = get_field($repeater['field'], get_the_ID());
			                $img = wp_get_attachment_image_src($repeater_field[0][$element->get_settings('ae_featured_bg_cf_field_key')], $img_size);

		                }else {
			                $img = wp_get_attachment_image_src(get_sub_field($element->get_settings('ae_featured_bg_cf_field_key')), $img_size);
		                }
	                }
	                $image = $img[0];

                    break;

                case 'term_custom_field' :
                    if (!class_exists('ACF') && !class_exists('acf')) {
                        $image = '';
                        break;
                    }

                    if (Plugin::instance()->editor->is_edit_mode()) {
                        $term = $helper->get_preview_term_data();
                    } else {
                        $term = get_queried_object();
                    }

                    $img = wp_get_attachment_image_src(get_field($element->get_settings('ae_featured_bg_cf_field_key'), $term), $img_size);
                    $image = $img[0];
                    break;

                default                 :
                    $image = '';
            }


            $element->add_render_attribute('_wrapper', [
                'data-ae-bg' => $image,
            ]);
        }


        if( $element->get_settings('enable_link') == 'yes'){
            $link_source = $element->get_settings('dynamic_link_source');
            $helper = new Helper();

            switch ($link_source){
                case 'post'                 :       $bg_link = get_permalink();
                                                    break;
                case 'custom_field_url'     :       $bg_link = get_post_meta(get_the_id(), $element->get_settings('dynamic_link_custom_field'), true);
									                $repeater = $helper->is_repeater_block_layout();
									                if($repeater['is_repeater']){
										                if(isset($repeater['field'])){
											                $repeater_field = get_field($repeater['field'], get_the_ID());
											                $bg_link = $repeater_field[0][$element->get_settings('dynamic_link_custom_field')];

										                }else {
											                $bg_link = get_sub_field($element->get_settings('dynamic_link_custom_field'));
										                }
									                }
                                                    break;
                case 'static_url'           :       $bg_link = $element->get_settings('static_url');
                                                    break;
                default                     :       $bg_link = '';
            }

            $element->add_render_attribute( '_wrapper', [
                'data-ae-url' => $bg_link,
            ] );

        }

    }


}

FeaturedBG::instance();