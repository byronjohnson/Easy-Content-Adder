<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://byronj.me
 * @since      1.1.0
 *
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.0
 * @package    Easy_Content_Adder
 * @subpackage Easy_Content_Adder/includes
 * @author     Byron Johnson <me@byronj.me>
 */
class Easy_Content_Adder_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'easy-content-adder',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
