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
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue admin assets
 *
 * @since 1.0
 */
function wp_translations_enqueue_admin_assets() {

	$current_screen = get_current_screen();
	$css_ext = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.css' : '.min.css';
	$js_ext  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.js' : '.min.js';

	$wpt_update_data = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'		=> wp_create_nonce( 'wpt-update-nonce' )
	);

	wp_register_script(
		'wp-translations-admin-script',
		WP_TRANSLATIONS_PLUGIN_URL . 'assets/js/wp-translations-admin-script.js',
		array( 'jquery' ),
		WP_TRANSLATIONS_VERSION,
		false
	);

	if ( isset( $current_screen ) && 'plugins' === $current_screen->id ) {
		wp_enqueue_script( 'wp-translations-admin-script' );
		wp_localize_script( 'wp-translations-admin-script', 'wpt_update_ajax', $wpt_update_data );
	}

}
add_action( 'admin_print_styles', 'wp_translations_enqueue_admin_assets' );
