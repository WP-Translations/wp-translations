<?php
/**
 * Admin Notices
 *
 * @package     WP-Translations
 * @subpackage  includes
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Translations_Notices' ) ) :
	class WP_Translations_Notices {

		public function __construct() {
			$this->run();
		}

		public function run() {
			add_action( is_multisite() ? 'network_admin_notices' : 'admin_notices', array( $this, 'show_notices' ) );
		}

		public function show_notices() {
			$notices = array(
				'updated' => array(),
				'error'   => array(),
			);

			if ( isset( $_GET['wp-translations-message'] ) ) {
				$capability = is_multisite() ? 'manage_network' : 'manage_options';
				if ( current_user_can( $capability ) ) {
					switch ( $_GET['wp-translations-message'] ) {
						case 'settings_updated':
							$notices['updated']['wp-translations-settings-updated'] = __( 'Settings updated.', 'wp-translations' );
							break;
						case 'translation_updated':
							$notices['updated']['wp-translations-settings-updated'] = __( 'Translation settings updated.', 'wp-translations' );
							break;
					}
				}
			}

			if ( count( $notices['updated'] ) > 0 ) {
				foreach ( $notices['updated'] as $notice => $message ) {
					add_settings_error( 'wp-translations-notices', $notice, $message, 'updated' );
				}
			}

			if ( count( $notices['error'] ) > 0 ) {
				foreach ( $notices['error'] as $notice => $message ) {
					add_settings_error( 'wp-translations-notices', $notice, $message, 'error' );
				}
			}

			settings_errors( 'wp-translations-notices' );

		}

	}
endif;

return new WP_Translations_Notices();
