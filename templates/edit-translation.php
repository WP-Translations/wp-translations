<?php

/*
 * Edit Translation
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


if ( ! isset( $_GET['textdomain'] ) ) {
	wp_die( esc_html__( 'Something went wrong.', 'wp-translations' ), __( 'Error', 'wp-translations' ), array( 'response' => 400 ) );
}

$options            = get_site_option( 'wp_translations_settings' ) ? get_site_option( 'wp_translations_settings' ) : array();
$global_auto_update = ( isset( $options['disable_update'] ) ) ? $options['disable_update'] : false;
$auto_update        = ! empty( $options['textdomains'][ esc_attr( $_GET['textdomain'] ) ]['disable_update'] ) ? (bool) $options['textdomains'][ esc_attr( $_GET['textdomain'] ) ]['disable_update'] : false;
$repo_priority      = isset( $options['textdomains'][ esc_attr( $_GET['textdomain'] ) ]['repo_priority'] ) ? $options['textdomains'][ esc_attr( $_GET['textdomain'] ) ]['repo_priority'] : false;
?>
	<form id="wp-translations-edit" action="" method="POST">
		<table class="form-table">
			<tbody>
				<?php if ( false === $global_auto_update ) : ?>
				<tr>
					<th scope="row" valign="top">
						<label for="wp-translations-disable-update"><?php esc_html_e( 'Disable async update', 'wp-translations' ); ?></label>
					</th>
					<td>
						<input name="wp_translations_repo[disable_update]" id="wp-translations-disable-update" type="checkbox" value="1" <?php checked( true, $auto_update ); ?>/>
						<span class="description"><?php esc_html_e( 'By default the translations are updated at the same time as the plugins and themes.', 'wp-translations' ); ?></span>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<th scope="row" valign="top">
						<label for="wp-translations-repo-priority"><?php esc_html_e( 'Select priority repository', 'wp-translations' ); ?></label>
					</th>
					<td>
						<select name="wp_translations_repo[repo_priority]" id="wp-translations-repo-priority">
							<option value="wordpress" <?php selected( $repo_priority, 'wordpress' ); ?>>WordPress</option>
							<option value="wp-translations" <?php selected( $repo_priority, 'wp-translations' ); ?>>WP-Translations</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="hidden" name="wp-translations-action" value="save_translation"/>
			<input type="hidden" name="wp-translations-edit-textdomain" value="<?php echo esc_attr( $_GET['textdomain'] ); ?>"/>
			<input type="hidden" name="wp-translations-edit-nonce" value="<?php echo wp_create_nonce( 'wp_translations_edit_nonce' ); ?>"/>
			<input type="submit" value="<?php esc_html_e( 'Save', 'wp-translations' ); ?>" class="button-primary"/>
		</p>
	</form>
<?php
if ( true === WP_TRANSLATIONS_DEBUG ) {
	echo '<pre>';
		print_r( $options );
	echo '</pre>';
}
