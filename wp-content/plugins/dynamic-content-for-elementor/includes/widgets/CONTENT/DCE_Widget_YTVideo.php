<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

use DynamicContentForElementor\DCE_Helper;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils as Utils;
use Elementor\Embed as Embed;
use Elementor\Group_Control_Image_Size as Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter as Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow as Group_Control_Text_Shadow;
use Elementor\Widget_Video as Widget_Video;


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor YT Video BETA
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_YTVideo extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-ytvideo';
    }
    
    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Youtube', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-idea';
    }
    public function get_script_depends() {
        return [ 'dce-youtube' ];
    }
    
    protected function _register_controls() {
        
        /*****************************ELEMENTOR********************************/
        $this->start_controls_section(
			'dce_section_video',
			[
				'label' => __( 'Video', 'elementor' ),
			]
		);
                
        $this->add_control(                        
            'video_type',
			[
				'label' => __( 'Video type', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'youtube',
			]
		);
        $this->add_control(                        
            'youtube_url',
			[
				'label' => __( 'Link', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __( 'Enter your URL', 'elementor' ) . ' (YouTube)',
				'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block' => true,
			]
		);

		$this->add_control(
			'start',
			[
				'label' => __( 'Start Time', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Specify a start time (in seconds)', 'elementor' ),
				'condition' => [
					'loop' => '',
				],
			]
		);

		$this->add_control(
			'end',
			[
				'label' => __( 'End Time', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Specify an end time (in seconds)', 'elementor' ),
				'condition' => [
					'loop' => '',
				],
			]
		);

		$this->add_control(
			'video_options',
			[
				'label' => __( 'Video Options', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
                                'frontend_available' => true,
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => __( 'Mute', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
                                'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'controls',
			[
				'label' => __( 'Player Controls', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'modestbranding',
			[
				'label' => __( 'Modest Branding', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'controls' => 'yes',
				],
			]
		);

		// YouTube.
		$this->add_control(
			'yt_privacy',
			[
				'label' => __( 'Privacy Mode', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'elementor' ),
			]
		);

		$this->add_control(
			'rel',
			[
				'label' => __( 'Suggested Videos', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Current Video Channel', 'elementor' ),
					'yes' => __( 'Any Video', 'elementor' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dce_section_image_overlay',
			[
				'label' => __( 'Image Overlay', 'elementor' ),
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label' => __( 'Image Overlay', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label' => __( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'lazy_load',
			[
				'label' => __( 'Lazy Load', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_overlay', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_play_icon',
			[
				'label' => __( 'Play Icon', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);
                
		$this->end_controls_section();

		$this->start_controls_section(
			'dce_section_video_style',
			[
				'label' => __( 'Video', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => __( 'Aspect Ratio', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
				],
				'default' => '169',
				'prefix_class' => 'elementor-aspect-ratio-',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-wrapper',
			]
		);

		$this->add_control(
			'play_icon_title',
			[
				'label' => __( 'Play Icon', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'play_icon_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Shadow', 'Text Shadow Control', 'elementor' ),
					],
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);
                
                $this->end_controls_section();

		
        /**********************************************************************/
        $this->start_controls_section(
            'dce_section_dynamic_video', [
                'label' => __('Dynamic', 'dynamic-content-for-elementor'),
            ]
        );
        
        /*$this->add_control(
            'fullscreen', [
                'label' => __('Fullscreen', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => '',
                'description' => __('Force video size to fullscreen', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'selectors' => [
                    'iframe#dce-ytplayer-{{ID}}' => 'width: 100%;',
                    '#dce-ytplayer-{{ID}} iframe' => 'width: 100%;'
                    
                ],
            ]
        );*/
        
        $this->add_control(
            'fullwidth', [
                'label' => __('FullWidth', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('Force video size to widescreen', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'selectors' => [
                    'iframe#dce-ytplayer-{{ID}}' => 'width: 100%; display: block;',
                    '#dce-ytplayer-{{ID}} iframe' => 'width: 100%; display: block;'
                    
                ],
                /*'condition' => [
                    'fullscreen' => ''
                ]*/
            ]
        );
        $this->add_control(
            'base_width', [
                'label' => __('Default Width', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '640',
                'frontend_available' => true,
                'selectors' => [
                    'iframe#dce-ytplayer-{{ID}}' => 'width: {{VALUE}}px;',
                    '#dce-ytplayer-{{ID}} iframe' => 'width: {{VALUE}}px;'
                ],
                'condition' => [
                    //'fullscreen' => '',
                    'fullwidth' => '',
                ]
                
            ]
        );
        $this->add_control(
            'proportion', [
                'label' => __('Maintain proportions', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                /*'condition' => [
                    'fullscreen' => ''
                ]*/
            ]
        );
        $this->add_control(
            'base_height', [
                'label' => __('Default Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '320',
                'frontend_available' => true,
                'selectors' => [
                    'iframe#dce-ytplayer-{{ID}}' => 'height: {{VALUE}}px;',
                    '#dce-ytplayer-{{ID}} iframe' => 'height: {{VALUE}}px;'
                ],
                'condition' => [
                    'proportion' => '',
                    //'fullscreen' => ''
                ]
            ]
        );

        // https://developers.google.com/youtube/iframe_api_reference
        $this->add_control(
            'player_api', [
                'label' => __('Use Player API', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
            ]
        );
        
        
        $this->add_control(
            'pause_on_scroll', [
                'label' => __('Pause On scroll', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                'description' => __('Automatically pause video when user scroll beyond the video, he probably does not care about the video', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'condition' => [
                    'disable_native_interaction!' => '',
                    'player_api!' => '',
                ]
            ]
        );
        /*$this->add_control(
            'custom_mute', [
                'label' => __('Mute', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                'description' => __('Video will start mute', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'condition' => [
                    'player_api!' => '',
                ]
            ]
        );*/
        $this->add_control(
            'showinfo', [
                'label' => __('Show info', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'elementor' ),
                'label_on' => __( 'Show', 'elementor' ),
                'default' => '',
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'condition' => [
                    'player_api!' => '',
                ]
            ]
        );
        $this->add_control(
            'disable_native_interaction', [
                'label' => __('Disable native interaction', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'selectors' => [
                    '.elementor-element-{{ID}} .elementor-widget-container' => 'position: relative;',
                    '.elementor-element-{{ID}} .elementor-widget-container .dce-youtube-overlay' => 'top: 0; left: 0; position: absolute; width: 100%; height: 100%; background-color: white; opacity: 0;',
                ],
                'condition' => [
                    'controls' => '',
                    'player_api!' => '',
                ]
            ]
        );
        $this->add_control(
            'custom_play_pause', [
                'label' => __('Play/Pause on Click', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'condition' => [
                    'controls' => '',
                    'disable_native_interaction!' => '',
                    'player_api!' => '',
                ]
            ]
        );
        $this->add_control(
            'custom_mute_toggle', [
                'label' => __('Button Mute Toggle', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'condition' => [
                    'controls' => '',
                    'disable_native_interaction!' => '',
                    'player_api!' => '',
                ]
            ]
        );
        $this->add_control(
            'custom_mute_position', [
                'label' => __('Button Mute Position', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top_left' => __('Top Left', 'dynamic-content-for-elementor'),
                    'top_right' => __('Top Right', 'dynamic-content-for-elementor'),
                    'bottom_left' => __('Bottom Left', 'dynamic-content-for-elementor'),
                    'bottom_right' => __('Bottom Right', 'dynamic-content-for-elementor'),
                ],
                'default' => 'bottom_right',
                //'description' => __('Maintain orginal video proportions', 'dynamic-content-for-elementor'),
                'frontend_available' => true,
                'condition' => [
                    'controls' => '',
                    'disable_native_interaction!' => '',
                    'player_api!' => '',
                    'custom_mute_toggle!' => '',
                ]
            ]
        );
        
        
        
        $this->end_controls_section();
    }
    
    protected function render() {
		$settings = $this->get_settings_for_display();
                //var_dump($settings);
                //$elementorVideo = new Widget_Video(); // used to use native methods

		$video_url = $settings[ 'youtube_url' ];
		if ( empty( $video_url ) ) {
			return;
		}
                
                $is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

                $embed_params = $this->get_embed_params();

                $embed_options = $this->get_embed_options();

                $video_html = Embed::get_embed_html( $video_url, $embed_params, $embed_options );
		
		if ( empty( $video_html ) ) {
			echo esc_url( $video_url );
			return;
		}

		//$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-wrapper' );
                //$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-fit-aspect-ratio' );
                if ($settings['proportion'] && (!$settings['player_api'] || $is_edit_mode)) {
                    //$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-fit-aspect-ratio' );
                    $this->add_render_attribute( 'video-wrapper', 'class', 'elementor-fit-aspect-ratio' );
                }
                

		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-open-inline' );
                $video_properties = Embed::get_video_properties( $settings['youtube_url'] );
                $params['playlist'] = $video_id = $video_properties['video_id'];
                
                $base_width = $settings['base_width'] ? $settings['base_width'] : 1280 ;
                $base_height = $settings['base_height'] ? $settings['base_height'] : self::get_proportional_height($base_width) ;
                
		?>
		<div id="dce-ytplayer-<?php echo $this->get_id(); ?>" <?php echo $this->get_render_attribute_string( 'video-wrapper' ); ?>>
			<?php
                        if (!$settings['player_api'] || $is_edit_mode) {
                            echo $video_html; // XSS ok.
                        }

			if ( $this->has_image_overlay() ) {
				$this->add_render_attribute( 'image-overlay', 'class', 'elementor-custom-embed-image-overlay' );
                                $this->add_render_attribute( 'image-overlay', 'style', 'background-image: url(' . Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings ) . ');' );

				?>
				<div <?php echo $this->get_render_attribute_string( 'image-overlay' ); ?>>
                                    <?php if ( 'yes' === $settings['show_play_icon'] ) : ?>
                                        <div class="elementor-custom-embed-play" role="button">
                                                <i class="eicon-play" aria-hidden="true"></i>
                                                <span class="elementor-screen-only"><?php echo __( 'Play Video', 'elementor' ); ?></span>
                                        </div>
                                    <?php endif; ?>
				</div>
			<?php } ?>
		</div>
		<?php
                if ($settings['player_api'] && !$is_edit_mode) {
                    
                    if ($settings['disable_native_interaction']) { ?>
                        <div class="dce-youtube-overlay"></div>
                    <?php }
                    if ($settings['custom_play_pause']) { ?>
                        <div class="dce-youtube-play"><span class="screen-reader-text">PLAY/PAUSE</span></div>
                    <?php }
                    if ($settings['custom_mute_toggle']) { ?>
                        <div class="dce-youtube-mute dce-youtube-mute-position-<?php echo $settings['custom_mute_position']; ?>"><span class="screen-reader-text">MUTE</span></div>
                    <?php } ?>
                        
                        
                    <script>
                    // Load the IFrame Player API code asynchronously.
                    var tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/player_api";
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                    // Replace the 'ytplayer' element with an <iframe> and
                    // YouTube player after the API code downloads.
                    var dce_player_<?php echo $this->get_id(); ?>;
                    function onYouTubePlayerAPIReady() {
                        dce_player_<?php echo $this->get_id(); ?> = new YT.Player('dce-ytplayer-<?php echo $this->get_id(); ?>', {
                            //host: 'https://www.youtube.com',
                            height: '<?php echo $base_height; ?>',
                            width: '<?php echo $base_width; ?>',
                            videoId: '<?php echo $video_id; ?>',
                            playerVars: { 
                                /*color: 'transparent',*/
                                showinfo: <?php echo $settings['showinfo'] ? '1' : '0'; ?>, 
                                rel: 0, 
                                ecver: 2,
                                loop: <?php echo $settings['loop'] ? '1' : '0'; ?>, 
                                autoplay: <?php echo $settings['autoplay'] ? '1' : '0'; ?>, 
                                controls: <?php echo $settings['controls'] ? '1' : '0'; ?>, 
                                playlist: '<?php echo $video_id; ?>', 
                                modestbranding: <?php echo $settings['modestbranding'] ? '1' : '0'; ?>,
                                mute: <?php echo $settings['mute'] ? '1' : '0'; ?>,
                                //origin: window.location.origin,
                            }, //hd: 1, title: ''
                            events: {
                                'onReady': onPlayerReady
                            }
                        });
                    }
                  </script>
                  
                  <style>
                        .dce-youtube-play {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            opacity: 0;
                            /*background-color: rgba(0,0,0,0.5);*/
                            background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(0,0,0,1) 10%,rgba(255,255,255,0) 40%,rgba(0,0,0,1) 80%,rgba(0,0,0,1) 100%);
                            transition: opacity 0.5s;
                            cursor: pointer;
                        }
                        .dce-youtube-play.paused {
                            opacity: 1;
                        }
                        .dce-youtube-play.paused::after {
                          content: '| |';
                          font-family: 'FontAwesome';
                          content: "\f04b";
                          position: absolute;
                          width: 100%;
                          text-align: center;
                          top: 50%;
                          font-size: 200px;
                          font-weight: bold;
                          color: white;
                          margin-top: -100px;
                          cursor: pointer;
                        }
                        
                        .dce-youtube-mute {
                            position: absolute;
                            display: block;
                            border: 1px solid black;
                            border-radius: 50%;
                            background-color: white;
                            opacity: 0.5;
                            margin: 20px;
                            /*text-indent: -100px;*/
                            text-align: center;
                            line-height: 40px;
                            overflow: hidden;
                            width: 40px;
                            height: 40px;
                            cursor: pointer;
                        }
                        .dce-youtube-mute.dce-youtube-mute-position-top_left {
                            top: 0;
                            left: 0;
                        }
                        .dce-youtube-mute.dce-youtube-mute-position-top_right {
                            top: 0;
                            right: 0;
                        }
                        .dce-youtube-mute.dce-youtube-mute-position-bottom_left {
                            bottom: 0;
                            left: 0;
                        }
                        .dce-youtube-mute.dce-youtube-mute-position-bottom_right {
                            bottom: 0;
                            right: 0;
                        }
                        .dce-youtube-mute:after {
                            font-family: FontAwesome;
                            content: "\f028";
                            display: block;
                        }
                        .dce-youtube-mute:hover {
                            opacity: 1;
                        }
                        .dce-youtube-mute.mute:after {
                            box-shadow: 0 0 2px whitesmoke;
                            opacity: 1;
                        }
                        .dce-youtube-mute.mute:after {
                            content: "\f026";
                        }
                    </style>
                <?php
                }
                
	}
        
        static public function get_proportional_height($width) {
            
            if ($width) {
                // 16:9
                return round(($width / 16) * 9);
            }
            
            return 720;
        }
        
        /**********************************************************************/
        
        /**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params() {
		$settings = $this->get_settings_for_display();

		$params = [];

		if ( $settings['autoplay'] && ! $this->has_image_overlay() ) {
			$params['autoplay'] = '1';
		}

		$params_dictionary = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'controls',
				'mute',
				'rel',
				'modestbranding',
			];

			if ( $settings['loop'] ) {
				$video_properties = Embed::get_video_properties( $settings['youtube_url'] );

				$params['playlist'] = $video_properties['video_id'];
			}

			$params['start'] = $settings['start'];

			$params['end'] = $settings['end'];

			$params['wmode'] = 'opaque';
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'mute' => 'muted',
				'vimeo_title' => 'title',
				'vimeo_portrait' => 'portrait',
				'vimeo_byline' => 'byline',
			];

			$params['color'] = str_replace( '#', '', $settings['color'] );

			$params['autopause'] = '0';
		} elseif ( 'dailymotion' === $settings['video_type'] ) {
			$params_dictionary = [
				'controls',
				'mute',
				'showinfo' => 'ui-start-screen-info',
				'logo' => 'ui-logo',
			];

			$params['ui-highlight'] = str_replace( '#', '', $settings['color'] );

			$params['start'] = $settings['start'];

			$params['endscreen-enable'] = '0';
		}

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			$setting_value = $settings[ $setting_name ] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
	}

	/**
	 * Whether the video widget has an overlay image or not.
	 *
	 * Used to determine whether an overlay image was set for the video.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return bool Whether an image overlay was set for the video.
	 */
	protected function has_image_overlay() {
		$settings = $this->get_settings_for_display();

		return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
	}

	/**
	 * @since 2.1.0
	 * @access private
	 */
	private function get_embed_options() {
		$settings = $this->get_settings_for_display();

		$embed_options = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$embed_options['privacy'] = $settings['yt_privacy'];
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$embed_options['start'] = $settings['start'];
		}

		$embed_options['lazy_load'] = ! empty( $settings['lazy_load'] );

		return $embed_options;
	}

}
