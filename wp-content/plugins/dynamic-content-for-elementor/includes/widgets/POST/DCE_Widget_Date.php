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
 * Dynaminc Content Post Date
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Date extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-date';
    }
    
    static public function is_enabled() {
        return true;
    }
    static public function get_position() {
        return 4;
    }
    public function get_title() {
        return __('Date', 'dynamic-content-for-elementor');
        /* $post_type_object = get_post_type_object( get_post_type() );

          return sprintf(
          //translators: %s: Post type singular name (e.g. Post or Page)
          __( '%s Date', 'dynamic-content-for-elementor' ),
          $post_type_object->labels->singular_name
          ); */
    }
    public function get_description() {
        return __('Put a date on your article', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/date/';
    }
    public function get_icon() {
        return 'icon-dyn-date';
    }

    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_content', [
                'label' => __('Date', 'dynamic-content-for-elementor')
            ]
        );

        $this->add_control(
            'date_type', [
                'label' => __('Date Type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'publish' => __('Publish Date', 'dynamic-content-for-elementor'),
                    'modified' => __('Last Modified Date', 'dynamic-content-for-elementor'),
                ],
                'default' => 'publish',
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
        $this->add_control(
            'format', [
                'label' => __('Format date', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'l, j F, Y',
            ]
        );
        $this->add_control(
            'format2', [
                'label' => __('2 - Format date', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $this->add_control(
            'format3', [
                'label' => __('3 - Format date', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'format2!' => '',
                ],
            ]
        );
        $this->add_control(
          'date_separator', [
              'label' => __('Date text separator', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::TEXT,
              'frontend_available' => true,
              'default' => '',
              'condition' => [
                    'format2!' => '',
                ],
          ]
        );
        // --------------- Text Before
        $this->add_control(
            'date_text_before', [
                'label' => __('Text before', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $this->add_responsive_control(
            'date_text_before_block', [
                'label' => __('Before - Inline or Block', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('Inline', 'dynamic-content-for-elementor'),
                'label_on' => __('Block', 'dynamic-content-for-elementor'),
                'return_value' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-date span.tx-before' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'date_text_before!' => '',
                ]
            ]
        );

        // --------------- Alignment
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
                    'post' => 'Post URL',
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
            'float', [
                'label' => __('Float left', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HIDDEN,
            ]
        );
        $this->add_control(
            'block', [
                'label' => __('Use Block', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('block', 'dynamic-content-for-elementor'),
                'label_off' => __('span', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style', [
                'label' => __('Date', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        /* 1 */
        $this->add_control(
            'date_sec_1',
            [
                'label' => __( 'Date', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-date' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dynamic-content-for-elementor-date a' => 'color: {{VALUE}};',
                ],
        
            ]
        );
         $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-date > *',
        
            ]
        );
        /* 2 */
        $this->add_control(
            'date_sec_2',
            [
                'label' => __( 'Date 2', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'format2!' => '',
                ],
            ]
        );
        $this->add_control(
            'color2', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-date .d2' => 'color: {{VALUE}};'
                    
                ],
                'condition' => [
                    'format2!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography2',
                'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-date .d2',
                'condition' => [
                    'format2!' => '',
                ],
            ]
        );
        /* 3 */
        $this->add_control(
            'date_sec_3',
            [
                'label' => __( 'Date 3', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'format3!' => '',
                ],
            ]
        );
        $this->add_control(
            'color3', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-date .d3' => 'color: {{VALUE}};',
                   
                ],
                'condition' => [
                    'format3!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography3',
                'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-date .d3',
                'condition' => [
                    'format3!' => '',
                ],
            ]
        );
        
        /* ------------------ Text Before ------------ */
        $this->add_control(
            'txbefore_heading',
            [
                'label' => __( 'Text before', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'date_text_before!' => '',
                ]
            ]
        );
        $this->add_control(
            'tx_before_color', [
                'label' => __('Text Before Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-date span.tx-before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dynamic-content-for-elementor-date a span.tx-before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'date_text_before!' => '',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_tx_before',
                'label' => __('Font Before', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-date span.tx-before',
                'condition' => [
                    'date_text_before!' => '',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
           return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];
        // ------------------------------------------

        // Backwards compitability check
        if ($settings['date_type'])
            $date_type = $settings['date_type'];
        else
            $date_type = 'publish';

        $date2 = '';
        $date3 = '';
        switch ($date_type) {
            case 'modified' :
                $date = get_the_modified_date($settings['format'], $id_page);
                if( $settings['format2'] != '' ) $date2 = get_the_modified_date($settings['format2'], $id_page);
                if( $settings['format3'] != '' ) $date3 = get_the_modified_date($settings['format3'], $id_page);
                break;

            case 'publish' :
            default:
                $date = get_the_date($settings['format'], $id_page);
                if( $settings['format2'] != '' ) $date2 = get_the_date($settings['format2'], $id_page);
                if( $settings['format3'] != '' ) $date3 = get_the_date($settings['format3'], $id_page);
                break;
        }

        if (empty($date))
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
                $link = esc_url(get_the_permalink($id_page));
                break;

            case 'home' :
                $link = esc_url(get_home_url());
                break;

            case 'none' :
            default:
                $link = false;
                break;
        }
        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';

        $animation_class = ''; //!empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        //echo 'xxxxxx '.$settings['float'];
        $floatDate = '';
        if ('yes' == $settings['float']) {
            $floatDate = ' in_linea';
        }
        $floatDate = '';
        if ('yes' == $settings['block']) {
            $subtag = 'div';
        }else{
            $subtag = 'span';
        }
        $date_separator1 = '';
        $date_separator2 = '';
        if( $date2 != '' ) $date_separator1 = '<span class="d-separator">'.$settings['date_separator'].'</span>';
        if( $date3 != '' ) $date_separator2 = '<span class="d-separator">'.$settings['date_separator'].'</span>';

        $textBefore = '';
        if($settings['date_text_before']) $textBefore = '<span class="tx-before">'.__($settings['date_text_before'], 'dynamic-content-for-elementor'.'_texts').'</span>';

        $allDate = $textBefore.'<'.$subtag.' class="d1">'.$date.'</'.$subtag.'>'.$date_separator1.'<'.$subtag.' class="d2">'.$date2.'</'.$subtag.'>'.$date_separator2.'<'.$subtag.' class="d3">'.$date3.'</'.$subtag.'>';
        $html = sprintf('<%1$s class="dynamic-content-for-elementor-date %2$s%3$s">', $settings['html_tag'], $animation_class, $floatDate);
        if ($link) {
            $html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $allDate );
        } else {
            $html .= $allDate;
        }
        $html .= sprintf('</%s>', $settings['html_tag']);

        echo $html;
    }

    protected function _content_template() {
        /* global $post;
          $dateMod = get_the_modified_date($post->ID );
          echo $dateMod;
          ?>

          <#
          // Backwards compitability check
          var datetype;
          if (settings.date_type) {
          datetype = settings.date_type;
          } else {
          datetype = "publish";
          }
          var data_fields = [];
          data_fields[ "modified" ] = "<?php echo get_the_modified_date(); ?>";
          data_fields[ "publish" ] = "<?php echo get_the_date(); ?>";
          //
          var date = data_fields[ datetype ];

          var link_url;
          switch( settings.link_to ) {
          case 'custom':
          link_url = settings.link.url;
          break;
          case 'post':
          link_url = '<?php echo esc_url( get_the_permalink() ); ?>';
          break;
          case 'home':
          link_url = '<?php echo esc_url( get_home_url() ); ?>';
          break;
          case 'none':
          default:
          link_url = false;
          }
          var target = settings.link.is_external ? 'target="_blank"' : '';

          var animation_class = '';
          if ( '' !== settings.hover_animation ) {
          animation_class = 'elementor-animation-' + settings.hover_animation;
          }
          var floatDate = '';
          if ( 'yes' === settings.show_title ) {
          floatDate =  ' in_linea';
          }
          var html = '<' + settings.html_tag + ' class="dynamic-content-for-elementor-date ' + animation_class + floatDate + '">';
          if ( link_url ) {
          html += '<a href="' + link_url + '" ' + target + '>' + date + '</a>';
          } else {
          html += date;
          }
          html += '</' + settings.html_tag + '>';

          print( html );
          #>
          <?php
         */
    }

}
