<?php

class acf_field_generous_slider extends acf_field {
	
	/**
	 * The id of the field.
	 *
	 * @since    3.6
	 *
	 * @var      array          $name       Single word, no spaces.
	 */
	var $name;

	/**
	 * The label for the field.
	 *
	 * @since    3.6
	 *
	 * @var      string         $label      Visible when selecting a field type.
	 */
	var $label;

	/**
	 * The type of ACF field.
	 *
	 * @since    3.6
	 *
	 * @var      string         $category   Basic|Content|Choice|Relational|Layout
	 */
	var $category;

	/**
	 * The settings for the field.
	 *
	 * @since    3.6
	 *
	 * @var      array          $settings   The settings for the field.
	 */
	var $settings;

	/**
	 * Default settings which are merged into the field object.
	 *
	 * @since    3.6
	 *
	 * @var      array          $defaults   The defaults for the field.
	 */
	var $defaults;

	/**
	 * Initialize the class.
	 *
	 * Set name, label needed for actions, filters.
	 *
	 * @since    3.6
	 */
	function __construct() {
		$this->name     = 'generous_slider';
		$this->label    = __( 'Generous Slider');
		$this->category = __( 'Basic', 'acf' );
		$this->defaults = array();
		$this->settings = array(
			'path' => apply_filters( 'acf/helpers/get_path', __FILE__ ),
			'dir' => apply_filters( 'acf/helpers/get_dir', __FILE__ ),
			'version' => '0.1.0'
		);

		parent::__construct();
	}

	/**
	 * Create extra options for your field. This is rendered when editing a field.
	 * The value of $field['name'] can be used (like below) to save extra data to the $field
	 *
	 * @since    3.6
	 *
	 * @param    array          $field    The field array holding all the field options.
	 */
	function create_options( $field ) {
		$key = $field['name'];
	}

	/**
	 * Creates the HTML interface for the field
	 *
	 * @since    3.6
	 *
	 * @param    array          $field    The field array holding all the field options.
	 */
	function create_field( $field ) {

		global $wp_plugin_generous;

		$options = $wp_plugin_generous->get_options();

		if( isset( $options['username'] ) && $options['username'] !== '') {

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

		wp_register_script( 'acf-input-generous_slider', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-generous_slider', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		wp_enqueue_script(array(
			'acf-input-generous_slider',	
		));

		wp_enqueue_style(array(
			'acf-input-generous_slider',	
		));

	}

	/**
	 * This action is called in the admin_head action on the edit screen where
	 * your field is created. Use this action to add CSS and JavaScript to assist
	 * your create_field() action.
	 *
	 * Info: http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	 *
	 * @since    3.6
	 */
	function input_admin_head() {
		// Note: This function can be removed if not used
	}

	/**
	 * This action is called in the admin_enqueue_scripts action on the edit
	 * screen where your field is edited. Use this action to add CSS + JavaScript
	 * to assist your create_field_options() action.
	 *
	 * Info: http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *
	 * @since    3.6
	 */
	function field_group_admin_enqueue_scripts() {
		// Note: This function can be removed if not used
	}

	/**
	 * This action is called in the admin_head action on the edit screen where
	 * your field is edited. Use this action to add CSS and JavaScript to assist
	 * your create_field_options() action.
	 *
	 * Info: http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	 *
	 * @since    3.6
	 */
	function field_group_admin_head() {
		// Note: This function can be removed if not used
	}

	/**
	 * Loads the value after the database.
	 *
	 * This filter is applied to the $value after it is loaded from the db.
	 *
	 * @since    3.6
	 *
	 * @param    array          $value    The value found in the database.
	 * @param    int            $post_id  The $post_id from which the value was loaded.
	 * @param    array          $field    The field array holding all the field options.
	 *
	 * @return   array|false    $value    The new value with slider data, or false.
	 */
	function load_value( $value, $post_id, $field ) {
		return $value;
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
	 * Formats the value before the field is created in the admin.
	 *
	 * This filter is applied to the $value after it is loaded from the db and
	 * before it is passed to the create_field action.
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
		return $value;
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
	function format_value_for_api( $value, $post_id, $field ) {
		if ( isset( $value['id'] ) ) {
			$api = new WP_Generous_Api();
			return $api->get_slider( $value['id'] );
		} else {
			return false;
		}
	}
	
	/**
	 * Updates the field after it is loaded from the database.
	 *
	 * This filter is applied to the $field after it is loaded from the database.
	 *
	 * @since    3.6
	 *
	 * @param    array          $field    The field array holding all the field options.
	 *
	 * @return   array          $field    The modified field.
	 */
	function load_field( $field ) {
		return $field;
	}
	
	/**
	 * Updates the field before it's saved to the database.
	 *
	 * This filter is applied to the $field before it is saved to the database.
	 *
	 * @since    3.6
	 *
	 * @param    array          $field    The field array holding all the field options.
	 * @param    int            $post_id  The field group ID (post_type = acf).
	 *
	 * @return   array          $field    The modified field.
	 */
	function update_field( $field, $post_id ) {
		return $field;
	}

}

new acf_field_generous_slider();