<?php
/**
 * Language_Pack for Themes
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

if ( ! class_exists( 'WP_Translations_Themes_LP_Update' ) ) :
	class WP_Translations_Themes_LP_Update {

		public function __construct( $resource, $slug ) {

			$this->resource = $resource;
			$this->slug     = $slug;

			$this->run();
		}

		protected function run() {
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'pre_set_site_transient' ) );
		}

		public function pre_set_site_transient( $transient ) {

			if ( ! is_object( $transient ) ) {
				$transient = new stdClass();
			}

			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
			$locales = get_available_languages();
			$locales = ! empty( $locales ) ? $locales : array( get_locale() );

			$translations = wp_get_installed_translations( 'themes' );

			$remote_lp = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/' . $this->resource . '/language-pack.json' );
			$remote_lp_json = json_decode( wp_remote_retrieve_body( $remote_lp ) );

			foreach ( $locales as $locale ) {

				if ( in_array( $locale, array_keys( (array) $remote_lp_json ), true ) ) {

					$lang_pack_mod = isset( $remote_lp_json->{$locale}->updated )
						? strtotime( substr( $remote_lp_json->{$locale}->updated, 0, -3 ) )
						: 0;

					$translation_mod = isset( $translations[ $this->resource ][ $locale ] )
						? strtotime( substr( $translations[ $this->resource ][ $locale ]['PO-Revision-Date'],0 ,-5 ) )
						: 0;

					if ( $lang_pack_mod > $translation_mod ) {
						$transient->translations[] = (array) $remote_lp_json->{$locale};
					}
				}
			}

			$transient->last_checked = current_time( 'timestamp' );
			if ( ! empty( $transient->translations ) ) {
				$transient->translations = array_unique( $transient->translations, SORT_REGULAR );
			}

			return $transient;

		}

	}

endif;
