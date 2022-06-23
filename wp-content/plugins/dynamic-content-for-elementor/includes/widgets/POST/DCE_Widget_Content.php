<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


/**
 * Dynamic Content Content
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Content extends DCE_Widget_Prototype {
    
    static $remove_recursion_loop = [];

    public function get_name() {
        return 'dyncontel-content';
    }
    static public function is_enabled() {
        return true;
    }
    public function get_title() {
        return __('Content', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Put a content of an article', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/content/';
    }
    public function get_icon() {
        return 'icon-dyn-content';
    }
    static public function get_position() {
        return 2;
    }
    public function get_script_depends() {
        return ['imagesloaded'];
    }
    public function get_dce_script_depends() {
        return ['dce-content'];
    }
    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_content', [
                'label' => __('Content', 'dynamic-content-for-elementor'),
            ]
        );
         $this->add_control(
            'use_filters_content', [
                'label' => __('Use the content-filters', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'use_content_limit', [
                'label' => __('Use the content limit', 'dynamic-content-for-elementor'),
                'description' => __('This option strip all tags', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                 'condition' => [
                    'use_filters_content' => ''
                ]
            ]
        );
        $this->add_control(
            'use_content_autop', [
                'label' => __('Use the content auto-p.', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                /*'condition' => [
                    'use_filters_content' => ''
                ]*/
            ]
        );
        $this->add_control(
            'count_content_limit', [
                'label' => __('Number of characters', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '15',
                'condition' => [
                    'use_content_limit' => 'yes',
                    'use_filters_content' => ''
                ]
            ]
        );
       
        $this->add_control(
            'html_tag', [
                'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __('H1', 'dynamic-content-for-elementor'),
                    'h2' => __('H2', 'dynamic-content-for-elementor'),
                    'h3' => __('H3', 'dynamic-content-for-elementor'),
                    'h4' => __('H4', 'dynamic-content-for-elementor'),
                    'h5' => __('H5', 'dynamic-content-for-elementor'),
                    'h6' => __('H6', 'dynamic-content-for-elementor'),
                    'p' => __('p', 'dynamic-content-for-elementor'),
                    'div' => __('div', 'dynamic-content-for-elementor'),
                    'span' => __('span', 'dynamic-content-for-elementor'),
                ],
                'default' => 'div',
            ]
        );
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
        $this->add_control(
            'link_to', [
                'label' => __('Link to', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'dynamic-content-for-elementor'),
                    'home' => __('Home URL', 'dynamic-content-for-elementor'),
                    'post' => __('Post URL', 'dynamic-content-for-elementor'),
                    'custom' => __('Custom URL', 'dynamic-content-for-elementor'),
                ],
            ]
        );
        $this->add_control(
            'link', [
                'label' => __('Link', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
            ]
        );

        $this->add_control(
                'no_shortcode',
                [
                    'label' => __('Remove Shortcode', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __( 'Remove all Visual Composer & Shortcodes', 'dynamic-content-for-elementor' ),
                ]
            );


        $this->add_control(
            'enable_unfold',
            [
              'label' => __( 'Enable Unfold', 'dynamic-content-for-elementor' ),
              'description' => __( 'Useful when you want to limit the display of the content', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'frontend_available' => true,
              'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'height_content', [
                'label' => __('Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'default' => [
                    'size' => 280,
                ],
                'range' => [
                    'px' => [
                        'max' => 600,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-content.unfolded' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'render_type' => 'template',
                'condition' => [
                    'enable_unfold' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        // ------------------------------------------------ STYLE
        $this->start_controls_section(
            'section_style', [
                'label' => __('Content', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-content, {{WRAPPER}} .dce-content a.dce-content-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dce-content',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .dce-content',
            ]
        );
        $this->add_responsive_control(
            'space', [
                'label' => __('Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-content' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
                
            ]
        );
        $this->add_control(
            'rollhover_heading',
            [
                'label' => __( 'Roll-Hover', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'hover_color', [
                'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-content:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
       
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_unfold', [
                'label' => __('Unfold', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_unfold' => 'yes',
                  ], 
            ]
        );

        $this->add_control(
            'unfold_color', [
                'label' => __('Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .unfold-btn a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'unfold_color_hover', [
                'label' => __('Rollover Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .unfold-btn a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'unfold_size', [
                'label' => __('Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'default' => [
                    'size' => 50,
                ],
                'range' => [
                    'px' => [
                        'max' => 600,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .unfold-btn a' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_unfold' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'unfold_space', [
                'label' => __('Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .unfold-btn' => 'margin-top: {{SIZE}}{{UNIT}};'
                ],
                
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------ SETTINGS 
        $this->start_controls_section(
            'section_dce_settings', [
                'label' => __('Dynamic Content', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );
         $this->add_control(
            'data_source',
            [
              'label' => __( 'Source', 'dynamic-content-for-elementor' ),
              'description' => __( 'Select the data source', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Same', 'dynamic-content-for-elementor' ),
              'label_off' => __( 'other', 'dynamic-content-for-elementor' ),
              'return_value' => 'yes',
            ]
        );
        /*$this->add_control(
            'other_post_source', [
              'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT,
              
              'groups' => DCE_Helper::get_all_posts(get_the_ID(),true),
              'label_block' => true,
              'default' => '',
              'condition' => [
                'data_source' => '',
              ], 
            ]
        );*/
        $this->add_control(
                'other_post_source',
                [
                    'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
                    'type' 		=> 'ooo_query',
                    'placeholder'	=> __( 'Post Title', 'dynamic-content-for-elementor' ),
                    'label_block' 	=> true,
                    'query_type'	=> 'posts',
                    'condition' => [
                        'data_source' => '',
                    ],
                ]
        );
        /*$this->add_control(
          'go_to_page',
          [
             'type'    => Controls_Manager::RAW_HTML,
             'raw' => '<a target="_blank" class="dce-go-to-page-template dce-btn" href="#">
                <i class="fa fa-pencil"></i>'. __( 'Edit Page', 'dynamic-content-for-elementor' ).'</a>',
             'content_classes' => 'dce-btn-go-page',
             'separator' => 'after',
             //'render_type' => 'template',
             'condition' => [
                    'other_post_source!' => '',
                ],
          ]
        );*/
        /*$this->add_control(
            'mod_page',
            [
                'type' => Controls_Manager::BUTTON,
                'label' => __( 'Modify', 'dynamic-content-for-elementor' ),
                'label_block' => true,
                'show_label' => false,
                'text' => __( 'View page', 'dynamic-content-for-elementor' ),
                'separator' => 'none',
                'event' => 'dceMain:previewPage',
                'condition' => [
                    'other_post_source!' => 0,
                    'data_source' => '',
                ],
            ]
        );*/
        $this->end_controls_section();
    }
    /*private function dce_other_content_template() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return '';

        return $settings['other_post_source'];
    }*/
    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';
        //
        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        if( $settings['data_source'] == 'yes' ){
            //
            if(!empty($demoPage)){
                $id_page = $demoPage;
                $type_page = get_post_type($demoPage);
                //echo 'A - Demo';
                //echo 'DEMO ...';
            } 
            else if (!empty($global_ID)) {
                $id_page = $global_ID;
                $type_page = get_post_type($id_page);
                //echo 'B - Global';
                //echo 'global ...';
            }else {
                $id_page = get_the_id();
                $type_page = get_post_type();
                //echo 'C - Naturale';
                //echo 'natural ...';
            }
        }else{
            //
            $original_global_ID = $global_ID;
            //
            //echo 'D - Other';
            //$id_page = $settings['other_post_source'];
            $id_page = apply_filters( 'wpml_object_id', $settings['other_post_source'], get_post_type($settings['other_post_source']), true );
            $type_page = get_post_type($id_page);
            //
            $global_ID = $id_page;

        }
        // ------------------------------------------
        //
        //echo '<br>------------------ ['.$id_page.'] ------------------';
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - // remove recursion
        //if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === true ){
            
            //echo $id_page.' ...<br>';
            
            
            if ( isset( $remove_recursion_loop[ $id_page ] ) ) {
                return;
            }

            $remove_recursion_loop[ $id_page ] = true;
            //var_dump($remove_recursion_loop);
        //}
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';
        $animation_class = '';//!empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        //
        
        //
        /*echo 'aa '.$demoPage;
        echo 'bb '.$id_page;*/
        if ($type_page == 'elementor_library' && empty($demoPage)) {
            //
            // Questo perché la pagina template non possiede un content quindi quando è vistualizzato dal template mostra un finto testo per ingombro.
            $content = $this->content;
            if ($settings['use_content_limit'])
                $content = wp_strip_all_tags(substr($content, 0, $settings['count_content_limit']) . ' ...');
            //
            $html = sprintf('<%1$s class="dce-content %2$s"><div class="dce-content-wrapper">', $settings['html_tag'], $animation_class);
            $html .= $content;
            $html .= sprintf('</div></%s>', $settings['html_tag']);
            //$html .= $id_page;
        }else{

            
            if(is_home()){
                //echo 'HOME';
                $content = '';
            }
            // All other Taxonomies
            else if (is_post_type_archive() || is_tax() || is_category() || is_tag() || is_author() ) {
                //echo 'tax';
                /*if($global_is == 'archive' ){
                    $post = get_post($id_page);
                    $content = $post->post_content;
                    $content = wpautop($content);
                }else{
                   
                }*/
                //echo 'is_post_type_archive';
                $content = get_the_archive_description();
               
            } else { 
               
                // if( is_single() )
                // Il Contenuto del post
                    // -------------------------
                if( $settings['use_filters_content'] == 'yes'){
                    
                    //echo 'Global: '.$global_ID.' - ID:'.get_the_ID().' - id_page: '.$id_page.'  '.$global_TYPE.' - '.$global_is;

                    $is_elementor = get_post_meta($id_page, '_elementor_edit_mode', true);
                    //echo 'E '.$is_elementor;
                    if($is_elementor){
                        //echo 'uso elementor '.$id_page;
                        //echo 'SI '.$id_page;
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $content = do_shortcode('[dce-elementor-template id="'.$id_page.'" inlinecss="true"]');
                        }else{
                            $content = do_shortcode('[dce-elementor-template id="'.$id_page.'"]'); 
                        }
                        
                    }else{
                        //echo 'uso wordpress '.$id_page;
                        //echo 'NO '.$id_page.' '.get_the_ID();
                        $post_wp = get_post($id_page);
                        //$content = wpautop($post->post_content);
                        $content = $post_wp->post_content;

                        if( $type_page == 'elementor_library' && $content == '' ) $content = $this->content;

                        // -----------------------------------------------
                        /*$editor = \Elementor\Plugin::instance()->editor;

                        // Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
                        $is_edit_mode = $editor->is_edit_mode();
                        echo 'xxx '.$id_page.$is_edit_mode;
                        $editor->set_edit_mode( false );

                        // Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the Elementor filter has been removed to avoid recursion.
                        $content = \Elementor\Plugin::instance()->frontend->get_builder_content( $id_page, true );

                        // Restore edit mode state
                        \Elementor\Plugin::instance()->editor->set_edit_mode( $is_edit_mode );

                        if ( empty( $content ) ) {
                            \Elementor\Plugin::instance()->frontend->remove_content_filter();
                            
                            //$content = apply_filters( 'the_content', $post->post_content );
                            \Elementor\Plugin::instance()->frontend->add_content_filter();
                        }*/
                        // -----------------------------------------------
                        if($content){

                            //$content_filter = do_shortcode(apply_filters('the_content', $post_wp->post_content));


                            //add_filter( 'the_content', $post_wp->post_content, 9 );
                            //$content = apply_filters( 'the_content', $post_wp->post_content );
                            //remove_filter( 'the_content', $post_wp->post_content, 9 );

                        }
                            //\Elementor\Plugin::instance()->frontend->remove_content_filter();
                            
                            //\Elementor\Plugin::instance()->frontend->add_content_filter();
                        
                        //$content = apply_filters( 'the_content', $content );
                        /*if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === true ){
                        $content = apply_filters( 'the_content', $post_wp->post_content );
                       }*/
                    }
                    
                    //
                }else{
                    //
                    $post = get_post($id_page);
                    $content = $post->post_content; //do_shortcode($post['post_content']); //$content_post->post_content; //
                    //
                    
                    if ($settings['use_content_limit'] && $content != '' ){
                        //$thecontent = get_the_content();
                        $content = wp_strip_all_tags(substr($content, 0, $settings['count_content_limit']) . ' ...'); //get_the_content();
                    }
                }

                if ($settings['use_content_autop']){
                        $content = wpautop($content);

                    }
                
            }

            if (empty($content))
                return;

            switch ($settings['link_to']) {
                case 'custom' :
                    if (!empty($settings['link']['url'])) {
                        $link = esc_url($settings['link']['url']);
                    } else {
                        $link = false;
                    }
                    break;

                case 'post' :
                    $link = esc_url(get_the_permalink());
                    break;

                case 'home' :
                    $link = esc_url(get_home_url());
                    break;

                case 'none' :
                default:
                    $link = false;
                    break;
            }

            $html = sprintf('<%1$s class="dce-content %2$s"><div class="dce-content-wrapper">', $settings['html_tag'], $animation_class);
            if ($link) {
                $html .= sprintf('<a class="dce-content-link" href="%1$s" %2$s>%3$s</a>', $link, $target, $content);
            } else {
                $html .= $content;
            }
            $html .= sprintf('</div></%s>', $settings['html_tag']);
        }

        if ($settings['no_shortcode']) {
            $html = strip_shortcodes( $html );
            $html = DCE_Helper::vc_strip_shortcodes($html);
        }

        echo do_shortcode($html);

        if($settings['enable_unfold']){
            $unfoldbtn = '<i class="fa fa-plus-circle" aria-hidden="true"></i>';//'Leggi di più';
            echo '<div class="unfold-btn"><a href="#">'.$unfoldbtn.'</a></div>';
        }
        if( $settings['data_source'] == '' ){
            $global_ID = $original_global_ID;
        }
        //echo 'dce_print_tools';
        //add_action( 'elementor/widget/before_render_content', array($this,'dce_print_tools') );
    }
    /*public function before_render() {
        echo 'before render';

        $settings = $this->get_settings_for_display();
        
        echo '<a target="_blank" class="dce-edit-template" href="'.get_permalink($settings['other_post_source']).'">
                <i class="fa fa-pencil"></i>'. __( 'Edit other source', 'dynamic-content-for-elementor' ).'</a>';
        ?>
        
        <?php
        
    }*/
    /*protected function dce_print_tools( $template_content ) {
        $this->render_edit_tools();
        echo 'print tools';
        $settings = $this->get_settings_for_display();
        //
        echo '<a target="_blank" class="dce-edit-template" href="'.get_permalink($settings['other_post_source']).'">
                <i class="fa fa-pencil"></i>'. __( 'Edit other source', 'dynamic-content-for-elementor' ).'</a>';
        //

        ?>
        <div class="elementor-widget-container">
            <?php
            echo $template_content; // XSS ok.
            ?>
        </div>
        <?php
    }*/
    
    protected function _content_template() {
        
    }
    public $content = 'This is the text place holder for page content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut risus id lectus hendrerit mattis. Nunc augue risus, dignissim vel nibh quis, gravida ultrices tortor. Nam volutpat nec est sed molestie. Mauris pellentesque diam in arcu bibendum convallis. Aenean non nisi et velit eleifend lobortis. Fusce lobortis tortor enim, eget elementum urna varius mollis. Vivamus imperdiet dignissim tincidunt. Praesent sit amet nulla lobortis, tempor ipsum id, feugiat felisss.';
}