<?php
/**
 * Plugin Notifications for Language Packs
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

if ( ! class_exists( 'WP_Translations_Plugins_Notification' ) ) :

	class WP_Translations_Plugins_Notification {

		public function __construct( $slug, $file ) {
			$this->slug = $slug;
			$this->file = $file;
			$this->run();
		}

		protected function run() {

			remove_action( 'after_plugin_row_' . $this->file, 'wp_plugin_update_row', 10 );
			add_action( 'after_plugin_row_' . $this->file, array( $this, 'show_update_notification' ), 10, 3 );
		}

		/**
		 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
		 *
		 * @param string  $file
		 * @param array   $plugin
		 */
		public function show_update_notification( $file, $plugin, $status ) {

			$plugins_updates = get_site_transient( 'update_plugins' );

			$languages = array();
			foreach ( $plugins_updates->translations as $update ) {
				if ( $update['slug'] === $this->slug ) {
					$languages[] = $update['language'];
				}
			}

			if ( empty( $languages ) ) {
				return;
			}

			$status       = ( is_plugin_active( $this->file ) ) ? 'active' : 'inactive';
			$count_lp     = count( $languages );
			$message      = esc_html__( 'New translations are available:&nbsp;', 'wp-translations' );
			$update_link  = '<button id="wp-translations-update-' . esc_attr( $this->slug ) . '" class="button-link" type="button" data-type="plugins" data-slug="' . esc_attr( $this->slug ) . '">Update now</button>';

			echo '<tr class="plugin-update-tr ' . esc_attr( $status ) . ' wp-translations-update-row" id="' . esc_attr( $this->slug ) . '-update" data-slug="' . esc_attr( $this->slug ) . '" data-plugin="' . esc_attr( $file ) . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message wp-translations-notice notice inline notice-warning notice-alt"><p>';
			echo $message . implode( ',&nbsp;', $languages ) . '&nbsp;-&nbsp;' . $update_link;

			echo '</p></div></td></tr>';
		}

	}

endif;
