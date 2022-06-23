<?php

namespace DynamicContentForElementor;

/**
 * DCE_Settings Class
 *
 * Settings page
 *
 * @since 0.0.1
 */
class DCE_Settings {

    private $options = array();

    /**
     * Constructor
     *
     * @since 0.0.1
     *
     * @access public
     */
    public function __construct() {
        $this->init();
    }

    public function init() {

        $this->options = get_option(DCE_OPTIONS);

        if (is_admin()) {

            // Checking PHP version
            if (version_compare(PHP_VERSION, '7.0', '<')) {
                if (isset($_GET['dismiss_notice'])) {
                    if ($_GET['dismiss_notice'] == 'php') {
                        update_option('dce_php_version_notice', 1);
                    }
                }
                if (!get_option('dce_php_version_notice')) {
                    $pieces = explode('-', PHP_VERSION);
                    $PHP_VERSION = reset($pieces);
                    $notice_text = __('Your server are using PHP version:', 'dynamic-content-for-elementor') . ' <strong>' . $PHP_VERSION . '</strong>';
                    $notice_text .= '<br>' . __('We strongly raccomand to update to more recent PHP version, you will have best results with PHP 7.x or newer.', 'dynamic-content-for-elementor');
                    $notice_text .= '<br><br><a class="btn button" href="' . admin_url('admin.php?page=dce_opt&dismiss_notice=php') . '"><span class="dashicons dashicons-yes"></span> ' . __('Ok, I understand. Thanks for suggestion.', 'dynamic-content-for-elementor') . '</a>';
                    DCE_Notice::dce_admin_notice__warning($notice_text);
                }
            }

            /*
              // Go to settings
              if (empty($this->options)) {
              if (isset($_GET['dismiss_notice'])) {
              if ($_GET['dismiss_notice'] == 'template') {
              update_option('dce_template_system_notice', 1);
              }
              }
              if (!get_option('dce_template_system_notice')) {
              $notice_text = __('It seems that you didn\'t configured the', 'dynamic-content-for-elementor').' <strong>Dynamic Template System</strong>';
              $notice_text .= '<br>'.__('Please read our guides to obtain best results from Wordpress + Elementor.', 'dynamic-content-for-elementor');
              $notice_text .= '<br><br><a class="btn button" href="'.admin_url('admin.php?page=dce_opt&tab=settings&dismiss_notice=template').'"><span class="dashicons dashicons-yes"></span> '.__('Ok, I understand. I will use it!', 'dynamic-content-for-elementor' ).'</a> <a class="button button-primary" href="https://docs.dynamic.ooo" target="_blank"><span class="dashicons dashicons-book-alt"></span> Read the docs</a>';
              DCE_Notice::dce_admin_notice__warning($notice_text);
              }
              }

              // Enable functionalities (Widget, Extension, Document)
              $this->excluded = array();
              $this->excluded['widgets'] = json_decode(get_option(SL_PRODUCT_ID . '_excluded_widgets'), true);
              $this->excluded['extensions'] = json_decode(get_option(SL_PRODUCT_ID . '_excluded_widgets'), true);
              $this->excluded['documents'] = json_decode(get_option(SL_PRODUCT_ID . '_excluded_documents'), true);
              //var_dump($this->excluded);
              if (!empty($this->excluded)) {
              if (isset($_GET['dismiss_notice'])) {
              if ($_GET['dismiss_notice'] == 'dynamic') {
              update_option('dce_dynamic_notice', 1);
              }
              }
              if (!get_option('dce_dynamic_notice')) {
              $notice_text = __('Please configure the plugin to enable all functionalities.', 'dynamic-content-for-elementor');
              $notice_text .= '<br>'.__('Discover our Windgets, Extension and Documents.', 'dynamic-content-for-elementor');
              $notice_text .= '<br><br><a class="btn button" href="'.admin_url('admin.php?page=dce_opt&tab=widgets&dismiss_notice=dynamic').'"><span class="dashicons dashicons-yes"></span> '.__('Ok, I understand. Let\'s go to activate them!', 'dynamic-content-for-elementor' ).'</a> <a class="button button-primary" href="https://docs.dynamic.ooo" target="_blank"><span class="dashicons dashicons-book-alt"></span> Read the docs</a>';
              DCE_Notice::dce_admin_notice__warning($notice_text);
              }
              }
             */
        }
    }

    public function dce_setting_templatesystem() {
        //exit(wp_redirect("admin.php?page=dce_opt"));
        $this->dce_setting_page(true);
    }

    public function dce_setting_page($tplsys = false) {

        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // add error/update messages
        // check if the user have submitted the settings
        // wordpress will add the "settings-updated" $_GET parameter to the url
        if (isset($_GET['settings-updated'])) {
            // add settings saved message with the class of "updated"
            add_settings_error('dce_messages', 'dce_message', __('Settings Saved', 'dynamic-content-for-elementor'), 'updated');
        }

        $dce_apis = DCE_Assets::get_dce_apis();
        $dce_apis = array_filter($dce_apis);
        DCE_Assets::dce_icon();
        //var_dump($dce_apis);
        if (empty($dce_apis) && !$tplsys) {
            DCE_Notice::dce_admin_notice__warning(__('Please fill API keys to use third parts services. <a class="btn button" href="?page=dce_opt&tab=apis">Set them now</a>', 'dynamic-content-for-elementor'));
        }
        
        DCE_License::dce_active_domain_check();
        DCE_License::dce_expired_license_notice();

        // show error/update messages
        settings_errors('dce_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php if (!$tplsys) { ?>
            <div id="dce-settings-tabs-wrapper" class="nav-tab-wrapper">
                <a id="dce-settings-tab-settings" class="nav-tab<?php if (!isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'settings')) { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=settings">
                    <span class="icon icon-dyn-logo-dce"></span> <?php _e('Template System', 'dynamic-content-for-elementor'); ?>
                </a>
                <a id="dce-settings-tab-widgets" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'widgets') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=widgets">
                    <span class="elementor-icon eicon-apps"></span> <?php _e('Widgets', 'dynamic-content-for-elementor'); ?>
                    <span class="dce-badge"><?php $widgets = DCE_Widgets::get_active_widgets(); echo count($widgets); ?></span>
                </a>
                <a id="dce-settings-tab-extensions" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'extensions') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=extensions">
                    <span class="dashicons dashicons-admin-generic"></span> <?php _e('Extensions', 'dynamic-content-for-elementor'); ?>
                    <span class="dce-badge"><?php $extensions = DCE_Extensions::get_active_extensions(); echo count($extensions); ?></span>
                </a>
                <a id="dce-settings-tab-controls" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'documents') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=documents">
                    <span class="dashicons dashicons-admin-generic"></span> <?php _e('Documents', 'dynamic-content-for-elementor'); ?>
                    <span class="dce-badge"><?php $documents = DCE_Documents::get_documents(); echo count($documents); ?></span>
                </a>
                <a id="dce-settings-tab-apis" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'apis') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=apis">
                    <span class="dashicons dashicons-admin-plugins"></span> <?php _e('APIs', 'dynamic-content-for-elementor'); ?>
                </a>
                <?php 
                /* <a id="dce-settings-tab-license" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'log') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=log">
                    <span class="dashicons dashicons-media-text"></span> <?php _e('Log', 'dynamic-content-for-elementor'); ?>
                </a> */
                ?>
                <a id="dce-settings-tab-license" class="nav-tab<?php if (isset($_GET['tab']) && $_GET['tab'] == 'license') { ?> nav-tab-active<?php } ?>" href="?page=dce_opt&tab=license">
                    <span class="dashicons dashicons-admin-network"></span> <?php _e('License', 'dynamic-content-for-elementor'); ?>
                </a>
            </div>
            <div class="metabox-holder dce-metabox-holder-no">
                <?php
            }
                if (isset($_GET['tab']) && $_GET['tab'] == 'license') {
                    DCE_License::show_license_form();
                }
                if (isset($_GET['tab']) && $_GET['tab'] == 'apis') {
                    $this->show_apis_form();
                }
                if (isset($_GET['tab']) && $_GET['tab'] == 'widgets') {
                    $this->show_widgets_form();
                }
                if (isset($_GET['tab']) && $_GET['tab'] == 'documents') {
                    $this->show_documents_form();
                }
                if (isset($_GET['tab']) && $_GET['tab'] == 'extensions') {
                    $this->show_extensions_form();
                }
                if (isset($_GET['tab']) && $_GET['tab'] == 'log') {
                    $this->show_log_form();
                }
                if (!isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'settings') || $tplsys) {
                    //$this->show_dts_form();
                    $this->show_dce_dts_form();
                }
                
                
                if (!$tplsys) {
                ?>
            </div>
            <?php } ?>
        </div>
        <?php
    }
    
    public function show_log_form() {
        echo '<pre>';
        DCE_Log::output_diagnostic_info();
        //DCE_Log::output_log_file();
        echo '</pre>';
    }

    public function show_widgets_form() {
        ?>
        <p><?php __('Select the widgets you want to display on Elementor:', 'dynamic-content-for-elementor'); ?></p>
        <form action="" method="post">
            <?php
            if (isset($_POST['save-dce-widgets'])) {
                update_option(SL_PRODUCT_ID . '_active_widgets', json_encode($_POST['dce-widgets']));
                $excluded_widgets = array();
                foreach (DCE_Widgets::get_widgets_by_group() as $key => $value) {
                    foreach ($value as $className) {
                        if (!isset($_POST['dce-widgets'][$className])) {
                            $excluded_widgets[$className] = $key;
                        }
                    }
                }
                update_option(SL_PRODUCT_ID . '_excluded_widgets', json_encode($excluded_widgets));
                DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved. You will only see the active widgets.', 'dynamic-content-for-elementor'));
            }
            $excluded_widgets = json_decode(get_option(SL_PRODUCT_ID . '_excluded_widgets'), true);
            $i = 0;
            DCE_Widgets::includes();
            
            foreach (DCE_Widgets::get_active_widgets_by_group() as $key => $value) {
                if ($i) {
                    ?><?php } ?>
                <table class="widefat dce-form-table dce-filter-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" onclick="if (jQuery(this).attr('checked')) {
                                            jQuery('.dce-widgets-<?php echo strtolower($key); ?>').not(':disabled').attr('checked', true);
                                        } else {
                                            jQuery('.dce-widgets-<?php echo strtolower($key); ?>').not(':disabled').attr('checked', false);
                                        }">
                                <h3><?php
                                    echo $key;
                                    if (strtolower($key) == 'dev') {
                                        ?> <small><abbr style="opacity: 0.5;" title="<?php _e('For security reasons some of this widget are restricted to Admin use only.', 'dynamic-content-for-elementor'); ?>"><?php _e('Only for Admins', 'dynamic-content-for-elementor'); ?></abbr></small><?php } ?></h3>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($value as $className) {
                            $myWdgtClass = DCE_Widgets::$namespace . $className;
                            if ($myWdgtClass::is_enabled()) {
                                $myWdgtObj = new $myWdgtClass();
                                $enablePlugin = $myWdgtClass::get_satisfy_dependencies(true);
                                $pezzi = explode('_', $className, 3);
                                ?>
                                <tr>
                                    <td> &nbsp; &nbsp;
                                        <input class="dce-widgets-<?php echo strtolower($key); ?>" type="checkbox" name="dce-widgets[<?php echo $className; ?>]" id="dce-widget-<?php echo $className; ?>"<?php
                                        if (empty($enablePlugin)) {
                                            if (!$excluded_widgets || !isset($excluded_widgets[$className])) {
                                                ?> checked="checked"<?php
                                                   }
                                               } else {
                                                   ?> disabled="disabled"<?php } ?>>

                                        <label style="font-weight: bold;" for="dce-widget-<?php echo $className; ?>">
                                            <?php echo $myWdgtObj->get_title(); //echo end($pezzi);  ?>
                                        </label>
                                        <?php if (!empty($enablePlugin)) { ?>
                                            <small class="warning text-red red"><span class="dashicons dashicons-warning"></span> <?php _e('Required plugins', 'dynamic-content-for-elementor'); ?>: <?php echo implode(', ', $enablePlugin); ?></small>
                                        <?php }
                                        ?>
                                        <p>
                                            <i class="icon <?php echo $myWdgtObj->get_icon(); ?>" aria-hidden="true"></i>
                                            <em><?php echo $myWdgtObj->get_description(); ?></em>
                                            <a href="<?php echo $myWdgtObj->get_docs(); ?>" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
                                        </p>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                $i++;
            }
            ?>
            <input type="hidden" name="save-dce-widgets" value="1" />
            <?php submit_button('Save Widgets'); ?>
        </form>
        <?php
    }

    public function show_extensions_form() {
        ?>
        <p><?php __('Select the extensions you want to activate on Elementor:', 'dynamic-content-for-elementor'); ?></p>
        <form action="" method="post">
            <?php
            $extensions = DCE_Extensions::get_active_extensions();
            if (isset($_POST['save-dce-extensions'])) {
                update_option(SL_PRODUCT_ID . '_active_extensions', json_encode($_POST['dce-extensions']));
                $excluded_extensions = array();
                foreach ($extensions as $key => $value) {
                    if (!isset($_POST['dce-extensions'][$value])) {
                        $excluded_extensions[$value] = $key;
                    }
                }
                update_option(SL_PRODUCT_ID . '_excluded_extensions', json_encode($excluded_extensions));
                DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved. You will only see the active extensions.', 'dynamic-content-for-elementor'));
            }
            $excluded_extensions = json_decode(get_option(SL_PRODUCT_ID . '_excluded_extensions'), true);
            $i = 0;
            DCE_Extensions::includes();
            ?>
            <table class="widefat dce-form-table dce-filter-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" onclick="if (jQuery(this).attr('checked')) {
                                        jQuery('.dce-extensions').not(':disabled').attr('checked', true);
                                    } else {
                                        jQuery('.dce-extensions').not(':disabled').attr('checked', false);
                                    }">
                            <h3>Extensions</h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($extensions as $key => $className) {
                        $myWdgtClass = DCE_Extensions::$namespace . $className;
                        if ($myWdgtClass::is_enabled()) {
                            $myWdgtObj = new $myWdgtClass();
                            $pezzi = explode('_', $className, 3);
                            ?>
                            <tr>
                                <td> &nbsp; &nbsp;
                                    <?php 
                                        $extension_satisfy_plugin_depends = true;
                                        if (!empty($myWdgtClass::$depended_plugins)) { 
                                            foreach ($myWdgtClass::$depended_plugins as $aplugin) { 
                                                if (!DCE_Helper::is_plugin_active($aplugin)) {
                                                    $extension_satisfy_plugin_depends = false;
                                                }
                                            }
                                        }
                                    ?>
                                    <input class="dce-extensions dce-extensions-<?php echo strtolower($key); ?>" type="checkbox" name="dce-extensions[<?php echo $className; ?>]" id="dce-extensions-<?php echo $className; ?>"
                                    <?php
                                    if ((!$excluded_extensions || !isset($excluded_extensions[$className])) && $extension_satisfy_plugin_depends) {
                                        ?> checked="checked"<?php
                                    }
                                    if (!$extension_satisfy_plugin_depends) {
                                        echo ' disabled';
                                    }
                                    ?>>
                                    <label for="dce-extensions-<?php echo $className; ?>">
                                        <b><?php echo $myWdgtObj->name; //echo end($pezzi);  ?></b>
                                        <?php if (!$extension_satisfy_plugin_depends) { ?>
                                            <small class="warning text-red red"><span class="dashicons dashicons-warning"></span> <?php _e('Required plugins', 'dynamic-content-for-elementor'); ?>: <?php echo implode(', ', $myWdgtClass::$depended_plugins); ?></small>
                                        <?php } ?>
                                        <p>
                                            <?php /* <i class="icon <?php echo $myWdgtObj->get_icon(); ?>" aria-hidden="true"></i> */ ?>
                                            <em><?php echo $myWdgtObj->get_description(); ?></em>
                                            <a href="<?php echo $myWdgtObj->get_docs(); ?>" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
                                        </p>
                                    </label>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <input type="hidden" name="save-dce-extensions" value="1" />
            <?php submit_button('Save Extensions'); ?>
        </form>
        <?php
    }

    public function show_documents_form() {
        ?>
        <p><?php __('Select the documents you want to activate on Elementor:', 'dynamic-content-for-elementor'); ?></p>
        <form action="" method="post">
            <?php
            $documents = DCE_Documents::get_active_documents();
            if (isset($_POST['save-dce-documents'])) {
                update_option(SL_PRODUCT_ID . '_active_documents', json_encode($_POST['dce-documents']));
                $excluded_documents = array();
                foreach ($documents as $key => $className) {
                    if (!isset($_POST['dce-documents'][$className])) {
                        $excluded_documents[$className] = $key;
                    }
                }
                update_option(SL_PRODUCT_ID . '_excluded_documents', json_encode($excluded_documents));
                DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved. You will only see the active documents.', 'dynamic-content-for-elementor'));
            }
            $excluded_documents = json_decode(get_option(SL_PRODUCT_ID . '_excluded_documents'), true);
            $i = 0;
            DCE_Documents::includes();
            ?>
            <table class="widefat dce-form-table dce-filter-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" onclick="if (jQuery(this).attr('checked')) {
                                        jQuery('.dce-documents').not(':disabled').attr('checked', true);
                                    } else {
                                        jQuery('.dce-documents').not(':disabled').attr('checked', false);
                                    }">
                            <h3>Documents</h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($documents as $key => $className) {
                        $myWdgtClass = DCE_Documents::$namespace . $className;
                        if ($myWdgtClass::is_enabled()) {
                            $myWdgtObj = new $myWdgtClass();
                            $pezzi = explode('_', $className, 3);
                            ?>
                            <tr>
                                <td> &nbsp; &nbsp;
                                    <input class="dce-documents dce-documents-<?php echo strtolower($key); ?>" type="checkbox" name="dce-documents[<?php echo $className; ?>]" id="dce-documents-<?php echo $className; ?>"
                                    <?php
                                    if (!$excluded_documents || !isset($excluded_documents[$className])) {
                                        ?> checked="checked"<?php
                                           }
                                           ?>>
                                    <label style="font-weight: bold;" for="dce-documents-<?php echo $className; ?>">
                                        <?php echo $myWdgtObj->name; //echo end($pezzi);  ?>
                                    </label>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <input type="hidden" name="save-dce-documents" value="1" />
            <?php submit_button('Save Documents'); ?>
        </form>
        <?php
    }

    public function show_apis_form() {
        ?>
        <p><?php __('Insert your apis if you want to activate this services on your site:', 'dynamic-content-for-elementor'); ?></p>
        <form action="" method="post">
            <?php
            if (isset($_POST['save-dce-apis'])) {
                update_option(SL_PRODUCT_ID . '_apis', $_POST['dce-apis']);
                DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved.', 'dynamic-content-for-elementor'));
            }
            $dce_apis = DCE_Assets::get_dce_apis();
            /* if (empty($dce_apis)) {
              DCE_Notice::dce_admin_notice__warning(__('Please fill API keys to use third parts services.', 'dynamic-content-for-elementor'));
              } */
            ?>
            <table class="widefat dce-form-table">
                <thead>
                    <tr>
                        <th>
                            <h3>3<sup>rd</sup> parts APIs</h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>  &nbsp;

                            <label style="font-weight: bold;" for="dce-apis-gmaps">
                                Google <i>"Maps JavaScript API"</i> key:
                            </label>
                            <input class="dce-apis dce-apis-gmaps" type="text" name="dce-apis[dce_api_gmaps]" id="dce-apis-gmaps" value="<?php echo (isset($dce_apis['dce_api_gmaps'])) ? $dce_apis['dce_api_gmaps'] : ''; ?>">
                            <div class="dce-apis-gmaps-note">&nbsp;To learn more about the API-KEY for googleMap <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">see here</a></div>
                        </td>
                    </tr>

                </tbody>
            </table>

            <input type="hidden" name="save-dce-apis" value="1" />
            <?php submit_button('Save APIs'); ?>
        </form>
        <?php
    }

    public function show_dts_form() {
        //var_dump(get_option($dce_option));
        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            //var_dump($_POST[$dce_option]);
            update_option(DCE_OPTIONS, $_POST[DCE_OPTIONS]);
            $this->options = $_POST[DCE_OPTIONS];
            DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved.', 'dynamic-content-for-elementor'));
        }
        ?>
        <form action="#options.php" method="post">
            <?php
            // output security fields for the registered setting "dce"
            settings_fields('dyncontel_opt');
            // output setting sections and their fields
            // (sections are registered for "dce", each field is registered to a specific section)
            //do_settings_sections('dyncontel_opt');

            $args = array(
                'public' => true,
            );

            $output = 'names'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'

            $other_wp_pages = [];
            //$post_types = array_merge(get_post_types($args, $output, $operator), get_taxonomies($args, $output, $operator));
            //$post_types = get_post_types($args, $output, $operator);
            $post_types = array_merge(get_post_types($args, $output, $operator), $other_wp_pages);
            $theme = wp_get_theme();

            $templates = array('NO');
            $get_templates = get_posts(array('post_type' => DCE_TemplateSystem::$supported_types, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'suppress_filters' => false));

            if (!empty($get_templates)) {
                foreach ($get_templates as $template) {
                    $templates[$template->ID] = $template->post_title;
                }
            }
            // ------------------------------- [TYPES] ------------------------------
            echo '<h1 class="dce-title-settings">Types</h1>';
            // acf-field-group
            // register a new section in the "dce" XXX
            ///$chiave = 'paperino';
            $typesRegistered = DCE_Helper::get_types_registered();
            foreach ($typesRegistered as $chiave) {
                $object_t = get_post_type_object($chiave)->labels;
                $label_t = $object_t->name;
                //var_dump(get_post_type_object($chiave));
                $preview = get_post_type_archive_link($chiave);
                if ($chiave == 'page') {
                    $preview = get_home_url();
                }
                if ($chiave == 'post') {
                    $page_for_post = get_option('page_for_posts');
                    if ($page_for_post) {
                        $preview = get_permalink($page_for_post);
                    }
                }
                ?>
                <table class="widefat dce-form-table">
                    <thead>
                        <tr><th colspan="3"><h3><?php echo $label_t; ?> <?php if ($preview) { ?><a target="_blank" href="<?php echo $preview ?>"><span class="dashicons dashicons-admin-links"></span></a><?php } ?></h3></th></tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($chiave != 'attachment' && $chiave != 'user' && $chiave != 'page') {
                            $dce_key = 'dyncontel_before_field_archive' . $chiave;
                            ?>
                            <tr>
                                <th scope="dce-row">
                                    <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-upload"></span> Before Archive</label>
                                </th>
                                <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                                <?php //$this->_dce_settings_select_template_layout($dce_key);   ?>
                            </tr>
                            <?php
                        }
                        if ($chiave != 'attachment' && $chiave != 'page' /* && $chiave != 'user' */) {
                            $dce_key = 'dyncontel_field_archive' . $chiave;
                            ?>
                            <tr>
                                <th width="200" scope="dce-row">
                                    <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-exerpt-view"></span> <?php _e('Archive Blocks', 'dynamic-content-for-elementor'); ?></label>
                                </th>
                                <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                                <td><?php $this->_dce_settings_select_template_layout($dce_key); ?></td>
                                <td><?php $this->_dce_settings_archive($dce_key); ?></td>
                            </tr>
                            <?php
                        }
                        $dce_key = 'dyncontel_field_single' . $chiave;
                        ?>
                        <tr>
                            <th width="200" scope="dce-row">
                                <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('Single Post', 'dynamic-content-for-elementor'); ?></label>
                            </th>
                            <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                            <td><?php $this->_dce_settings_select_template_blank($dce_key); ?></td>
                        </tr>

                    </tbody>
                </table>
                <script>
                    /*jQuery('.dce-select').change(function() {
                     var selval = jQuery(this).val();
                     //alert(selval);
                     if (selval == '0') {
                     jQuery(this).closest('tr').find('.dce-checkbox').prop('checked', false);
                     jQuery(this).closest('tr').find('.dce-optionals').fadeOut();
                     } else {
                     jQuery(this).closest('tr').find('.dce-optionals').fadeIn();
                     }
                     });*/
                    // In your Javascript (external .js resource or <script> tag)
                </script>
                <?php
            } // end $typesRegistered foreach
            // ------------------------------- [SEARCH] ------------------------------
            echo '<h1 class="dce-title-settings">Other pages</h1';
            $chiave = 'search';
            $preview = get_search_link('lorem ipsum');
            ?>
            <br />
            <table class="widefat dce-form-table">
                <thead>
                    <tr><th colspan="3"><h3><?php _e('Search'); ?> <a target="_blank" href="<?php echo $preview; ?>"><span class="dashicons dashicons-admin-links"></span></a></h3></th></tr>
                </thead>
                <tbody>
                    <?php
                    $dce_key = 'dyncontel_field_single' . $chiave;
                    ?>
                    <tr>
                        <th width="200" scope="dce-row">
                            <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('Search Head', 'dynamic-content-for-elementor'); ?></label>
                        </th>
                        <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                        <?php //$this->_dce_settings_select_template_layout($dce_key);    ?>
                    </tr>
                    <?php
                    $dce_key = 'dyncontel_field_archive' . $chiave;
                    ?>
                    <tr>
                        <th width="200" scope="dce-row">
                            <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-exerpt-view"></span> <?php _e('Search Contents', 'dynamic-content-for-elementor'); ?></label>
                        </th>
                        <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                        <td><?php $this->_dce_settings_select_template_layout($dce_key); ?></td>
                        <td><?php $this->_dce_settings_archive($dce_key); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php
            // ------------------------------- [USER] ------------------------------
            $chiave = 'user';
            $preview = get_author_posts_url(get_current_user_id());
            ?>
            <br />
            <table class="widefat dce-form-table">
                <thead>
                    <tr><th colspan="3"><h3><?php _e('Users'); ?> <a target="_blank" href="<?php echo $preview; ?>"><span class="dashicons dashicons-admin-links"></span></a></h3></th></tr>
                </thead>
                <tbody>
                    <?php
                    $dce_key = 'dyncontel_field_single' . $chiave;
                    ?>
                    <tr>
                        <th width="200" scope="dce-row">
                            <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('User Head', 'dynamic-content-for-elementor'); ?></label>
                        </th>
                        <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                        <?php //$this->_dce_settings_select_template_layout($dce_key);    ?>
                    </tr>
                    <?php
                    $dce_key = 'dyncontel_field_archive' . $chiave;
                    ?>
                    <tr>
                        <th width="200" scope="dce-row">
                            <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-exerpt-view"></span> <?php _e('User Contents', 'dynamic-content-for-elementor'); ?></label>
                        </th>
                        <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                        <td><?php $this->_dce_settings_select_template_layout($dce_key); ?></td>
                        <td><?php $this->_dce_settings_archive($dce_key); ?></td>
                    </tr>
                </tbody>
            </table>



            <?php
            // ------------------------------- [TAXONOMY] ------------------------------
            echo '<h1 class="dce-title-settings">taxonomy</h1>';
            $taxonomiesRegistered = get_taxonomies();

            $customTaxonomies = array_diff($taxonomiesRegistered, DCE_TemplateSystem::$excluded_taxonomies);

            //var_dump($taxonomiesRegistered);
            foreach ($customTaxonomies as $chiave) {

                $object_t = get_taxonomy($chiave);
                //var_dump($object_t);
                $label_t = $object_t->label;
                $terms = get_terms($chiave);
                if (!empty($terms)) {
                    $preview = get_term_link(reset($terms));
                } else {
                    $preview = get_home_url();
                }
                //$preview = get_home_url().'/'.$object_t->rewrite['slug'];
                //var_dump($terms); //die();
                ?>
                <table class="widefat dce-form-table">
                    <thead>
                        <tr><th colspan="3"><h3><?php echo $label_t . '</h3> <h6 style="display: inline;">(' . $chiave . ')'; ?></h6> <?php if ($preview) { ?><a target="_blank" href="<?php echo $preview; ?>"><span class="dashicons dashicons-admin-links"></span></a><?php } ?></th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $dce_key = 'dyncontel_before_field_archive_taxonomy_' . $chiave;
                        ?>
                        <tr>
                            <th scope="dce-row">
                                <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-upload"></span> <?php _e('Before Archive', 'dynamic-content-for-elementor'); ?></label>
                            </th>
                            <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                        </tr>
                        <?php
                        $dce_key = 'dyncontel_field_archive_taxonomy_' . $chiave;
                        ?>
                        <tr>
                            <th width="200" scope="dce-row">
                                <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-exerpt-view"></span> <?php _e('Archive Blocks', 'dynamic-content-for-elementor'); ?></label>
                            </th>
                            <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                            <td><?php $this->_dce_settings_select_template_layout($dce_key); ?></td>
                            <td><?php $this->_dce_settings_archive($dce_key); ?></td>
                        </tr>
                        <?php
                        // non uso il template pagina su taxonomy non gerarchiche quindi su tags..
                        if (is_taxonomy_hierarchical($chiave)) {
                            $dce_key = 'dyncontel_field_single_taxonomy_' . $chiave;
                            ?>
                            <tr>
                                <th width="200" scope="dce-row">
                                    <label for="<?php echo $dce_key; ?>"><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('Single Post', 'dynamic-content-for-elementor'); ?></label>
                                </th>
                                <td width="100"><?php $this->_dce_settings_select_template($dce_key, $templates); ?></td>
                                <td><?php $this->_dce_settings_select_template_blank($dce_key); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
            }

            // output save settings button
            submit_button(__('Save Settings', 'dynamic-content-for-elementor'));
            ?>
        </form>
        <?php
    }

    private function _dce_settings_select_template($dce_key, $templates) {
        ?>
        <span class="dce-template-select-wrapper">
            <a class="dce-template-quick-remove<?php if (!isset($this->options[$dce_key]) || !$this->options[$dce_key]) { ?> hidden<?php } ?>" target="_blank" href="#<?php echo $dce_key ?>"><span class="dashicons dashicons-no-alt"></span></a>
            <select class="dce-select-template dce-select js-dce-select" id="<?php echo $dce_key; ?>" name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_key; ?>]">
                        <?php foreach ($templates as $key => $value) { ?>
                    <option value="<?php echo $key ?>"  <?php echo isset($this->options[$dce_key]) ? ( selected($this->options[$dce_key], $key, false) ) : ( '' ); ?>>
                        <?php echo $value ?>
                    </option>
                <?php } ?>
            </select>
            <?php
            $dce_quick_edit = admin_url('post.php?action=elementor&post=');
            ?>
            <a class="dce-template-quick-edit<?php if (!isset($this->options[$dce_key]) || !$this->options[$dce_key]) { ?> hidden<?php } ?>" target="_blank" data-href="<?php echo $dce_quick_edit; ?>" href="<?php echo $dce_quick_edit ?><?php echo isset($this->options[$dce_key]) ? $this->options[$dce_key] : ''; ?>"><span class="dashicons dashicons-edit"></span></a>
        </span>
        <?php
    }

    private function _dce_settings_select_template_blank($dce_key) {
        $dce_key_template = $dce_key . '_blank';
        $dce_template = isset($this->options[$dce_key_template]) ? $this->options[$dce_key_template] : false;
        ?>
        <div class="dce-optionals<?php /* if (!$dce_template) { ?> hidden<?php } */ ?>">
            <input class="dce-checkbox" type="checkbox" <?php if ($dce_template) { ?>checked="" <?php } ?>name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_key_template; ?>]" id="<?php echo $dce_key_template; ?>" value="1" onClick="jQuery(this).closest('.dce-template-main-content').find('.dce-template-page-content').toggleClass('dce-template-page-content-original').toggleClass('dce-template-page-content-full');">
            <label class="dce-template-single-full" for="<?php echo $dce_key_template; ?>">
                <?php esc_html_e('Force Full Width Template', 'dynamic-content-for-elementor'); ?> <a target="_blank" href="https://docs.elementor.com/article/316-using-elementor-s-full-width-page-template"><span class="dashicons dashicons-info"></span></a>
            </label>
        </div>
        <?php
    }

    private function _dce_settings_select_template_layout($dce_key) {
        $dce_key_template = $dce_key . '_template';
        $dce_template = isset($this->options[$dce_key_template]) ? $this->options[$dce_key_template] : false;
        ?>
        <!--
        <div class="dce-optionals<?php /* if (!$dce_template) { ?> hidden<?php } */ ?>">
            <input class="dce-checkbox" type="checkbox" <?php if ($dce_template) { ?>checked="" <?php } ?>name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_key_template; ?>]" id="<?php echo $dce_key_template; ?>" value="1">
            <label for="<?php echo $dce_key_template; ?>"><?php esc_html_e('Blank template', 'dynamic-content-for-elementor'); ?></label>
        </div>
        -->
        <div class="dce-options<?php /* if (!$dce_template) { ?> hidden<?php } */ ?>">
            <label for="<?php echo $dce_key_template; ?>"><?php esc_html_e('Select template', 'dynamic-content-for-elementor'); ?></label>
            <select id="<?php echo $dce_key_template; ?>" name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_key_template; ?>]" class="dce-select js-dce-select">
                <option value=""<?php if (!$dce_template) { ?> selected="selected"<?php } ?>><?php esc_html_e('Theme default (NO Before Archive)', 'dynamic-content-for-elementor'); ?></option>
                <option value="blank"<?php if ($dce_template == 'blank') { ?> selected="selected"<?php } ?>><?php esc_html_e('Blank FullWidth template', 'dynamic-content-for-elementor'); ?></option>
                <option value="boxed"<?php if ($dce_template == 'boxed') { ?> selected="selected"<?php } ?>><?php esc_html_e('Blank Boxed template', 'dynamic-content-for-elementor'); ?></option>
                <option value="canvas"<?php if ($dce_template == 'canvas') { ?> selected="selected"<?php } ?>><?php esc_html_e('Elementor Canvas', 'dynamic-content-for-elementor'); ?></option>
            </select>
        </div>
        <?php
    }

    private function _dce_settings_archive($dce_key) {
        $dce_col_md = $dce_key . '_col_md';
        $dce_col_sm = $dce_key . '_col_sm';
        $dce_col_xs = $dce_key . '_col_xs';
        ?>
        <div class="dce-optional">
            <label for="<?php echo $dce_col_md; ?>"><?php _e('Columns', 'dynamic-content-for-elementor'); ?></label>
            <div id="<?php echo $dce_key; ?>-switchers" class="dce-switchers">
                <div class="elementor-control-responsive-switchers dce-elementor-control-responsive-switchers">
                    <?php
                    $dce_col_md_val = isset($this->options[$dce_col_md]) ? $this->options[$dce_col_md] : 4;
                    $dce_col_sm_val = isset($this->options[$dce_col_sm]) ? $this->options[$dce_col_sm] : 3;
                    $dce_col_xs_val = isset($this->options[$dce_col_xs]) ? $this->options[$dce_col_xs] : 2;
                    ?>
                    <div class="field-group">
                        <input class="dce-input dce-input-md" type="number" min="1" name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_col_md; ?>]" id="<?php echo $dce_col_md; ?>" value="<?php echo $dce_col_md_val; ?>">
                        <input class="dce-input dce-input-sm" type="number" min="1" name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_col_sm; ?>]" id="<?php echo $dce_col_sm; ?>" value="<?php echo $dce_col_sm_val; ?>">
                        <input class="dce-input dce-input-xs" type="number" min="1" name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_col_xs; ?>]" id="<?php echo $dce_col_xs; ?>" value="<?php echo $dce_col_xs_val; ?>">
                    </div>
                    <div class="switchers-group">
                        <a onclick="jQuery('body').removeClass('elementor-device-mobile').removeClass('elementor-device-tablet').addClass('elementor-device-desktop');" class="elementor-responsive-switcher elementor-responsive-switcher-desktop" data-device="desktop">
                            <i class="eicon-device-desktop"></i>
                        </a>
                        <a onclick="jQuery('body').removeClass('elementor-device-mobile').removeClass('elementor-device-desktop').addClass('elementor-device-tablet');" class="elementor-responsive-switcher elementor-responsive-switcher-tablet" data-device="tablet">
                            <i class="eicon-device-tablet"></i>
                        </a>
                        <a onclick="jQuery('body').removeClass('elementor-device-tablet').removeClass('elementor-device-desktop').addClass('elementor-device-mobile');" class="elementor-responsive-switcher elementor-responsive-switcher-mobile" data-device="mobile">
                            <i class="eicon-device-mobile"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function show_dce_dts_form() {

        // SAVING DCE TEMPLATE SETTINGS
        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            //var_dump($_POST[$dce_option]);
            update_option(DCE_OPTIONS, $_POST[DCE_OPTIONS]);
            update_option('dce_template_disable', $_POST['dce_template_disable']);
            $this->options = $_POST[DCE_OPTIONS];
            DCE_Notice::dce_admin_notice__success(__('Your preferences have been saved.', 'dynamic-content-for-elementor'));
        }

        $templates = array('NO');
        $get_templates = DCE_Helper::get_templates();
        //$get_templates = get_posts(array('post_type' => DCE_TemplateSystem::$supported_types, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'suppress_filters' => false));
        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {
                //$templates[$template->ID] = $template->post_title;
                if ($template['type'] == 'widget') continue;
                $templates[$template['template_id']] = '['.$template['type'].'] '.$template['title'];
            }
        }

        $preview = array();
        $dceTemplate = array();

        $dceTemplate['post-types']['label'] = __('Types', 'dynamic-content-for-elementor');
        // ------------------------------- [TYPES] -----------------------------
        $typesRegistered = DCE_Helper::get_types_registered();
        foreach ($typesRegistered as $chiave) {
            $preview[$chiave] = get_post_type_archive_link($chiave);
            if ($chiave == 'page') {
                $preview[$chiave] = get_home_url();
                $id_privacy = get_option('wp_page_for_privacy_policy');
                if ($id_privacy) {
                    $preview[$chiave] = get_permalink($id_privacy);
                }
            }
            if ($chiave == 'post') {
                $page_for_post = get_option('page_for_posts');
                if ($page_for_post) {
                    $preview[$chiave] = get_permalink($page_for_post);
                }
            }
            $object_t = get_post_type_object($chiave)->labels;
            $label_t = $object_t->name;
            $dceTemplate['post-types']['options'][$chiave] = $label_t;
            $dceTemplate['post-types']['templates'][$chiave]['single'] = __('Single', 'dynamic-content-for-elementor');
            $dceTemplate['post-types']['templates'][$chiave]['archive'] = __('Archive', 'dynamic-content-for-elementor');
        }

        // ------------------------------- [TAXONOMY] --------------------------
        $taxonomiesRegistered = get_taxonomies();
        $customTaxonomies = array_diff($taxonomiesRegistered, DCE_TemplateSystem::$excluded_taxonomies);
        //var_dump($taxonomiesRegistered);
        $dceTemplate['taxonomies']['label'] = __('Taxonomies', 'dynamic-content-for-elementor');
        foreach ($customTaxonomies as $chiave) {
            $terms = get_terms($chiave);
            if (!empty($terms)) {
                $preview[$chiave] = get_term_link(reset($terms));
            } else {
                $preview[$chiave] = get_home_url();
            }
            $object_t = get_taxonomy($chiave);
            $label_t = $object_t->label;
            $dceTemplate['taxonomies']['options'][$chiave] = $label_t;
            $dceTemplate['taxonomies']['templates'][$chiave]['single'] = __('Single', 'dynamic-content-for-elementor');
            $dceTemplate['taxonomies']['templates'][$chiave]['archive'] = __('Archive', 'dynamic-content-for-elementor');
        }

        $dceTemplate['other-pages']['label'] = __('Other Pages', 'dynamic-content-for-elementor');
        // ------------------------------- [SEARCH] ----------------------------
        $chiave = 'search';
        $preview[$chiave] = get_search_link('lorem ipsum');
        $dceTemplate['other-pages']['options'][$chiave] = __('Search', 'dynamic-content-for-elementor');
        $dceTemplate['other-pages']['templates'][$chiave]['archive'] = __('Archive', 'dynamic-content-for-elementor');

        // ------------------------------- [USER] ------------------------------
        $chiave = 'user';
        $preview[$chiave] = get_author_posts_url(get_current_user_id());
        $dceTemplate['other-pages']['options'][$chiave] = __('User', 'dynamic-content-for-elementor');
        $dceTemplate['other-pages']['templates'][$chiave]['archive'] = __('Archive', 'dynamic-content-for-elementor');

        $dce_template_disable = get_option('dce_template_disable');
        ?>

        <div class="dce-nav-menus-template nav-menus-php">
            <form action="" method="post">
                <div id="nav-menus-frame" class="wp-clearfix">
                    <div id="menu-settings-column" class="metabox-holder">
                        <div class="clear"></div>
                        <div id="side-sortables" class="accordion-container">
                            <div id="dce_template_disabler" class="text-center column-posts wp-tab-active">
                                <br><?php /*<h2 class="text-red red"><?php _e('DCE Template System', 'dynamic-content-for-elementor'); ?></h2> */ ?>
                                <label class="dce-radio-container dce-radio-container-template" onclick="jQuery(this).closest('.accordion-container').find('.accordion-section').addClass('open').removeClass('dce-disabled'); jQuery('#menu-management-liquid').removeClass('dce-disabled');">
                                    <input value="0" type="radio"<?php if (!$dce_template_disable) { ?> checked="checked"<?php } ?> name="dce_template_disable">
                                    <span class="dce-radio-checkmark"></span>
                                    <span class="dce-radio-label"><b><span class="dashicons dashicons-controls-play"></span> <?php _e('Enable', 'dynamic-content-for-elementor'); ?></b></span>
                                </label>
                                <label class="dce-radio-container dce-radio-container-template" onclick="jQuery(this).closest('.accordion-container').find('.accordion-section').removeClass('open').addClass('dce-disabled'); jQuery('#menu-management-liquid').addClass('dce-disabled');">
                                    <input value="1" type="radio"<?php if ($dce_template_disable) { ?> checked="checked"<?php } ?> name="dce_template_disable">
                                    <span class="dce-radio-checkmark dce-radio-checkmark-disable"></span>
                                    <span class="dce-radio-label"><b><span class="dashicons dashicons-controls-pause"></span> <?php _e('Disable', 'dynamic-content-for-elementor'); ?></b></span>
                                </label>
                                <br><br>
                                <hr class="mb-0" style="margin-bottom: 0;">
                            </div>
                            <ul class="outer-border">
                                <?php
                                $k = 0;
                                foreach ($dceTemplate as $tkey => $tvalue) {
                                ?>
                                    <li class="control-section accordion-section<?php if (!$k || true) { ?> open<?php } ?>" id="dce-<?php echo $tkey; ?>">
                                        <h3 class="accordion-section-title hndle" tabindex="0" onclick="jQuery(this).parent().toggleClass('open')">
                                            <?php echo $tvalue['label']; ?>
                                        </h3>
                                        <div class="accordion-section-content">
                                            <div class="dce-inside">
                                                <ul class="dce-template-list">
                                                    <?php
                                                    foreach ($tvalue['options'] as $chiave => $label_t) {

                                                        $dce_key = 'dyncontel_field_single' . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;
                                                        $dce_akey = 'dyncontel_field_archive' . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;
                                                        $dce_template_used_single = isset($this->options[$dce_key]) && $this->options[$dce_key] ? true : false;
                                                        $dce_template_used_archive = isset($this->options[$dce_akey]) && $this->options[$dce_akey] ? true : false;
                                                        $dce_template_used = $dce_template_used_single || $dce_template_used_archive ? true : false;

                                                        $dashicon = '';
                                                        if ($tkey == 'post-types') {
                                                            $obj = get_post_type_object( $chiave );
                                                            if ($obj && $obj->menu_icon) {
                                                                $dashicon = $obj->menu_icon;
                                                            } else {
                                                                $dashicon = 'dashicons-'.$chiave.' dashicons-admin-'.$chiave;
                                                                if ($chiave != 'page') {
                                                                  $dashicon = 'dashicons-admin-post '.$dashicon;
                                                                }
                                                            }
                                                            if ($chiave == 'attachment') {
                                                                $dashicon = 'dashicons-admin-media';
                                                            }
                                                        }
                                                        if ($tkey == 'taxonomies') {
                                                            $obj = get_taxonomy( $chiave );
                                                            if ($obj && $obj->hierarchical) {
                                                                $dashicon = 'dashicons-category';
                                                            } else {
                                                                $dashicon = 'dashicons-tag';
                                                            }
                                                        }
                                                        if ($tkey == 'other-pages') {
                                                            $dashicon = 'dashicons-'.$chiave.' dashicons-admin-'.$chiave;
                                                            if ($chiave == 'user') {
                                                                $dashicon = 'dashicons-admin-users';
                                                            }
                                                        }
                                                        ?>
                                                        <li class="dce-template-list-li<?php echo $chiave == 'post' ? ' nav-tab-selected' : ''; ?>">
                                                            <?php /* if (isset($preview[$chiave]) && $preview[$chiave]) { ?><a href="<?php echo $preview[$chiave]; ?>" target="_blank" class="dce-template-preview"><span class="dashicons dashicons-external"></span></a><?php } */
                                                            $dce_tkey = $tkey.'-'.$chiave;
                                                            ?>
                                                            <?php if ($dce_template_used_archive) { ?><a class="dce-quick-goto-active-setting" href="#<?php echo $dce_tkey.'-archive'; ?>"><span class="pull-right dashicons dashicons-exerpt-view"></span></a><?php } ?>
                                                            <?php if ($dce_template_used_single) { ?><a class="dce-quick-goto-active-setting" href="#<?php echo $dce_tkey.'-single'; ?>"><span class="pull-right dashicons dashicons-welcome-widgets-menus"></span></a><?php } ?>
                                                            <a class="nav-tab-link" href="#<?php echo $dce_tkey; ?>" onClick="
                                                                    jQuery('.dce-template-edit').addClass('hidden');
                                                                    jQuery(jQuery(this).attr('href')).removeClass('hidden');
                                                                    jQuery('.dce-nav-menus-template .nav-tab-selected').removeClass('nav-tab-selected');
                                                                    jQuery(this).parent().addClass('nav-tab-selected');
                                                                    var scrollmem = jQuery('html').scrollTop() || jQuery('body').scrollTop();
                                                                    location.hash = jQuery(this).attr('href').substr(1);
                                                                    jQuery('html,body').scrollTop(scrollmem);
                                                                    jQuery('.dce-quick-goto-active-setting-active').removeClass('dce-quick-goto-active-setting-active');
                                                                    return false;
                                                                ">
                                                                <!-- <input type="checkbox" disabled=""<?php if ($dce_template_used) { ?> checked="checked"<?php } ?>> -->
                                                                <?php if ($dashicon) {
                                                                    if (filter_var($dashicon, FILTER_VALIDATE_URL)) { ?>
                                                                        <img src="<?php echo $dashicon; ?>" height="20" width="20" style="vertical-align: middle; filter: invert(70%);">
                                                                    <?php } else { ?>
                                                                        <span class="dashicons <?php echo $dashicon; ?>"></span>
                                                                <?php }
                                                                    } ?>
                                                                <abbr title="<?php echo $chiave; ?>"><?php echo $label_t; ?></abbr>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                    $k++;
                                }
                                ?>

                            </ul>
                        </div>
                    </div>

                    <div id="menu-management-liquid">
                        <div id="menu-management">
                            <?php
                            $i = 0;
                            foreach ($dceTemplate as $tkey => $tvalue) {
                                foreach ($tvalue['options'] as $chiave => $label_t) {
                                ?>
                                    <div id="<?php echo $tkey.'-'.$chiave; ?>" class="menu-edit dce-template-edit <?php echo $i ? 'hidden' : ''; ?>">

                                        <div class="nav-menu-header">
                                            <div class="major-publishing-actions wp-clearfix">

                                                <ul class="dce-template-tabs <?php echo $tkey; ?>-tabs wp-tab-bar">
                                                    <?php
                                                    $t = 0;
                                                    foreach ($tvalue['templates'][$chiave] as $skey => $svalue) {
                                                        if ($skey == 'archive' && $chiave == 'attachment') { continue; }
                                                    ?>
                                                    <li class="dce-wp-tab<?php if (!$t) { ?> wp-tab-active<?php } ?>">
                                                        <a class="nav-tab-link" href="#<?php echo $tkey.'-'.$chiave.'-'.$skey; ?>" onClick="
                                                                                jQuery('#<?php echo $tkey.'-'.$chiave; ?> .dce-template-post-body').addClass('hidden');
                                                                                jQuery(jQuery(this).attr('href')).removeClass('hidden');
                                                                                jQuery('#<?php echo $tkey.'-'.$chiave; ?> .dce-wp-tab').removeClass('wp-tab-active');
                                                                                jQuery(this).parent().addClass('wp-tab-active');
                                                                                var scrollmem = jQuery('html').scrollTop() || jQuery('body').scrollTop();
                                                                                location.hash = jQuery(this).attr('href').substr(1);
                                                                                jQuery('html,body').scrollTop(scrollmem);
                                                                                jQuery('.dce-quick-goto-active-setting-active').removeClass('dce-quick-goto-active-setting-active');
                                                                                jQuery('.dce-quick-goto-active-setting[href=#<?php echo $tkey.'-'.$chiave.'-'.$skey; ?>]').addClass('dce-quick-goto-active-setting-active');
                                                                                return false;
                                                        ">
                                                            <span class="dashicons dashicons-<?php echo $skey == 'archive' ? 'exerpt-view' : 'welcome-widgets-menus'; ?>"></span>
                                                            <?php echo $svalue; ?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $t++;
                                                    }
                                                    ?>
                                                </ul>

                                                <div class="publishing-action">
                                                    <input type="submit" name="save_menu_header" class="save_menu save_menu_header button button-primary button-large menu-save" value="<?php _e('Save Settings', 'dynamic-content-for-elementor'); ?>">
                                                </div><!-- END .publishing-action -->
                                            </div><!-- END .major-publishing-actions -->
                                        </div>

                                        <?php
                                        $k = 0;
                                        foreach ($tvalue['templates'][$chiave] as $skey => $svalue) {
                                        ?>
                                            <div id="<?php echo $tkey.'-'.$chiave.'-'.$skey; ?>" class="post-body dce-template-post-body dce-template-post-body-<?php echo $skey; ?> <?php echo $k ? 'hidden' : ''; ?>"">
                                                <div class="post-body-content" class="wp-clearfix">
                                                    <div class="tabs-panel-template" class="tabs-panel tabs-panel-active">
                                                        <div class="tabs-panel-inner">
                                                            <h1>
                                                                <?php echo $svalue; ?> / <abbr title="<?php echo $chiave; ?>"><?php echo $label_t; ?></abbr>
                                                                <?php if (isset($preview[$chiave]) && $preview[$chiave]) { ?>
                                                                <a href="<?php echo $preview[$chiave]; ?>" target="_blank" class="dce-template-preview"><!--<small><?php _e('Preview', 'dynamic-content-for-elementor'); ?></small>--> <span class="dashicons dashicons-admin-links"></span></a>
                                                                <?php } ?>
                                                            </h1>
                                                            <!--<div class="drag-instructions post-body-plain">
                                                                <p>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>
                                                            </div>-->
                                                            <br>
                                                            <div class="dce-template-panel dce-template-main">

                                                                <?php if ($skey != 'single') { ?>
                                                                <div class="dce-template-panel dce-template-before">
                                                                    <?php
                                                                    $dce_key = 'dyncontel_before_field_' . $skey . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;
                                                                    // compatibility with old settings
                                                                    if ($dce_key == 'dyncontel_before_field_archiveuser') {
                                                                        if (!isset($this->options[$dce_key]) && isset($this->options['dyncontel_field_singleuser'])) {
                                                                            $this->options[$dce_key] = $this->options['dyncontel_field_singleuser'];
                                                                        }
                                                                    }
                                                                    if ($dce_key == 'dyncontel_before_field_archivesearch') {
                                                                        if (!isset($this->options[$dce_key]) && isset($this->options['dyncontel_field_singlesearch'])) {
                                                                            $this->options[$dce_key] = $this->options['dyncontel_field_singlesearch'];
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <div class="dce-template-icon dce-template-before-icon">
                                                                        <div class="dce-template-icon-bar dce-template-before-icon-bar<?php echo (isset($this->options[$dce_key]) && $this->options[$dce_key]) ? ' dce-template-icon-bar-template' : ''; ?>"></div>
                                                                    </div>
                                                                    <h4><?php _e('Before', 'dynamic-content-for-elementor'); ?></h4>
                                                                    <!--<label for="<?php echo $dce_key; ?>"><?php _e('Template', 'dynamic-content-for-elementor'); ?></label>-->
                                                                    <?php $this->_dce_settings_select_template($dce_key, $templates); ?>
                                                                </div>
                                                                <?php } ?>

                                                                <div class="dce-template-main-content">

                                                                    <?php
                                                                    $dce_key = 'dyncontel_field_' . $skey . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;

                                                                    $template = 'original';
                                                                    if ($skey == 'single') {
                                                                        if (isset($this->options[$dce_key.'_blank']) && $this->options[$dce_key.'_blank']) {
                                                                            if ($this->options[$dce_key.'_blank'] == 'canvas') {
                                                                                $template = 'canvas';
                                                                            } else {
                                                                                $template = 'full';
                                                                            }
                                                                        }
                                                                    } else {
                                                                        if ((isset($this->options[$dce_key]) && $this->options[$dce_key])) {
                                                                            if (isset($this->options[$dce_key.'_template']) && $this->options[$dce_key.'_template']) {
                                                                                $template = $this->options[$dce_key.'_template'];
                                                                            } else {
                                                                                $template = 'canvas';
                                                                            }
                                                                        }
                                                                    }
                                                                    if ($template == 'blank' && $chiave == 'user') {
                                                                        if (isset($this->options[$dce_key]) && $this->options[$dce_key]) {
                                                                            $template = 'canvas';
                                                                        } else {
                                                                            $template = 'original';
                                                                        }
                                                                    }
                                                                    //var_dump($dce_key);
                                                                    //var_dump($this->options[$dce_key]);
                                                                    //var_dump($template);
                                                                    ?>
                                                                    <div class="dce-template-page dce-template-content-<?php echo (isset($this->options[$dce_key]) && $this->options[$dce_key]) ? 'template' : 'original'; ?> dce-template-content-<?php echo $template; ?>">
                                                                        <div class="dce-template-page-content dce-template-page-content-<?php echo $template; ?>">
                                                                            <span class="dce-template-page-content-preview"></span>
                                                                            <span class="dce-template-page-content-preview"></span>
                                                                            <span class="dce-template-page-content-preview"></span>
                                                                            <span class="dce-template-page-content-preview"></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if ($skey == 'single') {
                                                                        ?>
                                                                        <h4><?php _e('Page Template', 'dynamic-content-for-elementor'); ?></h4>
                                                                        <!--<label for="<?php echo $dce_key; ?>"><?php _e('Template', 'dynamic-content-for-elementor'); ?></label>-->
                                                                        <?php $this->_dce_settings_select_template($dce_key, $templates); ?>
                                                                        <br><br>
                                                                        <?php
                                                                        $dce_key = 'dyncontel_field_' . $skey . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;
                                                                        //$this->_dce_settings_select_template_blank($dce_key);
                                                                        
                                                                        $dce_tkey = $dce_key . '_blank';
                                                                        $dce_template = isset($this->options[$dce_tkey]) ? $this->options[$dce_tkey] : false;
                                                                        //var_dump($dce_template);
                                                                        ?>
                                                                        <div class="dce-template-single-type">
                                                                            <h4><?php _e('Layout', 'dynamic-content-for-elementor'); ?></h4>
                                                                            <label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="0" type="radio"<?php if (!$dce_template || $dce_template == '0') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Default', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>
                                                                            <label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="header-footer" type="radio"<?php if ($dce_template == '1' || $dce_template == 'header-footer') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Full-Width', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>
                                                                            <label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="canvas" type="radio"<?php if ($dce_template == 'canvas') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Canvas', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    if ($skey == 'archive') {
                                                                        ?>
                                                                        <h4><?php _e('Template', 'dynamic-content-for-elementor'); ?></h4>
                                                                        <!--<label for="<?php echo $dce_key; ?>"><?php _e('Template', 'dynamic-content-for-elementor'); ?></label>-->
                                                                        <?php $this->_dce_settings_select_template($dce_key, $templates); ?>
                                                                        <br>

                                                                        <?php
                                                                        //var_dump($template);
                                                                        //$this->_dce_settings_select_template_layout($dce_key);
                                                                        $teaser_template = isset($this->options[$dce_key]) ? $this->options[$dce_key] : 0;
                                                                        $dce_tkey = $dce_key . '_template';
                                                                        $dce_template = isset($this->options[$dce_tkey]) ? $this->options[$dce_tkey] : 'canvas'; //false;
                                                                        //var_dump($dce_template);
                                                                        ?>
                                                                        <div class="dce-template-archive-type<?php echo (!$teaser_template) ? ' hidden' : ''; ?>">
                                                                            <h4><?php _e('Layout', 'dynamic-content-for-elementor'); ?></h4>
                                                                            <!--<label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="" type="radio"<?php if (!$dce_template) { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Default', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>-->
                                                                            <!--<label class="dce-radio-container">
                                                                                <input value="blocks" type="radio"<?php if ($dce_template == 'blocks') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Blocks', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>-->
                                                                            <label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="canvas" type="radio"<?php if (!$dce_template || $dce_template == 'canvas') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Canvas', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label><label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="boxed" type="radio"<?php if ($dce_template == 'boxed') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Boxed', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label><label class="dce-radio-container dce-radio-container-template">
                                                                                <input value="blank" type="radio"<?php if ($dce_template == 'blank') { ?> checked="checked"<?php } ?> name="<?php echo DCE_OPTIONS; ?>[<?php echo $dce_tkey; ?>]">
                                                                                <span class="dce-radio-checkmark"></span>
                                                                                <span class="dce-radio-label"><?php _e('Full-Width', 'dynamic-content-for-elementor'); ?></span>
                                                                            </label>

                                                                            <br><br>
                                                                            <div class="dce-template-archive-blocks<?php if (!in_array($dce_template, array('full', 'boxed', 'blank'))) { ?> hidden<?php } ?>">
                                                                                <!--<?php _e('Mode', 'dynamic-content-for-elementor'); ?><br><br>-->
                                                                                <?php $this->_dce_settings_archive($dce_key); ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php /*
                                                                        <br>
                                                                        <div class="dce-template-teaser<?php echo ($dce_template == 'canvas') ? ' hidden' : ''; ?>">
                                                                            <div class="dce-template-archive-row dce-elementor-control-responsive-switchers">
                                                                                <?php
                                                                                $teaser_template = isset($this->options[$dce_key]) ? $this->options[$dce_key] : 0;
                                                                                $sizes = array('md', 'xs', 'sm');
                                                                                foreach ($sizes as $key => $asize) {
                                                                                    $dce_key = 'dyncontel_field_'.$skey.$chiave.'_col_'.$asize;
                                                                                    $cols = (isset($this->options[$dce_key])) ? $this->options[$dce_key] : 4;
                                                                                    ?>
                                                                                    <div class="dce-input-<?php echo $asize; ?>">
                                                                                    <?php
                                                                                    for ($i=0; $i<$cols; $i++) { ?>
                                                                                        <div class="dce-template-archive-col teaser<?php echo $teaser_template ? ' dce-template-archive-col-teaser' : ''; ?>"></div>
                                                                                    <?php } ?>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        */ ?>
                                                                    <?php } ?>
                                                                </div>

                                                                <?php if ($skey != 'single') { ?>
                                                                <div class="dce-template-panel dce-template-after">
                                                                    <?php
                                                                    $dce_key = 'dyncontel_after_field_' . $skey . ($tkey == 'taxonomies' ? '_taxonomy_' : '') . $chiave;
                                                                    ?>
                                                                    <div class="dce-template-icon dce-template-after-icon">
                                                                        <div class="dce-template-icon-bar dce-template-after-icon-bar<?php echo (isset($this->options[$dce_key]) && $this->options[$dce_key]) ? ' dce-template-icon-bar-template' : ''; ?>"></div>
                                                                    </div>
                                                                    <h4><?php _e('After', 'dynamic-content-for-elementor'); ?></h4>
                                                                    <!--<label for="<?php echo $dce_key; ?>"><?php _e('Template', 'dynamic-content-for-elementor'); ?></label>-->
                                                                    <?php $this->_dce_settings_select_template($dce_key, $templates); ?>
                                                                </div>
                                                                <?php } ?>

                                                            </div>

                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        <?php
                                            $k++;
                                        }
                                        ?>

                                        <div class="nav-menu-footer">
                                            <div class="major-publishing-actions wp-clearfix">
                                                <div class="publishing-action">
                                                    <input type="submit" name="save_menu_footer" class="save_menu save_menu_footer button button-primary button-large menu-save" value="<?php _e('Save Settings', 'dynamic-content-for-elementor'); ?>">
                                                </div><!-- END .publishing-action -->
                                            </div><!-- END .major-publishing-actions -->
                                        </div>

                                    </div><!-- /.menu-edit -->
                            <?php
                                $i++;
                                }
                            } ?>
                        </div><!-- /#menu-management -->
                    </div><!-- /#menu-management-liquid -->
                </div>
                <input type="hidden" value="update" name="action">
            </form>
        </div>

        <script>
        jQuery(document).ready(function(){

            // reopen last settings
            if (location.hash) {
                var hash = location.hash;
                var mbtn = '#'+jQuery('.nav-tab-link[href='+hash+']').closest('.dce-template-edit').attr('id');
                jQuery('.nav-tab-link[href='+mbtn+']').trigger('click');
                jQuery('.nav-tab-link[href='+hash+']').trigger('click');
            }

            jQuery('.dce-quick-goto-active-setting').on('click', function(){
                var href = jQuery(this).attr('href');
                //var soa = href.split('-').pop();
                var mbtn = jQuery(this).closest('.dce-template-list-li').find('.nav-tab-link');
                mbtn.trigger('click');
                jQuery('.nav-tab-link[href='+href+']').trigger('click');
                var scrollmem = jQuery('html').scrollTop() || jQuery('body').scrollTop();
                location.hash = href.substr(1);
                jQuery('html,body').scrollTop(scrollmem);
                jQuery(this).addClass('dce-quick-goto-active-setting-active');
                return false;
            });

            jQuery('.dce-template-quick-remove').on('click', function(){
                var quick_remove = jQuery(this).closest('.dce-template-select-wrapper').find('.dce-select-template');
                quick_remove.val(0);
                quick_remove.trigger('change');
                jQuery(this).addClass('hidden');
                return false;
            });

            jQuery('.dce-select-template').on('change', function(){
                var quick_edit = jQuery(this).closest('.dce-template-select-wrapper').find('.dce-template-quick-edit');
                var quick_remove = jQuery(this).closest('.dce-template-select-wrapper').find('.dce-template-quick-remove');
                if (jQuery(this).val() > 0) {
                    quick_remove.removeClass('hidden');
                    quick_edit.removeClass('hidden');
                    quick_edit.attr('href', quick_edit.data('href')+jQuery(this).val());
                } else {
                    quick_edit.addClass('hidden');
                    quick_edit.addClass('hidden');
                }
            });

            jQuery('.dce-template-post-body-single .dce-select-template').on('change', function(){
                if (jQuery(this).val() > 0) {
                    //alert(jQuery(this).val());
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').removeClass('dce-template-content-original').addClass('dce-template-content-template');
                } else {
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').addClass('dce-template-content-original').removeClass('dce-template-content-template');
                    //jQuery(this).closest('.dce-template-main-content').find('.dce-template-page-content').addClass('dce-template-page-content-original').removeClass('dce-template-page-content-full');
                    console.log(jQuery(this).attr('id')+'_blank');
                    //jQuery('#'+jQuery(this).attr('id')+'_blank').trigger('click');
                }
            });

            jQuery('.dce-template-post-body-archive .dce-select-template').on('change', function(){
                if (jQuery(this).val() > 0) {
                    //alert(jQuery(this).val());
                    //jQuery(this).closest('.dce-template-main-content').find('.dce-template-archive-col').addClass('dce-template-archive-col-teaser');
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-archive-type').removeClass('hidden');
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').removeClass('dce-template-content-original').addClass('dce-template-content-template');
                    jQuery(this).closest('.dce-template-main-content').find('.dce-radio-container input[type=radio]:checked').trigger('click');
                } else {
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').addClass('dce-template-content-original').removeClass('dce-template-content-template');
                    //jQuery(this).closest('.dce-template-main-content').find('.dce-template-archive-col').removeClass('dce-template-archive-col-teaser');
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-archive-type').addClass('hidden');
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page-content').attr('class', 'dce-template-page-content dce-template-page-content-original');
                }
            });
            
            
            
            

            jQuery('.dce-template-before .dce-select-template, .dce-template-after .dce-select-template').on('change', function(){
                if (jQuery(this).val() > 0) {
                    //alert(jQuery(this).val());
                    jQuery(this).closest('.dce-template-panel').find('.dce-template-icon-bar').addClass('dce-template-icon-bar-template');
                } else {
                    jQuery(this).closest('.dce-template-panel').find('.dce-template-icon-bar').removeClass('dce-template-icon-bar-template');
                }
            });

            jQuery('.dce-template-post-body-archive .dce-radio-container-template').on('click', function(){
                var value = jQuery(this).find('input[type=radio]').val();
                console.log(value);
                if (value && value != 'canvas') {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-archive-blocks').removeClass('hidden');
                } else {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-archive-blocks').addClass('hidden');
                }
                if (!value) {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-teaser').addClass('hidden');
                } else {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-teaser').removeClass('hidden');
                }
                if (!value) {
                    value = 'original';
                }
                if (value == 'blank') {
                    value = 'full';
                }
                jQuery(this).closest('.dce-template-main-content').find('.dce-template-page-content').attr('class', 'dce-template-page-content dce-template-page-content-'+value);
            });
            
            jQuery('.dce-template-post-body-single .dce-radio-container-template').on('click', function(){
                var value = jQuery(this).find('input[type=radio]').val();
                console.log(value);
                if (value && value != 'canvas') {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-single-blocks').removeClass('hidden');
                } else {
                    jQuery(this).closest('.dce-template-main').find('.dce-template-single-blocks').addClass('hidden');
                }
                if (!value || value == '0') {
                    value = 'original';
                }
                if (value == 'header-footer' || value == 1 || value == '1') {
                    value = 'full';
                }
                if (value == 'canvas' || value == 2 || value == '2') {
                    value = 'canvas';
                }
                jQuery(this).closest('.dce-template-main-content').find('.dce-template-page-content').attr('class', 'dce-template-page-content dce-template-page-content-'+value);
                
                jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').removeClass('dce-template-content-canvas').removeClass('dce-template-content-default').removeClass('dce-template-content-full');
                if (value != 'original') {
                    jQuery(this).closest('.dce-template-main-content').find('.dce-template-page').addClass('dce-template-content-'+value);
                }
            });            
            
            <?php if ($dce_template_disable) { ?>
                jQuery('#menu-management-liquid').addClass('dce-disabled');
                jQuery('#menu-settings-column .accordion-section').removeClass('open').addClass('dce-disabled');;
            <?php } ?>
        });
        
        
        </script>

        <?php
    }

}
