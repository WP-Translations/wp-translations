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
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WP_Translations_Admin_Settings' ) ):
class WP_Translations_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'admin_menu' ) );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
			$parent     = is_multisite() ? 'settings.php' : 'options-general.php';
			$capability = is_multisite() ? 'manage_network' : 'manage_options';

			add_submenu_page(
				$parent,
				esc_html__( 'WP-Translations Settings', 'github-updater' ),
				'WP-Translations',
				$capability,
				'wp-translations-settings',
				array( &$this, 'plugin_page' )
			);
    }

    function get_settings_sections() {
        $sections = array(
            array(
              'id'    => 'wpts_settings_transifex',
              'title' => __( 'Transifex', 'wp-translations-server' )
            ),
            array(
              'id'    => 'wpts_settings_github',
              'title' => __( 'GitHub', 'wp-translations-server' )
            ),
            array(
              'id'    => 'wpts_settings_slack',
              'title' => __( 'Slack', 'wp-translations-server' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wpts_settings_transifex' => array(
                array(
                  'name'              => 'transifex_username',
                  'label'             => __( 'Transifex username', 'wp-translations-server' ),
                  'desc'              => __( 'Add your transifex username', 'wp-translations-server' ),
                  'placeholder'       => __( 'Username', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'            => 'transifex_password',
                    'label'           => __( 'Transifex Password', 'wp-translations-server' ),
                    'desc'            => __( 'Add your transifex password', 'wp-translations-server' ),
                    'type'            => 'password',
                    'default' => ''
                ),
                array(
                   'name'              => 'transifex_completed',
                   'label'             => __( 'Percent completed', 'wp-translations-server' ),
                   'desc'              => __( 'Define translation percent completed to process a language pack', 'wp-translations-server' ),
                   'placeholder'       =>  '100',
                   'min'               => 0,
                   'max'               => 100,
                   'step'              => '1',
                   'type'              => 'number',
                   'default'           => '',
                   'sanitize_callback' => 'int'
               ),
               array(
                  'name'              => 'transifex_reviewed',
                  'label'             => __( 'Percent reviewed', 'wp-translations-server' ),
                  'desc'              => __( 'Define translation percent reviewed to process a language pack', 'wp-translations-server' ),
                  'placeholder'       =>  '100',
                  'min'               => 0,
                  'max'               => 100,
                  'step'              => '1',
                  'type'              => 'number',
                  'default'           => '',
                  'sanitize_callback' => 'int'
              )
            ),
            'wpts_settings_github' => array(
                array(
                  'name'              => 'github_organization',
                  'label'             => __( 'GitHub Organization', 'wp-translations-server' ),
                  'desc'              => __( 'Add your GitHub Organization', 'wp-translations-server' ),
                  'placeholder'       => __( 'Organization', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                  'name'              => 'github_repo',
                  'label'             => __( 'GitHub Repository', 'wp-translations-server' ),
                  'desc'              => __( 'Add your GitHub Repository', 'wp-translations-server' ),
                  'placeholder'       => __( 'Repository', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                  'name'              => 'github_token',
                  'label'             => __( 'GitHub token', 'wp-translations-server' ),
                  'desc'              => __( 'Add your GitHub token', 'wp-translations-server' ),
                  'placeholder'       => __( 'Token', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                )
            ),
            'wpts_settings_slack' => array(
								array(
										'name'  => 'slack_enable',
										'label' => __( 'Enable', 'wp-translations-server' ),
										'desc'  => __( 'Enable Slack notifications', 'wp-translations-server' ),
										'type'  => 'checkbox'
								),
                array(
                  'name'              => 'slack_url',
                  'label'             => __( 'Webhook URL', 'wp-translations-server' ),
                  'desc'              => __( 'Enter the Webhook URL', 'wp-translations-server' ),
                  'placeholder'       => __( 'https://', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                  'name'              => 'slack_channel',
                  'label'             => __( 'Channel', 'wp-translations-server' ),
                  'desc'              => __( 'Enter the channel name', 'wp-translations-server' ),
                  'placeholder'       => __( '#', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                  'name'              => 'slack_username',
                  'label'             => __( 'Bot Username', 'wp-translations-server' ),
                  'desc'              => __( 'Choose a username, by default transifex username', 'wp-translations-server' ),
                  'placeholder'       => __( 'Username', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                  'name'              => 'slack_emoji',
                  'label'             => __( 'Emoji', 'wp-translations-server' ),
                  'desc'              => __( 'Choose a emoji, by default ghost emoji', 'wp-translations-server' ),
                  'placeholder'       => __( 'ghost', 'wp-translations-server' ),
                  'type'              => 'text',
                  'default'           => '',
                  'sanitize_callback' => 'sanitize_text_field'
                ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';
        echo '<h1>WP-Translations Server</h1>';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 *
 * @return mixed
 */
function wpts_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

function wpts_admin_menu_impoter() {
    add_submenu_page(
      'options-general.php',
      __( 'Import', 'wp-translations-server' ),
      __( 'Import', 'wp-translations-server' ),
      'manage_options',
      'wpts_importer_screen',
       'wpts_importer_page'
    );
}
add_action( 'admin_menu', 'wpts_admin_menu_impoter' );

function wpts_importer_page() {
    echo '<div class="wrap">';
    echo '<h1>WP-Translations Test</h1>';

    do_settings_sections( 'wpts_importer_page' );

    echo '</div>';
}

function wpts_importer_init() {
    // register a new setting for "reading" page
    register_setting('wpts_importer_page', 'wpts_importer_data');

    // register a new section in the "reading" page
    add_settings_section(
        'wpts_settings_section',
        '',
        'wpts_settings_section_cb',
        'wpts_importer_page'
    );

    // register a new field in the "wpts_settings_section" section, inside the "reading" page
    add_settings_field(
        'wpts_settings_field',
        'Get list of projects',
        'wpts_settings_field_cb',
        'wpts_importer_page',
        'wpts_settings_section'
    );
}

/**
 * register wpts_settings_init to the admin_init action hook
 */
add_action('admin_init', 'wpts_importer_init');

// section content cb
function wpts_settings_section_cb() {
    echo '<p>Import projects from transifex.</p>';
}

// field content cb
function wpts_settings_field_cb() {

  $updates = array();
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
	$locales = get_available_languages();
	$locales = ! empty( $locales ) ? $locales : array( get_locale() );

	$plugins_updates = get_option('_site_transient_update_plugins');

	$projects_list = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/wpts-projects.json' );
	$projects = json_decode( wp_remote_retrieve_body( $projects_list ) );

	$plugins_active = get_plugins();
	$themes 				= array_keys( wp_get_themes() );
	foreach ( $themes as $key => $theme ) {
		$theme = wp_get_theme( $theme );
		$themes_domain[] = array(
			'slug'			 => $key,
			'TextDomain' => $theme->get( 'TextDomain' )
		);
	}
	$themes_domains	= wp_list_pluck( $themes_domain, 'TextDomain', 'slug' );
	$plugins 				= wp_list_pluck( $plugins_active, 'TextDomain' );
	$plugins_data   = array();
		foreach ( $plugins_active as $key => $plugin ) {
				$plugins_data[ $key ] = array(
				'name' => $plugin['Name'],
				'textdomain' => $plugin['TextDomain'],
				'file' => $key
			);
		}

	$local_projects = array_merge( $plugins, $themes_domains );

	foreach ( $projects as $key => $project ) {
		if( in_array( $key, $local_projects ) ) {

			foreach ( $project as $resource_slug => $resource ) {


				$remote_lp = wp_remote_get( 'https://raw.githubusercontent.com/WP-Translations/language-packs/master/' . $resource_slug . '/language-pack.json' );
				$remote_lp_json = json_decode( wp_remote_retrieve_body( $remote_lp ) );

				foreach ( $locales as $locale ) {

					if ( in_array( $locale, array_keys( (array) $remote_lp_json ) ) ) {

						if ( 'plugin' == $remote_lp_json->{$locale}->type ) {
							$translations = wp_get_installed_translations( 'plugins' );
						}
						if ( 'theme' === $remote_lp_json->{$locale}->type ) {
							$translations = wp_get_installed_translations( 'themes' );
						}

							$lang_pack_mod = isset( $remote_lp_json->{$locale}->updated )
								? substr( $remote_lp_json->{$locale}->updated, 0, -3 )
								: 0;

								echo 'remote ' . $resource_slug . '-' . $locale . ' : ' . $lang_pack_mod . '<br/>';

							$translation_mod = isset( $translations[ $resource_slug ][ $locale ] )
								? substr( $translations[ $resource_slug ][ $locale ]['PO-Revision-Date'],0 ,-5 )
								: 0;

								echo 'local ' . $resource_slug . '-' . $locale . ' : ' . $translation_mod . '<hr/>';

							if ( $lang_pack_mod > $translation_mod ) {
								$updates[] = (array) $remote_lp_json->{$locale};
							}

						//}

					}

				}

			}

		}
	}

	echo '<pre>';
		print_r( $plugins_updates );
	echo '</pre>';

}
