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

?>
<div class="wrap">
	<h2><?php esc_html_e( 'Edit Translation settings', 'wp-translations' ); ?> - <a href="<?php echo esc_url( admin_url( 'admin.php?page=wp-translations-admin' ) ); ?>" class="add-new-h2"><span class="dashicons dashicons-arrow-left"></span> <?php esc_html_e( 'Back', 'wp-translations-updater' ); ?></a></h2>

</div>
