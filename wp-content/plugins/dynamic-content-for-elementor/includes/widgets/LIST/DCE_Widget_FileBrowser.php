<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
//use Elementor\Group_Control_Border;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_HSB;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor FileBrowser
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_FileBrowser extends DCE_Widget_Prototype {

    public $file_metadata = array(); // save it in a hidden field in json, values only for this post

    public function get_name() {
        return 'dce-filebrowser';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('FileBrowser', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Display a list of files you uploaded in a specific “uploads” directory. This is particularly useful when you need to make pictures or documents available in a simple and intuitive way', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/file-browser/';
    }

    public function get_icon() {
        return 'icon-dyn-filebrowser';
    }

    public function get_style_depends() {
        return ['dce-file-icon'];
    }
    
    public function get_dce_style_depends() {
        return ['dce-filebrowser'];
    }

    protected function _register_controls() {
        
        $post_metas = array();
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            //$post_metas = DCE_Helper::get_post_metas();
            //var_dump($post_metas); die();
        }
        
        
        $this->start_controls_section(
                'section_filebrowser', [
            'label' => __('FileBrowser', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'path_selection',
                [
                    'label' => __('Select path', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'uploads' => [
                            'title' => __('Uploads', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-upload',
                        ],
                        'custom' => [
                            'title' => __('Custom', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-folder-o',
                        ],
                        'media' => [
                            'title' => __('Media Library', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-file-image-o',
                        ],
                        'taxonomy' => [
                            'title' => __('Taxonomy', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-tags',
                        ],
                        /*'meta' => [
                            'title' => __('Meta', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-tags',
                        ],*/
                        'post' => [
                            'title' => __('Post Medias', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-file-o',
                        ],
                        'csv' => [
                            'title' => __('CSV', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-file-excel-o',
                        ],
                    ],
                    'default' => 'uploads',
                    'toggle' => false,
                    'label_block' => 'true',
                ]
        );

        $this->add_control(
                'folder_custom', [
            'label' => __('Custom Path', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'myfolder/docs',
            'description' => __('A custom path from site root. You can use Token for dynamic path.', 'dynamic-content-for-elementor') . '<br>Ex: \'myfolder/document/[post:my_meta_field]\'',
            'default' => 'wp-content/uploads',
            'condition' => [
                'path_selection' => ['custom', 'csv'],
            ],
                ]
        );
        
        $this->add_control(
                'medias_field',
                [
                    'label' => __('Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'fields',
                    'object_type' => 'any',
                    'condition' => [
                        'path_selection' => 'media',
                    ],
                ]
        );
        $this->add_control(
                'medias',
                [
                    'label' => __('Choose Files', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'description' => '<style>.elementor-control-medias .wp-editor-container, .elementor-control-medias .wp-editor-tabs { display: none; }</style>',
                    'condition' => [
                        'path_selection' => 'media',
                        'medias_field' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'metas',
                [
                    'label' => __('Post Meta field', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => $post_metas,
                    'condition' => [
                        'path_selection' => 'meta',
                    ],
                ]
        );

        $this->add_control(
                'folder',
                [
                    'label' => __('Root Folder', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $this->getFolders(),
                    'default' => date('Y'),
                    'description' => __('You can add more files trought <a target="_blank" href="' . admin_url('upload.php', 'relative') . '">MediaLibrary</a>, via FTP or using specific plugin as <a href="https://wordpress.org/plugins/wp-file-manager/" target="_blank">WP File Manager</a>', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'path_selection' => 'uploads',
                    ],
                ]
        );
        foreach ($this->getFolders() as $key => $value) {
            $subfolders = $this->getFoldersRic($this->getRootDir($value), false, $value);
            $subfolders = array_reverse($subfolders, true);
            $subfolders['/'] = '/';
            $subfolders = array_reverse($subfolders, true);
            $this->add_control(
                    'subfolder_' . $value,
                    [
                        'label' => __('SubFolder', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT2,
                        'options' => $subfolders,
                        'default' => '/',
                        'description' => __('Select specific subfolder or root', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'path_selection' => 'uploads',
                            'folder' => $value,
                        ],
                    ]
            );
        }

        $taxonomies = DCE_Helper::get_taxonomies( false, 'attachment');
        $this->add_control(
            'taxonomy', [
                'label' => __('Select Taxonomy', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => ['' => __('None', 'dynamic-content-for-elementor')] + $taxonomies, //get_taxonomies(array('public' => true)),
                'description' => __('Use selected taxonomy as folder', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'path_selection' => 'taxonomy',
                ],
            ]
        );
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $tkey => $atax) {
                if ($tkey) {
                    $terms = DCE_Helper::get_taxonomy_terms($tkey, true);
                    //var_dump($tkey); var_dump($terms); die();
                    $this->add_control(
                        'terms_' . $tkey, [
                        'label' => __('Terms', 'dynamic-content-for-elementor'), //.' '.$atax,
                        'type' => Controls_Manager::SELECT2,
                        'options' => ['' => __('All', 'dynamic-content-for-elementor')] + $terms,
                        'description' => __('Filter results by selected taxonomy term', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'condition' => [
                            'taxonomy' => $tkey,
                            'path_selection' => 'taxonomy',
                        ],
                    ]
                    );
                }
            }
        }
        

        $this->add_control(
                'title', [
            'label' => __('Show folder title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'condition' => [
                'path_selection!' => 'media',
            ]
                ]
        );
        $this->add_control(
                'title_size',
                [
                        'label' => __( 'Title HTML Tag', 'dynamic-content-for-elementor' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                                'div' => 'div',
                                'span' => 'span',
                                'p' => 'p',
                        ],
                        'default' => 'h4',
                    'condition' => [
                        'path_selection!' => 'media',
                        'title!' => '',
                    ]
                ]
        );

        $this->add_control(
                'empty', [
            'label' => __('Show empty folders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'path_selection' => ['uploads', 'custom'],
            ]
                ]
        );

        $this->add_control(
                'resized', [
            'label' => __('Show resized images', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Wordpress automatically create for every uploaded image many resized version (es: my-image-150x150.png, another-img-310x250.jpg), if you want view them enable this setting', 'dynamic-content-for-elementor'),
            'condition' => [
                'path_selection' => ['uploads', 'custom'],
            ]
                ]
        );

        $this->add_control(
                'order',
                [
                    'label' => __('Order', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => array(
                        SCANDIR_SORT_NONE => __('None', 'dynamic-content-for-elementor'),
                        0 => __('Ascending', 'dynamic-content-for-elementor'),
                        1 => __('Descending', 'dynamic-content-for-elementor'),
                    ),
                    'default' => '0',
                    'description' => __('Select file order', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'path_selection' => ['uploads', 'custom'],
                    ]
                ]
        );

        $this->add_control(
                'file_type',
                [
                    'label' => __('Filter by file extension', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => 'gif, jpg, png',
                    'description' => __('Show only specific file types. Separate each extension by comma', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'path_selection!' => 'media',
                    ]
                ]
        );
        $this->add_control(
                'file_type_show', [
            'label' => __('Show/Hide specified file types', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'file_type!' => ''
            ],
                ]
        );

        $this->add_control(
                'img_icon', [
            'label' => __('Use thumbnail for images', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('If file is an image then use it\'s thumb as icon', 'dynamic-content-for-elementor'),
                ]
        );

        /*
          $this->add_control(
          'subfolder',
          [
          'label' => __( 'Root Folder', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT,
          'options' => $this->plainDirToArray(),
          'default' => '/',
          'description' => 'Select specific subfolder or root'
          ]
          );
         */


        $this->add_control(
                'search', [
            'label' => __('Enable quick search form', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before'
                ]
        );


        $this->add_control(
                'enable_metadata', [
                    'separator' => 'before',
            'label' => __('Enable metadata info', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_file_csv',
            [
                'label' => __('CSV', 'dynamic-content-for-elementor'),
                'condition' => [
                    'path_selection' => 'csv',
                ],
            ]
        );
        $this->add_control(
            'folder_csv', [
            'label' => __('CSV Path', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'myfolder/file_list.csv',
                ]
        );
        $this->add_control(
            'folder_csv_filter', [
            'label' => __('CSV Folder Path Filter', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'myfolder/docs',
                ]
        );
        $this->add_control(
            'folder_csv_separator', [
            'label' => __('CSV Folder Separator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '/',
                ]
        );
        $this->add_control(
            'folder_csv_header', [
            'label' => __('CSV Header line', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
            'folder_csv_col_dir', [
            'label' => __('CSV Directory col', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
                ]
        );
        $this->add_control(
            'folder_csv_col_file', [
            'label' => __('CSV File col', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
            'min' => 1,
                ]
        );
        $this->add_control(
            'folder_csv_title', [
            'label' => __('Use Dir basename as file Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
                'section_file_form',
                [
                    'label' => __('Search form', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'search!' => '',
                    ],
                ]
        );
            $this->add_control(
                'search_text',
                [
                    'label' => __('Search Text', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Quick search', 'dynamic-content-for-elementor'),
                    'placeholder' => __('Quick search', 'dynamic-content-for-elementor'),
                ]
            );
            $this->add_control(
                    'search_text_size',
                    [
                            'label' => __( 'Form Title HTML Tag', 'dynamic-content-for-elementor' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                    'h1' => 'H1',
                                    'h2' => 'H2',
                                    'h3' => 'H3',
                                    'h4' => 'H4',
                                    'h5' => 'H5',
                                    'h6' => 'H6',
                                    'div' => 'div',
                                    'span' => 'span',
                                    'p' => 'p',
                            ],
                        'default' => 'h4',
                        'condition' => [
                            'search_text!' => '',
                        ],
                    ]
            );
            $this->add_control(
                'search_notice',
                [
                    'label' => __('Search Notice', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('* at least 3 character min', 'dynamic-content-for-elementor'),
                    'placeholder' => __('* at least 3 character min', 'dynamic-content-for-elementor'),
                ]
            );
            $this->add_control(
                    'search_quick',
                    [
                        'label' => __('Quick search', 'dynamic-content-for-elementor'),
                        'description' => __('Search on input change, no buttons needed', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                    ]
            );
            $this->add_control(
                    'search_find_text',
                    [
                        'label' => __('Find Text', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Find', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'search_quick' => '',
                        ]
                    ]
            );
            $this->add_control(
                    'search_reset',
                    [
                        'label' => __('Use Reset button', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'condition' => [
                            'search_quick' => '',
                        ]
                    ]
            );
            $this->add_control(
                    'search_reset_text',
                    [
                        'label' => __('Reset Text', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Reset', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'search_quick' => '',
                            'search_reset!' => ''
                        ]
                    ]
            );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_file_metadata',
                [
                    'label' => __('Metadata', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'enable_metadata!' => '',
                    ],
                ]
        );
        $this->add_control(
                'extension', [
            'label' => __('Show file extension', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_size', [
            'label' => __('Show file size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_hits', [
            'label' => __('Add a download counter for statistics', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'enable_metadata_description', [
            'label' => __('Add description to files', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_wp_description', [
            'label' => __('Use WP Caption', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Use Media Caption as description if file is managed by native WP interface', 'dynamic-content-for-elementor'),
            'condition' => [
                'enable_metadata' => 'yes',
                'enable_metadata_description' => 'yes'
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_custom_title', [
            'label' => __('Set custom title to files and folders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_wp_title', [
            'label' => __('Use WP Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Use Media Title if file is managed by native WP interface', 'dynamic-content-for-elementor'),
            'condition' => [
                'enable_metadata' => 'yes',
                'enable_metadata_custom_title' => 'yes'
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_hide', [
            'label' => __('Hide some files and folders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("You can select what file and folder hide if you don't want to share it", 'dynamic-content-for-elementor'),
            'condition' => [
                'enable_metadata' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'enable_metadata_hide_reverse', [
            'label' => __('Invert: show only selected files and folders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("If enabled you select the exact file and folder to show, all other file and folders will be hidden", 'dynamic-content-for-elementor'),
            'condition' => [
                'enable_metadata' => 'yes',
                'enable_metadata_hide' => 'yes',
            ],
                ]
        );
        $this->end_controls_section();

        
        //////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////  STYLE  ///////////////////////////
        // ++++++++++++++++++++++ Title ++++++++++++++++++++++

        $this->start_controls_section(
                'section_style_title',
                [
                    'label' => __('Title', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'title' => 'yes',
                    ],
                ]
        );
        
        $this->add_responsive_control(
            'title_align',
            [
                    'label' => __( 'Alignment', 'elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                            'left' => [
                                    'title' => __( 'Left', 'elementor' ),
                                    'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                    'title' => __( 'Center', 'elementor' ),
                                    'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                    'title' => __( 'Right', 'elementor' ),
                                    'icon' => 'eicon-text-align-right',
                            ],
                            'justify' => [
                                    'title' => __( 'Justified', 'elementor' ),
                                    'icon' => 'eicon-text-align-justify',
                            ],
                    ],
                    'default' => '',
                    'selectors' => [
                            '{{WRAPPER}} .dce-filebrowser-title' => 'text-align: {{VALUE}};',
                    ],
            ]
    );

        $this->add_control(
                'title_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-filebrowser-title' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-filebrowser-title',
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'title_text_shadow',
                    'selector' => '{{WRAPPER}} .dce-filebrowser-title',
                ]
        );
        $this->add_control(
                'title_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-filebrowser-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        // ++++++++++++++++++++++ Folders ++++++++++++++++++++++
        $this->start_controls_section(
                'section_style_folders',
                [
                    'label' => __('Folders', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );


        $this->add_control(
                'foldername_color',
                [
                    'label' => __('Name Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a .dce-dir-title' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'foldername_color_hover',
                [
                    'label' => __('Name Hover Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a:hover .dce-dir-title' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'foldername_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} a .dce-dir-title',
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'foldername_text_shadow',
                    'selector' => '{{WRAPPER}} a .dce-dir-title',
                ]
        );
        // Border Separator ----------------
        $this->add_control(
                'heading_folders_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'folder_border_type',
                [
                    'label' => __('Border type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __('Solid', 'dynamic-content-for-elementor'),
                        'dashed' => __('Dashed', 'dynamic-content-for-elementor'),
                        'dotted' => __('Dotted', 'dynamic-content-for-elementor'),
                        'double' => __('Double', 'dynamic-content-for-elementor'),
                        'none' => __('None', 'dynamic-content-for-elementor'),
                    ],
                    //'separator' => 'before',
                    'selectors' => [// You can use the selected value in an auto-generated css rule.
                        '{{WRAPPER}} .dce-list li.dir' => 'border-bottom-style: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'folder_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'folder_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir' => 'border-bottom-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'folder_border_stroke',
                [
                    'label' => __('Border weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'condition' => [
                        'folder_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        // Folders space ---------------------
        $this->add_control(
                'folder_list_space',
                [
                    'label' => __('Row Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 3,
                            'max' => 100,
                        ],
                    ],
                    'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'folder_list_padding', [
            'label' => __('Space around', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .dce-list-root' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        // ICONS  ---------------------
        $this->add_control(
                'heading_folders_icon',
                [
                    'label' => __('Icons', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'folder_icon_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 40,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 180,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .fiv-icon-folder' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'folder_icon_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .fiv-icon-folder' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_HSB::get_type(),
                [
                    'name' => 'icon_hue_filters',
                    'label' => 'Color (HSB)',
                    'selector' => '{{WRAPPER}} .dce-list .fiv-icon-folder',
                ]
        );
        $this->end_controls_section();

        // ++++++++++++++++++++++ SUB Folders ++++++++++++++++++++++
        $this->start_controls_section(
                'section_style_subfolders',
                [
                    'label' => __('Sub Folders', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );


        $this->add_control(
                'subfoldername_color',
                [
                    'label' => __('Name Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} li.dir li.dir a .dce-dir-title' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'subfoldername_color_hover',
                [
                    'label' => __('Name Hover Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} li.dir li.dir a:hover .dce-dir-title' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'subfoldername_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} li.dir li.dir a .dce-dir-title',
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'subfoldername_text_shadow',
                    'selector' => '{{WRAPPER}} li.dir li.dir a .dce-dir-title',
                ]
        );
        // Border Separator ----------------
        $this->add_control(
                'heading_subfolders_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'subfolder_border_type',
                [
                    'label' => __('Border type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __('Solid', 'dynamic-content-for-elementor'),
                        'dashed' => __('Dashed', 'dynamic-content-for-elementor'),
                        'dotted' => __('Dotted', 'dynamic-content-for-elementor'),
                        'double' => __('Double', 'dynamic-content-for-elementor'),
                        'none' => __('None', 'dynamic-content-for-elementor'),
                    ],
                    //'separator' => 'before',
                    'selectors' => [// You can use the selected value in an auto-generated css rule.
                        '{{WRAPPER}} .dce-list li.dir li.dir' => 'border-bottom-style: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'subfolder_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'subfolder_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir li.dir' => 'border-bottom-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'subfolder_border_stroke',
                [
                    'label' => __('Border weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'condition' => [
                        'subfolder_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir li.dir' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        // subFolders space ---------------------
        $this->add_control(
                'subfolder_list_space',
                [
                    'label' => __('Row Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 3,
                            'max' => 100,
                        ],
                    ],
                    'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir li.dir' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        // ICONS  ---------------------
        $this->add_control(
                'heading_subfolders_icon',
                [
                    'label' => __('Icons', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'subfolder_icon_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 40,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 180,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir li.dir .fiv-icon-folder' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'subfolder_icon_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.dir li.dir .fiv-icon-subfolder' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_HSB::get_type(),
                [
                    'name' => 'subf_icon_hue_filters',
                    'label' => 'Color (HSB)',
                    'selector' => '{{WRAPPER}} .dce-list li.dir li.dir .fiv-icon-folder',
                ]
        );
        $this->end_controls_section();

        // ++++++++++++++++++++++ Sub Folders ++++++++++++++++++++++
        $this->start_controls_section(
                'section_style_files',
                [
                    'label' => __('Files', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'filename_color',
                [
                    'label' => __('Name Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.dce-file-download' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'filename_color_hover',
                [
                    'label' => __('Name Hover Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                    //'show_childlist' => '1',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.dce-file-download:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'filename_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} a .dce-file-title',
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'filename_text_shadow',
                    'selector' => '{{WRAPPER}} a .dce-file-title',
                ]
        );
        // --------------------- Border Separator ---------------------
        $this->add_control(
                'heading_files_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'file_border_type',
                [
                    'label' => __('Border type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __('Solid', 'dynamic-content-for-elementor'),
                        'dashed' => __('Dashed', 'dynamic-content-for-elementor'),
                        'dotted' => __('Dotted', 'dynamic-content-for-elementor'),
                        'double' => __('Double', 'dynamic-content-for-elementor'),
                        'none' => __('None', 'dynamic-content-for-elementor'),
                    ],
                    //'separator' => 'before',
                    'selectors' => [// You can use the selected value in an auto-generated css rule.
                        '{{WRAPPER}} .dce-list li.file' => 'border-bottom-style: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'file_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'file_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.file' => 'border-bottom-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'file_border_stroke',
                [
                    'label' => __('Border weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'condition' => [
                        'file_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.file' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        // Files space ---------------------
        $this->add_control(
                'file_list_space',
                [
                    'label' => __('Row Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 3,
                            'max' => 100,
                        ],
                    ],
                    'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list li.file' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'heading_files_icon',
                [
                    'label' => __('Icons', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'file_icon_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 40,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 180,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-download .fiv-viv' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .dce-list .dce-file-download .dce-img-icon' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                        '{{WRAPPER}} .dce-list .dce-file-description' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'file_icon_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-download .fiv-viv' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .dce-list .dce-file-download .dce-img-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_HSB::get_type(),
                [
                    'name' => 'fileicon_hue_filters',
                    'label' => 'Color (HSB)',
                    'selector' => '{{WRAPPER}} .dce-list .dce-file-download .fiv-viv',
                ]
        );




        $this->add_control(
                'heading_files_size',
                [
                    'label' => __('Label Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'enable_metadata_size' => 'yes',
                    ],
                ]
        );
        $this->add_control(
                'filesizes_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'enable_metadata_size' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-download .dce-file-size-label' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'filesize_icon_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 180,
                        ],
                    ],
                    'condition' => [
                        'enable_metadata_size' => 'yes',
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-download .dce-file-size-label' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        // dce-file-size-label
        $this->add_control(
                'heading_files_hits',
                [
                    'label' => __('Label Hits', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'enable_metadata_hits' => 'yes',
                    ],
                ]
        );
        $this->add_control(
                'filehits_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'enable_metadata_hits' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-hits-label' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'filehits_icon_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 180,
                        ],
                    ],
                    'condition' => [
                        'enable_metadata_hits' => 'yes',
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-hits-label' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'filehits_icon_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 180,
                        ],
                    ],
                    'condition' => [
                        'enable_metadata_hits' => 'yes',
                    ],
                    //'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .dce-list .dce-file-hits-label' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        // dce-file-hits-label
        $this->end_controls_section();


        //////////////////////////////////////////////////////////////////////////////////////////////////
        // --------------------- SEARCH ---------------------
        $this->start_controls_section(
                'section_style_search',
                [
                    'label' => __('Search', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'search' => 'yes',
                    ],
                ]
        );

        $this->add_control(
                'align_search',
                [
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form' => 'text-align: {{VALUE}};',
                    ],
                ]
        );


        // Border ----------------
        $this->add_control(
                'heading_search_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        /* $this->add_group_control(
          Group_Control_Border::get_type(), [
          'name' => 'searchfield_border',
          'label' => __('Borders', 'dynamic-content-for-elementor'),
          'selector' => '{{WRAPPER}} .dce-file-search-form',

          ]
          ); */
        $this->add_control(
                'search_border_type',
                [
                    'label' => __('Border type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __('Solid', 'dynamic-content-for-elementor'),
                        'dashed' => __('Dashed', 'dynamic-content-for-elementor'),
                        'dotted' => __('Dotted', 'dynamic-content-for-elementor'),
                        'double' => __('Double', 'dynamic-content-for-elementor'),
                        'none' => __('None', 'dynamic-content-for-elementor'),
                    ],
                    //'separator' => 'before',
                    'selectors' => [// You can use the selected value in an auto-generated css rule.
                        '{{WRAPPER}} .dce-file-search-form' => 'border-style: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'search_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'search_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'search_border_stroke',
                [
                    'label' => __('Border weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'condition' => [
                        'search_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'search_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );


        // Background ----------------
        $this->add_control(
                'heading_search_background',
                [
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_search',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-file-search-form',
                ]
        );
        // Title ----------------
        $this->add_control(
                'heading_search_title',
                [
                    'label' => __('Title', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'search_text!' => '',
                    ],
                ]
        );
        $this->add_control(
                'search_title_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form-title' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'search_text!' => '',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'search_title_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-file-search-form-title',
            'condition' => [
                'search_text!' => '',
            ],
                ]
        );
        $this->add_control(
                'search_title_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-file-search-form-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'search_title_text_shadow',
                    'selector' => '{{WRAPPER}} .dce-file-search-form-title',
                    'condition' => [
                        'search_text!' => '',
                    ],
                ]
        );
        // Field ----------------
        $this->add_control(
                'heading_search_field',
                [
                    'label' => __('Field search', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'search_field_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} input.filetxt' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'search_field_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} input.filetxt' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'search_field_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} input.filetxt',
                ]
        );
        $this->add_control(
                'search_field_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} input.filetxt' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'search_field_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} input.filetxt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'search_field_border_type',
                [
                    'label' => __('Border type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __('Solid', 'dynamic-content-for-elementor'),
                        'dashed' => __('Dashed', 'dynamic-content-for-elementor'),
                        'dotted' => __('Dotted', 'dynamic-content-for-elementor'),
                        'double' => __('Double', 'dynamic-content-for-elementor'),
                        'none' => __('None', 'dynamic-content-for-elementor'),
                    ],
                    //'separator' => 'before',
                    'selectors' => [// You can use the selected value in an auto-generated css rule.
                        '{{WRAPPER}} input.filetxt' => 'border-style: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'search_field_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'condition' => [
                        'search_field_border_type!' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} input.filetxt' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'search_field_border_stroke', [
            'label' => __('Borders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => '',
                'right' => '',
                'bottom' => '',
                'left' => '',
                'isLinked' => true,
            ],
            'condition' => [
                'search_field_border_type!' => 'none',
            ],
            'selectors' => [
                '{{WRAPPER}} input.filetxt' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'search_field_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} input.filetxt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'search_field_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} input.filetxt',
                ]
        );
        // Description ----------------
        $this->add_control(
                'heading_desc_field',
                [
                    'label' => __('Small description', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'search_desc_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-desc small' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'search_desc_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-search-desc small',
                ]
        );
        // Buttons ----------------
        $this->add_control(
                'heading_search_buttons',
                [
                    'label' => __('Buttons', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'buttons_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-search-buttons input',
                ]
        );
        $this->add_control(
                'buttons_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'buttons_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-search-buttons input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'buttons_border_stroke', [
            'label' => __('Borders', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => '',
                'right' => '',
                'bottom' => '',
                'left' => '',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-search-buttons input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'buttons_v_space',
                [
                    'label' => __('Verical Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'buttons_h_space',
                [
                    'label' => __('Horizontal Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        // Button Reset ----------------
        $this->add_control(
                'heading_search_buttonReset',
                [
                    'label' => __('Button Reset', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => ['search_reset!' => ''],
                ]
        );
        $this->add_control(
                'buttonreset_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_txcolor_hover',
                [
                    'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_bgcolor_hover',
                [
                    'label' => __('Hover Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_border_color_hover',
                [
                    'label' => __('Hover Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ['search_reset!' => ''],
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        // Button Find ----------------
        $this->add_control(
                'heading_search_buttonFind',
                [
                    'label' => __('Button Find', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'buttonfind_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.find' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.find' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.find' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_txcolor_hover',
                [
                    'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.find:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_bgcolor_hover',
                [
                    'label' => __('Hover Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.find:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_border_color_hover',
                [
                    'label' => __('Hover Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .dce-search-buttons input.reset:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $baseDir = false;
        $files = $dirs = array();
        switch ($settings['path_selection']) {
            case 'custom':
                $baseDir = $settings['folder_custom'];
                $tmpTit = explode('/', $baseDir);
                $baseTitle = end($tmpTit);
                break;
            case 'csv':
                $baseDir = $settings['folder_custom'];
                $pieces = explode('.', $settings['folder_csv']);
                $ext = end($pieces); 
                $baseTitle = basename($settings['folder_csv'], '.'.$ext);
                break;
            case 'uploads':
                $baseDir = $settings['folder'];
                $baseTitle = $settings['folder'];
                if ($settings['subfolder_' . $settings['folder']]) {
                    $baseDir .= $settings['subfolder_' . $settings['folder']];
                    if ($settings['subfolder_' . $settings['folder']] != '/') {
                        $tmpTit = explode('/', $settings['subfolder_' . $settings['folder']]);
                        $baseTitle = end($tmpTit);
                    }
                }
                break;
            case 'media':
                $baseTitle = false;
                $baseDir = 'wp-content/uploads';
                if ($settings['medias_field']) {
                    $medias = get_post_meta(get_the_ID(), $settings['medias_field'], true);
                } else {
                    $medias = $settings['medias'];
                }
                $src_identifier = 'http';
                //$src_identifier = 'http';
                $tmp = explode($src_identifier, $medias);
                //var_dump(ABSPATH);                
                foreach ($tmp as $fkey => $afile) {
                    if ($fkey) {
                        list($furl, $other) = explode('"', $afile, 2);
                        $resized = DCE_Helper::is_resized_image($furl);
                        if ($resized) {
                            $furl = $resized;
                        }
                        list($other, $fpath) = explode($baseDir, $furl, 2);
                        $files[] = substr($fpath, 1);
                    }
                }
                array_filter($files);
                $files = array_unique($files);
                if (empty($files)) {
                    $baseDir = false;
                }
                //var_dump($files);
                /* foreach ( $files as $afile ) {
                  echo $afile . '<br>';
                  } */
                break;
            case 'taxonomy':
                $baseTitle = false;
                $baseDir = 'wp-content/uploads';
                if ($settings['taxonomy']) {
                    $term_id = intval($settings['terms_'.$settings['taxonomy']]);
                    if ($term_id) {
                        $taxonomy = get_taxonomy($settings['taxonomy']);
                        if ($taxonomy) {
                            $baseTitle = $taxonomy->label;                    
                            if ($term_id) {
                                $term = get_term_by('term_taxonomy_id', $term_id);
                                $baseTitle = $term->name;
                            }
                        }                    
                        $medias = DCE_Helper::get_term_posts( $term_id, 'attachment' );
                        //var_dump($medias);
                        if (!empty($medias)) {
                            foreach ($medias as $amedia) {
                                list($other, $fpath) = explode($baseDir, $amedia->guid, 2);
                                $files[] = substr($fpath, 1);
                            }
                        }                        
                    }
                }
                // TODO - subfolder                
                array_filter($files);
                if (empty($files)) {
                    $baseDir = false;
                }
                break;
            case 'meta':
                $baseTitle = false;
                $meta_key = $settings['metas'];
                if (!empty($files)) {
                    $baseDir = 'wp-content/uploads';
                }
                break;
            case 'post':
                $baseTitle = get_the_title();
                $baseDir = 'wp-content/uploads';
                $medias = get_attached_media( '', get_the_ID() );
                if (!empty($medias)) {
                    foreach ($medias as $amedia) {
                        list($other, $fpath) = explode($baseDir, $amedia->guid, 2);
                        $files[] = substr($fpath, 1);
                    }
                }
                array_filter($files);
                if (empty($files)) {
                    $baseDir = false;
                }
        }        
        //var_dump($baseDir); return false;

        if ($baseDir) {

            //var_dump($this->getRootDir($baseDir, $settings));
            if (is_dir($this->getRootDir($baseDir, $settings))) {
                
                if (isset($settings['enable_metadata']) && $settings['enable_metadata']) {
                    $this->file_metadata = get_option('dce-file-browser', array());
                }

                if (isset($settings['search']) && $settings['search']) {
                    $this->displayFileSearch($settings);
                }

                if (isset($settings['title']) && $settings['title'] && $baseTitle) {
                    echo '<'.$settings['title_size'].' class="dce-filebrowser-title">' . $baseTitle . '</'.$settings['title_size'].'>';
                }
                echo '<ul class="list-unstyled dce-list dce-list-root"';
                if (isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_hide']) && $settings['enable_metadata_hide'] && $settings['enable_metadata_hide_reverse']) {
                    echo ' data-hide-reverse="1"';
                }
                echo '>';

                /*if ($settings['path_selection'] == 'taxonomy') {
                    if ($settings['taxonomy']) {
                        $term_id = intval($settings['terms_'.$settings['taxonomy']]);
                        if ($term_id) {
                            $term = get_term_by('term_taxonomy_id', $term_id);
                            $term_children = get_term_children($term_id, $settings['taxonomy']);
                            $term_medias = get_posts(array(
                                'post_type' => 'attachment',
                                'numberposts' => -1,
                                'tax_query' => array(
                                  array(
                                    'taxonomy' => $settings['taxonomy'],
                                    'field' => 'id',
                                    'terms' => $term_id,
                                    'include_children' => false
                                  )
                                )
                              ));
                            foreach($term_medias as $afile) {
                                $files[$afile->post_name] = $afile->post_title; // $afile->post_guid;
                                //$files[$afile->post_guid] = $afile->post_title; // $afile->post_guid;
                            }
                            foreach($term_children as $aterm_id) 
                                $aterm = get_term_by('term_taxonomy_id', $aterm_id);{
                                //var_dump($aterm);
                                $dirs[$aterm->aterm_id] = $aterm->name;
                            }
                            //var_dump($dirs)
                        }
                    }
                    if (!$dirs && !$files) {
                        return false;
                    }
                }*/

                if ($settings['path_selection'] == 'csv') {
                    if ($settings['folder_csv']) {
                        $row_file = $settings['folder_csv_col_file'] - 1;
                        $row_path = $settings['folder_csv_col_dir'] - 1;
                        $csv_path = ABSPATH.$settings['folder_csv'];
                        if (file_exists($csv_path)) {
                            $csv = file($csv_path);
                            if (!empty($csv)) {
                                $hide = true;
                                foreach($csv as $ckey => $arow) {
                                    if ($settings['folder_csv_header'] && !$ckey) {
                                        // header row
                                    } else {
                                        $cols = explode(';', $arow);                                   
                                        if (isset($cols[$row_path])) {
                                            $chunk = explode($settings['folder_csv_separator'], $cols[$row_path]);
                                            $chunk = array_map('trim', $chunk);
                                            $chunk = array_filter($chunk);                                            
                                            $filedir = implode($settings['folder_csv_separator'], $chunk);
                                            if (isset($cols[$row_file])) {
                                                if ($row_file == $row_path) {
                                                    $filename = array_pop($chunk);
                                                } else {
                                                    $filename = $cols[$row_file];
                                                }
                                                $filename = trim(basename($filename));
                                                
                                                $last = end($chunk);
                                                if ($filename && $filename != 'NULL' && $last) {
                                                    //$pezzi = explode('.', $last);
                                                    //if (count($pezzi) > 1) {
                                                        $filepath = $baseDir.DIRECTORY_SEPARATOR.$filename;
                                                        //echo $filepath;
                                                        if (file_exists($filepath)) {
                                                            $files[$filename] = $last;
                                                        }
                                                    //}
                                                }                                                
                                                if (array_key_exists($filename, $files)) {
                                                    $tmp = array();
                                                    $filename_title = $filename;
                                                    if ($settings['folder_csv_title']) {
                                                        $filename_title = array_pop($chunk);
                                                    }
                                                    if (empty($chunk)) {
                                                        if ($row_file == $row_path) {
                                                            if ($settings['folder_csv_title']) {
                                                                $chunk[] = $files[$filename];
                                                            }
                                                        }
                                                    }
                                                    if (!empty($chunk)) {
                                                        foreach($chunk as $kkey => $cnk) {
                                                            $tmp[] = $cnk;
                                                            $arr_key = '["'.implode('"]["', $tmp).'"]';
                                                            $eval = 'if (!isset($dirs'.$arr_key.')) { $dirs'.$arr_key.' = array(); }';
                                                            eval($eval);
                                                            if ($kkey == count($chunk)-1) {
                                                                $dkey = '';
                                                                if ($row_file == $row_path) {
                                                                    if ($settings['folder_csv_title']) {
                                                                        $folder_csv = explode($settings['folder_csv_separator'], $settings['folder_csv_filter']);
                                                                        if ($filename_title == end($folder_csv)) {
                                                                            $dkey = '"'.$filename_title.'"';
                                                                        }
                                                                    }
                                                                }
                                                                $eval = '$dirs'.$arr_key.'['.$dkey.'] = array("'.$filename_title.'" => "'.$filename.'");';
                                                                eval($eval);
                                                            }
                                                            
                                                        }
                                                        //var_dump($dirs);
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }

                        if ($settings['folder_csv_filter']) {
                            $folder_csv = explode($settings['folder_csv_separator'], $settings['folder_csv_filter']);
                            $arr_key = '["'.implode('"]["', $folder_csv).'"]';
                            $eval = 'if (isset($dirs'.$arr_key.')) { if (is_array($dirs'.$arr_key.')) { $dirs = $dirs'.$arr_key.'; } else { $dirs = array($dirs'.$arr_key.'); } } else { $dirs = false; $files = false; }';
                            eval($eval);
                            if (!$dirs && !$files) {
                                return false;
                            }
                        }
                    }
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode() && empty($dirs)) {
                        _e('Empty CSV folder', 'dynamic-content-for-elementor');
                        return false;
                    }
                }
                //echo $this->getRootDir($baseDir, $settings); return false;
                //echo '<pre>';var_dump($dirs);echo '</pre>'; return false;
                //echo '<pre>';var_dump($files);echo '</pre>'; return false;
                    $this->dirToHtml($this->getRootDir($baseDir, $settings), null, $files, $dirs);
                echo '</ul>';
                
                $this->editorJavascript();
            } else {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    _e('Root folder not found', 'dynamic-content-for-elementor');
                }
            }
        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                _e('Select root folder or files', 'dynamic-content-for-elementor');
            }
        }
    }

    public static function agetRootDir($folder = false) {
        $dir = wp_upload_dir();
        $dir = $dir["basedir"];
        if ($folder) {
            $dir = $dir . DIRECTORY_SEPARATOR . $folder;
        }
        return $dir;
    }

    public function getRootDir($folder = false, $settings = array()) {
        /* if (!$upload) {
          $settings = $this->get_settings_for_display();
          } */
        if (!isset($settings['path_selection']) || $settings['path_selection'] == 'uploads') {
            $dir = wp_upload_dir();
            $dir = $dir["basedir"];
            if ($folder) {
                $dir = $dir . DIRECTORY_SEPARATOR . $folder;
            }
        } else {
            //var_dump(ABSPATH);
            $dir = ABSPATH;
            if ($folder && $folder != DIRECTORY_SEPARATOR) {
                $dir .= $folder;
            }
        }
        return $dir;
    }

    public function getFolders($dir = null, $settings = array()) {
        if (!$dir) {
            $dir = $this->getRootDir($dir, $settings);
        }
        //var_dump($dir); die();
        $scanned_directory = array_diff(scandir($dir), array('..', '.'));
        //var_dump($scanned_directory); die();
        $ret = array();
        foreach ($scanned_directory as $key => $value) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $ret[$value] = basename($value);
            }
        }
        return $ret;
    }

    public function getFoldersRic($dir, $hidden = false, $base = false) {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $plainName = str_replace($this->getRootDir(), '', $dir) . DIRECTORY_SEPARATOR . $value;
                    if ($base) {
                        $plainName = str_replace(DIRECTORY_SEPARATOR . $base, '', $plainName);
                    }
                    $result[$plainName] = $plainName;
                    $result = array_merge($result, $this->getFoldersRic($dir . DIRECTORY_SEPARATOR . $value, $hidden, $base));
                }
            }
        }
        return $result;
    }

    public function dirToHtml($dir, $hidden = false, $files = array(), $dirs = array()) {
        $image_exts = array('jpg', 'jpeg', 'jpe', 'gif', 'png');
        $settings = $this->get_settings_for_display();

        if (isset($settings['file_type']) && $settings['file_type']) {
            $file_type = strtolower($settings['file_type']);
            $file_type = str_replace(array('.', ','), ' ', $file_type);
            $extensions = explode(' ', $file_type);
            $extensions = array_filter($extensions);
        }

        //var_dump($dirs); return false;
        if (!empty($dirs) && is_array($dirs)) {
            $cdir = $dirs;
        }
        if ($settings['path_selection'] != 'csv') {
            if (!empty($files) && is_array($files)) {
                $cdir = $files;
            }
        }
        
        if (empty($cdir)) {
            $cdir = scandir($dir, isset($settings['order']) ? $settings['order'] : null);
        }

        /* if ($dir == '/') {
          if (empty($cdir)) {
          return false;
          }
          } */

        foreach ($cdir as $key => $value) {
            if (!is_array($value) && substr($value, 0, 1) == '.') { // hidden file
                continue;
            }
            $title = false;
            if (is_array($value)) {
                //var_dump($value); return false;
                $fulldir = $dir . DIRECTORY_SEPARATOR . $key;
                if ($settings['path_selection'] == 'csv') {    
                    if (count($value) == 1) {
                        //var_dump($key);
                        if (is_int($key)) {
                            $value = reset($value);
                            //var_dump($files); die();                        
                            if (!empty($files) && is_array($files)) {
                                //var_dump($value);
                                $filename = $value;
                                // TO FIX
                                if (is_array($filename)) {
                                    $filename = reset($filename);
                                    $value = $filename;
                                    if (is_array($filename)) {
                                        $keys = array_keys($filename);
                                        $filename = reset($filename);
                                    }
                                }
                                //$filename = basename($filename);
                                //var_dump($filename);
                                if (isset($files[$filename])) {
                                    $title = $files[$filename];
                                    //var_dump($title);
                                }
                            }
                            //$fulldir = $dir . DIRECTORY_SEPARATOR . $value;
                            //var_dump($filename);
                            
                            $fulldir = ABSPATH . $settings['folder_custom'] . DIRECTORY_SEPARATOR . $filename;
                        }
                    }
                    //$keys = array_keys($value);
                    //var_dump($keys);
                    /*if (is_int(reset($keys))) {
                        //var_dump(reset($keys));
                        $title = $key;
                        $value = $key;
                        $fulldir = $dir . DIRECTORY_SEPARATOR . $value;
                    }*/
                    
                }
            } else {
                if (substr($dir, -1, 1) == '/') {
                    $fulldir = $dir . $value;
                } else {
                    $fulldir = $dir . DIRECTORY_SEPARATOR . $value;
                }
            }

            $rdir = str_replace($this->getRootDir(null, $settings), '', $fulldir);
                
                if (is_array($value) || is_dir($fulldir)) {
                    $hide = false;
                    $kdir = sanitize_file_name($rdir);
                    //var_dump($kdir);
                    if (isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_hide']) && $settings['enable_metadata_hide']) {
                        $hide = $this->get_dir_meta($kdir, 'hidden');
                    }
                    if (isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_hide']) && $settings['enable_metadata_hide'] && isset($settings['enable_metadata_hide_reverse']) && $settings['enable_metadata_hide_reverse']) {
                        $hide = !$hide;
                    }

                    if (!DCE_Helper::is_empty_dir($fulldir) || $settings['empty']) {
                        if (!$hide || \Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $hideHtml = '';
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                if (isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_hide']) && $settings['enable_metadata_hide']) {
                                    $hid = 'dce-dir-hide-' . $kdir;
                                    if ($hide) {
                                        $hideHtml = '<a id="' . $hid . '" class="btn btn-xs btn-secondary pull-left dce-dir-hide" href="#" data-dir="' . $kdir . '"><span class="dashicons dashicons-hidden"></span></a>';
                                    } else {
                                        $hideHtml = '<a id="' . $hid . '" class="btn btn-xs btn-secondary pull-left dce-dir-hide" href="#" data-dir="' . $kdir . '"><span class="dashicons dashicons-visibility"></span></a>';
                                    }
                                }
                            }
                            $customTitle = 0;
                            if (!$title) {
                                if (is_array($value)) {
                                    $title = $key;
                                } else {
                                    if (\Elementor\Plugin::$instance->editor->is_edit_mode() && isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_title']) && $settings['enable_metadata_custom_title']) {
                                        $customTitle = 1;
                                    }
                                    if (isset($settings['enable_metadata_custom_title']) && $settings['enable_metadata_custom_title']) {
                                        $title = $this->get_dir_meta($kdir, 'title', $value);
                                    } else {
                                        $title = $value;
                                    }
                                }
                            }
                            ?>
                            <li class="dir">
                            <?php echo $hideHtml; ?>
                                <a class="<?php echo ($customTitle ? 'inline-' : ''); ?>block folder-dir" data-toggle="collapse" id="<?php echo $kdir; ?>" data-target="#<?php echo $kdir; ?>-ul" href="#<?php echo $kdir; ?>" onClick="jQuery(this).siblings('ul').slideToggle(); return false;">
                                    <span class="middle fiv-viv fiv-icon-folder"></span>
                                    <!--<img class="hidden dce-file-icon" alt="Icon" src="'.DCE_URL.'/assets/lib/file-icon/icons/vivid/folder.svg" width="56" height="56">-->
                            <?php if ($customTitle) { ?>
                                    </a> <input type="text" class="dce-dir-title" data-dir="<?php echo $kdir; ?>" name="dce-dir-browser[<?php echo $kdir; ?>][title]" value="<?php echo $title; ?>" /> <a class="inline-block" href="<?php echo DCE_Helper::path_to_url($fulldir); ?>" target="_blank">
                            <?php } else { ?>
                                        <strong class="dce-dir-title"><?php echo $title; ?></strong>
                            <?php } ?>
                                </a>
                                <ul class="dce-hidden collapse list-unstyled dce-list" id="#<?php echo $kdir; ?>-ul">
                                    <?php
                                    echo $this->dirToHtml($fulldir, null, $files, $value); 
                                    ?>
                                </ul>
                            </li>
                                <?php
                            }
                        }
                    } else {
                        
                        $filename = basename($value);
                        $pezzi = explode('.', $filename);
                        $ext = strtolower(end($pezzi));

                        if ($settings['path_selection'] == 'csv') {  
                            if (isset($files[$filename])) {
                                $title = $files[$filename];
                            }
                        }
                        

                        if (!empty($extensions)) {
                            if (isset($settings['file_type_show'])) {
                                if ($settings['file_type_show']) {
                                    if (!in_array($ext, $extensions)) {
                                        continue;
                                    }
                                } else {
                                    if (in_array($ext, $extensions)) {
                                        continue;
                                    }
                                }
                            }
                        }

                        if (in_array($ext, $image_exts)) {
                            $is_resized = DCE_Helper::is_resized_image($value); // ora controllo se è una immagine resized, quindi del seguente tipo: "nome-123x123.ext"
                            if ($is_resized) {
                                if ($settings['path_selection'] == 'media') {
                                    $value = $is_resized;
                                    //echo $value;
                                    $fulldir = $dir . DIRECTORY_SEPARATOR . $value;
                                    //echo $fulldir;
                                    $rdir = str_replace($this->getRootDir(null, $settings), '', $fulldir);
                                    //echo $rdir;
                                } else {
                                    if (!$settings['resized']) {
                                        continue;
                                    }
                                }
                            }
                        }



                        $md5 = md5($fulldir);
                        $post_id = 0;
                        // verifico se è una file caricato tramite wp media                        
                        /*$meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key=%s AND meta_value=%s", '_wp_attached_file', substr($rdir, 1)));
                        if (!empty($meta)) {
                            $meta = reset($meta);
                            //echo $rdir; var_dump($meta);
                            $post_id = $meta->post_id;
                            //echo $post_id;
                        }*/
                        //echo $rdir;
                        if ($settings['enable_metadata']) {
                            $post_id = DCE_Helper::get_image_id($rdir);
                        }

                        $hide = false;
                        if ($settings['enable_metadata'] && $settings['enable_metadata_hide']) {
                            $hide = $this->get_file_meta(($post_id ? $post_id : $md5), 'hidden');
                        }
                        if ($settings['enable_metadata'] && $settings['enable_metadata_hide'] && $settings['enable_metadata_hide_reverse']) {
                            $hide = !$hide;
                        }

                        /*if ($settings['path_selection'] == 'csv') {
                            if ($settings['folder_csv']) {
                                $csv_path = $settings['folder_csv'];
                                //var_dump($csv_path);
                                if (file_exists($csv_path)) {
                                    $csv = file($csv_path);
                                    //var_dump($csv);
                                    if (!empty($csv)) {
                                        $hide = true;
                                        foreach($csv as $arow) {
                                            $row_file = $settings['folder_csv_col_file'] - 1;
                                            if (isset($arow[$row_file])) {
                                                if ($arow[$row_file] == $filename) {
                                                    $hide = false;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }*/

                        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || !$hide) {

                            if (file_exists(DCE_PATH . '/assets/lib/file-icon/icons/vivid/' . $ext . '.svg')) {
                                $icon = DCE_URL . '/assets/lib/file-icon/icons/vivid/' . $ext . '.svg';
                            } else {
                                $icon = DCE_URL . '/assets/lib/file-icon/icons/vivid/unknown.svg';
                            }
                            if (!file_exists(DCE_PATH . '/assets/lib/file-icon/icons/vivid/' . $ext . '.svg')) {
                                $ext = 'blank';
                            }

                            echo '<li class="file ext-' . $ext . '">';



                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                if (isset($settings['enable_metadata']) && $settings['enable_metadata'] && isset($settings['enable_metadata_hide']) && $settings['enable_metadata_hide']) {
                                    $hid = 'dce-file-hide-' . $md5;
                                    if ($hide) {
                                        echo '<a id="' . $hid . '" class="btn btn-xs btn-secondary pull-left dce-file-hide" href="#" data-md5="' . $md5 . '"' . ($post_id ? ' data-post-id="' . $post_id . '"' : '') . '><span class="dashicons dashicons-hidden"></span></a>';
                                    } else {
                                        echo '<a id="' . $hid . '" class="btn btn-xs btn-secondary pull-left dce-file-hide" href="#" data-md5="' . $md5 . '"' . ($post_id ? ' data-post-id="' . $post_id . '"' : '') . '><span class="dashicons dashicons-visibility"></span></a>';
                                    }
                                }
                            }

                            $customTitle = 0;
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['enable_metadata'] && $settings['enable_metadata_custom_title']) {
                                $customTitle = 1;
                            }
                            echo '<a class="' . ($customTitle ? 'inline-' : '') . 'block btn-block dce-file-download" href="' . DCE_Helper::path_to_url($fulldir) . '"  data-md5="' . $md5 . '"' . ($post_id ? ' data-post-id="' . $post_id . '"' : '') . ' target="_blank">';

                            if (!empty($settings['img_icon']) && in_array($ext, $image_exts) && $post_id) {
                                if ($post_id) {
                                    //echo wp_get_attachment_image($post_id, array(100, 100), true, array('class' => 'middle dce-img-icon'));
                                    echo wp_get_attachment_image($post_id, 'thumbnail', true, array('class' => 'middle dce-img-icon'));
                                } else {
                                    // TODO: img preview for non media
                                }
                            } else {
                                echo '<span class="middle fiv-viv fiv-icon-' . $ext . '"></span>'; // <img class="hidden dce-file-icon" alt='.__('File Icon', 'dynamic-content-for-elementor').'" src="'.$icon.'" width="56" height="56">';
                            }
                            echo '<span class="dce-file-text">';
                            if (!$settings['extension']) {
                                $value = substr($value, 0, -(strlen($ext) + 1));
                            }
                            if ($settings['enable_metadata_custom_title']) {
                                if ($settings['enable_metadata_wp_title'] && $post_id) {
                                    $title = get_the_title($post_id);
                                } else {
                                    $title = $this->get_file_meta(($post_id ? $post_id : $md5), 'title', $value);
                                }
                            } else {
                                if (!$title) {
                                    $title = basename($value);
                                }
                            }
                            if ($customTitle) {
                                echo '</a>';
                                if (!empty($settings['enable_metadata']) && $settings['enable_metadata_wp_title'] && $post_id) {
                                    echo '<strong class="dce-file-title"><a target="_blank" onclick="window.open(jQuery(this).attr(\'href\'));" href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit"><span class="dashicons dashicons-edit" style="vertical-align: middle;"></span> ' . $title . '</a></strong>';
                                } else {
                                    echo '<input type="text" class="dce-file-title" data-md5="' . $md5 . '"' . ($post_id ? ' data-post-id="' . $post_id . '"' : '') . ' name="dce-file-browser[' . $md5 . '][title]" value="' . $title . '" />';
                                }
                                echo '<a class="inline-block" href="' . DCE_Helper::path_to_url($fulldir) . '" target="_blank">';
                            } else {
                                echo '<strong class="dce-file-title">' . $title . '</strong>';
                            }

                            if (!empty($settings['enable_metadata']) && $settings['enable_metadata_size']) {
                                echo ' <small class="label label-default dce-file-size-label">(' . $this->readableFilesize(filesize($fulldir), 0) . ')</small>';
                            }

                            if (!empty($settings['enable_metadata']) && $settings['enable_metadata_hits']) {
                                echo ' <small class="label label-default dce-file-hits-label"><i class="fa fa-download" aria-hidden="true"></i> <b>' . $this->get_file_meta(($post_id ? $post_id : $md5), 'hits', 0) . '</b></small>';
                            }

                            echo '</span>';
                            echo '</a>';

                            if (!empty($settings['enable_metadata'])) {
                                if (isset($settings['enable_metadata_description']) && $settings['enable_metadata_description']) {
                                    if ($settings['enable_metadata_wp_description'] && $post_id) {
                                        $description = wp_get_attachment_caption($post_id);
                                    } else {
                                        $description = $this->get_file_meta(($post_id ? $post_id : $md5), 'description');
                                    }
                                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                        if ($settings['enable_metadata_wp_description'] && $post_id) {
                                            echo '<div class="dce-file-description block"><a target="_blank" onclick="window.open(jQuery(this).attr(\'href\'));" href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit"><span class="dashicons dashicons-edit"></span> ' . ($description ? $description : '<span class="dce-empty-capton">' . __('Edit caption', 'dynamic-content-for-elementor') . '</span>') . '</a></div>';
                                        } else {
                                            echo '<textarea class="dce-file-description block" data-md5="' . $md5 . '"' . ($post_id ? ' data-post-id="' . $post_id . '"' : '') . ' name="dce-file-browser[' . $md5 . '][description]">' . $description . '</textarea>';
                                        }
                                    } else {
                                        if (trim($description)) {
                                            echo '<div class="dce-file-description block">' . $description . '</div>';
                                        }
                                    }
                                }
                            }

                            echo '</li>';
                        }
                    }
                }
            
            return '';
        }
        
        public function readableFilesize($size, $precision = 2, $space = '') {
            if( $size <= 0 ) {
                return '0' . $space . 'KB';
            }
            if( $size === 1 ) {
                return '1' . $space . 'byte';
            }
            $mod = 1024;
            $units = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB');
            for( $i = 0; $size > $mod && $i < count($units) - 1; ++$i ) {
                $size /= $mod;
            }
            return round($size, $precision) . $space . $units[$i];
        }

        public function humanFilesize($bytes, $decimals = 2) {
            $sz = 'BKMGTP';
            $factor = floor((strlen($bytes) - 1) / 3);
            return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
        }

        public function get_file_meta($file_id, $meta = '', $fallback = '') {
            if (is_numeric($file_id)) {
                $ret = get_post_meta($file_id, 'dce-file', true);
            } else {
                $ret = get_option('dce-file-' . $file_id);
            }
            if ($ret) {
                if (isset($ret[$meta]))
                    return $ret[$meta];
            }
            if (isset($this->file_metadata[$file_id])) {
                return $this->file_metadata[$file_id][$meta];
            }
            return $fallback;
        }

        public function get_dir_meta($dir_id, $meta = '', $fallback = '') {
            $ret = get_option('dce-dir-' . $dir_id);
            //var_dump($ret);
            if ($ret) {
                if (isset($ret[$meta]))
                    return $ret[$meta];
            }
            if (isset($this->file_metadata[$dir_id])) {
                return $this->file_metadata[$dir_id][$meta];
            }
            return $fallback;
        }

        public function plainDirToArray($dir) {
            $folders = DCE_Helper::dir_to_array($dir, false, false);
            return '/';
        }

        public function displayFileSearch($settings) {
            ?>
        <form action="" class="dce-file-search-form">
        <?php if ($settings['search_text'] != '') echo '<'.$settings['search_text_size'].' class="dce-file-search-form-title">' . __($settings['search_text'], 'dynamic-content-for-elementor') . '</'.$settings['search_text_size'].'>'; ?>
            <div class="form-control"><input type="text" class="filetxt" name="filetxt" value=""></div>
            <?php if ($settings['search_notice']) { ?><div class="dce-search-desc"><small><?php _e($settings['search_notice'], 'dynamic-content-for-elementor'); ?></small></div><?php } ?>
            <?php if (!$settings['search_quick']) { ?><div class="text-right dce-search-buttons"><?php if ($settings['search_reset']) { ?><input class="reset" type="reset" value="<?php echo $settings['search_reset_text']; ?>""> <?php } ?><input class="find" type="submit" value="<?php echo $settings['search_find_text']; ?>"></div><?php } ?>
        </form>
        <br />
        <?php
    }

    public function editorJavascript() {
        $settings = $this->get_settings_for_display();
        ?>
        <script type="text/javascript" >
            var lastHide = '';
            jQuery(document).ready(function () {
                if (typeof ajaxurl === 'undefined') {
                    var ajaxurl = "<?php echo admin_url('admin-ajax.php', 'relative'); ?>";
                }

            <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['enable_metadata']) {
                if ($settings['enable_metadata_description']) {
                    ?>
                        jQuery(document).on("change", ".dce-file-description", function () {
                            var data = {}
                            if (jQuery(this).attr("data-post-id")) {
                                data['action'] = "wpa_update_postmetas";
                                data['post_id'] = jQuery(this).attr("data-post-id");
                                data['dce-file'] = {'description': jQuery(this).val()};
                            } else {
                                data['action'] = "wpa_update_options";
                                data["dce-file-" + jQuery(this).attr("data-md5")] = {'description': jQuery(this).val()};
                            }
                            jQuery.post(ajaxurl, data, function (response) {
                                //alert("Got this from the server: " + response);
                            });
                        });
            <?php }
            if ($settings['enable_metadata_custom_title']) {
                ?>
                        jQuery(document).on("change", ".dce-file-title, .dce-dir-title", function () {
                            var data = {};
                            if (jQuery(this).hasClass('dce-file-title')) {
                                if (jQuery(this).attr("data-post-id")) {
                                    data['action'] = "wpa_update_postmetas";
                                    data['post_id'] = jQuery(this).attr("data-post-id");
                                    data['dce-file'] = {'title': jQuery(this).val()};
                                } else {
                                    data['action'] = "wpa_update_options";
                                    data["dce-file-" + jQuery(this).attr("data-md5")] = {'title': jQuery(this).val()};
                                }
                            }
                            if (jQuery(this).hasClass('dce-dir-title')) {
                                data['action'] = "wpa_update_options";
                                data["dce-dir-" + jQuery(this).attr("data-dir")] = {'title': jQuery(this).val()};
                            }
                            console.log(data);
                            jQuery.post(ajaxurl, data, function (response) {
                                //alert("Got this from the server: " + response);
                            });
                        });
            <?php }
            if ($settings['enable_metadata_hide']) {
                ?>
                        //jQuery(document).on("click",".dce-file-hide",function(event){
                        jQuery(document).on("click", ".dce-file-hide, .dce-dir-hide", function (event) {
                            console.log(jQuery(this).attr('data-stop'));
                            if (!jQuery(this).attr('data-stop')) {
                                var data = {};
                                var visible = '';
                                if (jQuery(this).children(".dashicons").hasClass('dashicons-hidden')) {
                                    jQuery(this).children(".dashicons").removeClass('dashicons-hidden').addClass('dashicons-visibility');
                                    visible = '';
                                } else {
                                    jQuery(this).children(".dashicons").removeClass('dashicons-visibility').addClass('dashicons-hidden');
                                    visible = 'hidden';
                                }
                                //alert(visible);
                                //alert(jQuery(this).closest('.dce-list').attr('data-hide-reverse'));
                                if (jQuery(this).closest('.dce-list-root').attr('data-hide-reverse')) {
                                    //alert(jQuery(this).closest('.dce-list').attr('data-hide-reverse'));
                                    if (visible != '') {
                                        visible = '';
                                    } else {
                                        visible = 'hidden';
                                    }
                                }
                                //alert(visible);
                                //alert( "clicked: " + event.target.nodeName );
                                if (jQuery(this).hasClass('dce-file-hide')) {
                                    if (jQuery(this).attr("data-post-id")) {
                                        data['action'] = "wpa_update_postmetas";
                                        data['post_id'] = jQuery(this).attr("data-post-id");
                                        data['dce-file'] = {'hidden': visible};
                                    } else {
                                        data['action'] = "wpa_update_options";
                                        data["dce-file-" + jQuery(this).attr("data-md5")] = {'hidden': visible};
                                    }
                                }
                                if (jQuery(this).hasClass('dce-dir-hide')) {
                                    data['action'] = "wpa_update_options";
                                    data["dce-dir-" + jQuery(this).attr("data-dir")] = {'hidden': visible};
                                }

                                console.log(data);
                                lastHide = jQuery(this).attr('id');
                                jQuery.post(ajaxurl, data, function (response) {
                                    //alert("Got this from the server: " + response);
                                    //alert(lastHide);
                                    jQuery('#' + lastHide).removeAttr('data-stop');

                                });
                                jQuery(this).attr('data-stop', 1);
                                return false;
                            }
                        });
            <?php }
        }
        ?>

        <?php if ($settings['search']) {                    
                if ($settings['search_quick']) { ?>
                    jQuery(".dce-file-search-form .filetxt").keyup(function (event) {
                        var dce_form = jQuery(this).closest(".dce-file-search-form");
                        if (jQuery(this).val().length > 2) {
                            dce_form.siblings('.dce-list').find('ul.dce-list').show();
                            dce_form.siblings('.dce-list').find('li.file').each(function () {
                                if (jQuery(this).text().toLowerCase().indexOf(jQuery(".dce-file-search-form .filetxt").val().toLowerCase()) >= 0) {
                                    jQuery(this).show();
                                } else {
                                    jQuery(this).hide();
                                }
                            });
                        } else {
                            dce_form.siblings('.dce-list').find('li.file').show();
                        }
                    });
                <?php  } ?>
                    jQuery(".dce-file-search-form").submit(function (event) {
                        if (jQuery(this).find('.filetxt').val().length > 2) {
                            jQuery(this).siblings('.dce-list').find('ul.dce-list').show();
                            jQuery(this).siblings('.dce-list').find('li.file').each(function () {
                                if (jQuery(this).text().toLowerCase().indexOf(jQuery(".dce-file-search-form .filetxt").val().toLowerCase()) >= 0) {
                                    jQuery(this).show();
                                } else {
                                    jQuery(this).hide();
                                }
                            });
                        } else {

                        }
                        return false;
                    });
                    jQuery(".dce-file-search-form .reset").click(function (event) {
                        jQuery(this).closest('.dce-file-search-form').siblings('.dce-list').find('ul.dce-list').hide();
                        jQuery(this).closest('.dce-file-search-form').siblings('.dce-list').find('li.file').show();
                    });
        <?php }
        if ($settings['enable_metadata_hits']) {
            ?>
                    jQuery(document).on("click", ".dce-list > .file a.dce-file-download", function (event) {
                        var data = {};
                        data['action'] = "dce_file_browser_hits";
                        if (jQuery(this).attr("data-post-id")) {
                            data['post_id'] = jQuery(this).attr("data-post-id");
                        }
                        data["md5"] = jQuery(this).attr("data-md5");
                        console.log(data);
                        jQuery.post(ajaxurl, data, function (response) {
                            //alert("Got this from the server: " + response);
                        });
                    });
        <?php } ?>
            });
        </script>
        <?php
    }

}
