<?php
/**
 * Get External Language-Packs
 *
 * @package     WP-Translations
 * @subpackage  includes
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_Translations_Setup', false ) ) :

	/**
	 * WP_Translations_Repository Class.
	 */
	class WP_Translations_Setup {

		public function __construct() {

			add_action( 'init',                  array( $this, 'translations_plugins_update' ) );
			add_action( 'init',                  array( $this, 'translations_themes_update' ) );
			add_filter( 'wp_get_update_data',    array( $this, 'translations_update_count' ) );
			add_action( 'core_upgrade_preamble', array( $this, 'list_translations_update' ) );
		}

		public function translations_plugins_update() {

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$translations = wp_list_pluck( wp_get_translation_updates(), 'type', 'slug' );

			$projects_list = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/wpts-projects.json' );
			$projects = json_decode( wp_remote_retrieve_body( $projects_list ) );

			$plugins_active = get_plugins();
			$plugins = array();
			foreach ( $plugins_active as $file => $data ) {
				$plugins[ $data['TextDomain'] ] = array(
					'name'       => $data['Name'],
					'textdomain' => $data['TextDomain'],
					'file'       => $file,
				);
			}
			$domains        = wp_list_pluck( $plugins_active, 'TextDomain' );

			$plugins_data   = array();
			foreach ( $plugins_active as $key => $plugin ) {
				$plugins_data[ $key ] = array(
					'name' => $plugin['Name'],
					'textdomain' => $plugin['TextDomain'],
					'file' => $key,
				);
			}

			foreach ( $projects as $key => $project ) {
				if ( in_array( $key, $domains, true ) ) {
					foreach ( $project as $resource_slug => $resource ) {
						$update = new WP_Translations_Plugins_LP_Update( $resource_slug );
					}
				}
			}

			foreach ( $translations as $slug => $type ) {
				if ( 'plugin' === $type ) {
					$notification = new WP_Translations_Plugins_Notification( $slug, $plugins[ $slug ]['file'] );
				}
			}

		}

		public function translations_themes_update() {

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$projects_list = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/wpts-projects.json' );
			$projects = json_decode( wp_remote_retrieve_body( $projects_list ) );

			$themes = wp_get_themes();
			foreach ( $themes as $key => $theme ) {
				$themes[ $key ] = $theme->get( 'TextDomain' );
			}

			foreach ( $projects as $key => $project ) {
				if ( in_array( $key, $themes, true ) ) {
					foreach ( $project as $resource_slug => $resource ) {
						$update = new WP_Translations_Themes_LP_Update( $resource_slug, $key );
					}
				}
			}

		}

		public function translations_update_count( $updates ) {

			$updates['counts']['translations'] = ( 0 < count( wp_get_translation_updates() ) ) ? count( wp_get_translation_updates() ) : '';
			if ( 1 < count( wp_get_translation_updates() ) ) {
				 $updates['counts']['total'] = ( count( wp_get_translation_updates() ) + $updates['counts']['total'] ) - 1;
			}

			return $updates;
		}

		public function list_translations_update() {

			$updates_lp = wp_get_translation_updates();

			$count_updates_lp = count( $updates_lp );

			foreach ( $updates_lp as $update ) {
				$lang[ $update->slug ][] = $update->language;
				$updates[ $update->slug ] = array(
					'slug'    => $update->slug,
					'type'    => $update->type,
					'locales' => implode( ', ', $lang[ $update->slug ] ),
				);
			}

			if ( ! empty( $updates ) ) :
			?>
			<table class="widefat updates-table" id="update-translations-table">
				<thead>
				<tr>
					<td class="manage-column check-column"><input type="checkbox" id="themes-select-all" /></td>
					<td class="manage-column"><label for="themes-select-all"><?php esc_html_e( 'Select All', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Translations Type', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Languages', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Actions', 'wp-translations' ); ?></label></td>
				</tr>
				</thead>
				<tbody class="translations">
					<?php
					foreach ( $updates as $update ) :
						$checkbox_id = 'checkbox_' . md5( 'lp_' . $update['slug'] );
						switch ( $update['type'] ) {
							case 'core':
								/* translators: Icon type. */
								$type = sprintf( esc_html__( '%s Core', 'wp-translations' ), '<span class="dashicons dashicons-wordpress"></span>' );
								$data_type = $update['type'];
								break;

							case 'plugin':
								/* translators: Icon type. */
								$type = sprintf( esc_html__( '%s Plugin', 'wp-translations' ), '<span class="dashicons dashicons-admin-plugins"></span>' );
								$data_type = $update['type'] . 's';
								break;

							case 'theme':
								/* translators: Icon type. */
								$type = sprintf( esc_html__( '%s Theme', 'wp-translations' ), '<span class="dashicons dashicons-admin-appearance"></span>' );
								$data_type = $update['type'] . 's';
								break;
						}
						?>

						<tr>
							<td class="check-column">
								<input type="checkbox" name="checked[]" id="<?php echo esc_attr( $checkbox_id ); ?>" data-type="<?php echo esc_attr( $update['type'] ); ?>" value="<?php echo esc_attr( $update['slug'] ); ?>" />
								<label for="<?php echo esc_attr( $checkbox_id ); ?>" class="screen-reader-text"></label>
							</td>
							<td class="plugin-title"><p>
								<strong><?php echo esc_attr( $update['slug'] ); ?></strong>
							</p></td>
							<td><?php echo wp_kses_post( (string) $type ); ?></td>
							<td><?php echo esc_html( $update['locales'] ); ?></td>
							<td><button id="wp-translations-update-<?php echo esc_attr( $update['slug'] ); ?>" class="button-link button wp-translations-to-update" type="button" data-type="<?php echo esc_attr( $data_type ); ?>" data-slug="<?php echo esc_attr( $update['slug'] ); ?>"><?php esc_html_e( 'Update now', 'wp-translations' ); ?></button><div id="wp-translations-update-result-<?php echo esc_attr( $update['slug'] ); ?>" class="screen-reader-text"></div></td>
						</tr>

					<?php endforeach; ?>
				</tbody>
				<tfoot>
				<tr>
					<td class="manage-column check-column"><input type="checkbox" id="themes-select-all-2" /></td>
					<td class="manage-column"><label for="themes-select-all-2"><?php esc_html_e( 'Select All', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Translations Type', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Languages', 'wp-translations' ); ?></label></td>
					<td class="manage_column"><label><?php esc_html_e( 'Actions', 'wp-translations' ); ?></label></td>
				</tr>
				</tfoot>
			</table>
		<?php
		endif;
		}
	}

endif;

return new WP_Translations_Setup();
