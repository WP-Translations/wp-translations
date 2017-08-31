<?php
/**
 * Register Admin Settings
 *
 * @package     WP-Translations
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wp_translations_admin_page() {
	$updates = absint( count( wp_get_translation_updates() ) );
	add_menu_page(
		'Translations',
		/* translators: Number of plugins updates */
		sprintf( esc_html__( 'Translations %s', 'wp-translations' ), '<span class="update-plugins count-' . esc_attr( $updates ) . '"><span class="plugin-count">' . number_format_i18n( $updates ) . '</span></span>' ),
		'manage_options',
		'wp-translations-admin',
		'wp_translations_admin_output',
		'dashicons-translation',
		72
	);
}
add_action( 'admin_menu', 'wp_translations_admin_page' );

function wp_translations_admin_output() {

	if ( isset( $_GET['wp-translations-action'] ) && 'edit_translation' == $_GET['wp-translations-action'] ) {
		require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/edit-translation.php';
	} elseif ( isset( $_GET['wp-translations-action'] ) && 'settings_translation' == $_GET['wp-translations-action'] ) {
		require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/settings-translation.php';
	} else {
		require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/list-translation.php';
	}

}
