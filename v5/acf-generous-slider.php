<?php

class acf_field_generous_slider extends acf_field {

	/**
	 * The id of the field.
	 *
	 * @since    5.0.0
	 *
	 * @var      array          $name       Single word, no spaces.
	 */
	var $name;

	/**
	 * The label for the field.
	 *
	 * @since    5.0.0
	 *
	 * @var      string         $label      Visible when selecting a field type.
	 */
	var $label;

	/**
	 * The type of ACF field.
	 *
	 * @since    5.0.0
	 *
	 * @var      string         $category   basic|content|choice|relational|layout|custom
	 */
	var $category;

	/**
	 * Default settings which are merged into the field object.
	 *
	 * @since    5.0.0
	 *
	 * @var      array          $defaults   The defaults for the field.
	 */
	var $defaults;

	/**
	 * Array of strings that are used in JavaScript. This allows JS strings
	 * to be translated in PHP and loaded via:
	 *
	 * var message = acf._e('generous_slider', 'error');
	 *
	 * @since    5.0.0
	 *
	 * @var      array          $l10n       The defaults for the field.
	 */
	var $l10n;

	/**
	 * Initialize the class.
	 *
	 * Setup the field type data.
	 *
	 * @since    5.0.0
	 */
	function __construct() {
		$this->name = 'generous_slider';
		$this->label = __( 'Generous Slider', 'acf-generous_slider' );
		$this->category = 'basic';
		$this->defaults = array();
		$this->l10n = array();

		parent::__construct();
	}
	
	/**
	 * Creates the HTML interface for the field
	 *
	 * @since    3.6
	 *
	 * @param    array          $field    The field array holding all the field options.
	 */
	function render_field( $field ) {

		global $wp_plugin_generous;

		$options = $wp_plugin_generous->get_options();

		if ( isset( $options['username'] ) && $options['username'] !== '') {

			$field_id = '';
			$field_title = '';

			if ( isset( $field['value'], $field['value']['id'] ) ) {
				$field_id = $field['value']['id'];

				if ( isset( $field['value']['title'] ) ) {
					$field_title = $field['value']['title'];
				}
			}

			$prefix = 'acf-generous-slider--search';

			echo "<input type=\"text\" name=\"{$field['name']}[title]\" class=\"{$prefix}-input-title\" value=\"" . htmlspecialchars( $field_title ) . "\" />";
			echo "<input type=\"hidden\" name=\"{$field['name']}[id]\" class=\"{$prefix}-input-id\" value=\"{$field_id}\" />";
			echo "<input type=\"hidden\" class=\"{$prefix}-account\" value=\"{$options['username']}\" />";

			echo "<div class=\"{$prefix}-results\"></div>";

		} else {

			echo "<span>Generous username not found. Check plugin settings.</span>";

		}

	}

	/**
	 * This action is called in the admin_enqueue_scripts action on the edit
	 * screen where your field is created. Use this action to add CSS + JavaScript
	 * to assist your create_field() action.
	 *
	 * Info: http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *
	 * @since    3.6
	 */
	function input_admin_enqueue_scripts() {
		
		$dir = plugin_dir_url( dirname( __FILE__ ) );

		wp_register_script( 'acf-input-generous_slider', "{$dir}assets/js/input.js" );
		wp_register_style( 'acf-input-generous_slider', "{$dir}assets/css/input.css" );

		wp_enqueue_script('acf-input-generous_slider');
		wp_enqueue_style('acf-input-generous_slider');
		
	}
	

	/**
	 * Updates the value for the database.
	 *
	 * This filter is applied to the $value before it is updated in the db.
	 *
	 * @since    3.6
	 *
	 * @param    array          $value    The value which will be saved in the database.
	 * @param    int            $post_id  The $post_id of which the value will be saved.
	 * @param    array          $field    The field array holding all the field options.
	 *
	 * @return   array          $value    The modified value.
	 */
	function update_value( $value, $post_id, $field ) {

		if ( '' === $value['id'] ) {
			$value = false;
		}

		return $value;
	}

	/**
	 * Validates the value prior to saving to database.
	 *
	 * This filter is used to perform validation on the value prior to saving.
	 * All values are validated regardless of the field's required setting. This
	 * allows you to validate and return messages to the user if the value is not correct.
	 *
	 * @since    5.0
	 *
	 * @param    bool           $valid    Validation status based on the value and the fields required setting.
	 * @param    mixed          $value    The $_POST value.
	 * @param    array          $field    The field array holding all the field options.
	 * @param    string         $input    The corresponding input name for $_POST value
	 *
	 * @return   bool           $value    If values are valid.
	 */
	function validate_value( $valid, $value, $field, $input ){
		if( '' === $value['id'] ) {
			$valid = false;
		}

		return $valid;
	}

	/**
	 * Formats the value for the frontend.
	 *
	 * This filter is applied to the $value after it is loaded from the db and
	 * before it is passed back to the API functions such as the_field.
	 *
	 * @since    3.6
	 *
	 * @param    array          $value    The value which will be saved in the database.
	 * @param    int            $post_id  The $post_id of which the value will be saved.
	 * @param    array          $field    The field array holding all the field options.
	 *
	 * @return   array          $value    The modified value.
	 */
	function format_value( $value, $post_id, $field ) {
		if ( isset( $value['id'] ) ) {
			$api = new WP_Generous_Api();
			return $api->get_slider( $value['id'] );
		} else {
			return false;
		}
	}

}

new acf_field_generous_slider();