<?php
/*
 * Settings Tab UI
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

$core_updates     = ! empty( $options ) ? (bool) $options['core_updates'] : false;
$plugins_updates  = ! empty( $options ) ? (bool) $options['plugins_updates'] : false;
$themes_updates   = ! empty( $options ) ? (bool) $options['themes_updates'] : false;
$bubble_count     = ! empty( $options ) ? (bool) $options['bubble_count'] : false;
$page_hook        = ! empty( $options ) ? $options['page_hook'] : false;

?>
<div class="postbox">
	<div class="inside">
		<form id="wp-translations-settings" action="" method="POST">

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-core-updates"><?php esc_html_e( 'Core updates', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[core_updates]" id="wp-translations-core-updates" type="checkbox" value="1" <?php checked( true, $core_updates ); ?>/>
							<span class="description"><?php esc_html_e( 'See translations updates details in core update page.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-plugins-updates"><?php esc_html_e( 'Plugins updates', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[plugins_updates]" id="wp-translations-plugins-updates" type="checkbox" value="1" <?php checked( true, $plugins_updates ); ?>/>
							<span class="description"><?php esc_html_e( 'See translations updates details in plugins update page.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-themes-updates"><?php esc_html_e( 'Themes updates', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[themes_updates]" id="wp-translations-themes-updates" type="checkbox" value="1" <?php checked( true, $themes_updates ); ?>/>
							<span class="description"><?php esc_html_e( 'See translations updates details in themes update page.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-bubble-count"><?php esc_html_e( 'Bubble count', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[bubble_count]" id="wp-translations-bubble-count" type="checkbox" value="1" <?php checked( true, $bubble_count ); ?>/>
							<span class="description"><?php esc_html_e( 'Updates bubble count with translations.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-page-hook"><?php esc_html_e( 'Select admin page position', 'wp-translations' ); ?></label>
						</th>
						<td>
							<select name="wp_translations_settings[page_hook]" id="wp-translations-repo-priority">
								<option value="menu" <?php selected( $page_hook, 'wordpress' ); ?>><?php esc_html_e( 'Dedicated page', 'wp-translations' ); ?></option>
								<option value="options" <?php selected( $page_hook, 'wp-translations' ); ?>><?php esc_html_e( 'Settings subpage', 'wp-translations' ); ?></option>
							</select>
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
	</div><!-- /end .inside -->
</div><!-- /end .postbox -->
