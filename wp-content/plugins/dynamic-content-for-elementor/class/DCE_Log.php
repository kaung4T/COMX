<?php

namespace DynamicContentForElementor;

/**
 * DCE_Log Class
 *
 * Log info page
 *
 * @since 0.0.1
 */
class DCE_Log {
	
	public static function get_remote_ip( $url ) {
		$parsed_url = Util::parse_url( $url );
		if ( ! isset( $parsed_url['host'] ) ) {
			return false;
		}
		// '.' appended to host name to avoid issues with nslookup caching - see documentation of gethostbyname for more info
		$host = $parsed_url['host'] . '.';

		$ip = gethostbyname( $host );

		return ( $ip === $host ) ? false : $ip;
	}

	/**
	 * Check for wpmdb-download-log and related nonce
	 * if found begin diagnostic logging
	 *
	 * @return void
	 */
	public static function http_prepare_download_log() {
		if ( isset( $_GET['wpmdb-download-log'] ) && wp_verify_nonce( $_GET['nonce'], 'wpmdb-download-log' ) ) {
			ob_start();
			self::output_diagnostic_info();
			$log      = ob_get_clean();
			$url      = Util::parse_url( home_url() );
			$host     = sanitize_file_name( $url['host'] );
			$filename = sprintf( '%s-diagnostic-log-%s.txt', $host, date( 'YmdHis' ) );
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Length: ' . strlen( $log ) );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			echo $log;
			exit;
		}
	}

	/**
	 * Outputs useful diagnostic info text at the Diagnostic Info & Error Log
	 * section under the Help tab so the information can be viewed or
	 * downloaded and shared for debugging.
	 *
	 *
	 * @return void
	 */
	public static function output_diagnostic_info() {
		$diagnostic_info = self::get_diagnostic_info();

		foreach ( $diagnostic_info as $section => $arr ) {
                        if (!empty($arr)) {
			$key_lengths    = array_map( 'strlen', array_keys( $arr ) );
			$max_key_length = max( $key_lengths );
			foreach ( $arr as $key => $val ) {
				if ( 0 === $key ) {
					echo $val . "\r\n";
					continue;
				}
				if ( is_array( $val ) ) {
					foreach ( $val as $subsection => $subval ) {
						echo " - ";
						if ( ! preg_match( '/^\d+$/', $subsection ) ) {
							echo "$subsection: ";
						}
						echo "$subval\r\n";
					}
					continue;
				}
				if ( ! preg_match( '/^\d+$/', $key ) ) {
					$pad_chr = '.';
					if ( $max_key_length - strlen( $key ) < 3 ) {
						$pad_chr = ' ';
					}
					echo '<strong>'.str_pad( "$key: ", $max_key_length + 2, $pad_chr, STR_PAD_RIGHT ).'</strong>';
				}
				echo " $val\r\n";

			}
			echo "\r\n";
                        }
		}

		return;
	}

	/**
	 * Gets diagnostic information about current site
	 *
	 * @return array
	 */
	public static function get_diagnostic_info() {
		global $wpdb;
		$diagnostic_info = array(); // group display sections into arrays

		$diagnostic_info['basic-info'] = array(
			'site_url()' => site_url(),
			'home_url()' => home_url(),
		);

		$diagnostic_info['db-info'] = array(
			'Database Name' => $wpdb->dbname,
			'Table Prefix'  => $wpdb->base_prefix,
		);

		$diagnostic_info['wp-version'] = array(
			'WordPress Version' => get_bloginfo( 'version' ),
                        'Elementor Version' => get_option( 'elementor_version' ),
                        'Elementor PRO Version' => get_option( 'elementor_version' ),
		);

		if ( is_multisite() ) {
			$diagnostic_info['multisite-info'] = array(
				'Multisite'            => defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL ? 'Sub-domain' : 'Sub-directory',
				'Domain Current Site'  => defined( 'DOMAIN_CURRENT_SITE' ) ? DOMAIN_CURRENT_SITE : 'Not Defined',
				'Path Current Site'    => defined( 'PATH_CURRENT_SITE' ) ? PATH_CURRENT_SITE : 'Not Defined',
				'Site ID Current Site' => defined( 'SITE_ID_CURRENT_SITE' ) ? SITE_ID_CURRENT_SITE : 'Not Defined',
				'Blog ID Current Site' => defined( 'BLOG_ID_CURRENT_SITE' ) ? BLOG_ID_CURRENT_SITE : 'Not Defined',
			);
		}

		$mdb_plugins = array();
		$diagnostic_info['mdb-plugins'] = $mdb_plugins;

		$diagnostic_info['server-info'] = array(
			'Web Server'                      => ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '',
			'PHP'                             => ( function_exists( 'phpversion' ) ) ? phpversion() : '',
			'WP Memory Limit'                 => WP_MEMORY_LIMIT,
			'PHP Time Limit'                  => ( function_exists( 'ini_get' ) ) ? ini_get( 'max_execution_time' ) : '',
			'Blocked External HTTP Requests'  => ( ! defined( 'WP_HTTP_BLOCK_EXTERNAL' ) || ! WP_HTTP_BLOCK_EXTERNAL ) ? 'None' : ( WP_ACCESSIBLE_HOSTS ? 'Partially (Accessible Hosts: ' . WP_ACCESSIBLE_HOSTS . ')' : 'All' ),
			'fsockopen'                       => ( function_exists( 'fsockopen' ) ) ? 'Enabled' : 'Disabled',
			//'OpenSSL'                         => ( $this->util->open_ssl_enabled() ) ? OPENSSL_VERSION_TEXT : 'Disabled',
			'cURL'                            => ( function_exists( 'curl_init' ) ) ? 'Enabled' : 'Disabled',
			//'Enable SSL verification setting' => ( 1 == $this->settings['verify_ssl'] ) ? 'Yes' : 'No',
		);

		$diagnostic_info['db-server-info'] = array(
			'MySQL'                    => mysqli_get_server_info( $wpdb->dbh ),
			'ext/mysqli'               => empty( $wpdb->use_mysqli ) ? 'no' : 'yes',
			'WP Locale'                => get_locale(),
			'DB Charset'               => DB_CHARSET,
			'WPMDB_STRIP_INVALID_TEXT' => ( defined( 'WPMDB_STRIP_INVALID_TEXT' ) && WPMDB_STRIP_INVALID_TEXT ) ? 'Yes' : 'No',
		);

		$diagnostic_info['debug-settings'] = array(
			'Debug Mode'    => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No',
			'Debug Log'     => ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) ? 'Yes' : 'No',
			'Debug Display' => ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) ? 'Yes' : 'No',
			'Script Debug'  => ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'Yes' : 'No',
			'PHP Error Log' => ( function_exists( 'ini_get' ) ) ? ini_get( 'error_log' ) : '',
		);

		$server_limits = array(
			'WP Max Upload Size' => size_format( wp_max_upload_size() ),
			//'PHP Post Max Size'  => size_format( $this->util->get_post_max_size() ),
		);

		if ( function_exists( 'ini_get' ) ) {
			if ( $suhosin_limit = ini_get( 'suhosin.post.max_value_length' ) ) {
				$server_limits['Suhosin Post Max Value Length'] = is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit;
			}
			if ( $suhosin_limit = ini_get( 'suhosin.request.max_value_length' ) ) {
				$server_limits['Suhosin Request Max Value Length'] = is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit;
			}
		}
		$diagnostic_info['server-limits'] = $server_limits;

		$diagnostic_info['mdb-settings'] = array(
			//'WPMDB Bottleneck'       => size_format( $this->util->get_bottleneck() ),
			'Compatibility Mode'     => ( isset( $GLOBALS['wpmdb_compatibility']['active'] ) ) ? 'Yes' : 'No',
			//'Delay Between Requests' => ( $this->settings['delay_between_requests'] > 0 ) ? $this->settings['delay_between_requests'] / 1000 . 's' : 0,
		);

		$constants = array(
			'WP_HOME'        => ( defined( 'WP_HOME' ) && WP_HOME ) ? WP_HOME : 'Not defined',
			'WP_SITEURL'     => ( defined( 'WP_SITEURL' ) && WP_SITEURL ) ? WP_SITEURL : 'Not defined',
			'WP_CONTENT_URL' => ( defined( 'WP_CONTENT_URL' ) && WP_CONTENT_URL ) ? WP_CONTENT_URL : 'Not defined',
			'WP_CONTENT_DIR' => ( defined( 'WP_CONTENT_DIR' ) && WP_CONTENT_DIR ) ? WP_CONTENT_DIR : 'Not defined',
			'WP_PLUGIN_DIR'  => ( defined( 'WP_PLUGIN_DIR' ) && WP_PLUGIN_DIR ) ? WP_PLUGIN_DIR : 'Not defined',
			'WP_PLUGIN_URL'  => ( defined( 'WP_PLUGIN_URL' ) && WP_PLUGIN_URL ) ? WP_PLUGIN_URL : 'Not defined',
		);

		if ( is_multisite() ) {
			$constants['UPLOADS']        = ( defined( 'UPLOADS' ) && UPLOADS ) ? UPLOADS : 'Not defined';
			$constants['UPLOADBLOGSDIR'] = ( defined( 'UPLOADBLOGSDIR' ) && UPLOADBLOGSDIR ) ? UPLOADBLOGSDIR : 'Not defined';
		}

		$diagnostic_info['constants'] = $constants;

		$diagnostic_info = array_merge( $diagnostic_info, apply_filters( 'wpmdb_diagnostic_info', array(), $diagnostic_info ) );

		$theme_info     = wp_get_theme();
		$theme_info_log = array(
			'Active Theme Name'   => $theme_info->Name,
			'Active Theme Folder' => $theme_info->get_stylesheet_directory(),
		);
		if ( $theme_info->get( 'Template' ) ) {
			$theme_info_log['Parent Theme Folder'] = $theme_info->get( 'Template' );
		}
		/*if ( ! $this->filesystem->file_exists( $theme_info->get_stylesheet_directory() ) ) {
			$theme_info_log['WARNING'] = 'Active Theme Folder Not Found';
		}*/
		$diagnostic_info['theme-info'] = $theme_info_log;

		$active_plugins_log = array( 'Active Plugins' );

		$active_plugins_log[1] = array();
                $whitelist = array();
		if ( isset( $GLOBALS['wpmdb_compatibility']['active'] ) ) {
			//$whitelist = array_flip( (array) $this->settings['whitelist_plugins'] );
		} else {
			$whitelist = array();
		}
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$network_active_plugins = wp_get_active_network_plugins();
			$active_plugins         = array_map( 'self::remove_wp_plugin_dir' , $network_active_plugins );
		}
		foreach ( $active_plugins as $plugin ) {
			$active_plugins_log[1][] = self::get_plugin_details( WP_PLUGIN_DIR . '/' . $plugin, isset( $whitelist[ $plugin ] ) ? '*' : '' );
		}

		$diagnostic_info['active-plugins'] = $active_plugins_log;

		$mu_plugins = wp_get_mu_plugins();
		if ( $mu_plugins ) {
			$mu_plugins_log    = array( 'Must-Use Plugins' );
			$mu_plugins_log[1] = array();
			foreach ( $mu_plugins as $mu_plugin ) {
				$mu_plugins_log[1][] = self::get_plugin_details( $mu_plugin );
			}
			$diagnostic_info['mu-plugins'] = $mu_plugins_log;
		}

		return $diagnostic_info;
	}
        
        public static function remove_wp_plugin_dir( $name ) {
		$plugin = str_replace( WP_PLUGIN_DIR, '', $name );

		return substr( $plugin, 1 );
	}
        
        public static function get_plugin_details( $plugin_path, $prefix = '' ) {
		$plugin_data = get_plugin_data( $plugin_path );
		$plugin_name = strlen( $plugin_data['Name'] ) ? $plugin_data['Name'] : basename( $plugin_path );

		if ( empty( $plugin_name ) ) {
			return;
		}

		$version = '';
		if ( $plugin_data['Version'] ) {
			$version = sprintf( " (v%s)", $plugin_data['Version'] );
		}

		$author = '';
		if ( $plugin_data['AuthorName'] ) {
			$author = sprintf( " by %s", $plugin_data['AuthorName'] );
		}

		return sprintf( "%s %s%s%s", $prefix, $plugin_name, $version, $author );
	}

}
