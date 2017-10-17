<?php
/*
 * Settings Tab Performances
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
$mo_cache = ! empty( $options ) ? $options['mo_cache'] : false;
?>
<div class="postbox">
	<div class="inside">
		<form id="wp-translations-performances" action="" method="POST">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-cache-test"><?php esc_html_e( 'Object cache', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[mo_cache]" id="wp-translations-mo-cache" type="checkbox" value="1" <?php checked( true, $mo_cache ); ?>/>
							<span class="description"><?php esc_html_e( 'Enable mo cache', 'wp-translations' ); ?></span>
							<button id="wp-translations-test-object-cache" class="button" type="button"><?php esc_html_e( 'Check Object cache', 'wp-translations' ); ?></button>
						</td>
					</tr>

				</tbody>
			</table>

			<p class="submit">
				<input type="hidden" name="wp-translations-action" value="save_performances"/>
				<input type="hidden" name="wp-translations-settings-nonce" value="<?php echo wp_create_nonce( 'wp_translations_settings_nonce' ); ?>"/>
				<input type="submit" value="<?php esc_html_e( 'Save Settings', 'wp-translations' ); ?>" class="button-primary"/>
			</p>
		</form>
	</div><!-- /end .inside -->
</div><!-- /end .postbox -->
