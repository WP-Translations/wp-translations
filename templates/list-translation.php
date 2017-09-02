<?php

/*
 * List Translations
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

?>
<div class="wrap">
	<h2><?php esc_html_e( 'All Translations', 'wp-translations' ); ?> - <a href="<?php echo esc_url( add_query_arg( array( 'wp-translations-action' => 'settings_translation' ) ) ); ?>" class="add-new-h2"><span class="dashicons dashicons-admin-generic"></span> <?php _e( 'Settings', 'wp-translations' ); ?></a></h2>
	<?php	settings_errors(); ?>
	<div id="translations-tab">
	<form method="post">
			<input type="hidden" name="page" value="ttest_list_table">
			<?php
			$list_table = new WP_Translations_List_Table();
			$list_table->prepare_items();
			$list_table->display();
			?>
	</form>
	</div>
</div><!-- /end .wrap -->
<?php
if ( true === WP_TRANSLATIONS_DEBUG ) {
	$updates = wp_get_translation_updates();
	echo '<pre>';
		print_r( $updates );
	echo '</pre>';
}
