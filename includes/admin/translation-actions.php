<?php

/*
 * List Translations
 *
 * @package     WP-Translations
 * @subpackage  Includes
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Processes all actions sent via POST and GET by looking for the 'wp-translations-action'
 * request and running do_action() to call the function
 *
 * @since 1.0
 * @return void
 */
function wp_translations_process_actions() {

	if ( isset( $_POST['wp-translations-action'] ) ) {
		do_action( 'wp_translations_' . $_POST['wp-translations-action'], $_POST );
	}

	if ( isset( $_GET['wp-translations-action'] ) ) {
		do_action( 'wp_translations_' . $_GET['wp-translations-action'], $_GET );
	}

}
