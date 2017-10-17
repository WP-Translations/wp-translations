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

if ( ! class_exists( 'WP_Translations_Admin' ) ) :

class WP_Translations_Admin {

	public function __construct() {
		$this->options   = get_site_option( 'wp_translations_settings' ) ? get_site_option( 'wp_translations_settings' ) : array();
		$this->page_hook = ! empty( $this->options ) ? (bool) $this->options['page_hook'] : false;
		$this->run();
	}

	protected function run() {
		if ( 'menu' == $this->page_hook ) {
			add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'wp_translations_admin_page' ) );
			add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'wp_translations_settings_page' ) );
		} else {
			add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'wp_translations_admin_page' ) );
		}
	}

	public function wp_translations_admin_page() {
		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		$updates = absint( count( wp_get_translation_updates() ) );
		if( 'menu' == $this->page_hook ) {
			add_menu_page(
				__( 'Translations', 'wp-translations' ),
				__( 'Translations', 'wp-translations' ),
				/* translators: Number of plugins updates */
				//sprintf( esc_html__( 'Translations %s', 'wp-translations' ), '<span class="update-plugins count-' . esc_attr( $updates ) . '"><span class="plugin-count">' . number_format_i18n( $updates ) . '</span></span>' ),
				$capability,
				'wp-translations-admin',
				array( $this, 'wp_translations_admin_output' ),
				'dashicons-translation',
				72
			);
		} else {
			add_options_page(
				__( 'Translations', 'wp-translations' ),
				__( 'Translations', 'wp-translations' ),
				/* translators: Number of plugins updates */
				//sprintf( esc_html__( 'Translations %s', 'wp-translations' ), '<span class="update-plugins count-' . esc_attr( $updates ) . '"><span class="plugin-count">' . number_format_i18n( $updates ) . '</span></span>' ),
				$capability,
				'wp-translations-admin',
				array( $this, 'wp_translations_admin_output' )
			);
		}


	}

	public function wp_translations_admin_output() {
		$updates = absint( count( wp_get_translation_updates() ) );
		$page    = get_plugin_page_url( 'admin.php?page=wp-translations-admin' );
		$settings = get_plugin_page_url( 'admin.php?page=wp-translations-settings' );
		?>
		<div class="wrap">
			<header class="wp-translations-header">
				<img src="<?php echo WP_TRANSLATIONS_PLUGIN_URL . '/assets/img/WPT-ORG-WTAsset 4.svg'; ?>" />
				<button class="wp-translations-button-transifex">Join us on transifex</button>
			</header>

			<div class="wp-filter wp-translations-nav">
				<div class="filter-count">
					<span class="count theme-count"><?php echo number_format_i18n( $updates ); ?></span>
				</div>
				<ul class="filter-links">
					<li>
						<a class="current" href="#"><span class="dashicons dashicons-translation"></span> Translations</a>
					</li>
					<li>
						<a href="#"><span class="dashicons dashicons-cloud"></span> Repositories</a>
					</li>
					<li>
						<a href="#"><span class="dashicons dashicons-admin-network"></span> Licenses</a>
					</li>
				</ul>
				<div class="search-form">
					<?php if ( isset( $_GET['wp-translations-action'] ) ) : ?>
						<a href="<?php echo esc_url( $page ); ?>" class="add-new-h2"><span class="dashicons dashicons-arrow-left"></span> <?php esc_html_e( 'Back', 'wp-translations-updater' ); ?></a>
					<?php endif; ?>
					<a href="<?php echo esc_url( $settings ); ?>" class="add-new-h2"><span class="dashicons dashicons-admin-generic"></span> <?php _e( 'Settings', 'wp-translations' ); ?></a>
				</div>
			</div>
		<?php

		if ( isset( $_GET['wp-translations-action'] ) && 'edit_translation' == $_GET['wp-translations-action'] ) {
			require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/edit-translation.php';
		} else {
			require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/list-translation.php';
		}

		?>
		</div>
		<?php
	}

	public function wp_translations_settings_page() {

		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		add_submenu_page(
			 'wp-translations-admin',
			 __( 'Settings', 'wp-translations' ) ,
			 __( 'Settings', 'wp-translations' ) ,
			 $capability,
			 'wp-translations-settings',
			 array( $this, 'wp_translations_settings_output' )
		);
	}

	public function wp_translations_settings_output() {
		?>
		<div class="wrap">
			<header class="wp-translations-header">
				<img src="<?php echo WP_TRANSLATIONS_PLUGIN_URL . '/assets/img/WPT-ORG-WTAsset 4.svg'; ?>" />
				<button class="wp-translations-button-transifex">Join us on transifex</button>
			</header>
		<?php
		require_once WP_TRANSLATIONS_PLUGIN_DIR . '/templates/settings-translation.php';
		?>
		</div>
		<?php
	}

}

endif;
return new WP_Translations_Admin();
