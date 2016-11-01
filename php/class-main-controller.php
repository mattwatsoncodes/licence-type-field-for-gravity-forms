<?php

namespace mkdo\license_type_field_for_gravity_forms;

/**
 * Class Main_Controller
 *
 * The main loader for this plugin
 *
 * @package mkdo\license_type_field_for_gravity_forms
 */
class Main_Controller {

	private $plugin_options;
	private $assets_controller;
	private $admin_notices;
	private $license_type_field;

	/**
	 * Constructor
	 *
	 * @param Options            $options              Object defining the options page
	 * @param AssetsController   $assets_controller    Object to load the assets
	 */
	public function __construct(
		Plugin_Options $plugin_options,
		Assets_Controller $assets_controller,
		Admin_Notices $admin_notices,
		License_Type_Field $license_type_field
	) {
		$this->plugin_options          = $plugin_options;
        $this->assets_controller       = $assets_controller;
		$this->admin_notices           = $admin_notices;
		$this->license_type_field      = $license_type_field;
	}

	/**
	 * Do Work
	 */
	public function run() {
		load_plugin_textdomain( MKDO_LFGF_TEXT_DOMAIN, false, MKDO_LFGF_ROOT . '\languages' );
		$this->plugin_options->run();
		$this->assets_controller->run();
		$this->admin_notices->run();
		$this->license_type_field->run();
	}
}
