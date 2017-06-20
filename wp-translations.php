<?php
/**
 * Plugin Name: WP-Translations
 * Plugin URI: https://wp-translations.org
 * Description: Import WP language-packs from transifex.
 * Author: WP-Translations Team
 * Author URI: https://wp-translations.org
 * Version: 1.0.0
 * Text Domain: wp-translations
 * Domain Path: languages
 *
 * WP-Translations Server is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP-Translations Server is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy Digital Downloads. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package WP-Translations
 * @category Core
 * @author Sadler Jerome
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WP_Translations' ) ) :

/**
 * Main WP_Translations Class.
 *
 * @since 1.0.0
 */
final class WP_Translations {
	/** Singleton *************************************************************/

	/**
	 * @var WP_Translations The one true WP_Translations
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Main WP_Translations Instance.
	 *
	 * Insures that only one instance of WP_Translations exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0.0
	 * @static
	 * @staticvar array $instance
	 * @uses WP_Translations::setup_constants() Setup the constants needed.
	 * @uses WP_Translations::includes() Include the required files.
	 * @uses WP_Translations::load_textdomain() load the language files.
	 * @see WPTS()
	 * @return object|WP_Translations The one true WP_Translations
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Translations ) ) {

			self::$instance = new WP_Translations;

			self::$instance->setup_constants();

			register_activation_hook( __FILE__, 	array( self::$instance, 'activation' ) );
			add_action( 'plugins_loaded', 				array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			add_action( 'plugins_loaded', 				array( self::$instance, 'init_admin' ) );

			register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

			add_action( 'init', array( self::$instance, 'translations_plugins_update' ) );
		}

		return self::$instance;

	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-translations' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-translations' ), '1.0.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {

		// Plugin name.
		if ( ! defined( 'WP_TRANSLATIONS_PLUGIN_NAME' ) ) {
			define( 'WP_TRANSLATIONS_PLUGIN_NAME', 'WP-Translations' );
		}

		// Plugin version.
		if ( ! defined( 'WP_TRANSLATIONS_VERSION' ) ) {
			define( 'WP_TRANSLATIONS_VERSION', '1.0.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'WP_TRANSLATIONS_PLUGIN_DIR' ) ) {
			define( 'WP_TRANSLATIONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'WP_TRANSLATIONS_PLUGIN_URL' ) ) {
			define( 'WP_TRANSLATIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'WP_TRANSLATIONS_PLUGIN_FILE' ) ) {
			define( 'WP_TRANSLATIONS_PLUGIN_FILE', __FILE__ );
		}

	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {
		global $wp_translations_options;

		require_once WP_TRANSLATIONS_PLUGIN_DIR . 'vendor/autoload.php';
		require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/register-settings.php';
		require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-plugins-lp-update.php';
		require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/enqueue.php';
		require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/ajax-updates.php';

	}

	/**
	 * Loads the plugin language files.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-translations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Instantiate Async Tasks.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function init_admin() {
		new WP_Translations_Admin_Settings();
	}

	/**
	 * Fire on plugin activation
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function activation() {

	}

	/**
	 * Fire on plugin deactivation
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function deactivation() {

	}

	public function translations_plugins_update() {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$projects_list = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/wpts-projects.json' );
		$projects = json_decode( wp_remote_retrieve_body( $projects_list ) );

		$plugins_active = get_plugins();
		$plugins = array();
		foreach ( $plugins_active as $file => $data ) {
			$plugins[ $data['TextDomain'] ] = array(
				'name' => $data['Name'],
				'textdomain' => $data['TextDomain'],
				'file' => $file
			);
		}
		$domains 				= wp_list_pluck( $plugins_active, 'TextDomain' );

		$plugins_data   = array();
		foreach ( $plugins_active as $key => $plugin ) {
				$plugins_data[ $key ] = array(
				'name' => $plugin['Name'],
				'textdomain' => $plugin['TextDomain'],
				'file' => $key
			);
		}

		foreach ( $projects as $key => $project ) {
			if( in_array( $key, $domains ) ) {
				foreach ( $project as $resource_slug => $resource ) {
					$update = new WP_Translations_Plugins_LP_Update( $resource_slug, $key, $plugins[ $key ] );
				}
			}
		}

	}

}

endif; // End if class_exists check.




/**
 * The main function for that returns WP_Translations
 *
 * The main function responsible for returning the one true WP_Translations
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.0.0
 * @return object|WP_Translations The one true WP_Translations Instance.
 */
function wp_translations_run() {
	return WP_Translations::instance();
}
wp_translations_run();
