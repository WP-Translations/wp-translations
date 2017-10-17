<?php

/*
 * Settings Translation
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

$options       = get_site_option( 'wp_translations_settings' ) ? get_site_option( 'wp_translations_settings' ) : array();
$current_tab   = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'ui';
$page          = get_plugin_page_url( 'admin.php?page=wp-translations-settings' );
$current_screen = get_current_screen();
?>
	<div class="wp-filter wp-translations-nav">
		<ul class="filter-links">
			<li>
				<a class="<?php echo  $active = 'ui' == $current_tab ? 'current' : ''; ?>" href="<?php echo esc_url( $page . '&tab=ui' ); ?>"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'Integration', 'wp-translations' ); ?></a>
			</li>
			<li>
				<a class="<?php echo  $active = 'updates' == $current_tab ? 'current' : ''; ?>" href="<?php echo esc_url( $page . '&tab=updates' ); ?>"><span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Updates', 'wp-translations' ); ?></a>
			</li>
			<li>
				<a class="<?php echo  $active = 'repositories' == $current_tab ? 'current' : ''; ?>" href="<?php echo esc_url( $page . '&tab=repositories' ); ?>"><span class="dashicons dashicons-cloud"></span> <?php esc_html_e( 'Repositories', 'wp-translations' ); ?></a>
			</li>
			<li>
				<a class="<?php echo  $active = 'performances' == $current_tab ? 'current' : ''; ?>" href="<?php echo esc_url( $page . '&tab=performances' ); ?>"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Performances', 'wp-translations' ); ?></a>
			</li>
		</ul>
		<div class="search-form">
			<a href="<?php echo esc_url( $page ); ?>" class="add-new-h2"><span class="dashicons dashicons-arrow-left"></span> <?php esc_html_e( 'Back', 'wp-translations-updater' ); ?></a>
		</div>
	</div>

	<?php
	settings_errors( 'wp-translations-notices' );
	require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/_settings_tab_' . $current_tab . '.php';
	?>


<?php
if ( true === WP_TRANSLATIONS_DEBUG ) {
	echo '<pre>';
		print_r( $options );
	echo '</pre>';
}
