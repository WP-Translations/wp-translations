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
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WP_Translations_Plugins_LP_Update' ) ):
class WP_Translations_Plugins_LP_Update {

	public function __construct( $resource, $slug, $file ) {

		$this->resource = $resource;
		$this->slug			= $slug;
		$this->file			= $file['file'];

		$this->run();
	}

	protected function run() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient' ) );
		remove_action( 'after_plugin_row_' . $this->file, 'wp_plugin_update_row', 10 );
		add_action( 'after_plugin_row_' . $this->file, array( $this, 'show_update_notification' ), 10, 3 );
	}

	public function pre_set_site_transient( $transient ) {

		if ( ! is_object( $transient ) ) {
			$transient = new stdClass;
		}

		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
		$locales = get_available_languages();
		$locales = ! empty( $locales ) ? $locales : array( get_locale() );

		$translations = wp_get_installed_translations( 'plugins' );

		$remote_lp = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/' . $this->resource . '/language-pack.json' );
		$remote_lp_json = json_decode( wp_remote_retrieve_body( $remote_lp ) );

		foreach ( $locales as $locale ) {

			if ( in_array( $locale, array_keys( (array) $remote_lp_json ) ) ) {

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
		if( ! empty( $transient->translations ) ) {
			$transient->translations = array_unique( $transient->translations, SORT_REGULAR );
		}

		return $transient;

	}

	/**
	 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
	 *
	 * @param string  $file
	 * @param array   $plugin
	 */
	public function show_update_notification( $file, $plugin, $status  ) {

		$plugins_updates = get_site_transient( 'update_plugins' );

		$languages = array();
		foreach( $plugins_updates->translations as $update ) {
			if( $update['slug'] == $this->slug ) {
				$languages[] = $update['language'];
			}
		}

		if( empty( $languages ) ) {
			return;
		}

		$status 			= ( is_plugin_active( $this->file ) ) ? 'active' : 'inactive';
		$count_lp 		= count( $languages );
		$message 			= esc_html__( 'New translations are available:&nbsp;', 'wp-translations' );
		$update_link	= '<button id="wp-translations-update-' . $this->slug . '" class="button-link" type="button" data-type="plugins" data-slug="' . $this->slug . '">Update now</button>';

		echo '<tr class="plugin-update-tr ' . $status . ' wp-translations-update-row" id="' . $this->slug . '-update" data-slug="' . $this->slug . '" data-plugin="' . $file . '">';
		echo '<td colspan="3" class="plugin-update colspanchange">';
		echo '<div class="update-message notice inline notice-warning notice-alt"><p>';
		echo $message . implode( ',&nbsp;', $languages ) . '&nbsp;-&nbsp;' . $update_link;

	  echo '</p></div></td></tr>';
	}

}

endif;
