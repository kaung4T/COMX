<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Barcode
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_BarCode extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce_barcode';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Bar & QR Codes', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-qrcode';
    }
    
    public function get_description() {
        return __('Quick creation for 1D e 2D barcodes, like EAN e QRCode', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/qr-and-bars-code/';
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        
        $types1d = array(
            'C39',
            'C39+',
            'C39E',
            'C39E+',
            'C93',
            'S25',
            'S25+',
            'I25',
            'I25+',
            'C128',
            'C128A',
            'C128B',
            'C128C',
            'EAN2',
            'EAN5',
            'EAN8',
            'EAN13',
            'UPCA',
            'UPCE',
            'MSI',
            'MSI+',
            'POSTNET',
            'PLANET',
            'RMS4CC',
            'KIX',
            'IMB',
            'IMBPRE',
            'CODABAR',
            'CODE11',
            'PHARMA',
            'PHARMA2T'
        );
        
        
        $types2d = array(
            'DATAMATRIX',
            'PDF417', // string of multiple params xx,yy,zz
            'QRCODE', // array('L','M','Q','H') // es: QRCODE,L
            'RAW',
            'RAW2',
            'TEST',
        );
        
        $this->start_controls_section(
                'section_barcode',
                [
                    'label' => __('Code', 'elementor'),
                ]
        );
        
        $this->add_control(
                'dce_barcode_dimension',
                [
                    'label' => __('Dimension', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1d' => [
                            'title' => __('1D', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-barcode',
                        ],
                        '2d' => [
                            'title' => __('2D', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-qrcode',
                        ],
                    ],
                    'label_block' => true,
                    'default' => '2d',
                    'toggle' => false,
                ]
        );
        
        $types1d_options = array();
        foreach ($types1d as $key => $value) {
            $types1d_options[$value] = $value;
        }
        $this->add_control(
                'dce_barcode_1d_type', [
            'label' => __('Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $types1d_options,
            'default' => 'EAN13',
            'condition' => [
                'dce_barcode_dimension' => '1d',
            ]
                ]
        );
        
        $types2d_options = array();
        foreach ($types2d as $key => $value) {
            $types2d_options[$value] = $value;
        }
        $this->add_control(
                'dce_barcode_2d_type', [
            'label' => __('Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $types2d_options,
            'default' => 'QRCODE',
            'condition' => [
                'dce_barcode_dimension' => '2d',
            ]
                ]
        );
        
        $this->add_control(
                'dce_barcode_type_options', [
            'label' => __('PDF417 Options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'xx,yy,zz',
            'condition' => [
                'dce_barcode_dimension' => '2d',
                'dce_barcode_2d_type' => 'PDF417',
            ]
                ]
        );
        $this->add_control(
                'dce_barcode_type_qr', [
            'label' => __('QR Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => array('L' => 'L','M' => 'M','Q' => 'Q','H' => 'H'),
            'default' => 'L',
            'condition' => [
                'dce_barcode_dimension' => '2d',
                'dce_barcode_2d_type' => 'QRCODE',
            ]
                ]
        );
        
        $this->add_control(
                'dce_barcode_code', [
                'label' => __('Code', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('The value of barcode, can be an URL, a NUMBER or a simple TEXT', 'elementor'),
                'default' => get_bloginfo('url'),
                'label_block' => true,
                ]
        );
        
        $this->add_control(
                'dce_barcode_render',
                [
                    'label' => __('Render as', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'SVGcode' => [
                            'title' => __('SVG', 'elementor'),
                            'icon' => 'fa fa-code-fork',
                        ],
                        'PngData' => [
                            'title' => __('PNG', 'elementor'),
                            'icon' => 'fa fa-image',
                        ],
                        'HTML' => [
                            'title' => __('HTML', 'elementor'),
                            'icon' => 'fa fa-code',
                        ],
                    ],
                    'default' => 'PngData',
                    'toggle' => false,
                    'label_block' => true,
                ]
        );
        
        $this->add_control(
                'dce_barcode_cols', [
            'label' => __('Cols', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            //'default' => 2,
            'min' => 1,
                ]
        );
        $this->add_control(
                'dce_barcode_rows', [
            'label' => __('Rows', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            //'default' => 30,
            'min' => 1,
                ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
			'section_style_code',
			[
				'label' => __( 'Code', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
                'align',
                [
                    'label' => __('Alignment', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __('Justified', 'elementor'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_code_color',
                [
                    'label' => __('Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'render_type' => 'template',
                    'selectors' => [
                        '{{WRAPPER}} .dce-barcode-svg #elements' => 'fill: {{VALUE}} !important;',
                        '{{WRAPPER}} .dce-barcode-html > div' => 'background-color: {{VALUE}} !important;',
                    ],
                ]
        );
       /* $this->add_control(
                'border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-barcode-png, .dce-barcode-svg, .dce-barcode-html > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );*/
        $this->end_controls_section();
        
        
        $this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                            'condition' => [
                              'dce_barcode_render' => ['PngData','SVGcode'],  
                            ],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dce-barcode' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Max Width', 'elementor' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dce-barcode' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .dce-barcode',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dce-barcode' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .dce-barcode',
			]
		);

		$this->end_controls_section();

		
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display(null, true);
        //var_dump($settings);
        $code = $settings['dce_barcode_code'];
        if ($code) {
            switch ($settings['dce_barcode_dimension']) {
                case '1d':
                    /**
                     * @param $code (string) code to print
                     * @param $type (string) type of barcode: <ul><li>C39 : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.</li><li>C39+ : CODE 39 with checksum</li><li>C39E : CODE 39 EXTENDED</li><li>C39E+ : CODE 39 EXTENDED + CHECKSUM</li><li>C93 : CODE 93 - USS-93</li><li>S25 : Standard 2 of 5</li><li>S25+ : Standard 2 of 5 + CHECKSUM</li><li>I25 : Interleaved 2 of 5</li><li>I25+ : Interleaved 2 of 5 + CHECKSUM</li><li>C128 : CODE 128</li><li>C128A : CODE 128 A</li><li>C128B : CODE 128 B</li><li>C128C : CODE 128 C</li><li>EAN2 : 2-Digits UPC-Based Extension</li><li>EAN5 : 5-Digits UPC-Based Extension</li><li>EAN8 : EAN 8</li><li>EAN13 : EAN 13</li><li>UPCA : UPC-A</li><li>UPCE : UPC-E</li><li>MSI : MSI (Variation of Plessey code)</li><li>MSI+ : MSI + CHECKSUM (modulo 11)</li><li>POSTNET : POSTNET</li><li>PLANET : PLANET</li><li>RMS4CC : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)</li><li>KIX : KIX (Klant index - Customer index)</li><li>IMB: Intelligent Mail Barcode - Onecode - USPS-B-3200</li><li>CODABAR : CODABAR</li><li>CODE11 : CODE 11</li><li>PHARMA : PHARMACODE</li><li>PHARMA2T : PHARMACODE TWO-TRACKS</li></ul>
                     */
                    $type = $settings['dce_barcode_1d_type'];
                    $barcode = new \TCPDFBarcode($code, $type);
                    break;
                case '2d':
                    /**
                     * @param $code (string) code to print
                     * @param $type (string) type of barcode: <ul><li>DATAMATRIX : Datamatrix (ISO/IEC 16022)</li><li>PDF417 : PDF417 (ISO/IEC 15438:2006)</li><li>PDF417,a,e,t,s,f,o0,o1,o2,o3,o4,o5,o6 : PDF417 with parameters: a = aspect ratio (width/height); e = error correction level (0-8); t = total number of macro segments; s = macro segment index (0-99998); f = file ID; o0 = File Name (text); o1 = Segment Count (numeric); o2 = Time Stamp (numeric); o3 = Sender (text); o4 = Addressee (text); o5 = File Size (numeric); o6 = Checksum (numeric). NOTES: Parameters t, s and f are required for a Macro Control Block, all other parametrs are optional. To use a comma character ',' on text options, replace it with the character 255: "\xff".</li><li>QRCODE : QRcode Low error correction</li><li>QRCODE,L : QRcode Low error correction</li><li>QRCODE,M : QRcode Medium error correction</li><li>QRCODE,Q : QRcode Better error correction</li><li>QRCODE,H : QR-CODE Best error correction</li><li>RAW: raw mode - comma-separad list of array rows</li><li>RAW2: raw mode - array rows are surrounded by square parenthesis.</li><li>TEST : Test matrix</li></ul>
                     */
                    $type = $settings['dce_barcode_2d_type'];
                    if ($type == 'QRCODE') {
                        $type .= ','.$settings['dce_barcode_type_qr'];
                    }
                    if ($type == 'PDF417') {
                        $type .= ','.$settings['dce_barcode_type_options'];
                    }
                    $barcode = new \TCPDF2DBarcode($code, $type);
                    break;
            }
            if ($barcode && $settings['dce_barcode_render']) {
                $render = 'getBarcode'.$settings['dce_barcode_render'];
                //var_dump($render);
                //var_dump($barcode->{$render}());
                $cols = (empty($settings['dce_barcode_cols'])) ? null : $settings['dce_barcode_cols'];
                $rows = (empty($settings['dce_barcode_rows'])) ? null : $settings['dce_barcode_rows'];
                
                $color = 'black';
                if ($settings['dce_code_color']) {
                    $color = $settings['dce_code_color'];
                }
                if ($settings['dce_barcode_render'] == 'PngData') {
                    if ($settings['dce_code_color']) {
                        $color = sscanf($settings['dce_code_color'], "#%02x%02x%02x");
                        //var_dump($color);
                    } else {
                        $color = array(0,0,0);
                    }
                }
                if ($cols) {
                    if ($cols && $rows) {
                        $result = $barcode->{$render}($cols, $rows, $color);
                    } else {
                        $result = $barcode->{$render}($cols, 10, $color);
                    }
                }  else {
                    $result = $barcode->{$render}(10, 10, $color);
                }
                if ($settings['dce_barcode_render'] == 'PngData') {
                    $result = '<img class="dce-barcode dce-barcode-png" src="data:image/png;base64,'.base64_encode($result).'">';
                }
                if ($settings['dce_barcode_render'] == 'SVGcode') {
                    $result = str_replace('<svg ', '<svg class="dce-barcode dce-barcode-svg" ', $result);
                    //$result = '<div class="dce-barcode dce-barcode-svg" '.substr($result, 5);
                }
                if ($settings['dce_barcode_render'] == 'HTML') {
                    $result = '<div class="dce-barcode dce-barcode-html" '.substr($result, 5);
                }
                echo $result;
            }
        }
    }

}
