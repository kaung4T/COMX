<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor RemoteContent
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_RemoteContent extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-remotecontent';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Remote Content', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Dynamically read every type of content from the web, incorporate text blocks, pictures and more from external sources. Compatible with REST APIs, including the native ones from WordPress, and allows to format the resulting value in JSON', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/remote-content/';
    }

    public function get_icon() {
        return 'icon-dyn-remotecontent';
    }

    protected function _register_controls() {
        $this->start_controls_section(
                'section_remotecontent', [
            'label' => __('Remote Content', 'dynamic-content-for-elementor'),
                ]
        );

        if (current_user_can('administrator') || !\Elementor\Plugin::$instance->editor->is_edit_mode()) {

            $this->add_control(
                    'url', [
                'label' => __('Page URL', 'dynamic-content-for-elementor'),
                'description' => __('The full URL of page to include', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'frontend_available' => true,
                'placeholder' => 'https://www.dynamic.ooo/widget/remote-content/',
                'default' => '',
                    ]
            );

            $this->add_control(
                    'incorporate', [
                'label' => __('Incorporate in page', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Insert remote content in page html or simply add as iframe.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'url!' => '',
                ],
                    ]
            );

            $this->add_control(
                    'connect_timeout', [
                'label' => __('Connection Timeout', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'description' => __('Max time in seconds your server wait response from target server.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                ],
                    ]
            );

            /*$this->add_control(
                    'iframe_height', [
                'label' => __('Iframe Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 320,
                'frontend_available' => true,
                'condition' => [
                    'incorporate' => '',
                    'url!' => '',
                ],
                    ]
            );*/
            $this->add_responsive_control(
              'iframe_height',
              [
                  'label' => __( 'height', 'dynamic-content-for-elementor' ),
                  'type' => Controls_Manager::SLIDER,
                  'default' => [
                    'size' => '80',
                    'unit' => 'vh',
                  ],
                  /*
                'tablet_default' => [
                    'unit' => 'vh',
                ],
                'mobile_default' => [
                    'unit' => 'vh',
                ],*/
                  'range' => [
                      'px' => [
                          'min' => 0,
                          'max' => 1920,
                          'step' => 1,
                      ],
                      '%' => [
                          'min' => 5,
                          'max' => 100,
                          'step' => 1,
                      ],
                      'vh' => [
                          'min' => 5,
                          'max' => 100,
                          'step' => 1,
                      ],
                  ],
                  'size_units' => [ '%', 'px', 'vh' ],
                  'selectors' => [
                    '{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
                  ],
                  'condition' => [
                        'incorporate' => '',
                        'url!' => '',
                    ],
                ]
            );
            $this->add_control(
                    'data_json', [
                'label' => __('Data is JSON formatted', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('If it is the result from an API call probably is formatted in json language.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                ],
                    ]
            );

            $this->add_control(
                    'tag_id', [
                'label' => __('Tag, ID or Class', 'dynamic-content-for-elementor'),
                'description' => __('To include only subcontent of remote page. Use like jQuery selector (footer, #element, h2.big, etc).', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'frontend_available' => true,
                'placeholder' => 'body',
                'default' => 'body',
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'data_json' => '',
                ],
                    ]
            );

            $this->add_control(
                    'limit_tags', [
                'label' => __('Limit elements', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'placeholder' => __('Set negative, 0 or empty for unlimited', 'dynamic-content-for-elementor'),
                'description' => __('Limit results for a specific amount.', 'dynamic-content-for-elementor'),
                'default' => -1,
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'data_json' => '',
                ],
                    ]
            );

            $this->add_control(
                    'data_template', [
                'label' => __('Tokens', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<div class="dce-remote-content"><h3 class="dce-remote-content-title">[DATA:title:rendered]<h3><div class="dce-remote-content-body">[DATA:excerpt:rendered]</div><a class="btn btn-primary" href="[DATA:link]">Read more</a></div>',
                'description' => 'Add a specific format to data elements. Use token to rapresent json fields.',
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'data_json' => 'yes',
                ],
                    ]
            );



            $this->add_control(
                    'single_or_archive', [
                'label' => __('Single or Archive', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('Archive', 'dynamic-content-for-elementor'),
                'label_on' => __('Single', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                //'description' => __('Is a Single element o an Archive?', 'dynamic-content-for-elementor'),
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'data_json' => 'yes',
                ],
                    ]
            );

            $this->add_control(
                    'archive_path', [
                'label' => __('Archive Array path', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Leave empty if json result is a direct array (like in WP Api). For web service usually might use "results". You can browse sub arrays separate them by comma like "data.people"', 'dynamic-content-for-elementor'),
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'data_json' => 'yes',
                    'single_or_archive' => ''
                ],
                    ]
            );

            $this->add_control(
                    'limit_contents', [
                'label' => __('Limit elements', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'frontend_available' => true,
                'placeholder' => __('Set negative, 0 or empty for unlimited', 'dynamic-content-for-elementor'),
                'description' => __('Limit results for a specific amount.', 'dynamic-content-for-elementor'),
                'default' => -1,
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'single_or_archive' => '',
                ],
                    ]
            );
            
            $this->add_control(
                    'offset_contents', [
                'label' => __('Start from', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'frontend_available' => true,
                'description' => __('0 or empty for start from the first', 'dynamic-content-for-elementor'),
                'default' => -1,
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                    'single_or_archive' => '',
                ],
                    ]
            );

            /* $this->add_control(
              'data_anchors', [
              'label' => __('Correct link', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'label_off' => __('Off', 'dynamic-content-for-elementor'),
              'label_on' => __('On', 'dynamic-content-for-elementor'),
              'default' => '',
              'description' => __('Fix anchors if there are relative paths.', 'dynamic-content-for-elementor'),
              'condition' => [
              'incorporate!' => '',
              'url!' => '',
              ],
              ]
              ); */


            $this->add_control(
                    'data_cache', [
                'label' => __('Enable Cache', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('If linked sites are slow or not reachable is better enable cache. To force refresh it, disable, save and re-enable.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'incorporate!' => '',
                    'url!' => '',
                ],
                    ]
            );
            $this->add_control(
                    'data_cache_maxage', [
                'label' => __('Cache Max-age', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 86400,
                'description' => 'How long cache is valid? Set it in seconds. (86400 is a day, so every day it will be refreshed.)',
                'frontend_available' => true,
                'condition' => [
                    'data_cache!' => '',
                    'url!' => '',
                    'incorporate!' => '',
                ],
                    ]
            );
            $this->add_control(
                    'data_cache_refresh', [
                'label' => __('Last time Cache rebuilt', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'default' => '',
                'type' => Controls_Manager::TEXT,
                'description' => '<style>.elementor-control-data_cache_refresh{display:none !important;}</style>',
                'frontend_available' => true,
                'condition' => [
                    'data_cache!' => '',
                    'url!' => '',
                    'incorporate!' => '',
                ],
                    ]
            );
            $this->add_control(
                    'data_cache_content', [
                'label' => __('Cache content', 'dynamic-content-for-elementor'),
                //'description' => __('Here what is saved in cache. Empty it to refresh.', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'description' => '<style>.elementor-control-data_cache_content{display:none !important;}</style>',
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'condition' => [
                    'data_cache!' => '',
                    'url!' => '',
                    'incorporate!' => '',
                ],
                    ]
            );
        } else {
            $this->add_control(
                    'html_avviso', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<div class="dce-notice dce-error dce-notice-error">You must be admin to set this widget.</div>', 'dynamic-content-for-elementor'),
                'content_classes' => 'avviso',
                    ]
            );
        }
        
        $this->end_controls_section();
        
        
        $this->start_controls_section(
                'section_html_manipulation', [
            'label' => __('Html Manipulation', 'dynamic-content-for-elementor'),
            'condition' => [
                'incorporate!' => '',
                'url!' => '',
            ],
                ]
        );
        
        $this->add_control(
                'fix_links', [
            'label' => __('Fix Relative links', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Enable if remote page contain relative link.', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'blank_links', [
            'label' => __('Target Blank links', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Enable if you want open links in new page.', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'lazy_images', [
            'label' => __('Fix Lazy Images src', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Enable if you want show lazy images without use specific javascript.', 'dynamic-content-for-elementor'),
                ]
        );


        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if ($settings['url']) {
            $url = $settings['url'];

            if (filter_var($url, FILTER_VALIDATE_URL)) {

                if ($settings['incorporate']) {

                    $pageHtml = $this->getCache();
                    //var_dump($pageHtml);
                    if (!$pageHtml) {
                        $pageHtml = self::file_get_contents($url, false, NULL, $settings['connect_timeout']);
                    }

                    if ($pageHtml !== false && !is_wp_error($pageHtml)) {

                        //$pageHtml = str_replace('https', 'http', $pageHtml); // remove ssl

                        $pageBody = $pageHtml;

                        if ($settings['data_json']) {

                            $jsonData = json_decode($pageBody, true);
                            $pageBody = array();
                            if ($settings['single_or_archive']) {
                                $pageBody[] = $this->replaceTemplateTokens($settings['data_template'], $jsonData);
                            } else {
                                $jsonDataArchive = $jsonData;
                                //var_dump($settings['archive_path']); //die();
                                if (isset($settings['archive_path']) && $settings['archive_path']) {
                                    $pezzi = explode('.', $settings['archive_path']);
                                    $archive_path = "['" . implode("']['", $pezzi) . "']";
                                    //var_dump($archive_path); //die();
                                    eval('if (isset($jsonData' . $archive_path . ')) { $jsonDataArchive = $jsonData' . $archive_path . '; }');
                                }
                                if (!empty($jsonDataArchive)) {
                                    foreach ($jsonDataArchive as $aJsonData) {
                                        $pageBody[] = $this->replaceTemplateTokens($settings['data_template'], $aJsonData);
                                    }
                                }
                            }
                        } else if ($settings['tag_id']) {

                            /*if (!class_exists('simple_html_dom') && !class_exists('simple_html_dom_node')) { // NextGen also use it
                                require_once DCE_PATH . 'vendor/simple-html-dom/simple_html_dom.php';
                            }*/
                            //use Symfony\Component\DomCrawler\Crawler;
                            //$dom = new \simple_html_dom();
                            // The second parameter can force the selectors to all be lowercase.
                            //$dom->load($pageHtml);
                            $crawler = new \Symfony\Component\DomCrawler\Crawler($pageHtml);
                            //$crawler = $crawler->filter($settings['tag_id']);
                            $pageBody = array();
                            $pageBody = $crawler->filter($settings['tag_id'])->each(function (\Symfony\Component\DomCrawler\Crawler $node, $i) {
                                return $node->html();
                            });

                            if (isset($settings['limit_tags']) && $settings['limit_tags'] > 0 && count($pageBody) > $settings['limit_tags']) {
                              $pageBody = array_slice($pageBody, 0, $settings['limit_tags']);
                            }
                            //$pageBody = $dom->find($settings['tag_id']);
                            /*if (!empty($crawler)) {
                                $pageBody = $crawler->html();
                            } else {
                              $pageBody = '';
                            }*/
                            //$pageBody = array($pageBody);
                            //var_dump($pageBody);
                        } else {

                            $pageBody = array($pageBody);
                        }

                        $host = '';
                        if (isset($settings['fix_links']) && $settings['fix_links']) {
                          $pezzi = explode('/', $settings['url'], 4);
                          array_pop($pezzi);
                          $host = implode('/', $pezzi);
                        }

                        echo '<div class="dynamic-remote-content">';
                        $showed = 0;
                        foreach ($pageBody as $key => $aElem) {
                            if ($settings['limit_contents'] <= 0 || $showed <= $settings['limit_contents']) {
                                if ($key >= $settings['offset_contents']) {
                                    echo '<div class="dynamic-remote-content-element">';

                                    if (isset($settings['fix_links']) && $settings['fix_links']) {
                                      $aElem = str_replace('href="/', 'href="'.$host.'/', $aElem);
                                    }

                                    if (isset($settings['lazy_images']) && $settings['lazy_images']) {
                                      $imgs = explode('<img ', $aElem);
                                      foreach ($imgs as $ikey => $aimg) {
                                          if( strpos( $aimg, 'data-lazy-src' ) !== false) {
                                            $imgs[$ikey] = str_replace(' src="', 'data-placeholder-src="', $imgs[$ikey]);
                                            $imgs[$ikey] = str_replace('data-lazy-src="', 'src="', $imgs[$ikey]);
                                            $imgs[$ikey] = str_replace('data-lazy-srcset="', 'srcset="', $imgs[$ikey]);
                                            $imgs[$ikey] = str_replace('data-lazy-sizes="', 'sizes="', $imgs[$ikey]);
                                          }
                                      }
                                      $aElem = implode('<img ', $imgs);
                                    }

                                    if (isset($settings['blank_links']) && $settings['blank_links']) {
                                        $anchors = explode('<a ', $aElem);
                                        foreach ($anchors as $akey => $anchor) {
                                            if( strpos( $anchor, ' target="_' ) !== false) {
                                              $anchors[$akey] = 'target="_blank" '.$anchors[$akey];
                                            }
                                        }
                                        $aElem = implode('<a ', $anchors);
                                    }

                                    echo $aElem;
                                    echo '</div>';
                                }
                                $showed++;
                            }
                        }
                        echo '</div>';
                    } else {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            _e('Can\'t fetch remote content. Please check url', 'dynamic-content-for-elementor');
                        }
                    }
                } else {
                    // view as simple iframe
                    echo '<iframe src="' . $url . '" frameborder="0" width="100%" height="' . $settings['iframe_height']['size'] . '"></iframe>';
                }
            } else {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    _e('The url is not valid', 'dynamic-content-for-elementor');
                }
            }
        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                _e('Add remote url to begin', 'dynamic-content-for-elementor');
            }
        }
    }

    public function wp_head_dce() {
        echo '<!-- DCE -->';
        die();
    }

    public function replaceTemplateTokens($text, $content) {
        // /wp-admin/options.php
        $text = \DynamicContentForElementor\DCE_Tokens::replace_var_tokens($text, 'DATA', $content);
        $pezzi = explode('[', $text);
        if (count($pezzi) > 1) {
            foreach ($pezzi as $key => $avalue) {
                if ($key) {
                    $pezzo = explode(']', $avalue);
                    $metaParams = reset($pezzo);

                    $optionParams = explode('.', $metaParams);
                    $fieldName = $optionParams[0];
                    //var_dump( $optionName);
                    $optionValue = isset($content[$fieldName]) ? $content[$fieldName] : '';
                    //var_dump($optionValue);
                    $replaceValue = $this->checkArrayValue($optionValue, $optionParams);
                    $text = str_replace('[' . $metaParams . ']', $replaceValue, $text);
                }
            }
        }
        return $text;
    }

    private function checkArrayValue($optionValue = array(), $optionParams = array()) {
        if (is_array($optionValue)) {
            if (count($optionValue) == 1) {
                $tmpValue = reset($optionValue);
                if (!is_array($tmpValue)) {
                    return $tmpValue;
                }
            }
            if (is_array($optionParams)) {
                $val = $optionValue;
                foreach ($optionParams as $key => $value) {
                    if (isset($val[$value])) {
                        $val = $val[$value];
                    }
                }
                if (is_array($val)) {
                    $val = var_export($val, true);
                }
                return $val;
            }
            if ($optionParams) {
                return $optionValue[$optionParams];
            }
            return var_export($optionValue, true);
        }
        return $optionValue;
    }

    private static function file_get_contents_file($url) {
        $content = false;
        // using file() function to get content
        if ($lines_array = @file($url)) {
            // turn array into one variable
            $content = implode('', $lines_array);
            //output, you can also save it locally on the server
        }
        return $content;
    }

    private static function file_get_contents_curl(
            $url, $curl_timeout, $opts
    ) {
        $content = false;

        if (function_exists('curl_init')) {
            //Tools::refreshCACertFile();
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($curl, CURLOPT_CAINFO, _PS_CACHE_CA_CERT_FILE_);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

            if ($opts != null) {
                if (isset($opts['http']['method']) && strtolower($opts['http']['method']) == 'post') {
                    curl_setopt($curl, CURLOPT_POST, true);
                    if (isset($opts['http']['content'])) {
                        parse_str($opts['http']['content'], $post_data);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
                    }
                }
            }
            $content = curl_exec($curl);
            curl_close($curl);
        }

        return $content;
    }

    private static function file_get_contents_fopen(
            $url
    ) {
        $content = false;

        //fopen opens webpage in Binary
        if ($handle = @fopen($url, "rb")) {
            // initialize
            $content = "";
            // read content line by line
            do {
                $data = fread($handle, 1024);
                if (strlen($data) == 0) {
                    break;
                }
                $content .= $data;
            } while (true);
            //close handle to release resources
            fclose($handle);
            //output, you can also save it locally on the server
            echo $content;
        }

        return $content;
    }

    /**
     * This method allows to get the content from either a URL or a local file
     * @param string $url the url to get the content from
     * @param bool $use_include_path second parameter of http://php.net/manual/en/function.file-get-contents.php
     * @param resource $stream_context third parameter of http://php.net/manual/en/function.file-get-contents.php
     * @param int $curl_timeout
     * @param bool $fallback whether or not to use the fallback if the main solution fails
     * @return bool|string false or the string content
     */
    public static function file_get_contents(
            $url, $use_include_path = false, $stream_context = null, $curl_timeout = 5, $fallback = false
    ) {
        $is_local_file = !preg_match('/^https?:\/\//', $url);
        $require_fopen = false;
        $opts = null;

        if ($stream_context) {
            $opts = stream_context_get_options($stream_context);
            if (isset($opts['http'])) {
                $require_fopen = true;
                $opts_layer = array_diff_key($opts, array('http' => null));
                $http_layer = array_diff_key($opts['http'], array('method' => null, 'content' => null));
                if (empty($opts_layer) && empty($http_layer)) {
                    $require_fopen = false;
                }
            }
        } elseif (!$is_local_file) {
            $stream_context = @stream_context_create(
                            array(
                                'http' => array('timeout' => $curl_timeout),
                                'ssl' => array(
                                    'verify_peer' => false,
                                    "verify_peer_name" => false,
                                //'cafile' => CaBundle::getBundledCaBundlePath()
                                )
                            )
            );
        }

        $content = false;

        if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1'))) {
            $content = @file_get_contents($url, $use_include_path, $stream_context);
        }

        if (!$content) {
            $content = self::file_get_contents_file($url);
        }

        if (!$content) {
            $content = self::file_get_contents_fopen($url);
        }

        if (!$content) {
            $content = self::file_get_contents_curl($url, $curl_timeout, $opts);
        }

        return $content;
    }

    public function getCache() {
        $wCache = false;
        if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $settings = $this->get_settings_for_display();

            if ($settings['data_cache']) {

                $lastRenew = (int) $settings['data_cache_refresh'];
                if ($lastRenew + $settings['data_cache_maxage'] < time() || !$settings['data_cache_content']) {
                    // cache refresh
                    //echo 'cache refresh';
                    $wCache = self::file_get_contents($settings['url'], false, NULL, $settings['connect_timeout']);
                    if ($wCache) {
                        $wCachePrepared = base64_encode($wCache);
                        $this->update_settings('data_cache_content', $wCachePrepared);
                        $this->update_settings('data_cache_refresh', time());
                    }
                } else {
                    $wCache = stripslashes($settings['data_cache_content']);
                    $wCache = base64_decode($wCache);
                }

                if ($wCache) {
                    //$wCache = "CACHED".$wCache;
                    //echo "cached"; die();
                }
            }
        }
        return $wCache;
    }

}
