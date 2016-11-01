<?php
namespace mkdo\license_type_field_for_gravity_forms;
/**
 * Class Admin_Notices
 *
 * Notifies the user if the admin needs attention
 *
 * @package mkdo\license_type_field_for_gravity_forms
 */
class Admin_Notices {

	private $options_prefix;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix      = $plugin_options->get_options_prefix();
		$this->plugin_settings_url = $plugin_options->get_plugin_settings_url();
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function admin_notices() {
		if ( ! class_exists( 'GFFormsModel', false ) ) {
			$gravity_forms_url = 'http://www.gravityforms.com/';
			?>
			<div class="notice notice-warning is-dismissible">
			<p>
			<?php _e( sprintf( 'The %sLicence Type Field for Gravity Forms%s plugin requires that you %sinstall and activate the Gravity Forms plugin%s.', '<strong>', '</strong>', '<a href="' . $gravity_forms_url . '" target="_blank">', '</a>' ) , MKDO_LFGF_TEXT_DOMAIN ); ?>
			</p>
			</div>
			<?php
		}
	}
}
