<?php

/**
 * @link              https://github.com/mkdo/license-type-field-for-gravity-forms
 * @package           mkdo\license_type_field_for_gravity_forms
 *
 * Plugin Name:       License Type Field for Gravity Forms
 * Plugin URI:        https://github.com/mkdo/license-type-field-for-gravity-forms
 * Description:       License Type field designed to work with Gravity Forms
 * Version:           1.0.0
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        http://www.makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       license-type-field-for-gravity-forms
 * Domain Path:       /languages
 */

// Constants
define( 'MKDO_LFGF_ROOT', __FILE__ );
define( 'MKDO_LFGF_VERSION', '1.0.0' );
define( 'MKDO_LFGF_TEXT_DOMAIN', 'license-type-field-for-gravity-forms' );

// Load Classes
require_once 'php/class-main-controller.php';
require_once 'php/class-plugin-options.php';
require_once 'php/class-assets-controller.php';
require_once 'php/class-admin-notices.php';
require_once 'php/class-license-type-field.php';

// Use Namespaces
use mkdo\license_type_field_for_gravity_forms\Main_Controller;
use mkdo\license_type_field_for_gravity_forms\Plugin_Options;
use mkdo\license_type_field_for_gravity_forms\Assets_Controller;
use mkdo\license_type_field_for_gravity_forms\Admin_Notices;
use mkdo\license_type_field_for_gravity_forms\License_Type_Field;

// Initialize Classes
$plugin_options              = new Plugin_Options();
$assets_controller           = new Assets_Controller( $plugin_options );
$admin_notices               = new Admin_Notices( $plugin_options );
$license_type_field          = new License_Type_Field();
$main_controller             = new Main_Controller(
	$plugin_options,
	$assets_controller,
	$admin_notices,
	$license_type_field
);

// Run the Plugin
$main_controller->run();
