<?php

/*
 * Settings Translation
 *
 * @package     WP-Translations
 * @subpackage  Templates
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options = get_site_option( 'wp_translations_settings' ) ? get_site_option( 'wp_translations_settings' ) : array();
$auto_update = ! empty( $options ) ? (bool) $options['disable_update'] : false;

?>
<div class="wrap">
	<h2><?php esc_html_e( 'Settings', 'wp-translations' ); ?> - <a href="<?php echo esc_url( get_plugin_page_url( 'admin.php?page=wp-translations-admin' ) ); ?>" class="add-new-h2"><span class="dashicons dashicons-arrow-left"></span> <?php esc_html_e( 'Back', 'wp-translations-updater' ); ?></a></h2>

	<form id="wp-translations-settings" action="" method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" valign="top">
						<label for="wp-translations-disable-update"><?php esc_html_e( 'Disable translations auto-update', 'wp-translations' ); ?></label>
					</th>
					<td>
						<input name="wp_translations_settings[disable_update]" id="wp-translations-disable-update" type="checkbox" value="1" <?php checked( true, $auto_update ); ?>/>
						<span class="description"><?php esc_html_e( 'By default the translations are updated at the same time as the plugins and themes.', 'wp-translations' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="hidden" name="wp-translations-action" value="save_settings"/>
			<input type="hidden" name="wp-translations-settings-nonce" value="<?php echo wp_create_nonce( 'wp_translations_settings_nonce' ); ?>"/>
			<input type="submit" value="<?php esc_html_e( 'Save Settings', 'wp-translations' ); ?>" class="button-primary"/>
		</p>
	</form>

</div>
