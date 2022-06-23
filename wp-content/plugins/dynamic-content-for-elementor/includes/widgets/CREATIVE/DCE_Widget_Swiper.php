<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Swiper
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_Swiper extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-swiper';
    }
    static public function is_enabled() {
        return false;
    }    
    public function get_title() {
        return __('Swiper', 'dynamic-content-for-elementor');
    }
    public function get_icon() {
        return 'icon-dyn-carousel';
    }
    public function get_script_depends() {
        return [ 'jquery-swiper', 'dce-swiper'];
    }
    public function get_style_depends() {
        return [ 'dce-photoSwipe_default','dce-photoSwipe_skin','dce-swiper' ];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_swiper_slides', [
                'label' => __('Swiper', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_responsive_control(
            'height', [
                'label' => __('Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                  'unit' => 'px',
                ],
                'mobile_default' => [
                   'unit' => 'px',
                ],
                'size_units' => [ 'px', 'rem', 'vh'],
                'range' => [
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-swiper .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_responsive_control(
            'spaceV', [
                'label' => __('Spazio Vericale', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                /* 'tablet_default' => [
                  'unit' => 'px',
                  ],
                  'mobile_default' => [
                  'unit' => 'px',
                  ], */
                'size_units' => [ 'px', 'em', 'vh'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-swiper .swiper-container' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};', //'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_responsive_control(
            'spaceH', [
                'label' => __('Spazio Orizzontale', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                  'unit' => 'px',
                ],
                'mobile_default' => [
                  'unit' => 'px',
                ],
                'size_units' => [ 'px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-swiper .swiper-container' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};', //'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );
        $repeater = new Repeater();

        $repeater->start_controls_tabs('swiper_repeater');

        $repeater->start_controls_tab('tab_content', [ 'label' => __('Item', 'dynamic-content-for-elementor')]);
        $repeater->add_control(
            'id_name', [
                'label' => __('Name', 'dynamic-content-for-elementor'),
                'description' => __('Il nome LABEL della sezione.', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Section',
            ]
        );
        $repeater->add_control(
            'slug_name', [
                'label' => __('Slug', 'dynamic-content-for-elementor'),
                'description' => __('Lo SLUG della slide, usato nell\'indirizzo URL e negli identificativi interni. (deve essere univoco)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'section-fp',
            ]
        );
        //
        //
		$repeater->add_control(
            'colorbg_section', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
           ]
        );
        $repeater->add_control(
            'bg_image', [
                'label' => __('Image', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );
        /*$repeater->add_control(
            'template', [
                'label' => __('Select Template', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                //'options' => get_post_taxonomies( $post->ID ),
                'options' => DCE_Helper::get_all_template(),
                'default' => '',
            ]
        );*/
        $repeater->add_control(
                'template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                ]
        );
        //




        $repeater->end_controls_tab();

        /* $repeater->start_controls_tab( 'tab_media', [ 'label' => __( 'Media', 'dynamic-content-for-elementor' ) ] );



          $repeater->end_controls_tab(); */

        $repeater->start_controls_tab('tab_style', [ 'label' => __('Style', 'dynamic-content-for-elementor')]);

        // Single Slide Style ......

        $repeater->end_controls_tab();


        $repeater->end_controls_tabs();

        $this->add_control(
            'swiper', [
                'label' => __('Slides', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                ],
                'fields' => array_values($repeater->get_controls()),
                'title_field' => '{{{id_name}}}',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Base Settings, Slides grid, Grab Cursor
        $this->start_controls_section(
            'section_swiper_settings', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'direction', [
                'label' => __('Direzione', 'dynamic-content-for-elementor'),
                'description' => __('La direzione dello slider', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
                    'vertical' => __('Vertical', 'dynamic-content-for-elementor'),
                ],
                'default' => 'horizontal',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'speed', [
                'label' => __('Velocità', 'dynamic-content-for-elementor'),
                'description' => __('Durata della transizione tra diapositive (in ms)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 0,
                'max' => 3000,
                'step' => 10,
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'effects', [
                'label' => __('Effect of transition', 'dynamic-content-for-elementor'),
                'description' => __('L\'effetto di transizione tra le slides', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => __('Slide', 'dynamic-content-for-elementor'),
                    'fade' => __('Fade', 'dynamic-content-for-elementor'),
                    'cube' => __('Cube', 'dynamic-content-for-elementor'),
                    'coverflow' => __('Coverflow', 'dynamic-content-for-elementor'),
                    'flip' => __('Flip', 'dynamic-content-for-elementor'),
                    'custom1' => __('Custom1', 'dynamic-content-for-elementor'),
                ],
                'default' => 'slide',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'centeredSlides', [
                'label' => __('Centered Slides', 'dynamic-content-for-elementor'),
                'description' => __('Se è vero, la diapositiva attiva sarà centrata, non sul lato sinistro.', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'special_options', [
                'label' => __('Specials options', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'setWrapperSize', [
                'label' => __('Set Wrapper Size', 'dynamic-content-for-elementor'),
                'description' => __('imposta la larghezza / altezza sul wrapper swiper pari alla dimensione totale di tutte le diapositive. Principalmente dovrebbe essere utilizzato come opzione di backup di compatibilità per il browser che non supporta bene il layout di flessibilità', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'virtualTranslate', [
                'label' => __('Virtual Translate', 'dynamic-content-for-elementor'),
                'description' => __('Utile quando è necessario creare una transizione personalizzata (vedi effects)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'autoHeight', [
                'label' => __('Auto Height', 'dynamic-content-for-elementor'),
                'description' => __('Impostato su SI e lo slider wrapper adotterà la sua altezza all\'altezza della diapositiva attualmente attiva', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'roundLengths', [
                'label' => __('Round Lengths', 'dynamic-content-for-elementor'),
                'description' => __('Impostare valori veraci a valori rotondi della larghezza e dell\'altezza delle diapositive per evitare testi sfocati sulle schermate di risoluzione usuali (se si dispone di tali)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'nested', [
                'label' => __('Nidificato', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su SI su Swiper nidificato, per intercettazioni corrette degli eventi di tocco. Utilizzare solo su spazzole annidate che utilizzano la stessa direzione del genitore', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'grabCursor', [
                'label' => __('Grab Cursor', 'dynamic-content-for-elementor'),
                'description' => __('Questa opzione può un po\' migliorare l\'usabilità del desktop. Se è vero , l\'utente vedrà il cursore afferrare quando si trova su Swiper', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );

        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Grid: Slide $ Flip
        $this->start_controls_section(
            'section_swiper_grid', [
                'label' => __('Slider/Coveflow Grid', 'dynamic-content-for-elementor'),
                'condition' => [
                    'effects' => ['slide', 'coverflow'],
                ]
            ]
        );
        $this->add_control(
            'more_options', [
                'label' => __('Slides Grid', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'spaceBetween', [
                'label' => __('Space Between', 'dynamic-content-for-elementor'),
                'description' => __('Distanza tra diapositive in px.', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'tablet_default' => '',
                'mobile_default' => '',
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'frontend_available' => true
            ]
        );
        $this->add_responsive_control(
            'slidesPerView', [
                'label' => __('Slides Per View', 'dynamic-content-for-elementor'),
                'description' => __('Numero di diapositive per visualizzazione (diapositive visibili allo stesso tempo sul contenitore) Se il valore è 0 indica "auto" (NOTA: auto non è compatibile con: slidesPerColumn > 1). Se viene impostato "auto" e anche "loop", è necessario impostare "loopedSlides".', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                //'tablet_default' => '',
                //'mobile_default' => '',
                'min' => 0,
                'max' => 12,
                'step' => 1,
                'frontend_available' => true
            ]
        );
        $this->add_responsive_control(
            'slidesPerGroup', [
                'label' => __('Slides Per Group', 'dynamic-content-for-elementor'),
                'description' => __('Nmposta i numeri di diapositive per definire e abilitare la scorrimento del gruppo. Utile da utilizzare con diapositivePerView > 1', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'tablet_default' => '',
                'mobile_default' => '',
                'min' => 0,
                'max' => 12,
                'step' => 1,
                'frontend_available' => true
            ]
        );

        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Autoplay
        $this->start_controls_section(
            'section_swiper_autoplay', [
                'label' => __('Autoplay', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'autoplay', [
                'label' => __('Auto Play', 'dynamic-content-for-elementor'),
                'description' => __('Ritardo tra transizioni (in ms). Se questo parametro non è specificato (di default), la riproduzione automatica sarà disattivata', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'min' => 0,
                'max' => 3000,
                'step' => 100,
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'autoplayStopOnLast', [
                'label' => __('Autoplay stop on last slide', 'dynamic-content-for-elementor'),
                'description' => __('Abilitare questo parametro e l\'autoplay verrà interrotto quando raggiunge l\'ultima diapositiva (non ha alcun effetto in modalità loop/ciclico)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'autoplayDisableOnInteraction', [
                'label' => __('Autoplay Disable on interaction', 'dynamic-content-for-elementor'),
                'description' => __('Impostato su NO e l\'autoplay non verrà disattivato dopo le interazioni utente (swipes), verrà riavviato ogni volta dopo l\'interazione', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Progress
        $this->start_controls_section(
            'section_swiper_progress', [
                'label' => __('Progress', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'watchSlidesProgress', [
                'label' => __('Watch Slides Progress', 'dynamic-content-for-elementor'),
                'description' => __('Attiva questa funzionalità per calcolare ogni progresso delle diapositive', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'watchSlidesVisibility', [
                'label' => __('Watch Slides Visibility', 'dynamic-content-for-elementor'),
                'description' => __('Abilita questa opzione e le diapositive che sono in visualizzazione avranno una classe visibile supplementare', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'watchSlidesProgress' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Freemode
        $this->start_controls_section(
            'section_swiper_freemode', [
                'label' => __('Freemode', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'freeMode', [
                'label' => __('Free Mode', 'dynamic-content-for-elementor'),
                'description' => __('Se true, le diapositive non avranno posizioni fisse', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'freeModeMomentum', [
                'label' => __('Free Mode Momentum', 'dynamic-content-for-elementor'),
                'description' => __('Se è vero, allora la diapositiva continuerà a muoversi per un po\' dopo averlo rilasciato', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'freeModeMomentumRatio', [
                'label' => __('Free Mode Momentum Ratio', 'dynamic-content-for-elementor'),
                'description' => __('Il valore più elevato produce distanza più grande di slancio dopo aver rilasciato il dispositivo di scorrimento', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                    'freeModeMomentum' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'freeModeMomentumVelocityRatio', [
                'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
                'description' => __('Il valore più elevato produce una velocità di slancio maggiore dopo aver rilasciato il dispositivo di scorrimento', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                    'freeModeMomentum' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'freeModeMomentumBounce', [
                'label' => __('Free Mode Momentum Bounce', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su false se si desidera disattivare il rimbalzo della moto in modalità libera', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'freeModeMomentumBounceRatio', [
                'label' => __('Free Mode Momentum Bounce Ratio', 'dynamic-content-for-elementor'),
                'description' => __('Il valore più elevato produce un effetto di rimbalzo più grande del momento', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                    'freeModeMomentumBounce' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'freeModeMinimumVelocity', [
                'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
                'description' => __('Velocità di spostamento minima necessaria per attivare la mossa di modalità libera', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.02,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'freeModeSticky', [
                'label' => __('Free Mode Sticky', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su true per abilitare lo snap a scorrimento delle posizioni in modalità libera', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'freeMode' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();

        // ------------------------------------------------------------------------------- Parallax
        /* $this->start_controls_section(
          'section_swiper_tarallax',
          [
          'label'         => __( 'Parallax', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Touches, Touch Resistance
        /* $this->start_controls_section(
          'section_swiper_touch',
          [
          'label'         => __( 'Touches & Touch Resistance', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Swiping / No swiping
        /* $this->start_controls_section(
          'section_swiper_swiping',
          [
          'label'         => __( 'Swiping / No swiping', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Navigation Controls, Pagination, Navigation Buttons, Scollbar, Accessibility
        /* $this->start_controls_section(
          'section_swiper_navigation',
          [
          'label'         => __( 'Navigation', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Keyboard / Mousewheel
        $this->start_controls_section(
            'section_swiper_keyboardMousewheel', [
                'label' => __('Keyboard / Mousewheel', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'keyboardControl', [
                'label' => __('Keyboard Control', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su true per abilitare lo scorrimento da tastiera', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'mousewheelControl', [
                'label' => __('Mousewheel Control', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su true per abilitare lo scorrimento con la rotella del mouse', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Hash/History Navigation
        /* $this->start_controls_section(
          'section_swiper_hashHistory',
          [
          'label'         => __( 'Hash/History Navigation', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Images
        /* $this->start_controls_section(
          'section_swiper_images',
          [
          'label'         => __( 'Images', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Loop
        $this->start_controls_section(
            'section_swiper_loop', [
                'label' => __('Loop', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'loop', [
                'label' => __('Loop', 'dynamic-content-for-elementor'),
                'description' => __('Impostare su true per abilitare la modalità di ciclo continuo', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------------------- Zoom
        /* $this->start_controls_section(
          'section_swiper_zoom',
          [
          'label'         => __( 'Zoom', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Controller
        /* $this->start_controls_section(
          'section_swiper_controller',
          [
          'label'         => __( 'Controller', 'dynamic-content-for-elementor'),
          ]
          );
          $this->end_controls_section(); */
        // -------------------------------------------------------------------------------
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;

        $effect = ' ' . $settings['effects'];
        $direction = ' direction-' . $settings['direction'];
        //
        echo '<div class="dce-swiper' . $effect . $direction . '">';
        echo '	<div class="swiper-container">';
        echo '		<div class="swiper-wrapper">';
        $counter_item = 1;
        $swiperItems = $settings['swiper'];
        if (!empty($swiperItems)) {
            foreach ($swiperItems as $swpitem) :

                $id_name = $swpitem['id_name'];
                $slug_name = $swpitem['slug_name'];

                $colorbg_section = $swpitem['colorbg_section'];
                $bg_image = $swpitem['bg_image']['url'];

                $template = $swpitem['template'];

                $bgcolor = '';
                if ($colorbg_section)
                    $bgcolor = 'background-color:' . $colorbg_section . ';';
                $bgimg = '';
                if ($bg_image)
                    $bgimg = ' background-image:url(' . $bg_image . ');';
                //echo $swpitem['id_name'];
                //echo $swpitem['slug_name'];
                echo '<div id="' . $slug_name . '" class="swiper-slide">';

                echo '<div class="slide-inner" style="' . $bgcolor . $bgimg . '"></div>';
                //echo do_shortcode('[dce-elementor-template id="'.$template.'"]');
                echo '</div>';


                $counter_item++;
            endforeach;
        }
        echo '		</div>';
        ?>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <!-- <div class="swiper-scrollbar"></div> -->
        <?php
        echo '	</div>';
        echo '</div>';
    }

}
