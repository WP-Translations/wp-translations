<?php
/**
 * Language_Pack
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

if ( ! class_exists( 'WP_Translations_Plugins_LP_Update' ) ) :
	class WP_Translations_Plugins_LP_Update {

		public function __construct( $resource ) {

			$this->resource = $resource;
			$this->run();
		}

		protected function run() {
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient' ) );
			add_filter( 'site_transient_update_plugins',         array( $this, 'check_repo_priority' ) );
		}

		public function pre_set_site_transient( $transient ) {

			if ( ! is_object( $transient ) ) {
				$transient = new stdClass();
			}

			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
			$locales = get_available_languages();
			$locales = ! empty( $locales ) ? $locales : array( get_locale() );

			$translations = wp_get_installed_translations( 'plugins' );

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

				$options = get_site_option( 'wp_translations_settings' );
				foreach ( $transient->translations as $key => $update ) {
					if ( ! empty( $update->version ) && 'wordpress' != $options['repo_priority'] ) {
						unset( $transient->translations[ $key ] );
					}
				}

				$transient->translations = array_unique( $transient->translations, SORT_REGULAR );
			}

			return $transient;

		}

		public function check_repo_priority( $value ) {
			$transient = (array) $value;
			$updates   = array();
			$excludes  = array();
			$options   = get_site_option( 'wp_translations_settings' );

			if ( isset( $transient['translations'] ) && ! empty( $transient['translations'] ) ) {
				foreach ( $transient['translations'] as $key => $update ) {
					if ( ! isset( $update['repo'] ) ) {
						$transient['translations'][ $key ]['repo'] = 'wordpress';
					}
					$update['ID'] = $key;
					$updates[ $update['slug'] ][ $update['language'] ][] = $update;
				}

				foreach ( $updates as $plugins ) {
					foreach ( $plugins as $translations ) {
						if ( 1 < count( $translations ) ) {
							foreach ( $translations as $translation ) {
								$repo_priority = ( isset( $options['textdomains'][ $translation['slug'] ]['repo_priority'] ) ) ? $options['textdomains'][ $translation['slug'] ]['repo_priority'] : $options['repo_priority'];
								$translation['repo'] = ( isset( $translation['repo'] ) ) ? $translation['repo'] : 'wordpress';
								if ( $repo_priority !== $translation['repo'] ) {
									$excludes[] = $translation['ID'];
								}
							}
						}
					}
				}

				foreach ( $excludes as $exclude ) {
					unset( $transient['translations'][ $exclude ] );
				}
			}
			return (object) $transient;
		}

	}

endif;
