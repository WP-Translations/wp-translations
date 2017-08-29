<?php

/**
 * Get all translation
 *
 * @param $args array
 *
 * @return array
 */
function wp_translations_get_all_translation( $args = array() ) {
	$plugins = get_plugins();
	$data = array();
	$i = 0;
	foreach ( $plugins as $plugin ) {
		$updates = wp_get_translation_updates( 'plugins' );
		$plugins_updates = array();
		foreach ( $updates as $update ) {
			if ( $plugin['TextDomain'] == $update->slug ) {
				$plugins_updates[] = $update;
			}
		}

		$data[] = (object) array(
			'id'	=> $i++,
			'Name' => $plugin['Name'],
			'textdomain' => $plugin['TextDomain'],
			'type' => 'plugin',
			'updates' => $plugins_updates
		);
	}

	$themes = array_keys( wp_get_themes() );
	foreach ( $themes as $theme ) {
		$theme_data = wp_get_theme( $theme );
		$updates = wp_get_translation_updates( 'themes' );
		$themes_updates = array();
		foreach ( $updates as $update ) {
			if ( $theme_data->get( 'TextDomain' ) == $update->slug ) {
				$themes_updates[] = $update;
			}
		}

		$data[] = (object) array(
			'id' => $i++,
			'Name' => $theme_data->get( 'Name' ),
			'textdomain' => $theme_data->get( 'TextDomain' ),
			'type' => 'theme',
			'updates' => $themes_updates,
		);
	}

	return $data;
}

/**
 * Fetch all translation from database
 *
 * @return array
 */
function wp_translations_get_translation_count() {
    $count = count( wp_translations_get_all_translation() );
}

/**
 * Fetch a single translation from database
 *
 * @param int   $id
 *
 * @return array
 */
function wp_translations_get_translation( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'translations WHERE id = %d', $id ) );
}
