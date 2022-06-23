<?php
namespace Aepro;

use Aepro\EDD_SL_Plugin_Updater;

class License{

    private static $_instance;

    private static $_store_url = 'https://shop.webtechstreet.com';

    private static $_item_name = 'AnyWhere Elementor Pro';

    private static $_transient_lifetime = 43200;


    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){

        add_action('admin_menu', [$this, 'license_menu']);
        add_action( 'admin_init', [$this, 'register_license_option']);



        add_action( 'admin_init', [$this, 'ae_plugin_updater'] , 0 );

        add_action( 'admin_init', [$this, 'license_update']);

        add_action( 'admin_notices', [ $this, 'admin_notices'] );

        register_activation_hook(AE_PRO_FILE, [ $this, 'plugin_activated']);
    }

    public function plugin_activated(){

        // get old license status
        $old_license_status = get_site_transient('ae_license');
        if($old_license_status == 'valid'){
            $license_key = get_option('ae_pro_license_key');
            $this->activate_license($license_key,false);
        }
    }

    public function license_menu(){
        add_submenu_page('edit.php?post_type=ae_global_templates', __('Settings','ae-pro'), __('Settings','ae-pro'), 'manage_options', 'aepro-settings', [$this, 'license_page']);
    }

    public function license_page(){
        //$license = get_option('ae_pro_license_key');

        $license = self::get_hidden_ae_license_key();

        $status = $this->license_status();

        $map_key = get_option('ae_pro_gmap_api');

        $enable_generic = get_option('ae_pro_generic_theme');


        ?>
        <div class="wrap">
            <h2><?php _e('Plugin License Options'); ?></h2>
            <form method="post" action="edit.php?post_type=ae_global_templates&page=aepro-settings">

                <?php settings_fields('aepro_edd_license'); ?>
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License Key'); ?>
                        </th>
                        <td>
	                        <?php if( $status !== false && $status == 'valid' ) { ?>
                                <input id="ae_pro_license_key" name="ae_pro_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" disabled="disabled" />
                            <?php }else{ ?>
                                <input id="ae_pro_license_key" name="ae_pro_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                                <label class="description" for="ae_pro_license_key"><?php _e('Enter your license key'); ?></label>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <?php if( $status !== false && $status == 'valid' ) { ?>
                                <span style="color:green;"><?php _e('active'); ?></span>
                                <?php wp_nonce_field( 'aep_license_nonce', 'aep_license_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="aep_edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                            <?php } else {
                                wp_nonce_field( 'aep_license_nonce', 'aep_license_nonce' ); ?>
                                <input type="submit" class="button-primary" name="aep_edd_license_activate" value="<?php _e('Activate License'); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <hr/>
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Google Map Api Key', 'ae-pro'); ?>
                        </th>
                        <td width="200px">
                            <input id="ae_pro_gmap_api" name="ae_pro_gmap_api" type="text" class="regular-text" value="<?php esc_attr_e( $map_key ); ?>" />
                            <br/><label class="description" for="ae_pro_license_key">
                                <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">
                                    <?php echo _e('Click Here') ?>
                                </a> to generate API KEY
                            </label>
                        </td>
                    </tr>


                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Enable Theme Support', 'ae-pro'); ?>
                        </th>
                        <td width="200px">

                            <input type="checkbox" value="1" name="enable_generic_theme_support" <?php echo (isset($enable_generic) && $enable_generic==1)?'checked':''; ?> />
                            <label class="description">
                                <?php echo __('Enable support for your theme', 'ae-pro'); ?>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="submit" class="button-primary" name="aep_settings_update"  value="<?php _e('Update', 'ae-pro'); ?>" />
                        </td>
                    </tr>


                    </tbody>
                </table>

                <hr/>





            </form>
        </div>

        <?php
    }

    public function register_license_option(){
        // creates our settings in the options table
        register_setting('aepro_edd_license', 'ae_pro_license_key', [ $this, 'edd_sanitize_license'] );
        register_setting('aepro_edd_license', 'ae_pro_gmap_api', [ $this, 'edd_sanitize_license'] );
    }

    public function edd_sanitize_license($new){
        return $new;
    }

    protected function license_status(){
        $licence_key = get_option('ae_pro_license_key');
        if(!isset($licence_key) || empty($licence_key)){
            // license missing
            return 'missing';
        }else{
            // get transient
            $ae_license_transient = get_site_transient('aep_license_status');

            if(isset($ae_license_transient) && $ae_license_transient != ''){
                return $ae_license_transient;
            }


            // check license status
            $license_status = $this->check_license();
            set_site_transient('aep_license_status',$license_status,self::$_transient_lifetime);

            return $license_status;
        }
    }

    protected function check_license(){
        $license = get_option('ae_pro_license_key');

        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $license,
            'item_name' => urlencode( self::$_item_name ),
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( self::$_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( is_wp_error( $response ) )
            return false;

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if( $license_data->license == 'valid' ) {
            return 'valid';
        } else {
            return $license_data->license;
        }

    }

    public function ae_plugin_updater(){
        $license_key = trim( get_option( 'ae_pro_license_key' ) );

        $edd_updater = new EDD_SL_Plugin_Updater( self::$_store_url, AE_PRO_FILE, array(
                'version' 	=> AE_PRO_VERSION, 				// current version number
                'license' 	=> $license_key, 		        // license key (used get_option above to retrieve from DB)
                'item_id'   => 21, 	                        // name of this plugin
                'author' 	=> 'WebTechStreet',             // author of this plugin
                'beta'		=> false,
                'name'      => self::$_item_name
            )
        );
    }

    public function license_update(){

        if(isset($_POST['aep_edd_license_activate'])){
            if( ! check_admin_referer( 'aep_license_nonce', 'aep_license_nonce' ) )
                return; // get out if we didn't click the Activate button

            // update license key
            update_option('ae_pro_license_key',trim($_POST['ae_pro_license_key']));
            $this->activate_license(trim($_POST['ae_pro_license_key']));
        }

        if(isset($_POST['aep_edd_license_deactivate'])){
            if( ! check_admin_referer( 'aep_license_nonce', 'aep_license_nonce' ) )
                return; // get out if we didn't click the desctivate button button

            $license_key = get_option('ae_pro_license_key');
            $this->deactivate_license($license_key);

        }

        if(isset($_POST['aep_settings_update'])){
            if( ! check_admin_referer( 'aep_license_nonce', 'aep_license_nonce' ) )
                return;

            update_option('ae_pro_gmap_api', trim($_POST['ae_pro_gmap_api']));

            if(isset($_POST['enable_generic_theme_support'])){
                update_option('ae_pro_generic_theme', trim($_POST['enable_generic_theme_support']));
            }else{
                update_option('ae_pro_generic_theme', '');
            }

        }


    }

    function activate_license($license_key, $redirect = true){
        //prepare data for api request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license_key,
            'item_id'    => 21, // The ID of the item in EDD
            'url'        => home_url()
        );

        $response = wp_remote_post( self::$_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
           $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
        } else {
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            if ( false === $license_data->success ) {
                switch( $license_data->error ) {
                    case 'expired' :
                        $message = sprintf(
                            __( 'Your license key expired on %s.' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;
                    case 'revoked' :
                        $message = __( 'Your license key has been disabled.' );
                        break;
                    case 'missing' :
                        $message = __( 'Invalid license.' );
                        break;
                    case 'invalid' :
                    case 'site_inactive' :
                        $message = __( 'Your license is not active for this URL.' );
                        break;
                    case 'item_name_mismatch' :
                        $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), EDD_SAMPLE_ITEM_NAME );
                        break;
                    case 'no_activations_left':
                        $message = __( 'Your license key has reached its activation limit.' );
                        break;
                    default :
                        $message = __( 'An error occurred, please try again.' );
                        break;
                }
            }
        }

        if ( ! empty( $message ) && $redirect ) {
            $base_url = admin_url( 'edit.php?post_type=ae_global_templates&page=aepro-settings' );
            $redirect = add_query_arg( array( 'aep_activate' => 'false', 'aep_res' => urlencode( $message ) ), $base_url );
            //echo $redirect; die();
            wp_redirect( $redirect );
            exit();
        }

        set_site_transient('aep_license_status',$license_data->license,self::$_transient_lifetime);

        if($redirect){
            $base_url = admin_url( 'edit.php?post_type=ae_global_templates&page=aepro-settings' );
            $redirect = add_query_arg( array( 'aep_activate' => 'true'), $base_url );
            wp_redirect( $redirect );
            exit();
        }
    }

    function deactivate_license($license_key){

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license_key,
            'item_name'  => urlencode( self::$_item_name ), // the name of our product in EDD
            'url'        => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( self::$_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
        //echo "<pre>"; print_r($response); die();
        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.' );
            }

            $base_url = admin_url( 'edit.php?post_type=ae_global_templates&page=aepro-settings' );
            $redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
            wp_redirect( $redirect );
            exit();
        }

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        // $license_data->license will be either "deactivated" or "failed"
        if( $license_data->license == 'deactivated' ) {
            delete_option( 'ae_pro_license_key' );
            delete_site_transient('aep_license_status');
        }

        wp_redirect( admin_url( 'edit.php?post_type=ae_global_templates&page=aepro-settings' ) );
        exit();
    }

    public function admin_notices(){

        $license_status = $this->license_status();

        $license_setting_page =  admin_url('edit.php?post_type=ae_global_templates&page=aepro-settings');
        switch($license_status){

            case 'valid'   :  break;
            case 'missing':
                ?>
                <div class="error">
                    <p>
                        <strong>AnyWhere Elementor Pro</strong><br/>
                        Please <a href="<?php echo $license_setting_page; ?>">activate your license key</a> to enable automatic updates
                    </p>
                </div>
                <?php
                break;

            case 'invalid': $license_key = trim( get_option( 'ae_pro_license_key' ) );
                ?>
                <div class="error">
                    <p>
                        <strong>AnyWhere Elementor Pro</strong><br/>
                        You license key <code><?php echo $license_key; ?></code> is invalid. Please <a href="<?php echo $license_setting_page; ?>">add a valid license key</a>.
                    </p>
                </div>
                <?php
                break;

            case 'expired':
                ?>
                <div class="error">
                    <p>
                        <strong>AnyWhere Elementor Pro</strong><br/>
                        Your <a href="<?php echo $license_setting_page; ?>">license key</a> is expired.
                    </p>
                </div>
                <?php
                break;

            case 'site_inactive': ?>
                <div class="error">
                    <p>
                        <strong>AnyWhere Elementor Pro</strong><br/>
                        Your <a href="<?php echo $license_setting_page; ?>">license key</a> is not active for this site.
                    </p>
                </div>
                <?php
                break;

            default: 	?>
                <div class="error">
                    <p>
                        <strong>AnyWhere Elementor Pro</strong><br/>
                        Please activate a valid <a href="<?php echo $license_setting_page; ?>">license key</a>.
                    </p>
                </div>
                <?php
                break;



        }
        if ( isset( $_GET['aep_activate'] ) ) {
            switch( $_GET['aep_activate'] ) {

                case 'false':
                    $message = urldecode( $_GET['aep_res'] );
                    ?>
                    <div class="error">
                        <p>
                            <strong>AnyWhere Elementor Pro</strong><br/>
                            <?php echo $message; ?>
                        </p>
                    </div>
                    <?php
                    break;

                case 'true':
                    ?>
                    <div class="updated">
                        <p>
                            <strong>AnyWhere Elementor Pro</strong><br/>
                            License updated successfully.
                        </p>
                    </div>
                    <?php
                default:
                    // Developers can put a custom success message here for when activation is successful if they way.
                    break;

            }
        }
    }

	static function get_hidden_ae_license_key() {
		$input_string = trim( get_option( 'ae_pro_license_key' ) );

		$start = 5;
		$length = mb_strlen( $input_string ) - $start - 5;

		$mask_string = preg_replace( '/\S/', 'X', $input_string );
		$mask_string = mb_substr( $mask_string, $start, $length );
		$input_string = substr_replace( $input_string, $mask_string, $start, $length );

		return $input_string;
	}
}

License::instance();