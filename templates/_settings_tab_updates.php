<?php
/*
 * Settings Tab Updates
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

$auto_update   = ! empty( $options ) ? (bool) $options['auto_update'] : false;
$beta_update   = ! empty( $options ) ? (bool) $options['beta_update'] : false;
$beta_percent  = ! empty( $options['beta_percent'] ) ? $options['beta_percent'] : '';
?>
<div class="postbox">
	<div class="inside">

		<form id="wp-translations-settings" action="" method="POST">

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-disable-update"><?php esc_html_e( 'Disable async update', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[disable_update]" id="wp-translations-disable-update" type="checkbox" value="1" <?php checked( true, $auto_update ); ?>/>
							<span class="description"><?php esc_html_e( 'By default the translations are updated at the same time as the plugins and themes.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-beta-update"><?php esc_html_e( 'Beta translations', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[beta_update]" id="wp-translations-beta-update" type="checkbox" value="1" <?php checked( true, $beta_update ); ?>/>
							<span class="description"><?php esc_html_e( 'Get beta translations from wp.org, by default translations are available when it translates to 95%.', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-beta-percent"><?php esc_html_e( 'Beta percent', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[beta_percent]" id="wp-translations-beta-percent" type="number" value="<?php echo $beta_percent; ?>" />
							<span class="description"><?php esc_html_e( 'Accept beta translations when completed at x%.', 'wp-translations' ); ?></span>
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="hidden" name="wp-translations-action" value="save_settings_updates"/>
				<input type="hidden" name="wp-translations-settings-nonce" value="<?php echo wp_create_nonce( 'wp_translations_settings_nonce' ); ?>"/>
				<input type="submit" value="<?php esc_html_e( 'Save Settings', 'wp-translations' ); ?>" class="button-primary"/>
			</p>

		</form>
	</div><!-- /end .inside -->
</div><!-- /end .postbox -->
