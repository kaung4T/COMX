<?php
//* Code goes here


function my_theme_enqueue_styles() {
      wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
      wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
    }

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function profanity_filter($content) {
$profanities = array("sissy","dummy");
$content=str_ireplace($profanities,'[censored]',$content);
return $content;
}
add_filter('the_content', 'profanity_filter');

//add action hook to register menu
function register_my_custom_menu_page() {
 add_menu_page( 'aaa', 'My Posts Menu', 'manage_options', 'edit.php', '', 'dashicons-admin-site', 6 );
}
add_action( 'admin_menu', 'register_my_custom_menu_page' );

// create custom plugin settings menu
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
    add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
    add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
}
