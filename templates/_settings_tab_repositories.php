<?php
/*
 * Settings Tab Repositories
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
$enable_repo   = ! empty( $options ) ? $options['enable_repo'] : false;
$repo_priority = ! empty( $options ) ? $options['repo_priority'] : false;
?>
<div class="postbox">
	<div class="inside">
		<form id="wp-translations-repositories" action="" method="POST">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-enable-repo"><?php esc_html_e( 'Enable external repositories', 'wp-translations' ); ?></label>
						</th>
						<td>
							<input name="wp_translations_settings[enable_repo]" id="wp-translations-disable-update" type="checkbox" value="1" <?php checked( true, $enable_repo ); ?>/>
							<span class="description"><?php esc_html_e( 'Allow translations updates from external repositories', 'wp-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="wp-translations-repo-priority"><?php esc_html_e( 'Select priority repository', 'wp-translations' ); ?></label>
						</th>
						<td>
							<select name="wp_translations_settings[repo_priority]" id="wp-translations-repo-priority">
								<option value="wordpress" <?php selected( $repo_priority, 'wordpress' ); ?>>WordPress</option>
								<option value="wp-translations" <?php selected( $repo_priority, 'wp-translations' ); ?>>WP-Translations</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="hidden" name="wp-translations-action" value="save_repositories"/>
				<input type="hidden" name="wp-translations-settings-nonce" value="<?php echo wp_create_nonce( 'wp_translations_settings_nonce' ); ?>"/>
				<input type="submit" value="<?php esc_html_e( 'Save Settings', 'wp-translations' ); ?>" class="button-primary"/>
			</p>
		</form>
	</div><!-- /end .inside -->
</div><!-- /end .postbox -->
