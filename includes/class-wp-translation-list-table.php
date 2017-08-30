<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class WP_Translations_List_Table extends \WP_List_Table {

	function __construct() {
		parent::__construct( array(
			'singular' => 'translation',
			'plural'   => 'translations',
			'ajax'     => false,
		) );
	}

	function get_table_classes() {
			return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
	}

	/**
	 * Message to show if no designation found
	 *
	 * @return void
	 */
	function no_items() {
			esc_html_e( 'No translation found', 'wp-translations' );
	}

	/**
	 * Default column values if no callback found
	 *
	 * @param  object  $item
	 * @param  string  $column_name
	 *
	 * @return string
	 */
	function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'name':
				return $item->name;

			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'name'        => __( 'Name', 'wp-translations' ),
			'type'        => __( 'Translations Type', 'wp-translations' ),
			'textdomain'  => __( 'Text Domain', 'wp-translations' ),
			'updates'     => __( 'Updates', 'wp-translations' ),

		);

		return $columns;
	}

	/**
	 * Render the designation name column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_name( $item ) {

			$actions           = array();
			$actions['edit']   = '<a href="' . add_query_arg( array( 'wp-translations-action' => 'edit_translation', 'textdomain' => $item->textdomain ) ) . '" class="editinline" data-id="' . esc_attr( $item->id ) . '" title="' . esc_html( $item->name ) . '">' . esc_html__( 'Edit', 'wp-translations' ) . '</a>';
			//$actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=wp-translations&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'wp-translations' ), __( 'Delete', 'wp-translations' ) );

			return sprintf( '<strong>%1$s</strong> %2$s', $item->name, $this->row_actions( $actions ) );
	}

	/**
	 * Render the designation name column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_type( $item ) {
		switch ( $item->type ) {
			case 'core':
				$type = '<span class="dashicons dashicons-wordpress"></span> ';
				$type .= __( 'Core', 'wp-translations' );
				$data_type = $item->type;
				break;

			case 'plugin':
				$type = '<span class="dashicons dashicons-admin-plugins"></span> ';
				$type .= __( 'Plugin', 'wp-translations' );
				$data_type = $item->type . 's';
				break;

			case 'theme':
				$type = '<span class="dashicons dashicons-admin-appearance"></span> ';
				$type .= __( 'Theme', 'wp-translations' );
				$data_type = $item->type . 's';
				break;
		}

		return $type;
	}

	/**
	 * Render the designation name column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_updates( $item ) {
		$updates = count( $item->updates );
		echo '<span class="update-plugins count-' . absint( $updates ) . '"><span class="plugin-count">' . absint( $updates ) . '</span></span>';
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	function get_sortable_columns() {
			$sortable_columns = array(
				'name' => array( 'name', true ),
			);

			return $sortable_columns;
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
			$actions = array(
				'update_translations'  => __( 'Update Translations', 'wp-translations' ),
			);
			return $actions;
	}

	/**
	 * Render the checkbox column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="translation_id[]" value="%d" />', $item->id
			);
	}

	/**
	 * Prepare the class items
	 *
	 * @return void
	 */
	function prepare_items() {

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page              = -1;
		$current_page          = $this->get_pagenum();
		$offset                = ( $current_page - 1 ) * $per_page;
		$this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

		// only ncessary because we have sample data
		$args = array(
			'offset' => $offset,
			'number' => $per_page,
		);

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
				$args['orderby'] = $_REQUEST['orderby'];
				$args['order']   = $_REQUEST['order'] ;
		}

		$this->items  = wp_translations_get_all_translation( $args );

		$this->set_pagination_args( array(
			'total_items' => wp_translations_get_translation_count(),
			'per_page'    => $per_page,
		) );
	}

	public function single_row( $item ) {
		global $l10n;
		if ( 0 < count( $item->updates ) ) {
			$class = 'warning';
		} else {
			$class = ( 1 == is_textdomain_loaded( $item->textdomain ) ) ? 'active' : '';
		}
		echo '<tr id="domain-' . esc_attr( $item->id ) . '" class="' . esc_attr( $class ) . '">';
			$this->single_row_columns( $item );
		echo '</tr>';
	}

}
