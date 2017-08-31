<?php
/**
 * Enqueue Admin Assets
 *
 * @package     WP-Translations
 * @subpackage  Admin
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin assets
 *
 * @since 1.0
 */
function wp_translations_enqueue_admin_assets() {

	$themes_translations = array();
	$themes_updates = get_site_transient( 'update_themes' );

	foreach ( $themes_updates->translations as $theme ) {
		$themes_translations[ $theme['slug'] ]['updates'][] = $theme['language'];
	}

	$current_screen = get_current_screen();
	$allowed_screens = array(
		'plugins',
		'themes',
		'toplevel_page_wp-translations-admin',
	);
	$css_ext = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.css' : '.min.css';
	$js_ext  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.js' : '.min.js';

	$wpt_update_data = array(
		'ajaxurl'             => admin_url( 'admin-ajax.php' ),
		'nonce'               => wp_create_nonce( 'wpt-update-nonce' ),
		'themes_translations' => $themes_translations,
		'update_message'      => esc_html__( 'New translations are available:&nbsp;', 'wp-translations' ),
	);

	$wpt_update_core = array(
		'ajaxurl'             => admin_url( 'admin-ajax.php' ),
		'nonce'               => wp_create_nonce( 'wpt-update-nonce' ),
		'updating_message'    => esc_html__( 'Updating translations', 'wp-translations' ),
		'updated_message'     => esc_html__( 'Translations updated', 'wp-translations' ),
		'all_updated_message' => esc_html__( 'The translations are up to date.', 'wp-translations' ),
	);

	wp_register_style(
		'wp-translations-admin-styles',
		WP_TRANSLATIONS_PLUGIN_URL . 'assets/css/admin-style' . $css_ext,
		'',
		WP_TRANSLATIONS_VERSION
	);

	wp_register_script(
		'wp-translations-admin-script',
		WP_TRANSLATIONS_PLUGIN_URL . 'assets/js/wp-translations-admin-script' . $js_ext,
		array( 'jquery' ),
		WP_TRANSLATIONS_VERSION,
		false
	);

	wp_register_script(
		'wp-translations-update-core',
		WP_TRANSLATIONS_PLUGIN_URL . 'assets/js/wp-translations-update-core' . $js_ext,
		array( 'jquery' ),
		WP_TRANSLATIONS_VERSION,
		false
	);

	if ( isset( $current_screen ) && in_array( $current_screen->id, $allowed_screens, true ) ) {
		wp_enqueue_style( 'wp-translations-admin-styles' );
		wp_enqueue_script( 'wp-translations-admin-script' );
		wp_localize_script( 'wp-translations-admin-script', 'wpt_update_ajax', $wpt_update_data );
	}

	$update_core_screen = is_multisite() ? 'update-core-network' : 'update-core';

	if ( isset( $current_screen ) && $update_core_screen === $current_screen->id ) {
		wp_enqueue_script( 'wp-translations-update-core' );
		wp_localize_script( 'wp-translations-update-core', 'wpt_update_core', $wpt_update_core );
	}

}
add_action( 'admin_print_styles', 'wp_translations_enqueue_admin_assets' );
