<?php

namespace DynamicContentForElementor;

//require_once ABSPATH .'wp-admin/includes/class-wp-upgrader-skins.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

class DCE_Upgrader_Skin extends \WP_Upgrader_Skin {

    public function feedback($string, ...$args) {
        if (isset($this->upgrader->strings[$string])) {
            $string = $this->upgrader->strings[$string];
        }

        if (strpos($string, '%') !== false) {
            if ($args) {
                $args = array_map('strip_tags', $args);
                $args = array_map('esc_html', $args);
                $string = vsprintf($string, $args);
            }
        }
        if (empty($string)) {
            return;
        }
        //show_message( $string );
    }

}

class DCE_License {

    public $license_key;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->activation_advisor();

        // gestisco lo scaricamento dello zip aggiornato inviando i dati della licenza
        add_filter('upgrader_pre_download', array($this, 'filter_upgrader_pre_download'), 10, 3);
    }

    static public function set_constant() {
        define('SL_APP_DEMO_URL', 'https://www.dynamic.ooo');
        //the url where the WooCommerce Software License plugin is being installed
        define('SL_APP_API_URL', 'https://shop.dynamic.ooo');
        //the Software Unique ID as defined within product admin page
        define('SL_PRODUCT_ID', 'WP-DCE-1');
        //A code variable constant is required, which is the user application code version. This will be used by API to compare against the new version on shop server.
        define('SL_VERSION', DCE_VERSION);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        define('SL_INSTANCE', str_replace($protocol, "", get_bloginfo('wpurl')));
        $license = get_option(SL_PRODUCT_ID . '_license_key');
        define('SL_LICENSE', $license);
    }

    public function activation_advisor() {
        $license_activated = get_option(SL_PRODUCT_ID . '_license_activated');
        //var_dump($license_activated);
        $tab_license = (isset($_GET['tab']) && $_GET['tab'] == 'license') ? true : false;
        if (!$license_activated && !$tab_license) {
            add_action('admin_notices', '\DynamicContentForElementor\DCE_Notice::dce_admin_notice__license');
            add_filter('plugin_action_links_' . DCE_PLUGIN_BASE, '\DynamicContentForElementor\DCE_License::dce_plugin_action_links_license');
        }
    }

    // define the upgrader_pre_download callback
    public function filter_upgrader_pre_download($false, $package, $instance) {
        //var_dump($package);
        //var_dump($instance);
        //die();
        // ottengo lo slug del plugin corrente
        $plugin = false;
        if (property_exists($instance, 'skin')) {
            if ($instance->skin) {
                if (property_exists($instance->skin, 'plugin')) {
                    // aggiornamento da pagina
                    if ($instance->skin->plugin) {
                        $pezzi = explode('/', $instance->skin->plugin);
                        $plugin = reset($pezzi);
                    }
                }
                if (!$plugin && isset($instance->skin->plugin_info["TextDomain"])) {
                    // aggiornamento ajax
                    $plugin = $instance->skin->plugin_info["TextDomain"];
                }
            }
        }
        //var_dump($plugin); die();
        // agisco solo per il mio plugin
        if ($plugin == DCE_TEXTDOMAIN || isset($_POST['dce_version'])) {
            return $this->upgrader_pre_download($package, $instance);
            //\DynamicContentForElementor\DCE_license::upgraderPreDownload();
        }
        return $false;
    }

    public function upgrader_pre_download($package, $instance = null) {
        //solo se stò aggiornando lo shop stesso (caso isolato)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $shopInstance = str_replace($protocol, "", SL_APP_API_URL);
        if (SL_INSTANCE == $shopInstance) {
            global $wp_filesystem;
            $file = $wp_filesystem->abspath() . '.maintenance';
            $wp_filesystem->delete($file);
        }
        // ora verifico la licenza per l'aggiornamento
        //$license_status = self::call_api('plugin_update', SL_LICENSE, false);
        $license = self::call_api('status-check', SL_LICENSE, false, true);
        if (!self::is_active($license)) {
            if (!SL_LICENSE) {
                // l'utente non ha ancora impostato alcun codice di licenza
                return new \WP_Error('no_license', __('You have not entered the license.', 'dynamic-content-for-elementor') . ' <a target="_blank" href="' . SL_APP_API_URL . '">' . __('If you do not have one then buy it now', 'dynamic-content-for-elementor') . '</a>');
            }
            // qualcosa è andato storto...stampo tutti gli errori
            if (is_wp_error($license) || $license['response']['code'] != 200) {
                return new \WP_Error('no_license', __('Error connecting to the server.', 'dynamic-content-for-elementor') . ' -- KEY: ' . SL_LICENSE . ' - DOMAIN: ' . SL_INSTANCE . ' - STATUS-CHECK: ' . var_export($license_dump, true));
            }
            // oppure semplicemente la licenza utilizzata non è attiva o valida
            return new \WP_Error('no_license', __('Your license is not valid', 'dynamic-content-for-elementor') . ' <a href="' . admin_url() . 'admin.php?page=dce_opt&tab=license&licence_check=1">' . __('Check it in the plugin settings', 'dynamic-content-for-elementor') . '</a>.');
        }
        if (self::is_expired($license)) {
            // la licenza è scaduta
            return new \WP_Error('no_license', __('Your license is not valid for plugin updates, probably is expired', 'dynamic-content-for-elementor') . ' <a href="' . admin_url() . 'admin.php?page=dce_opt&tab=license&licence_check=1">' . __('Check it in the plugin settings', 'dynamic-content-for-elementor') . '</a>.');
        }
        
        // aggiungo quindi le info aggiuntive della licenza alla richiesta per abilitarmi al download
        $package .= (strpos($package, '?') === false) ? '?' : '&';
        $package .= 'license_key=' . SL_LICENSE . '&license_instance=' . SL_INSTANCE;
        if (get_option('dce_beta', false)) {
            $package .= '&beta=true';
        }
        //$instance->skin->feedback( 'downloading_package', $package );
        self::plugin_backup();
        //return new WP_Error('no_license', $package);
        $download_file = download_url($package);
        if (is_wp_error($download_file))
            return new \WP_Error('download_failed', __('Error downloading the update package', 'dynamic-content-for-elementor'), $download_file->get_error_message());
        return $download_file;
    }

    static public function plugin_backup() {
        // do a zip of current version
        $dce_backup = !get_option('dce_backup_disable');
        if ($dce_backup) {
            // create zip in /wp-content/backup
            if (!is_dir(DCE_BACKUP_PATH)) {
                mkdir(DCE_BACKUP_PATH, 0777, true);
            }
            $outZipPath = DCE_BACKUP_PATH . '/' . DCE_TEXTDOMAIN . '_' . DCE_VERSION . '.zip';
            if (is_file($outZipPath)) {
                unlink($outZipPath);
            }

            $options = array(
                'source_directory' => DCE_PATH,
                'zip_filename' => $outZipPath,
                'zip_foldername' => DCE_TEXTDOMAIN,
            );
            
            if (extension_loaded('zip')) {
                DCE_Helper::zip_folder($options);
            }
            //die($options['zip_filename']);
        }
    }

    static public function call_api($action, $license_key, $iNotice = false, $debug = false) {
        global $wp_version;
        $args = array(
            'woo_sl_action' => $action,
            'licence_key' => $license_key,
            'product_unique_id' => SL_PRODUCT_ID,
            'domain' => SL_INSTANCE,
            'api_version' => '1.1',
            'wp-version' => $wp_version,
            'version' => DCE_VERSION,
        );
        $request_uri = SL_APP_API_URL . '?' . http_build_query($args);
        $data = wp_remote_get($request_uri);
        //var_dump($args); //die();
        //echo '--------';var_dump($data);

        if (is_wp_error($data) || $data['response']['code'] != 200) {
            //echo '-- ERROR 200 --'; var_dump($data);
            if ($debug) {
                return $data;
            }
            //there was a problem establishing a connection to the API server
            add_action('admin_notices', 'DCE_Notice::dce_admin_notice__server_error');
            return false;
        }

        $data_body = json_decode($data['body']);
        if (is_array($data_body)) {
            $data_body = reset($data_body);
        }
        //var_dump($data_body);
        if (isset($data_body->status)) {
            if ($data_body->status == 'success') {
                if (($action == 'status-check' && ($data_body->status_code == 's200' || $data_body->status_code == 's205')) ||
                        ($action == 'activate' && ($data_body->status_code == 's100' || $data_body->status_code == 's101')) ||
                        ($action == 'deactivate' && $data_body->status_code == 's201') ||
                        ($action == 'plugin_update' && $data_body->status_code == 's401')) {
                    //the license is active and the software is active
                    $message = $data_body->message;
                    $expiration_date = self::get_expiration_date($data);
                    if ($expiration_date) {
                        $message .= '. <b>Expiration date:</b> ' . $expiration_date;
                        if (self::is_expired($data)) {
                            update_option('dce_beta', false);
                        }
                    }
                    if ($iNotice) {
                        DCE_Notice::dce_admin_notice__success($message);
                    } else {
                        add_option('dce_notice', $message);
                        add_action('admin_notices', 'DCE_Notice::dce_admin_notice__success');
                    }
                    //doing further actions like saving the license and allow the plugin to run
                    //var_dump($data_body);
                    if ($debug) {
                        return $data;
                    }
                    return true;
                } else {
                    if ($debug) {
                        return $data;
                    }
                    if ($iNotice) {
                        DCE_Notice::dce_admin_notice__warning($data_body->message);
                    } else {
                        add_option('dce_notice', $data_body->message . ' - domain: ' . SL_INSTANCE);
                        add_action('admin_notices', 'DCE_Notice::dce_admin_notice__warning');
                    }
                    update_option('dce_beta', false);
                    //var_dump($data_body); //die();
                    //return $data_body;
                    return false;
                }
            } else {
                if ($debug) {
                    return $data;
                }
                //there was a problem activating the license
                if ($iNotice) {
                    DCE_Notice::dce_admin_notice__warning($data_body->message);
                } else {
                    add_option('dce_notice', $data_body->message . ' - domain: ' . SL_INSTANCE);
                    add_action('admin_notices', 'DCE_Notice::dce_admin_notice__warning');
                }
                //var_dump($data_body); //die();
                //return $data_body;
                return false;
            }
        } else {
            //echo '-- ERROR status --'; //var_dump($data);
            if ($debug) {
                return $data;
            }
            //there was a problem establishing a connection to the API server
            add_action('admin_notices', 'DCE_Notice::dce_admin_notice__server_error');
            return false;
        }
    }

    static public function is_active($data) {
        if (!is_wp_error($data)) {
            if (isset($data['body'])) {
                $data_body = json_decode($data['body']);
                if (is_array($data_body)) {
                    $data_body = reset($data_body);
                }
                //var_dump($data_body);
                if (isset($data_body->status)) {
                    if ($data_body->status == 'success') {
                        if ((($data_body->status_code == 's200' || $data_body->status_code == 's205')) ||
                                (($data_body->status_code == 's100' || $data_body->status_code == 's101')) ||
                                ($data_body->status_code == 's201') ||
                                ($data_body->status_code == 's401')) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    static public function get_expiration_date($data) {
        if (!is_wp_error($data)) {
            if (isset($data['body'])) {
                $data_body = json_decode($data['body']);
                if (is_array($data_body)) {
                    $data_body = reset($data_body);
                }
                //var_dump($data_body);
                if (property_exists($data_body, 'licence_expire')) {
                    if ($data_body->licence_expire) {
                        return $data_body->licence_expire;
                    }
                }
            }
        }
        return false;
    }

    static public function is_expired($data) {
        $expiration_date = self::get_expiration_date($data);
        if ($expiration_date) {
            if ($expiration_date < date('Y-m-d')) {
                return true;
            }
        }
        return false;
    }

    static public function show_license_form() {

        $licence_key = SL_LICENSE;
        if (isset($_POST['licence_key'])) {
            if (intval($_POST['licence_status'])) {
                $res = self::call_api('deactivate', $licence_key);
                if ($res) {
                    update_option(SL_PRODUCT_ID . '_license_activated', 0);
                }
            }
            $licence_key = $_POST['licence_key'];
            if (SL_LICENSE != $licence_key || !intval($_POST['licence_status'])) {
                // aggiorno la chiave di licenza inserita
                update_option(SL_PRODUCT_ID . '_license_key', $licence_key);
                // provo ad attivare con la nuova chiave
                $res = self::call_api('activate', $licence_key, true);

                // mi salvo lo stato della licenza per non effettuare troppe chiamate al server
                if ($res) {
                    update_option(SL_PRODUCT_ID . '_license_activated', 1);
                    update_option(SL_PRODUCT_ID . '_license_domain', base64_encode(SL_INSTANCE));
                } else {
                    update_option(SL_PRODUCT_ID . '_license_activated', 0);
                    $licence_key = '';
                }
            }
        }

        if (isset($_POST['beta_status'])) {
            if (isset($_POST['dce_beta'])) {
                update_option('dce_beta', 1);
            } else {
                update_option('dce_beta', 0);
            }
        }
        
        if (isset($_POST['backup_status'])) {
            if (isset($_POST['dce_backup_disable'])) {
                update_option('dce_backup_disable', 0);
            } else {
                update_option('dce_backup_disable', 1);
            }
        }
        
        $licence_check = isset($_GET['licence_check']) ? $_GET['licence_check'] : false;
        $license_data = self::call_api('status-check', $licence_key, $licence_check, true);
        if ($license_data) {
            $expiration_date = self::get_expiration_date($license_data);
            update_option(SL_PRODUCT_ID . '_license_expiration', $expiration_date);
        }
        $licence_status = ($licence_key && self::is_active($license_data));
        //var_dump($licence_key);
        //var_dump(self::call_api('plugin_update', $licence_key, $licence_check));
        //$update_status = ($licence_check && self::call_api('plugin_update', $licence_key, $licence_check));

        $licence_key_hidden = '';
        $licence_pieces = explode('-', $licence_key);
        if (isset($licence_pieces[1]) && isset($licence_pieces[2])) {
            $licence_pieces[1] = $licence_pieces[2] = 'xxxxxxxx';
            $licence_key_hidden = implode('-', $licence_pieces);
        }

        $dce_domain = base64_decode(get_option(SL_PRODUCT_ID . '_license_domain'));
        //var_dump($dce_domain);
        $dce_activated = intval(get_option(SL_PRODUCT_ID . '_license_activated', 0));
        $classes = ($licence_status) ? 'dce-success dce-notice-success' : 'dce-error dce-notice-error';
        if ($dce_activated && $licence_status && $dce_domain && $dce_domain != SL_INSTANCE) {
            $classes = 'dce-warning dce-notice-warning';
        }
        ?>
        <div class="dce-notice <?php echo $classes; ?>">
            <h2>LICENSE Status <a href="?<?php echo $_SERVER['QUERY_STRING']; ?>&licence_check=1"><span class="dashicons dashicons-info"></span></a></h2>
            <form action="" method="post">
                <?php _e('Your key', 'dynamic-content-for-elementor'); ?>: <input type="text" name="licence_key" value="<?php
                         if ($dce_activated) {
                             echo $licence_key_hidden;
                         }
                         ?>" id="licence_key" placeholder="dce-xxxxxxxx-xxxxxxxx-xxxxxxxx" style="width: 240px; max-width: 100%;">
                <input type="hidden" name="licence_status" value="<?php echo $licence_status; ?>" id="licence_status">
            <?php ($licence_status) ? submit_button('Deactivate', 'cancel') : submit_button('Save Key and Activate'); ?>
            </form>
        <?php
        if ($licence_status) {
            if ($dce_domain && $dce_domain != SL_INSTANCE) {
                ?>
                    <p><strong style="color:#f0ad4e;"><?php _e('Your license is valid but there is something wrong: <b>License Mismatch</b>.', 'dynamic-content-for-elementor'); ?></strong></p>
                    <p><?php _e('Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL. Please deactivate the license and then reactivate it again.', 'dynamic-content-for-elementor'); ?></p>
                <?php } else { ?>
                    <p><strong style="color:#46b450;"><?php _e('Your license is valid and active.', 'dynamic-content-for-elementor'); ?></strong></p>
                    <p><?php _e('Thank you for choosing to use our plugin.', 'dynamic-content-for-elementor'); ?><br><?php _e('Feel free to create your new dynamic and creative website.', 'dynamic-content-for-elementor'); ?><br><?php _e('If you think that our widgets are fantastic do not forget to recommend it to your friends.', 'dynamic-content-for-elementor'); ?></p>
                <?php
                }
            } else {
                ?>
                <p><?php _e('Enter your license here to keep the plugin updated, obtaining new widgets, future compatibility, more stability and security.', 'dynamic-content-for-elementor'); ?></p>
                <p><?php _e('Do not you have one yet? Get it right away:', 'dynamic-content-for-elementor'); ?> <a href="http://www.dynamic.ooo" class="button button-small" target="_blank"><?php _e('visit our official page', 'dynamic-content-for-elementor'); ?></a></p>
        <?php } ?>
        </div>

        <?php
        if ($licence_status) {
            $dce_beta = get_option('dce_beta');
            ?>
            <div class="dce-notice dce-warning dce-notice-warning">
                <h3><?php _e('Beta release', 'dynamic-content-for-elementor'); ?></h3>
                <form action="" method="post">
                    <label><input type="checkbox" name="dce_beta" value="beta"<?php if ($dce_beta) { ?> checked="checked"<?php } ?>> <?php _e('Enable BETA releases (IMPORTANT: do NOT enable if you need a stable version).', 'dynamic-content-for-elementor'); ?></label>
                    <input type="hidden" name="beta_status" value="1" id="beta_status">
            <?php submit_button('Save my preference'); ?>
                </form>
            </div>

            <?php
            if (extension_loaded('zip')) {
                $dce_backup = !get_option('dce_backup_disable');
                ?>
                <div class="dce-notice dce-<?php echo $dce_backup ? 'success' : 'error'; ?> dce-notice-<?php echo $dce_backup ? 'success' : 'error'; ?>">
                    <h3><?php _e('Safe upgrade', 'dynamic-content-for-elementor'); ?></h3>
                    <form action="" method="post">
                        <label><input type="checkbox" name="dce_backup_disable" value="backup"<?php if ($dce_backup) { ?> checked="checked"<?php } ?>> <?php _e('Perform a plugin Backup of the current version before the update action that allows easy Rollback.', 'dynamic-content-for-elementor'); ?></label>
                        <input type="hidden" name="backup_status" value="1" id="backup_status">
                <?php submit_button('Save my preference'); ?>
                    </form>
                </div>
                <?php
            }
            
            $rollback_versions = array(DCE_VERSION => DCE_VERSION);
            /*
            $rolls = SL_APP_API_URL . '/dce/rollback.php?v=' . DCE_VERSION . '&k=' . SL_LICENSE;
            $roll_response = wp_remote_get($rolls);
            $roll_code = wp_remote_retrieve_response_code($roll_response);
            if ($roll_response && !is_wp_error($roll_response) && $roll_code == 200) {
                $roll_body = wp_remote_retrieve_body($roll_response);
                $roll_body = json_decode($roll_body, true);
                $rollback_versions = json_decode($roll_body, true);
            }
            */

            $backups = glob(DCE_BACKUP_PATH.'/'.DCE_TEXTDOMAIN.'_*.zip');
            if (!empty($backups)) {
                foreach ($backups as $bak) {
                    list($pkg, $bak_version) = explode('_', str_replace('.zip', '', basename($bak)));
                    $rollback_versions[$bak_version] = $bak_version;  
                }
                //var_dump($backups);
                ?>
                <div class="dce-notice dce-error dce-notice-error">
                    <h3><?php _e('RollBack version', 'dynamic-content-for-elementor'); ?></h3>
                    <form action="" method="post">
                        <h4><?php _e('Your Current version', 'dynamic-content-for-elementor'); ?>: <?php echo DCE_VERSION; ?></h4>
                        <p><?php echo sprintf(__( 'Experiencing an issue with Dynamic Content for Elementor version %s? Rollback to a previous version before the issue appeared.', 'dynamic-content-for-elementor' ), DCE_VERSION);
                        //_e('IMPORTANT: if you upgraded and get problems with new plugin, then you can rollback to previous stable version.', 'dynamic-content-for-elementor'); ?></p>
                        <label><?php _e('Select version', 'dynamic-content-for-elementor'); ?>:</label>
                        <select name="dce_version" id="dce_version">
                            <?php
                            if (!empty($rollback_versions)) {
                                foreach ($rollback_versions as $aversion) { ?>
                                    <option value="<?php echo $aversion; ?>"><?php echo $aversion; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                        <?php submit_button('Rollback NOW'); ?>
                    </form>
                </div>
                <?php
            }
            
        }
    }

    public static function do_rollback() {

        // rollback or reinstall
        if (isset($_POST['dce_version']) && $_POST['dce_version']) {            
            if ($_POST['dce_version'] == DCE_VERSION) {
                // same version...so no change :)
                $rollback = true;
            } else {
                $backup = DCE_BACKUP_PATH.'/'.DCE_TEXTDOMAIN.'_'.$_POST['dce_version'].'.zip';
                if (is_file($backup)) {
                    // from local backup
                    $roll_url = DCE_BACKUP_URL.'/'.DCE_TEXTDOMAIN.'_'.$_POST['dce_version'].'.zip';;                  
                } else {
                    // from server                
                    $roll_url = SL_APP_API_URL . '/dce/last.php?v=' . $_POST['dce_version'];                
                }
                //var_dump($roll_url); die();

                ob_start();            
                $wp_upgrader_skin = new \DynamicContentForElementor\DCE_Upgrader_Skin();
                $wp_upgrader = new \WP_Upgrader($wp_upgrader_skin);
                $wp_upgrader->init();
                $rollback = $wp_upgrader->run(array('package' => $roll_url, 'destination' => DCE_PATH, 'clear_destination' => true));
                $roll_status = ob_get_flush();
            }
            if ($rollback) {
                exit(wp_redirect("admin.php?page=dce_info"));
            } else {
                die($roll_status);
            }
        }
    }
    
    public static function check_for_updates($file) {
        // Verify updates
        $info = self::check_for_updates_url();
        $myUpdateChecker = \Puc_v4p8_Factory::buildUpdateChecker(
            $info,
            $file,
            'dynamic-content-for-elementor'
        );
    }
    public static function check_for_updates_url() {
        // Verify updates
        $info = SL_APP_API_URL . '/dce/info.php?s=' . SL_INSTANCE . '&v=' . DCE_VERSION;
        if (SL_LICENSE) {
            $info .= '&k=' . SL_LICENSE;
        }
        if (get_option('dce_beta', false)) {
            $info .= '&beta=true';
        }
        //var_dump($info); die();
        return $info;
    }

    public static function dce_plugin_action_links_license($links) {
        $links['license'] = '<a style="color:brown;" title="Activate license" href="' . admin_url() . 'admin.php?page=dce_opt&tab=license"><b>' . __('License', 'dynamic-content-for-elementor') . '</b></a>';
        return $links;
    }

    public static function dce_active_domain_check() {
        $dce_activated = intval(get_option(SL_PRODUCT_ID . '_license_activated', 0));
        $dce_domain = base64_decode(get_option(SL_PRODUCT_ID . '_license_domain'));
        if ($dce_activated && $dce_domain && $dce_domain != SL_INSTANCE) {
            DCE_Notice::dce_admin_notice__warning(__('<b>License Mismatch</b><br>Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL. Please deactivate the license and then reactivate it again. <a class="btn button" href="' . admin_url() . 'admin.php?page=dce_opt&tab=license">Reactivate License</a>', 'dynamic-content-for-elementor'));
            return false;
        }
        return true;
    }

    public static function dce_expired_license_notice() {
        $dce_expiration_date = get_option(SL_PRODUCT_ID . '_license_expiration');
        if ($dce_expiration_date) {
            if ($dce_expiration_date < date('Y-m-d')) {
                DCE_Notice::dce_admin_notice__danger(__('<b>your License Expired on ' . $dce_expiration_date . '</b><br>Please renew your license or you can\'t get more plugin updates. <a class="btn button" target="_blank" href="https://shop.dynamic.ooo">Extend your license now</a>', 'dynamic-content-for-elementor'));
                return false;
            }
        }
        return true;
    }

}
