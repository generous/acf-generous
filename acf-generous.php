<?php
/**
 * Plugin Name:       Advanced Custom Fields: Generous
 * Plugin URI:        https://github.com/generous/acf-generous
 * Description:       Provides ACF field types for integrating Generous sliders.
 * Version:           0.1.1
 * Author:            Generous
 * Author URI:        https://genero.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       generous
 * Domain Path:       /languages
 *
 * @since             0.1.0
 *
 * @package           ACF_Generous
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core ACF Generous class.
 *
 * @since      0.1.0
 *
 * @package    ACF_Generous
 * @author     Matthew Govaere <matthew@genero.us>
 */
class ACF_Generous {

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 *
	 * @var      string                $version         The current version of the plugin.
	 */
	protected $version = '0.1.1';

	/**
	 * The core functionality of the plugin.
	 *
	 * @since    0.1.0
	 * @access   public
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load' ) );
	}

	/**
	 * Check to make sure required dependencies are loaded.
	 *
	 * @since    0.1.0
	 * @access   public
	 */
	public function load() {
		if( class_exists( 'WP_Generous' ) ) {
			$this->run();
		}
	}

	/**
	 * Run plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function run() {
		load_plugin_textdomain( 'acf-generous', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
		add_action( 'acf/register_fields', array( $this, 'register_fields_generous_slider' ) );
		add_action( 'acf/include_field_types', array( $this, 'include_field_types_generous_slider' ) );
	}

	/**
	 * ACF v4 settings.
	 *
	 * @since    0.1.0
	 * @access   public
	 */
	public function register_fields_generous_slider() {
		include_once('v4/acf-generous-slider.php');
	}

	/**
	 * ACF v5 settings.
	 *
	 * @since    0.1.0
	 * @access   public
	 */
	public function include_field_types_generous_slider( $version ) {
		include_once('v5/acf-generous-slider.php');
	}

}

/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
function acf_generous_init() {
	$wp_plugin_acf_generous = new ACF_Generous();
}

acf_generous_init();