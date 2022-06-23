<?php namespace Aepro;
/*
  Plugin Name: Check AE PRO Plugin
  Version: v1.0
  Plugin URI: http://shop.webtechstreet.com
  Author: Tips and Tricks HQ
  Author URI: https://www.webtechstreet.com/
  Description: AE PRO plugin to show you how you can interact with the software license manager API from your WordPress plugin or theme
 */


class Admin{

    const AEPRO_PLUGIN_SECRET_KEY = '5982e8dfe35ee9.55907414';
    //const AEPRO_PLUGIN_LICENSE_SERVER_URL = 'http://wts.com/elicense';
    const AEPRO_PLUGIN_LICENSE_SERVER_URL = 'http://api.webtechstreet.com';
    const AEPRO_PLUGIN_ITEM_REFERENCE = 'AnyWhere Elementor Pro';

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        add_action( 'admin_notices', [ $this, 'ae_admin_notices' ], 20 );
    }

    public function ae_admin_notices(){
        if (isset($_REQUEST['activate_license'])) {
            return;
        }
        $license_page_link = add_query_arg( [ 'page' => 'aepro-licence' ], admin_url( 'admin.php' ) );
        $license_status = $this->check_licence_status();
        if($license_status == 'missing'){
            $msg = sprintf( __( '<strong>AnyWhere Elementor Pro licence is missing</strong><br/>Please <a href="%s">add your license key</a> to enable automatic update notifications.', 'ae-pro' ), $license_page_link );
            printf( '<div class="error"><p>%s</p></div>', $msg );
            return;
        }elseif($license_status == 'invalid'){
            $msg = sprintf( __( '<strong>AnyWhere Elementor Pro licence is invalid</strong><br/>Please <a href="%s">add your license key</a> to enable automatic update notifications.', 'ae-pro' ), $license_page_link );
            printf( '<div class="error"><p>%s</p></div>', $msg );
            return;
        }

        return;
    }

    function check_licence_status(){

        $licence_key = get_option('ae_pro_license_key');
        if(!isset($licence_key) || empty($licence_key)){
            // license missing
            return 'missing';
        }else{

            // get transient
            $ae_license_transient = get_site_transient('ae_license');

            if(isset($ae_license_transient) && $ae_license_transient != ''){
                return $ae_license_transient;
            }

            $api_params = array(
                'slm_action'  => 'slm_check',
                'secret_key'  => self::AEPRO_PLUGIN_SECRET_KEY,
                'license_key' => $licence_key
            );
           // print_r($api_params);
            $query = esc_url_raw(add_query_arg($api_params, self::AEPRO_PLUGIN_LICENSE_SERVER_URL));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if($license_data->result == 'error'){
               set_site_transient('ae_license','invalid',60*60*12);
               return 'invalid';
            }elseif($license_data->status == 'active'){
                foreach($license_data->registered_domains as $domain){
                    if($_SERVER['SERVER_NAME'] == $domain->registered_domain){
                        set_site_transient('ae_license','valid',60*60*12);
                        return 'valid';
                    }
                }
                set_site_transient('ae_license','invalid',60*60*12);
                return 'invalid';
            }
        }
    }



}

new Admin();
// This is the secret key for API authentication. You configured it in the settings menu of the license manager plugin.
define('AEPRO_PLUGIN_SECRET_KEY', '58984c2e68e5a2.36558253'); //Rename this constant name so it is specific to your plugin or theme.

// This is the URL where API query request will be sent to. This should be the URL of the site where you have installed the main license manager plugin. Get this value from the integration help page.
define('AEPRO_PLUGIN_LICENSE_SERVER_URL', 'http://wts.com/elicense'); //Rename this constant name so it is specific to your plugin or theme.

// This is a value that will be recorded in the license manager data so you can identify licenses for this item/product.
define('AEPRO_PLUGIN_ITEM_REFERENCE', 'AE - PRO Plugin'); //Rename this constant name so it is specific to your plugin or theme.

add_action('admin_menu', 'Aepro\slm_ae_pro_license_menu');

function slm_ae_pro_license_menu() {
    add_options_page('AE - PRO License Activation Menu', 'AE PRO License', 'manage_options', 'aepro-licence', 'Aepro\ae_pro_license_management_page');
}

function ae_pro_license_management_page() {
    echo '<div class="wrap">';
    echo '<h2>AE PRO License Management</h2>';

    /*** License activate button was clicked ***/
    if (isset($_REQUEST['activate_license'])) {
        $license_key = $_REQUEST['ae_pro_license_key'];

        // API query parameters
        $api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => '5982e8dfe35ee7.11850781',
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(AEPRO_PLUGIN_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $query = esc_url_raw(add_query_arg($api_params, AEPRO_PLUGIN_LICENSE_SERVER_URL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){//Success was returned for the license activation
            
            //Uncomment the followng line to see the message that returned from the license server
            //echo '<br />The following message was returned from the server: '.$license_data->message;
            $msg = sprintf( __( '<strong>Licence Activation Successfull</strong>' ) );
            printf( '<div class="notice updated"><p>%s</p></div>', $msg );
            
            //Save the license key in the options table
            update_option('ae_pro_license_key', $license_key);
            set_site_transient('ae_license','valid',60*60*12);
        }
        else{
            //Show error to the user. Probably entered incorrect license key.
            $msg = sprintf( __( '<strong>Error in activating licence for AnyWhere Elementor Pro</strong> <br/> Reason: %s', 'ae-pro' ), $license_data->message );
            printf( '<div class="error"><p>%s</p></div>', $msg );
            //Uncomment the followng line to see the message that returned from the license server
           // echo '<br />The following message was returned from the server: '.$license_data->message;
        }

    }
    /*** End of license activation ***/
    
    /*** License activate button was clicked ***/
    if (isset($_REQUEST['deactivate_license'])) {
        $license_key = $_REQUEST['ae_pro_license_key'];

        // API query parameters
        $api_params = array(
            'slm_action' => 'slm_deactivate',
            'secret_key' => '58984c2e68e5a2.36558253',
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(AEPRO_PLUGIN_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $query = esc_url_raw(add_query_arg($api_params, AEPRO_PLUGIN_LICENSE_SERVER_URL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){//Success was returned for the license activation
            
            //Uncomment the followng line to see the message that returned from the license server
            //echo '<br />The following message was returned from the server: '.$license_data->message;

            $msg = sprintf( __( '<strong>License has been successfully deactivated</strong>') );
            printf( '<div class="notice"><p>%s</p></div>', $msg );
            
            //Remove the licensse key from the options table. It will need to be activated again.
            delete_site_transient('ae_license');
            update_option('ae_pro_license_key', '');
        }
        else{
            //Show error to the user. Probably entered incorrect license key.
            
            //Uncomment the followng line to see the message that returned from the license server
            $msg = sprintf( __( '<strong>Error in de-activating licence key for AnyWhere Elementor Pro</strong> <br/> Reason: %s', 'ae-pro' ), $license_data->message );
            printf( '<div class="error"><p>%s</p></div>', $msg );
            //echo '<br />The following message was returned from the server: '.$license_data->message;

        }
        
    }
    /*** End of ae pro license deactivation ***/

    if(isset($license_data->result) && $license_data->result == 'success'){

    }else{
    ?>
    <p>Please enter the license key for this product to activate it. You were given a license key when you purchased this item.</p>
    <?php } ?>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th style="width:100px;"><label for="ae_pro_license_key">License Key</label></th>
                <td ><input class="regular-text" type="text" id="ae_pro_license_key" name="ae_pro_license_key"  value="<?php echo get_option('ae_pro_license_key'); ?>" ></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="activate_license" value="Activate" class="button-primary" />
            <input type="submit" name="deactivate_license" value="Deactivate" class="button" />
        </p>
    </form>
    <?php
    
    echo '</div>';
}