<?php
/**
 * Class for providing debug data based on a users WordPress environment.
 *
 * @package WordPress
 * @subpackage Site_Health
 * @since 5.2.0
 */

class WP_Debug_Data {
	/**
	 * Calls all core functions to check for updates.
	 *
	 * @since 5.2.0
	 */
	static function check_for_updates() {
		wp_version_check();
		wp_update_plugins();
		wp_update_themes();
	}

	/**
	 * Static function for generating site debug data when required.
	 *
	 * @since 5.2.0
	 *
	 * @throws ImagickException
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @return array The debug data for the site.
	 */
	static function debug_data() {
		global $wpdb;

		// Save few function calls.
		$upload_dir             = wp_get_upload_dir();
		$permalink_structure    = get_option( 'permalink_structure' );
		$is_ssl                 = is_ssl();
		$users_can_register     = get_option( 'users_can_register' );
		$default_comment_status = get_option( 'default_comment_status' );
		$is_multisite           = is_multisite();
		$core_version           = get_bloginfo( 'version' );
		$core_updates           = get_core_updates();
		$core_update_needed     = '';

		foreach ( $core_updates as $core => $update ) {
			if ( 'upgrade' === $update->response ) {
				// translators: %s: Latest WordPress version number.
				$core_update_needed = ' ' . sprintf( __( '(Latest version: %s)' ), $update->version );
			} else {
				$core_update_needed = '';
			}
		}

		// Set up the array that holds all debug information.
		$info = array();

		$info['wp-core'] = array(
			'label'  => __( 'WordPress' ),
			'fields' => array(
				'version'                => array(
					'label' => __( 'Version' ),
					'value' => $core_version . $core_update_needed,
					'debug' => $core_version,
				),
				'site_language'          => array(
					'label' => __( 'Site Language' ),
					'value' => get_locale(),
				),
				'user_language'          => array(
					'label' => __( 'User Language' ),
					'value' => get_user_locale(),
				),
				'home_url'               => array(
					'label'   => __( 'Home URL' ),
					'value'   => get_bloginfo( 'url' ),
					'private' => true,
				),
				'site_url'               => array(
					'label'   => __( 'Site URL' ),
					'value'   => get_bloginfo( 'wpurl' ),
					'private' => true,
				),
				'permalink'              => array(
					'label' => __( 'Permalink structure' ),
					'value' => $permalink_structure ?: __( 'No permalink structure set' ),
					'debug' => $permalink_structure,
				),
				'https_status'           => array(
					'label' => __( 'Is this site using HTTPS?' ),
					'value' => $is_ssl ? __( 'Yes' ) : __( 'No' ),
					'debug' => $is_ssl,
				),
				'user_registration'      => array(
					'label' => __( 'Can anyone register on this site?' ),
					'value' => $users_can_register ? __( 'Yes' ) : __( 'No' ),
					'debug' => $users_can_register,
				),
				'default_comment_status' => array(
					'label' => __( 'Default comment status' ),
					'value' => 'open' === $default_comment_status ? _x( 'Open', 'comment status' ) : _x( 'Closed', 'comment status' ),
					'debug' => $default_comment_status,
				),
				'multisite'              => array(
					'label' => __( 'Is this a multisite?' ),
					'value' => $is_multisite ? __( 'Yes' ) : __( 'No' ),
					'debug' => $is_multisite,
				),
			),
		);

		if ( ! $is_multisite ) {
			$info['wp-paths-sizes'] = array(
				'label'  => __( 'Directories and Sizes' ),
				'fields' => array(),
			);
		}

		$info['wp-dropins'] = array(
			'label'       => __( 'Drop-ins' ),
			'show_count'  => true,
			'description' => __( 'Drop-ins are single files that replace or enhance WordPress features in ways that are not possible for traditional plugins.' ),
			'fields'      => array(),
		);

		$info['wp-active-theme'] = array(
			'label'  => __( 'Active Theme' ),
			'fields' => array(),
		);

		$info['wp-themes'] = array(
			'label'      => __( 'Other Themes' ),
			'show_count' => true,
			'fields'     => array(),
		);

		$info['wp-mu-plugins'] = array(
			'label'      => __( 'Must Use Plugins' ),
			'show_count' => true,
			'fields'     => array(),
		);

		$info['wp-plugins-active'] = array(
			'label'      => __( 'Active Plugins' ),
			'show_count' => true,
			'fields'     => array(),
		);

		$info['wp-plugins-inactive'] = array(
			'label'      => __( 'Inactive Plugins' ),
			'show_count' => true,
			'fields'     => array(),
		);

		$info['wp-media'] = array(
			'label'  => __( 'Media Handling' ),
			'fields' => array(),
		);

		$info['wp-server'] = array(
			'label'       => __( 'Server' ),
			'description' => __( 'The options shown below relate to your server setup. If changes are required, you may need your web host&#8217;s assistance.' ),
			'fields'      => array(),
		);

		$info['wp-database'] = array(
			'label'  => __( 'Database' ),
			'fields' => array(),
		);

		// Check if WP_DEBUG_LOG is set.
		$wp_debug_log_value = __( 'Disabled' );

		if ( is_string( WP_DEBUG_LOG ) ) {
			$wp_debug_log_value = WP_DEBUG_LOG;
		} elseif ( WP_DEBUG_LOG ) {
			$wp_debug_log_value = __( 'Enabled' );
		}

		// Check CONCATENATE_SCRIPTS.
		if ( defined( 'CONCATENATE_SCRIPTS' ) ) {
			$concatenate_scripts       = CONCATENATE_SCRIPTS ? __( 'Enabled' ) : __( 'Disabled' );
			$concatenate_scripts_debug = CONCATENATE_SCRIPTS ? 'true' : 'false';
		} else {
			$concatenate_scripts       = __( 'Undefined' );
			$concatenate_scripts_debug = 'undefined';
		}

		// Check COMPRESS_SCRIPTS.
		if ( defined( 'COMPRESS_SCRIPTS' ) ) {
			$compress_scripts       = COMPRESS_SCRIPTS ? __( 'Enabled' ) : __( 'Disabled' );
			$compress_scripts_debug = COMPRESS_SCRIPTS ? 'true' : 'false';
		} else {
			$compress_scripts       = __( 'Undefined' );
			$compress_scripts_debug = 'undefined';
		}

		// Check COMPRESS_CSS.
		if ( defined( 'COMPRESS_CSS' ) ) {
			$compress_css       = COMPRESS_CSS ? __( 'Enabled' ) : __( 'Disabled' );
			$compress_css_debug = COMPRESS_CSS ? 'true' : 'false';
		} else {
			$compress_css       = __( 'Undefined' );
			$compress_css_debug = 'undefined';
		}

		// Check WP_LOCAL_DEV.
		if ( defined( 'WP_LOCAL_DEV' ) ) {
			$wp_local_dev       = WP_LOCAL_DEV ? __( 'Enabled' ) : __( 'Disabled' );
			$wp_local_dev_debug = WP_LOCAL_DEV ? 'true' : 'false';
		} else {
			$wp_local_dev       = __( 'Undefined' );
			$wp_local_dev_debug = 'undefined';
		}

		$info['wp-constants'] = array(
			'label'       => __( 'WordPress Constants' ),
			'description' => __( 'These settings alter where and how parts of WordPress are loaded.' ),
			'fields'      => array(
				'ABSPATH'             => array(
					'label'   => 'ABSPATH',
					'value'   => ABSPATH,
					'private' => true,
				),
				'WP_HOME'             => array(
					'label' => 'WP_HOME',
					'value' => ( defined( 'WP_HOME' ) ? WP_HOME : __( 'Undefined' ) ),
					'debug' => ( defined( 'WP_HOME' ) ? WP_HOME : 'undefined' ),
				),
				'WP_SITEURL'          => array(
					'label' => 'WP_SITEURL',
					'value' => ( defined( 'WP_SITEURL' ) ? WP_SITEURL : __( 'Undefined' ) ),
					'debug' => ( defined( 'WP_SITEURL' ) ? WP_SITEURL : 'undefined' ),
				),
				'WP_CONTENT_DIR'      => array(
					'label' => 'WP_CONTENT_DIR',
					'value' => WP_CONTENT_DIR,
				),
				'WP_PLUGIN_DIR'       => array(
					'label' => 'WP_PLUGIN_DIR',
					'value' => WP_PLUGIN_DIR,
				),
				'WP_MAX_MEMORY_LIMIT' => array(
					'label' => 'WP_MAX_MEMORY_LIMIT',
					'value' => WP_MAX_MEMORY_LIMIT,
				),
				'WP_DEBUG'            => array(
					'label' => 'WP_DEBUG',
					'value' => WP_DEBUG ? __( 'Enabled' ) : __( 'Disabled' ),
					'debug' => WP_DEBUG,
				),
				'WP_DEBUG_DISPLAY'    => array(
					'label' => 'WP_DEBUG_DISPLAY',
					'value' => WP_DEBUG_DISPLAY ? __( 'Enabled' ) : __( 'Disabled' ),
					'debug' => WP_DEBUG_DISPLAY,
				),
				'WP_DEBUG_LOG'        => array(
					'label' => 'WP_DEBUG_LOG',
					'value' => $wp_debug_log_value,
					'debug' => WP_DEBUG_LOG,
				),
				'SCRIPT_DEBUG'        => array(
					'label' => 'SCRIPT_DEBUG',
					'value' => SCRIPT_DEBUG ? __( 'Enabled' ) : __( 'Disabled' ),
					'debug' => SCRIPT_DEBUG,
				),
				'WP_CACHE'            => array(
					'label' => 'WP_CACHE',
					'value' => WP_CACHE ? __( 'Enabled' ) : __( 'Disabled' ),
					'debug' => WP_CACHE,
				),
				'CONCATENATE_SCRIPTS' => array(
					'label' => 'CONCATENATE_SCRIPTS',
					'value' => $concatenate_scripts,
					'debug' => $concatenate_scripts_debug,
				),
				'COMPRESS_SCRIPTS'    => array(
					'label' => 'COMPRESS_SCRIPTS',
					'value' => $compress_scripts,
					'debug' => $compress_scripts_debug,
				),
				'COMPRESS_CSS'        => array(
					'label' => 'COMPRESS_CSS',
					'value' => $compress_css,
					'debug' => $compress_css_debug,
				),
				'WP_LOCAL_DEV'        => array(
					'label' => 'WP_LOCAL_DEV',
					'value' => $wp_local_dev,
					'debug' => $wp_local_dev_debug,
				),
			),
		);

		$is_writable_abspath            = wp_is_writable( ABSPATH );
		$is_writable_wp_content_dir     = wp_is_writable( WP_CONTENT_DIR );
		$is_writable_upload_dir         = wp_is_writable( $upload_dir['basedir'] );
		$is_writable_wp_plugin_dir      = wp_is_writable( WP_PLUGIN_DIR );
		$is_writable_template_directory = wp_is_writable( get_template_directory() . '/..' );

		$info['wp-filesystem'] = array(
			'label'       => __( 'Filesystem Permissions' ),
			'description' => __( 'Shows whether WordPress is able to write to the directories it needs access to.' ),
			'fields'      => array(
				'wordpress'  => array(
					'label' => __( 'The main WordPress directory' ),
					'value' => ( $is_writable_abspath ? __( 'Writable' ) : __( 'Not writable' ) ),
					'debug' => ( $is_writable_abspath ? 'writable' : 'not writable' ),
				),
				'wp-content' => array(
					'label' => __( 'The wp-content directory' ),
					'value' => ( $is_writable_wp_content_dir ? __( 'Writable' ) : __( 'Not writable' ) ),
					'debug' => ( $is_writable_wp_content_dir ? 'writable' : 'not writable' ),
				),
				'uploads'    => array(
					'label' => __( 'The uploads directory' ),
					'value' => ( $is_writable_upload_dir ? __( 'Writable' ) : __( 'Not writable' ) ),
					'debug' => ( $is_writable_upload_dir ? 'writable' : 'not writable' ),
				),
				'plugins'    => array(
					'label' => __( 'The plugins directory' ),
					'value' => ( $is_writable_wp_plugin_dir ? __( 'Writable' ) : __( 'Not writable' ) ),
					'debug' => ( $is_writable_wp_plugin_dir ? 'writable' : 'not writable' ),
				),
				'themes'     => array(
					'label' => __( 'The themes directory' ),
					'value' => ( $is_writable_template_directory ? __( 'Writable' ) : __( 'Not writable' ) ),
					'debug' => ( $is_writable_template_directory ? 'writable' : 'not writable' ),
				),
			),
		);

		// Conditionally add debug information for multisite setups.
		if ( is_multisite() ) {
			$network_query = new WP_Network_Query();
			$network_ids   = $network_query->query(
				array(
					'fields'        => 'ids',
					'number'        => 100,
					'no_found_rows' => false,
				)
			);

			$site_count = 0;
			foreach ( $network_ids as $network_id ) {
				$site_count += get_blog_count( $network_id );
			}

			$info['wp-core']['fields']['user_count'] = array(
				'label' => __( 'User count' ),
				'value' => get_user_count(),
			);

			$info['wp-core']['fields']['site_count'] = array(
				'label' => __( 'Site count' ),
				'value' => $site_count,
			);

			$info['wp-core']['fields']['network_count'] = array(
				'label' => __( 'Network count' ),
				'value' => $network_query->found_networks,
			);
		} else {
			$user_count = count_users();

			$info['wp-core']['fields']['user_count'] = array(
				'label' => __( 'User count' ),
				'value' => $user_count['total_users'],
			);
		}

		// WordPress features requiring processing.
		$wp_dotorg = wp_remote_get( 'https://wordpress.org', array( 'timeout' => 10 ) );

		if ( ! is_wp_error( $wp_dotorg ) ) {
			$info['wp-core']['fields']['dotorg_communication'] = array(
				'label' => __( 'Communication with WordPress.org' ),
				'value' => __( 'WordPress.org is reachable' ),
				'debug' => 'true',
			);
		} else {
			$info['wp-core']['fields']['dotorg_communication'] = array(
				'label' => __( 'Communication with WordPress.org' ),
				'value' => sprintf(
					// translators: 1: The IP address WordPress.org resolves to. 2: The error returned by the lookup.
					__( 'Unable to reach WordPress.org at %1$s: %2$s' ),
					gethostbyname( 'wordpress.org' ),
					$wp_dotorg->get_error_message()
				),
				'debug' => $wp_dotorg->get_error_message(),
			);
		}

		// Remove accordion for Directories and Sizes if in Multisite.
		if ( ! $is_multisite ) {
			$loading = __( 'Loading&hellip;' );

			$info['wp-paths-sizes']['fields'] = array(
				'wordpress_path' => array(
					'label' => __( 'WordPress directory location' ),
					'value' => untrailingslashit( ABSPATH ),
				),
				'wordpress_size' => array(
					'label' => __( 'WordPress directory size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
				'uploads_path'   => array(
					'label' => __( 'Uploads directory location' ),
					'value' => $upload_dir['basedir'],
				),
				'uploads_size'   => array(
					'label' => __( 'Uploads directory size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
				'themes_path'    => array(
					'label' => __( 'Themes directory location' ),
					'value' => get_theme_root(),
				),
				'themes_size'    => array(
					'label' => __( 'Themes directory size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
				'plugins_path'   => array(
					'label' => __( 'Plugins directory location' ),
					'value' => WP_PLUGIN_DIR,
				),
				'plugins_size'   => array(
					'label' => __( 'Plugins directory size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
				'database_size'  => array(
					'label' => __( 'Database size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
				'total_size'     => array(
					'label' => __( 'Total installation size' ),
					'value' => $loading,
					'debug' => 'loading...',
				),
			);
		}

		// Get a list of all drop-in replacements.
		$dropins = get_dropins();

		// Get dropins descriptions.
		$dropin_descriptions = _get_dropins();

		// Spare few function calls.
		$not_available = __( 'Not available' );

		foreach ( $dropins as $dropin_key => $dropin ) {
			$info['wp-dropins']['fields'][ sanitize_text_field( $dropin_key ) ] = array(
				'label' => $dropin_key,
				'value' => $dropin_descriptions[ $dropin_key ][0],
				'debug' => 'true',
			);
		}

		// Populate the media fields.
		$info['wp-media']['fields']['image_editor'] = array(
			'label' => __( 'Active editor' ),
			'value' => _wp_image_editor_choose(),
		);

		// Get ImageMagic information, if available.
		if ( class_exists( 'Imagick' ) ) {
			// Save the Imagick instance for later use.
			$imagick         = new Imagick();
			$imagick_version = $imagick->getVersion();
		} else {
			$imagick_version = __( 'Not available' );
		}

		$info['wp-media']['fields']['imagick_module_version'] = array(
			'label' => __( 'ImageMagick version number' ),
			'value' => ( is_array( $imagick_version ) ? $imagick_version['versionNumber'] : $imagick_version ),
		);

		$info['wp-media']['fields']['imagemagick_version'] = array(
			'label' => __( 'ImageMagick version string' ),
			'value' => ( is_array( $imagick_version ) ? $imagick_version['versionString'] : $imagick_version ),
		);

		// If Imagick is used as our editor, provide some more information about its limitations.
		if ( 'WP_Image_Editor_Imagick' === _wp_image_editor_choose() && isset( $imagick ) && $imagick instanceof Imagick ) {
			$limits = array(
				'area'   => ( defined( 'imagick::RESOURCETYPE_AREA' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_AREA ) ) : $not_available ),
				'disk'   => ( defined( 'imagick::RESOURCETYPE_DISK' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_DISK ) : $not_available ),
				'file'   => ( defined( 'imagick::RESOURCETYPE_FILE' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_FILE ) : $not_available ),
				'map'    => ( defined( 'imagick::RESOURCETYPE_MAP' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_MAP ) ) : $not_available ),
				'memory' => ( defined( 'imagick::RESOURCETYPE_MEMORY' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_MEMORY ) ) : $not_available ),
				'thread' => ( defined( 'imagick::RESOURCETYPE_THREAD' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_THREAD ) : $not_available ),
			);

			$limits_debug = array(
				'imagick::RESOURCETYPE_AREA'   => ( defined( 'imagick::RESOURCETYPE_AREA' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_AREA ) ) : 'not available' ),
				'imagick::RESOURCETYPE_DISK'   => ( defined( 'imagick::RESOURCETYPE_DISK' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_DISK ) : 'not available' ),
				'imagick::RESOURCETYPE_FILE'   => ( defined( 'imagick::RESOURCETYPE_FILE' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_FILE ) : 'not available' ),
				'imagick::RESOURCETYPE_MAP'    => ( defined( 'imagick::RESOURCETYPE_MAP' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_MAP ) ) : 'not available' ),
				'imagick::RESOURCETYPE_MEMORY' => ( defined( 'imagick::RESOURCETYPE_MEMORY' ) ? size_format( $imagick->getResourceLimit( imagick::RESOURCETYPE_MEMORY ) ) : 'not available' ),
				'imagick::RESOURCETYPE_THREAD' => ( defined( 'imagick::RESOURCETYPE_THREAD' ) ? $imagick->getResourceLimit( imagick::RESOURCETYPE_THREAD ) : 'not available' ),
			);

			$info['wp-media']['fields']['imagick_limits'] = array(
				'label' => __( 'Imagick Resource Limits' ),
				'value' => $limits,
				'debug' => $limits_debug,
			);
		}

		// Get GD information, if available.
		if ( function_exists( 'gd_info' ) ) {
			$gd = gd_info();
		} else {
			$gd = false;
		}

		$info['wp-media']['fields']['gd_version'] = array(
			'label' => __( 'GD version' ),
			'value' => ( is_array( $gd ) ? $gd['GD Version'] : $not_available ),
			'debug' => ( is_array( $gd ) ? $gd['GD Version'] : 'not available' ),
		);

		// Get Ghostscript information, if available.
		if ( function_exists( 'exec' ) ) {
			$gs = exec( 'gs --version' );

			if ( empty( $gs ) ) {
				$gs       = $not_available;
				$gs_debug = 'not available';
			} else {
				$gs_debug = $gs;
			}
		} else {
			$gs       = __( 'Unable to determine if Ghostscript is installed' );
			$gs_debug = 'unknown';
		}

		$info['wp-media']['fields']['ghostscript_version'] = array(
			'label' => __( 'Ghostscript version' ),
			'value' => $gs,
			'debug' => $gs_debug,
		);

		// Populate the server debug fields.
		if ( function_exists( 'php_uname' ) ) {
			$server_architecture = sprintf( '%s %s %s', php_uname( 's' ), php_uname( 'r' ), php_uname( 'm' ) );
		} else {
			$server_architecture = 'unknown';
		}

		if ( function_exists( 'phpversion' ) ) {
			$php_version_debug = phpversion();
			// Whether PHP supports 64bit
			$php64bit = ( PHP_INT_SIZE * 8 === 64 );

			$php_version = sprintf(
				'%s %s',
				$php_version_debug,
				( $php64bit ? __( '(Supports 64bit values)' ) : __( '(Does not support 64bit values)' ) )
			);

			if ( $php64bit ) {
				$php_version_debug .= ' 64bit';
			}
		} else {
			$php_version       = __( 'Unable to determine PHP version' );
			$php_version_debug = 'unknown';
		}

		if ( function_exists( 'php_sapi_name' ) ) {
			$php_sapi = php_sapi_name();
		} else {
			$php_sapi = 'unknown';
		}

		$info['wp-server']['fields']['server_architecture'] = array(
			'label' => __( 'Server architecture' ),
			'value' => ( 'unknown' !== $server_architecture ? $server_architecture : __( 'Unable to determine server architecture' ) ),
			'debug' => $server_architecture,
		);
		$info['wp-server']['fields']['httpd_software']      = array(
			'label' => __( 'Web server' ),
			'value' => ( isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : __( 'Unable to determine what web server software is used' ) ),
			'debug' => ( isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : 'unknown' ),
		);
		$info['wp-server']['fields']['php_version']         = array(
			'label' => __( 'PHP version' ),
			'value' => $php_version,
			'debug' => $php_version_debug,
		);
		$info['wp-server']['fields']['php_sapi']            = array(
			'label' => __( 'PHP SAPI' ),
			'value' => ( 'unknown' !== $php_sapi ? $php_sapi : __( 'Unable to determine PHP SAPI' ) ),
			'debug' => $php_sapi,
		);

		// Some servers disable `ini_set()` and `ini_get()`, we check this before trying to get configuration values.
		if ( ! function_exists( 'ini_get' ) ) {
			$info['wp-server']['fields']['ini_get'] = array(
				'label' => __( 'Server settings' ),
				'value' => __( 'Unable to determine some settings, as the ini_get() function has been disabled.' ),
				'debug' => 'ini_get() is disabled',
			);
		} else {
			$info['wp-server']['fields']['max_input_variables'] = array(
				'label' => __( 'PHP max input variables' ),
				'value' => ini_get( 'max_input_vars' ),
			);
			$info['wp-server']['fields']['time_limit']          = array(
				'label' => __( 'PHP time limit' ),
				'value' => ini_get( 'max_execution_time' ),
			);
			$info['wp-server']['fields']['memory_limit']        = array(
				'label' => __( 'PHP memory limit' ),
				'value' => ini_get( 'memory_limit' ),
			);
			$info['wp-server']['fields']['max_input_time']      = array(
				'label' => __( 'Max input time' ),
				'value' => ini_get( 'max_input_time' ),
			);
			$info['wp-server']['fields']['upload_max_size']     = array(
				'label' => __( 'Upload max filesize' ),
				'value' => ini_get( 'upload_max_filesize' ),
			);
			$info['wp-server']['fields']['php_post_max_size']   = array(
				'label' => __( 'PHP post max size' ),
				'value' => ini_get( 'post_max_size' ),
			);
		}

		if ( function_exists( 'curl_version' ) ) {
			$curl = curl_version();

			$info['wp-server']['fields']['curl_version'] = array(
				'label' => __( 'cURL version' ),
				'value' => sprintf( '%s %s', $curl['version'], $curl['ssl_version'] ),
			);
		} else {
			$info['wp-server']['fields']['curl_version'] = array(
				'label' => __( 'cURL version' ),
				'value' => $not_available,
				'debug' => 'not available',
			);
		}

		// SUHOSIN
		$suhosin_loaded = ( extension_loaded( 'suhosin' ) || ( defined( 'SUHOSIN_PATCH' ) && constant( 'SUHOSIN_PATCH' ) ) );

		$info['wp-server']['fields']['suhosin'] = array(
			'label' => __( 'Is SUHOSIN installed?' ),
			'value' => ( $suhosin_loaded ? __( 'Yes' ) : __( 'No' ) ),
			'debug' => $suhosin_loaded,
		);

		// Imagick
		$imagick_loaded = extension_loaded( 'imagick' );

		$info['wp-server']['fields']['imagick_availability'] = array(
			'label' => __( 'Is the Imagick library available?' ),
			'value' => ( $imagick_loaded ? __( 'Yes' ) : __( 'No' ) ),
			'debug' => $imagick_loaded,
		);

		// Check if a .htaccess file exists.
		if ( is_file( ABSPATH . '.htaccess' ) ) {
			// If the file exists, grab the content of it.
			$htaccess_content = file_get_contents( ABSPATH . '.htaccess' );

			// Filter away the core WordPress rules.
			$filtered_htaccess_content = trim( preg_replace( '/\# BEGIN WordPress[\s\S]+?# END WordPress/si', '', $htaccess_content ) );
			$filtered_htaccess_content = ! empty( $filtered_htaccess_content );

			$info['wp-server']['fields']['htaccess_extra_rules'] = array(
				'label' => __( '.htaccess rules' ),
				'value' => ( $filtered_htaccess_content ? __( 'Custom rules have been added to your .htaccess file.' ) : __( 'Your .htaccess file contains only core WordPress features.' ) ),
				'debug' => $filtered_htaccess_content,
			);
		}

		// Populate the database debug fields.
		if ( is_resource( $wpdb->dbh ) ) {
			// Old mysql extension.
			$extension = 'mysql';
		} elseif ( is_object( $wpdb->dbh ) ) {
			// mysqli or PDO.
			$extension = get_class( $wpdb->dbh );
		} else {
			// Unknown sql extension.
			$extension = null;
		}

		/*
		 * Check what database engine is used, this will throw compatibility
		 * warnings from PHP compatibility testers, but `mysql_*` is
		 * still valid in PHP 5.6, so we need to account for that.
		 */
		if ( method_exists( $wpdb, 'db_version' ) ) {
			if ( $wpdb->use_mysqli ) {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_get_server_info
				$server = mysqli_get_server_info( $wpdb->dbh );
			} else {
				// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysql_get_server_info
				$server = mysql_get_server_info( $wpdb->dbh );
			}
		} else {
			$server = null;
		}

		if ( isset( $wpdb->use_mysqli ) && $wpdb->use_mysqli ) {
			$client_version = $wpdb->dbh->client_info;
		} else {
			// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysql_get_client_info
			if ( preg_match( '|[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2}|', mysql_get_client_info(), $matches ) ) {
				$client_version = $matches[0];
			} else {
				$client_version = null;
			}
		}

		$info['wp-database']['fields']['extension'] = array(
			'label' => __( 'Extension' ),
			'value' => $extension,
		);

		$info['wp-database']['fields']['server_version'] = array(
			'label' => __( 'Server version' ),
			'value' => $server,
		);

		$info['wp-database']['fields']['client_version'] = array(
			'label' => __( 'Client version' ),
			'value' => $client_version,
		);

		$info['wp-database']['fields']['database_user'] = array(
			'label'   => __( 'Database user' ),
			'value'   => $wpdb->dbuser,
			'private' => true,
		);

		$info['wp-database']['fields']['database_host'] = array(
			'label'   => __( 'Database host' ),
			'value'   => $wpdb->dbhost,
			'private' => true,
		);

		$info['wp-database']['fields']['database_name'] = array(
			'label'   => __( 'Database name' ),
			'value'   => $wpdb->dbname,
			'private' => true,
		);

		$info['wp-database']['fields']['database_prefix'] = array(
			'label'   => __( 'Database prefix' ),
			'value'   => $wpdb->prefix,
			'private' => true,
		);

		// List must use plugins if there are any.
		$mu_plugins = get_mu_plugins();

		foreach ( $mu_plugins as $plugin_path => $plugin ) {
			$plugin_version = $plugin['Version'];
			$plugin_author  = $plugin['Author'];

			$plugin_version_string       = __( 'No version or author information is available.' );
			$plugin_version_string_debug = 'author: (undefined), version: (undefined)';

			if ( ! empty( $plugin_version ) && ! empty( $plugin_author ) ) {
				// translators: 1: Plugin version number. 2: Plugin author name.
				$plugin_version_string       = sprintf( __( 'Version %1$s by %2$s' ), $plugin_version, $plugin_author );
				$plugin_version_string_debug = sprintf( 'version: %s, author: %s', $plugin_version, $plugin_author );
			} else {
				if ( ! empty( $plugin_author ) ) {
					// translators: %s: Plugin author name.
					$plugin_version_string       = sprintf( __( 'By %s' ), $plugin_author );
					$plugin_version_string_debug = sprintf( 'author: %s, version: (undefined)', $plugin_author );
				}

				if ( ! empty( $plugin_version ) ) {
					// translators: %s: Plugin version number.
					$plugin_version_string       = sprintf( __( 'Version %s' ), $plugin_version );
					$plugin_version_string_debug = sprintf( 'author: (undefined), version: %s', $plugin_version );
				}
			}

			$info['wp-mu-plugins']['fields'][ sanitize_text_field( $plugin['Name'] ) ] = array(
				'label' => $plugin['Name'],
				'value' => $plugin_version_string,
				'debug' => $plugin_version_string_debug,
			);
		}

		// List all available plugins.
		$plugins        = get_plugins();
		$plugin_updates = get_plugin_updates();

		foreach ( $plugins as $plugin_path => $plugin ) {
			$plugin_part = ( is_plugin_active( $plugin_path ) ) ? 'wp-plugins-active' : 'wp-plugins-inactive';

			$plugin_version = $plugin['Version'];
			$plugin_author  = $plugin['Author'];

			$plugin_version_string       = __( 'No version or author information is available.' );
			$plugin_version_string_debug = 'author: (undefined), version: (undefined)';

			if ( ! empty( $plugin_version ) && ! empty( $plugin_author ) ) {
				// translators: 1: Plugin version number. 2: Plugin author name.
				$plugin_version_string       = sprintf( __( 'Version %1$s by %2$s' ), $plugin_version, $plugin_author );
				$plugin_version_string_debug = sprintf( 'version: %s, author: %s', $plugin_version, $plugin_author );
			} else {
				if ( ! empty( $plugin_author ) ) {
					// translators: %s: Plugin author name.
					$plugin_version_string       = sprintf( __( 'By %s' ), $plugin_author );
					$plugin_version_string_debug = sprintf( 'author: %s, version: (undefined)', $plugin_author );
				}

				if ( ! empty( $plugin_version ) ) {
					// translators: %s: Plugin version number.
					$plugin_version_string       = sprintf( __( 'Version %s' ), $plugin_version );
					$plugin_version_string_debug = sprintf( 'author: (undefined), version: %s', $plugin_version );
				}
			}

			if ( array_key_exists( $plugin_path, $plugin_updates ) ) {
				// translators: %s: Latest plugin version number.
				$plugin_version_string       .= ' ' . sprintf( __( '(Latest version: %s)' ), $plugin_updates[ $plugin_path ]->update->new_version );
				$plugin_version_string_debug .= sprintf( ' (latest version: %s)', $plugin_updates[ $plugin_path ]->update->new_version );
			}

			$info[ $plugin_part ]['fields'][ sanitize_text_field( $plugin['Name'] ) ] = array(
				'label' => $plugin['Name'],
				'value' => $plugin_version_string,
				'debug' => $plugin_version_string_debug,
			);
		}

		// Populate the section for the currently active theme.
		global $_wp_theme_features;
		$theme_features = array();

		if ( ! empty( $_wp_theme_features ) ) {
			foreach ( $_wp_theme_features as $feature => $options ) {
				$theme_features[] = $feature;
			}
		}

		$active_theme  = wp_get_theme();
		$theme_updates = get_theme_updates();

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$active_theme_version       = $active_theme->Version;
		$active_theme_version_debug = $active_theme_version;

		if ( array_key_exists( $active_theme->stylesheet, $theme_updates ) ) {
			$theme_update_new_version = $theme_updates[ $active_theme->stylesheet ]->update['new_version'];

			// translators: %s: Latest theme version number.
			$active_theme_version       .= ' ' . sprintf( __( '(Latest version: %s)' ), $theme_update_new_version );
			$active_theme_version_debug .= sprintf( ' (latest version: %s)', $theme_update_new_version );
		}

		$active_theme_author_uri = $active_theme->offsetGet( 'Author URI' );

		$info['wp-active-theme']['fields'] = array(
			'name'           => array(
				'label' => __( 'Name' ),
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'value' => $active_theme->Name,
			),
			'version'        => array(
				'label' => __( 'Version' ),
				'value' => $active_theme_version,
				'debug' => $active_theme_version_debug,
			),
			'author'         => array(
				'label' => __( 'Author' ),
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'value' => wp_kses( $active_theme->Author, array() ),
			),
			'author_website' => array(
				'label' => __( 'Author website' ),
				'value' => ( $active_theme_author_uri ? $active_theme_author_uri : __( 'Undefined' ) ),
				'debug' => ( $active_theme_author_uri ? $active_theme_author_uri : '(undefined)' ),
			),
			'parent_theme'   => array(
				'label' => __( 'Parent theme' ),
				'value' => ( $active_theme->parent_theme ? $active_theme->parent_theme : __( 'None' ) ),
				'debug' => ( $active_theme->parent_theme ? $active_theme->parent_theme : 'none' ),
			),
			'theme_features' => array(
				'label' => __( 'Theme features' ),
				'value' => implode( ', ', $theme_features ),
			),
			'theme_path'     => array(
				'label' => __( 'Theme directory location' ),
				'value' => get_template_directory(),
			),
		);

		// Populate a list of all themes available in the install.
		$all_themes = wp_get_themes();

		foreach ( $all_themes as $theme_slug => $theme ) {
			// Ignore the currently active theme from the list of all themes.
			if ( $active_theme->stylesheet === $theme_slug ) {
				continue;
			}
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$theme_version = $theme->Version;
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$theme_author = $theme->Author;

			// Sanitize
			$theme_author = wp_kses( $theme_author, array() );

			$theme_version_string       = __( 'No version or author information is available.' );
			$theme_version_string_debug = 'undefined';

			if ( ! empty( $theme_v