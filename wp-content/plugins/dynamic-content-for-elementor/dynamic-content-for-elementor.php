<?php
/**
 * Dynamic Content for Elementor
 *
 * Amiamo quello che facciamo con l'unico scopo di creare
 * la magia che vive attorno alle cose belle.
 * Questo plugin è dedicato alle persone che vogliono vivere felici
 * e credono che metterci il cuore faccia la differenza.
 *
 * @copyright Copyright (C) 2018-2020, Ovation S.r.l. - support@dynamic.ooo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name:       Dynamic Content for Elementor
 * Plugin URI:        https://www.dynamic.ooo/
 * Description:       Improve your website’s potential through additional widgets, expanding Elementor’s functionality. New creative widgets, every and each of them with the purpose of building pages with amazing contents.
 * Version:           1.8.2.1
 * Author:            Dynamic.ooo
 * Author URI:        https://www.dynamic.ooo/
 * Text Domain:       dynamic-content-for-elementor
 * Domain Path:       /languages
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Dynamic Content for Elementor incorporates code from:
 * - A-Frame, Copyright (c) 2015-2017 A-Frame authors, License: MIT, https://aframe.io
 * - Animate.css, Copyright (c) 2019 Daniel Eden, License: MIT, https://daneden.github.io/animate.css/
 * - Animsition, Copyright (c) 2013-2015 blivesta, License: MIT, http://git.blivesta.com/animsition/
 * - Clipboard.js, Copyright (c) 2019 Zeno Rocha, License: MIT, https://zenorocha.mit-license.org/
 * - Diamonds.js, Copyright (c) 2013 mqchen, License: MIT, https://github.com/mqchen/jquery.diamonds.js/
 * - GSAP, GreenSock files are subject to their own license (https://greensock.com/standard-license) and you can ONLY use the bonus files as a part of Dynamic Content for Elementor
 * - HoneyCombs, License: GPL v3, https://github.com/nasirkhan/honeycombs
 * - InfiniteScroll, License: GPL v3, https://infinite-scroll.com/
 * - Isotope, GPL v3, http://isotope.metafizzy.co
 * - justifiedGallery, Copyright (c) 2019 Miro Mannino, License: MIT, http://miromannino.github.io/Justified-Gallery/
 * - Parallax.js, Copyright (c) 2014 Matthew Wagerfield - @wagerfield, License: MIT, https://github.com/wagerfield/parallax
 * - PhotoSwipe, Copyright (c) 2014-2019 Dmitry Semenov, http://dimsemenov.com, License: MIT, http://photoswipe.com
 * - Rellax, Copyright (c) 2016 Dixon & Moe, License: MIT, https://dixonandmoe.com/rellax/
 * - Revealjs.com, Copyright (c) 2018 Hakim El Hattab (http://hakim.se) and reveal.js contributors, License: MIT, https://revealjs.com
 * - Scrollify.js, Copyright (c) 2017 Luke Haas, License: MIT, https://projects.lukehaas.me/scrollify/examples/pagination
 * - Slick, Copyright (c) 2013-2016, License: MIT, http://kenwheeler.github.io/slick/
 * - Swiper.js, 2019 (c) Swiper by Vladimir Kharlampidi from iDangero.us, License: MIT, https://idangero.us/swiper/
 * - Three Sixty Image slider, Copyright 2013 Gaurav Jassal, License: MIT, http://github.com/vml-webdev/threesixty-slider.git
 * - Tilt.js, Copyright (c) 2017 Gijs Rogé, License: MIT, https://gijsroge.github.io/tilt.js/
 * - Slick, Copyright (c) 2013-2016, License: MIT, http://kenwheeler.github.io
 * - SVG File Icons, Copyright (c) 2018 Daniel M. Hendricks, License: MIT, https://fileicons.org/
 * - THREEJS, Copyright (c) 2010-2019 three.js authors, License: MIT, https://github.com/mrdoob/three.js/blob/dev/LICENSE
 * - TwentyTwenty, Copyright 2018 zurb, License: MIT, https://zurb.com/playground/twentytwenty
 * - Velocity.js, Copyright (c) 2014 Julian Shapiro, License: MIT, http://velocityjs.org
 * - WOW.js, Copyright (c) 2016 Thomas Grainger, License: MIT, https://wowjs.uk/  
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('DCE__FILE__', __FILE__);
define('DCE_URL', plugins_url('/', __FILE__));
define('DCE_PATH', plugin_dir_path(__FILE__));
define('DCE_PLUGIN_BASE', plugin_basename(DCE__FILE__));
define('DCE_VERSION', '1.8.2.1');
define('DCE_ELEMENTOR_VERSION_REQUIRED', '2.6.0');
define('DCE_ELEMENTOR_PRO_VERSION_REQUIRED', '2.6.0');
define('DCE_PHP_VERSION_REQUIRED', '7.1');
define('DCE_TEXTDOMAIN', 'dynamic-content-for-elementor');
define('DCE_OPTIONS', 'dyncontel_options');
define('DCE_BACKUP_PATH', ABSPATH.'wp-content/backup');
define('DCE_BACKUP_URL', site_url().'/wp-content/backup');

/* ***********************LICENSE***************************** */
require_once( __DIR__ . '/class/DCE_Notice.php' );
require_once( __DIR__ . '/class/DCE_License.php' );
\DynamicContentForElementor\DCE_License::set_constant();

$dce_thecontent_is = false;

add_action('plugins_loaded', 'dce_load');
register_activation_hook(DCE__FILE__, 'dce_activate');

/**
 * Load Elements DCE
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 0.1.0
 */
function dce_load() {
    // Load localization file
    load_plugin_textdomain('dynamic-content-for-elementor');

    // Notice if the Elementor is not active
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'dce_fail_load');
        return;
    }

    require_once DCE_PATH . 'vendor/autoload.php';

    // Require the main plugin file
    require_once( __DIR__ . '/core/DCE_Plugin.php' );
    $dce_plugin = new \DynamicContentForElementor\DCE_Plugin();

    \DynamicContentForElementor\DCE_License::do_rollback();
    \DynamicContentForElementor\DCE_License::check_for_updates(__FILE__);
}

/**
 * Handles admin notice for non-active
 * Elementor plugin situations
 *
 * @since 0.1.0
 */
function dce_fail_load() {
    $class = 'notice notice-error';
    $message = sprintf(__('You need %1$s"Elementor"%2$s for the %1$s"Dynamic Content for Elementor"%2$s plugin to work and updated.', 'dynamic-content-for-elementor'), '<strong>', '</strong>');

    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
}

/**
 * Runs code upon activation
 *
 * @since 0.1.0
 */
function dce_activate() {
    add_option('dce_do_activation_redirect', true);
}

/**
 * Check errors upon activation
 *
 * @since 1.5.2
 */
function dce_save_activation_error() {
    update_option( 'dce_plugin_error',  ob_get_contents() );
}

if (WP_DEBUG) {
    add_action( 'activated_plugin', 'dce_save_activation_error' );
    /* Then to display the error message: */
    echo get_option( 'dce_plugin_error' );
    delete_option('dce_plugin_error');
}
