<?php
/**
 * Language_Pack
 *
 * @package     WP-Translations
 * @subpackage  includes/admin
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function wp_translations_quick_edit_form() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpt-update-nonce' ) ) {
		wp_die( esc_html__( 'Cheatin\'uh', 'wp-translations' ) );
	}

	$id = absint( $_POST['id'] );
	$name = $_POST['name'];

	$dropdown_locale = get_available_languages();

	?>

	<fieldset class="inline-edit-col-left">
		<legend class="inline-edit-legend"><?php /* translators: the project name. */ printf( esc_html__( 'Options for %s translations', 'wp-translations' ), '<u>' . esc_html( $name ) . '</u>' ); ?></legend>
		<div class="inline-edit-col">
			<label><h5>Select translations sources</h5></label>

			<?php foreach ( $dropdown_locale as $locale ) : ?>
			<label>
				<span class="title"><?php echo esc_html( $locale ); ?></span>
				<span>
					<select name="wp-translations-source-<?php echo esc_attr( $locale ); ?>">
						<option value="">WordPress</option>
						<option value="">WP-Translations</option>
						<option value="">Custom</option>
					</select>
				</span>
			</label>
			<?php endforeach; ?>

		</div>
	</fieldset>

	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="alignleft">
				<input type="checkbox" value="disable" name="auto-update">
				<span class="checkbox-title"><?php esc_html_e( 'Disable auto-update', 'wp-translations' ); ?></span>
			</label>
		</div>
	</fieldset>

	<p class="submit inline-edit-save">
		<button class="button cancel alignleft wp-translations-inline-cancel" data-id="<?php echo absint( $id ); ?>" type="button"><?php esc_html_e( 'Cancel', 'wp-translations' ); ?></button>
		<button class="button button-primary save alignright"><?php esc_html_e( 'Save', 'wp-translations' ); ?></button>
	</p>

	<?php
	die();
}
add_action( 'wp_ajax_wp_translations_quick_edit_form', 'wp_translations_quick_edit_form' );
