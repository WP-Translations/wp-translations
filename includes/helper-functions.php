<?php

/*
 * Helper Functions
 *
 * @package     WP-Translations
 * @subpackage  Includes
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function get_plugin_page_url( $args = false ) {
	$url = is_multisite() ? network_admin_url( $args ) : admin_url( $args );
	return $url;
}
