<?php

namespace Aepro\Admin;

class Admin{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [ $this, 'load_admin_style']);
    }


    function load_admin_style($hook){
    	wp_enqueue_style('aep-admin', AE_PRO_URL.'includes/admin/admin.css');
    }
}
new Admin();