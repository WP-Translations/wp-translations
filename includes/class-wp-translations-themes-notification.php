<?php
/**
 * Theme Notifications for Language Packs
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

if ( ! class_exists( 'WP_Translations_Themes_Notification' ) ) :

	class WP_Translations_Themes_Notification {

		public function __construct( $slug, $stylesheet ) {
			$this->slug = $slug;
			$this->stylesheet = $stylesheet;
			$this->run();
		}

		protected function run() {
			if ( is_multisite() ) {
				remove_action( 'after_theme_row_' . $this->slug, 'wp_theme_update_row', 10 );
				add_action( 'after_theme_row_' . $this->slug, array( $this, 'show_update_notification' ), 10, 3 );
			}
		}

		/**
		 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
		 *
		 * @param string  $file
		 * @param array   $plugin
		 */
		public function show_update_notification( $stylesheet, $theme, $status ) {

			$themes_updates = wp_get_translation_updates();

			$languages = array();
			foreach ( $themes_updates as $update ) {
				if ( $update->slug === $this->slug && 'theme' === $update->type ) {
					$languages[] = $update->language;
				}
			}

			if ( empty( $languages ) ) {
				return;
			}

			$allowed_themes = get_site_option( 'allowedthemes' );
			$status       = ( in_array( $this->slug, array_keys( $allowed_themes ), true ) ) ? 'active' : 'inactive';
			$count_lp     = count( $languages );
			$message      = esc_html__( 'New translations are available:&nbsp;', 'wp-translations' );
			$update_link  = '<button id="wp-translations-update-' . esc_attr( $this->slug ) . '" class="button-link" type="button" data-type="plugins" data-slug="' . esc_attr( $this->slug ) . '">Update now</button>';

			echo '<tr class="plugin-update-tr wp-translations-update-row ' . esc_attr( $status ) . '" id="' . esc_attr( $this->slug ) . '-update" data-slug="' . esc_attr( $this->slug ) . '" data-plugin="' . esc_attr( $this->stylesheet ) . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message wp-translations-notice notice inline notice-warning notice-alt"><p>';
			echo $message . implode( ',&nbsp;', $languages ) . '&nbsp;-&nbsp;' . $update_link;

			echo '</p></div></td></tr>';
		}

	}

endif;
