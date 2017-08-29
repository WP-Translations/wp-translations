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
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Translations', 'wp-translations' ); ?></h2>
		<?php
		settings_errors();
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'translations';
		?>

		 <div class="js-tabs">

			<ul class="nav-tab-wrapper js-tablist">
				<li class="js-tablist__item"><a href="#translations-tab" id="label_translations-tab" class="js-tablist__link nav-tab"><?php esc_html_e( 'Translations', 'wp-translations' ); ?></a></li>
				<li class="js-tablist__item"><a href="#repositories-tab" id="label_repositories-tab" class="js-tablist__link nav-tab"><?php esc_html_e( 'Repositories', 'wp-translations' ); ?></a></li>
			</ul>


			<div id="translations-tab" class="js-tabcontent">
				<form method="post">
						<input type="hidden" name="page" value="ttest_list_table">

						<?php
						$list_table = new WP_Translations_List_Table();
						$list_table->prepare_items();
						$list_table->search_box( 'search', 'search_id' );
						$list_table->display();
						?>
				</form>
			</div>

			<div id="repositories-tab" class="js-tabcontent">
				<h2>Repositories</h2>
			</div>

		</div><!-- js-tabs -->
	</div><!-- .wrap -->
	<?php
}
