<?php
namespace mkdo\licence_type_field_for_gravity_forms;
/**
 * Class Licence_Type_Field
 *
 * The Licence Type Field for Gravity Forms
 *
 * @package mkdo\licence_type_field_for_gravity_forms
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class Licence_Type_Field extends \GF_Field {

	public $type = 'licence_type';

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'gform_editor_js_set_default_values', array( $this, 'gform_editor_js_set_default_values' ) );
		add_action( 'gform_field_standard_settings', array( $this, 'gform_field_standard_settings' ) );
		add_filter( 'gform_tooltips', array( $this, 'gform_tooltips' ) );
		\GF_Fields::register( new \mkdo\licence_type_field_for_gravity_forms\Licence_Type_Field() );
	}

	/**
	 * Setup the form defaults
	 *
	 * This is where we need to define the label for the form, and also define any
	 * inputs (if a complex multi-input field).
	 *
	 * This function hooks into JS output in Gravity Forms so is a little odd to write.
	 */
	public function gform_editor_js_set_default_values() {
		?>
		case 'licence_type' :
			field.label = '<?php _e( 'Licence Type', MKDO_LFGF_TEXT_DOMAIN ); ?>';
			field.inputs = [
				new Input( field.id + 0.1, "<?php _e( 'Licence Type ID', MKDO_LFGF_TEXT_DOMAIN ); ?>" ),
				new Input( field.id + 0.2, "<?php _e( 'Licence Type Text', MKDO_LFGF_TEXT_DOMAIN ); ?>" )
			];
		break;
		<?php
	}

	/**
	 * Add backend fields to Gravity Forms
	 *
	 * These are all handled with JavaScript, and the JS is handled in the
	 * get_form_editor_inline_script_on_page_render function
	 *
	 * You need to check the position, as if you ommit this the field loops
	 * in all positions in the backend.
	 *
	 * Note that the class on the li ('licence_type_setting' in this case) will
	 * be used in the 'get_form_editor_field_settings' function to allow us to
	 * use these settings
	 *
	 * @param  int      $position    The position of the field in the backend
	 * @return string                HTML output
	 */
	public function gform_field_standard_settings( $position ) {
		if ( 25 == $position  ) {

			$services       = array();
			switch_to_blog( BLOG_ID_CURRENT_SITE );
			$checked_services = get_terms(
				array(
					'taxonomy'   => 'council_services',
					'hide_empty' => false,
				)
			);
			restore_current_blog();

			?>
			<li class="licence_type_setting field_setting">
				<label for="licence_type_admin_label" class="section_label">
					<?php _e( 'Choose Licence Type', MKDO_LFGF_TEXT_DOMAIN ); ?>
					<?php gform_tooltip( 'licence_type' ) ?>
				</label>
				<select id="licence_type" onchange="SetFieldProperty( 'licence_type', this.value ); ToggleLicenceType( this.value ); ">
					<option value="">None</option>
					<?php
					foreach ( $checked_services as $service ) {
						?>
						<option value="<?php echo  $service->term_id;?>"><?php echo $service->name;?></option>
						<?php
					}
					?>
				</select>
			</li>
			<?php
		}
	}

	/**
	 * Add inline script
	 *
	 * This lets us hook dynamically add our functions and bindings that will
	 * make our backend fields work
	 *
	 * @return String JavaScript output
	 */
	public function get_form_editor_inline_script_on_page_render() {
		$script = "
jQuery(document).bind( 'gform_load_field_settings', function( event, field, form ) {
	jQuery( '#licence_type').val( field.licence_type == undefined ? '' : field.licence_type );
});
function ToggleLicenceType( type ) {
	var field = GetSelectedField(),
		isSubmitButton = type == 'submit',
		id_value = jQuery( '#licence_type option:selected' ).val();
		text_value = jQuery( '#licence_type option:selected' ).text();
	jQuery( '#input_' + field.id + '_1' ).val( id_value );
	jQuery( '#input_' + field.id + '_2' ).val( text_value );
	SetFieldProperty( 'defaultValue', id_value + ':' + text_value );
	SetInputDefaultValue( id_value, + field.id + '.1' );
	SetInputDefaultValue( text_value, + field.id + '.2' );
}";
		return $script;
	}


	/**
	 * Form editor settings
	 *
	 * Add one or more backend settings here, make sure you add in your custom
	 * setting, in this case 'licence_type_setting'
	 *
	 * @return Array    An array of settings that the field uses in the backend
	 */
	function get_form_editor_field_settings() {
		return array(
			'licence_type_setting',
		    'conditional_logic_field_setting',
		    'prepopulate_field_setting',
		    'error_message_setting',
		    'label_setting',
		    //'sub_labels_setting',
		    //'label_placement_setting',
		    //'sub_label_placement_setting',
		    // 'admin_label_setting',
		    //'time_format_setting',
		    // 'rules_setting',
		    // 'visibility_setting',
		    // 'duplicate_setting',
		    'default_inputs_setting',
		    // 'input_placeholders_setting',
		    // 'description_setting',
		    'css_class_setting',
		);
	}

	/**
	 * Define any tool tips you have setup
	 *
	 * @param  Array   $tooltips    An array of tooltips
	 * @return Array                An array of tooltips
	 */
	public function gform_tooltips( $tooltips ) {
		$tooltips['licence_type'] = __( 'Choose a Licence Type', TEXTDOMAIN );
		return $tooltips;
	}

	/**
	 * Setup the form title
	 *
	 * @return String     The form title
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Licence Type', MKDO_LFGF_TEXT_DOMAIN );
	}

	/**
	 * Is conditional logic supported?
	 *
	 * @return Boolean  True if conditoinal logic is supported
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	/**
	 * Create our form button
	 *
	 * @return Array    Form button details
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * Validate the form
	 *
	 * This is left empty so that the $value does not get overridden
	 *
	 * @param  String/Array  $value The field value
	 * @param  Object        $form  The form
	 */
	function validate( $value, $form ) {}


	/**
	 * Render the field
	 *
	 * @param  Object        $form     The form object
	 * @param  String/Array  $value    The value of the field
	 * @param  Object        $entry    The entry value
	 * @return String                  HTML of the form
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {

		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$form_id         = absint( $form['id'] );
		$id              = intval( $this->id );
		$field_id        = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$class_suffix    = $is_entry_detail ? '_admin' : '';

		/**
		 * Code for sub-lable placements, I have overriden as this will always be hidden unless in the admin
		 */

		// $form_sub_label_placement  = rgar( $form, 'subLabelPlacement' );
		// $field_sub_label_placement = $this->subLabelPlacement;
		// $is_sub_label_above        = $field_sub_label_placement == 'above' || ( empty( $field_sub_label_placement ) && $form_sub_label_placement == 'above' );
		// $sub_label_class_attribute = $field_sub_label_placement == 'hidden_label' ? "class='hidden_sub_label screen-reader-text'" : '';

		$sub_label_class_attribute = is_admin() ? "class=''" : "class='hidden_sub_label screen-reader-text'";
		$disabled_text             = $is_form_editor ? "disabled='disabled'" : '';

		/**
		 * Grab the values from the value (should always be an array)
		 */
		$type_id   = null;
		$type_text = null;

		if ( is_array( $value ) ) {
			$type_id   = esc_attr( \RGForms::get( $this->id . '.1', $value ) );
			$type_text = esc_attr( \RGForms::get( $this->id . '.2', $value ) );
		}

		/**
		 * Set the field type, we want them hidden on the front end for this plugin
		 */
		$field_type      = is_admin() ? 'text' : 'hidden';

		/**
		 * Get the input values
		 */
		$type_id_input   = \GFFormsModel::get_input( $this, $this->id . '.1' );
		$type_text_input = \GFFormsModel::get_input( $this, $this->id . '.2' );

		/**
		 * Get the placeholder attributes (if set)
		 */
		$type_id_placeholder_attribute   = \GFCommon::get_input_placeholder_attribute( $type_id_input );
		$type_text_placeholder_attribute = \GFCommon::get_input_placeholder_attribute( $type_text_input );

		/**
		 * Get the tab indexes
		 */
		$type_id_tabindex   = $this->get_tabindex();
		$type_text_tabindex = $this->get_tabindex();

		/**
		 * Set the labels (these could be manually set if the backend is configured)
		 */
		$type_id_label   = rgar( $type_id_input, 'customLabel' ) != '' ? $type_id_input['customLabel'] : gf_apply_filters( array( 'licence_type_id', $form_id ), esc_html__( 'Licence Type ID', MKDO_LFGF_TEXT_DOMAIN ), $form_id );
		$type_text_label = rgar( $type_text_input, 'customLabel' ) != '' ? $type_text_input['customLabel'] : gf_apply_filters( array( 'licence_type_text', $form_id ), esc_html__( 'Licence Type Text', MKDO_LFGF_TEXT_DOMAIN ), $form_id );

		/**
		 * Create the labels and the fields
		 */
		$label1          = "<label for='{$field_id}_1' {$sub_label_class_attribute}>{$type_id_label}</label>";
		$label2          = "<label for='{$field_id}_2' {$sub_label_class_attribute}>{$type_text_label}</label>";
		$input1          = "<input type='{$field_type}' size='3' name='input_{$id}.1' id='{$field_id}_1' value='{$type_id}' {$type_id_tabindex}  {$disabled_text} {$type_id_placeholder_attribute} />";
		$input2          = "<input type='{$field_type}' size='10' name='input_{$id}.2' id='{$field_id}_2' value='{$type_text}' {$type_text_tabindex}  {$disabled_text} {$type_text_placeholder_attribute} />";

		// Return the output, with wrapper if on admin, without if on frontend
		if ( is_admin() ) {

			return "
			<div class='ginput_complex{$class_suffix} ginput_container gfield_trigger_change' id='{$field_id}'>
	            <span id='{$field_id}_1_container' class='licence_type_id'>
					{$input1}{$label1}
				</span>
				<span id='{$field_id}_2_container' class='licence_type_name'>
					{$input2}{$label2}
				</span>
				<div class='gf_clear gf_clear_complex'></div>
	        </div>
			";
		} else {
			return "{$input1}{$input2}";
		}
	}

	/**
	 * Get the field classes
	 * @return String     The field classes
	 */
	public function get_field_label_class() {
		return 'gfield_label gfield_label_before_complex';
	}

	/**
	 * Get input property
	 *
	 * @param  Int     $input_id      The ide of the input
	 * @param  String  $property_name The name of the propperty
	 * @return String                 Verturns the value
	 */
	public function get_input_property( $input_id, $property_name ) {
		$input = \GFFormsModel::get_input( $this, $this->id . '.' . (string) $input_id );

		return rgar( $input, $property_name );
	}

	/**
	 * Sanitize the settings
	 */
	public function sanitize_settings() {
		parent::sanitize_settings();
		if ( is_array( $this->inputs ) ) {
			foreach ( $this->inputs as &$input ) {
				if ( isset( $input['choices'] ) && is_array( $input['choices'] ) ) {
					$input['choices'] = $this->sanitize_settings_choices( $input['choices'] );
				}
			}
		}
	}

	/**
	 * Return the field
	 *
	 * @param  String/Array   $value                  The Value
	 * @param  Bool           $force_frontend_label   Force the frontend label
	 * @param  Object         $form                   The Form
	 * @return String                                 The field output
	 */
	public function get_field_content( $value, $force_frontend_label, $form ) {
	    $form_id         = $form['id'];
	    $admin_buttons   = $this->get_admin_buttons();
	    $is_entry_detail = $this->is_entry_detail();
	    $is_form_editor  = $this->is_form_editor();
	    //$is_admin        = $is_entry_detail || $is_form_editor;
	    $is_admin        = is_admin();
	    $field_label     = $this->get_field_label( $force_frontend_label, $value );
	    $field_id        = $is_admin || $form_id == 0 ? "input_{$this->id}" : 'input_' . $form_id . "_{$this->id}";
	    $field_content   = ! $is_admin ? '{FIELD}' : $field_content = sprintf( "%s<label for='input_%s' class='gfield_label'>%s</label>{FIELD}", $admin_buttons, $field_id, esc_html( $field_label ) );

	    return $field_content;
	}

	/**
	 * Show the value on the entries screen
	 */
	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		if ( is_array( $value ) && ! empty( $value ) ) {

			$type_id   = trim( $value[ $this->id . '.1' ] );
	        $type_text = trim( $value[ $this->id . '.2' ] );

			return $type_text . ' (' . $type_id . ')';
	    } else {
	        return $value;
	    }
	}
}
