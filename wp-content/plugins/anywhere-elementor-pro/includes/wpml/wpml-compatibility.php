<?php
namespace Aepro;


class WPML_Compatibility {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {

		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets' ] );

	}

	public function wpml_widgets($widgets){

		$widgets = $this->post_navigation($widgets);
		$widgets = $this->post_meta($widgets);
		$widgets = $this->post_readmore(($widgets));
		$widgets = $this->searchform($widgets);
		$widgets = $this->post_custom_taxonomy($widgets);
		$widgets = $this->post_blocks($widgets);
		$widgets = $this->post_custom_field($widgets);
		$widgets = $this->woo_sku($widgets);
		$widgets = $this->woo_readmore($widgets);
		$widgets = $this->woo_tabs($widgets);
		$widgets = $this->portfolio($widgets);

		return $widgets;
	}

	private function post_navigation($widgets){

		$widgets[ 'ae-post-navigation' ] = [
			'conditions'    => ['widgetType' => 'ae-post-navigation'],
			'fields'        => [
				[
					'field' => 'prev_label',
					'type'  => __('Previous', 'ae-pro'),
					'editor_type'   => 'LINE'
				],

				[
					'field' => 'next_label',
					'type'  => __('Next', 'ae-pro'),
					'editor_type'   => 'LINE'
				]
			]
		];

		return $widgets;

	}

    private function post_blocks($widgets){

        $widgets[ 'ae-post-blocks' ] = [
            'conditions'    => ['widgetType' => 'ae-post-blocks'],
            'fields'        => [
                [
                    'field' => 'prev_text',
                    'type'  => __('Previous', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ],

                [
                    'field' => 'next_text',
                    'type'  => __('Next', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]
        ];

        return $widgets;

    }

	private function post_meta($widgets){

		$widgets[ 'ae-post-meta' ]  = [

			'conditions'    => [ 'widgetType'  => 'ae-post-meta' ],
			'fields'        => [

				[
					'field' => 'cat_label',
					'type'  => __('', 'ae-pro'),
					'editor_type'   => 'LINE'
				],

				[
					'field' => 'tag_label',
					'type'  => __('', 'ae-pro'),
					'editor_type'   => 'LINE'
				],

                [
                    'field' => 'no_comment_label',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ],

                [
                    'field' => 'one_comment_label',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ],

                [
                    'field' => 'more_comment_label',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
			]

		];

		return $widgets;
	}

    private function post_readmore($widgets){

        $widgets[ 'ae-post-readmore' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-post-readmore' ],
            'fields'        => [
                [
                    'field' => 'read_more_text',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]
        ];

        return $widgets;
    }

    private function searchform($widgets){

        $widgets[ 'ae-searchform' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-searchform' ],
            'fields'        => [

                [
                    'field' => 'button_text',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ],

                [
                    'field' => 'input_placeholder_text',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]

        ];

        return $widgets;
    }

    private function post_custom_taxonomy($widgets){

        $widgets[ 'ae-taxonomy' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-taxonomy' ],
            'fields'        => [
                [
                    'field' => 'tax_label',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]
        ];

        return $widgets;
    }

    private function post_custom_field($widgets){

        $widgets[ 'ae-custom-field' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-custom-field' ],
            'fields'        => [

                [
                    'field' => 'cf_link_text',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ],

                [
                    'field' => 'cf_label',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]

        ];

        return $widgets;
    }

    private function woo_sku($widgets){

        $widgets[ 'ae-woo-sku' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-woo-sku' ],
            'fields'        => [
                [
                    'field' => 'sku_prefix',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]
        ];

        return $widgets;
    }

    private function woo_readmore($widgets){

        $widgets[ 'ae-woo-readmore' ]  = [

            'conditions'    => [ 'widgetType'  => 'ae-woo-readmore' ],
            'fields'        => [
                [
                    'field' => 'read_more_text',
                    'type'  => __('', 'ae-pro'),
                    'editor_type'   => 'LINE'
                ]
            ]
        ];

        return $widgets;
    }

    private function woo_tabs($widgets){

        $widgets[ 'ae-woo-tabs'] = [

            'conditions' => [ 'widgetType' => 'ae-woo-tabs' ],
            'fields'     => [],
            'integration-class' => '\Aepro\WPML_AE_Woo_Tabs'
        ];

        return $widgets;
    }

	private function portfolio($widgets){

		$widgets[ 'ae-portfolio' ] = [
			'conditions'    => ['widgetType' => 'ae-portfolio'],
			'fields'        => [
				[
					'field' => 'tab_all_text',
					'type'  => __("Tab 'All' Text", 'ae-pro'),
					'editor_type'   => 'LINE'
				]
			]
		];

		return $widgets;

	}

}

WPML_Compatibility::instance();