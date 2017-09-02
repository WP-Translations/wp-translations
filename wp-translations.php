<?php

/**
 * Plugin Name: WP-Translations
 * Plugin URI: https://wp-translations.pro
 * Description: Import WP language packs from transifex.
 * Author: WP-Translations Team
 * Author URI: https://wp-translations.pro
 * Version: 1.0.0
 * Text Domain: wp-translations
 * Domain Path: languages
 *
 * WP-Translations is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP-Translations is distributed in the hope that it will be useful,
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Translations' ) ) :

	/**
	 * Main WP_Translations Class.
	 *
	 * @since 1.0.0
	 */
	final class WP_Translations {

		/**
		 * The single instance of the class.
		 *
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main WP_Translations Instance.
		 *
		 * Insures that only one instance of WP_Translations exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @return object|WP_Translations The one true WP_Translations
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
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
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin\'huh?', 'wp-translations' ), '1.0.0' );
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
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin\'huh?', 'wp-translations' ), '1.0.0' );
		}

		/**
		 * WP-Translations Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wp_translations_loaded' );
		}

		/**
		 * Hook into actions and filters.
		 * @since  2.3
		 */
		private function init_hooks() {
			register_activation_hook( __FILE__,     array( $this, 'activation' ) );
			add_action( 'plugins_loaded',           array( $this, 'load_textdomain' ) );
			register_deactivation_hook( __FILE__,   array( $this, 'deactivation' ) );
		}

		/**
		 * Define plugin constants.
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function define_constants() {

			$this->define( 'WP_TRANSLATIONS_PLUGIN_NAME', 'WP-Translations' );
			$this->define( 'WP_TRANSLATIONS_VERSION',     '1.0.0' );
			$this->define( 'WP_TRANSLATIONS_DEBUG',       true );
			$this->define( 'WP_TRANSLATIONS_PLUGIN_DIR',   plugin_dir_path( __FILE__ ) );
			$this->define( 'WP_TRANSLATIONS_PLUGIN_URL',   plugin_dir_url( __FILE__ ) );
			$this->define( 'WP_TRANSLATIONS_PLUGIN_FILE',  __FILE__ );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
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
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/helper-functions.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/translation-actions.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/translation-functions.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/register-settings.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-notices.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translation-list-table.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-setup.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-plugins-lp-update.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-plugins-notification.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-themes-lp-update.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/class-wp-translations-themes-notification.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/enqueue.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/ajax-updates.php';
			require_once WP_TRANSLATIONS_PLUGIN_DIR . 'includes/admin/ajax-translations.php';
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
