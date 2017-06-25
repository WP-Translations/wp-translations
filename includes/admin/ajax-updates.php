<?php
/**
 * Update Language Packs
 *
 * @package     WP-Translations
 * @subpackage  includes/Admin
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function wp_translations_update_translations() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpt-update-nonce' ) ) {
		wp_die( esc_html__( 'Cheatin&#8217; uh?&nbsp;:&nbsp;', 'wp-translations' ) );
	}
	$slug = esc_attr( $_POST['slug'] );
	$type = esc_attr( $_POST['type'] );


	include_once( ABSPATH . '/wp-admin/includes/class-wp-upgrader.php' );
	if ( file_exists( ABSPATH . '/wp-admin/includes/screen.php' ) ) {
		include_once( ABSPATH . '/wp-admin/includes/screen.php' );
	}
	if ( file_exists( ABSPATH . '/wp-admin/includes/template.php' ) ) {
		include_once( ABSPATH . '/wp-admin/includes/template.php' );
	}
	if ( file_exists( ABSPATH . '/wp-admin/includes/misc.php' ) ) {
		include_once( ABSPATH . '/wp-admin/includes/misc.php' );
	}
	include_once( ABSPATH . '/wp-admin/includes/file.php' );

	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	$upgrader_args = array( 'url' => '', 'nonce' => '', 'title' => __( 'Translations' ), 'skip_header_footer' => true );
	$upgrader = new Language_Pack_Upgrader( new Language_Pack_Upgrader_Skin( $upgrader_args ) );
	$all_language_updates = wp_get_translation_updates();

	$transient_type = 'update_' . $type;
	$transient = get_site_transient( $transient_type );

	$language_updates = array();
	foreach ( $all_language_updates as $current_language_update ) {
		if ( $current_language_update->slug == $slug ) {
			$language_updates[] = $current_language_update;
		}
	}

	$result = count( $language_updates ) == 0 ? false : $upgrader->bulk_upgrade( $language_updates );

	foreach ( $transient->translations as $key => $update ) {
		if( $update['slug'] == $slug ) {
			unset( $transient->translations[ $key ] );
		}
	}
	$transient = set_site_transient( $transient_type, $transient );

	if ( empty( $result ) ) {
		esc_html_e( 'Translations failed to update.', 'wp-translations' );
	}

	$data = array(
		'updates' => $language_updates
	);

	die();
}
add_action( 'wp_ajax_wp_translations_update_translations', 'wp_translations_update_translations' );
