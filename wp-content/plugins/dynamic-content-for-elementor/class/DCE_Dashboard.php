<?php
namespace DynamicContentForElementor;

/**
 * DCE_Dashboard Class
 *
 * Settings page
 *
 * @since 0.0.1
 */
class DCE_Dashboard {
    
    public function __construct() {
        $this->init();
    }

    public function init() {
        // Dashboard box
        add_action('wp_dashboard_setup', array($this, 'add_dyncontel_dashboard_widget'));
    }
    
    // Add Dashboard box ----------------------------------------------
    public function add_dyncontel_dashboard_widget() {
        wp_add_dashboard_widget('dce-dashboard-overview', 'Dynamic Content for Elementor', array($this, 'dyncontel_dashboard_overview_widget'));
        global $wp_meta_boxes;
        $dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
        $ours = [
            'dce-dashboard-overview' => $dashboard['dce-dashboard-overview'],
        ];
        $wp_meta_boxes['dashboard']['normal']['core'] = array_merge($ours, $dashboard); // WPCS: override ok.
    }

    public function dyncontel_dashboard_overview_widget() {
        ?>
        <div class="e-dashboard-widget">
            <div class="dce-overview__header">
                <div class="dce-overview__logo"><div class="dce-logo-wrapper"><img src="<?php echo DCE_URL . 'assets/media/dce.png'; ?>" width="60" /></div></div>
                <div class="dce-overview__versions">
                    <span class="dce-overview__version"><?php echo 'Dynamic Content for Elementor'; ?> v<?php echo DCE_VERSION; ?></span>
                </div>
                <div class="dce-overview__create">
                    <a href="<?php echo admin_url('admin.php?page=dce_opt'); ?>" class="button"><span aria-hidden="true" class="dashicons dashicons-admin-generic"></span> Settings</a>
                </div>
            </div>
            <div class="dce-overview__links">
                <ul>
                    <li class="dce-overview__link"><a href="<?php echo admin_url('admin.php?page=dce_info'); ?>" target="_blank"><span aria-hidden="true" class="dashicons dashicons-admin-home"></span>Welcome page</a></li>
                    <li class="dce-overview__link"><a href="<?php echo admin_url('edit.php?post_type=elementor_library'); ?>" target="_blank"><span aria-hidden="true" class="dashicons dashicons-portfolio"></span>Saved Templates</a></li>
                    <li class="dce-overview__link"><a href="<?php echo admin_url('admin.php?page=dce_opt&tab=widgets'); ?>" target="_blank"><span aria-hidden="true" class="dashicons dashicons-admin-generic"></span>Manage settings</a></li>
                    <li class="dce-overview__link"><a href="<?php echo admin_url('edit.php?post_type=elementor_library&page=dce_templatesystem'); ?>" target="_blank"><span aria-hidden="true" class="dashicons dashicons-welcome-widgets-menus"></span>TEMPLATE SYSTEM</a></li>
                    <li class="dce-overview__link"><a href="<?php echo admin_url('admin.php?page=dce_opt&tab=widgets'); ?>" target="_blank"><span aria-hidden="true" class="elementor-icon eicon-apps"></span>Enable or disable WIDGETS</a></li>                   
                    <li class="dce-overview__link"><a href="<?php echo admin_url('admin.php?page=dce_opt&tab=license'); ?>" target="_blank"><span aria-hidden="true" class="dashicons dashicons-hammer"></span>Enable BETA releases</a></li>
                </ul>
            </div>
            <div class="dce-overview__footer">
                <ul>
                    <!-- <li class="dce-overview__docs"><a href="docs.dynamic.ooo" target="_blank">Docs <span class="screen-reader-text"><?php echo __('(opens in a new window)', 'elementor'); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li> -->
                    <li class="dce-overview__help"><a href="https://www.dynamic.ooo/" target="_blank">Dynamic.ooo <span class="screen-reader-text"><?php echo __('(opens in a new window)', 'elementor'); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                </ul>
            </div>
        </div>
        <?php
    }
}
