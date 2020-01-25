<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://byronj.me
 * @since             1.1.0
 * @package           Easy_Content_Adder
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Content Adder
 * Description:       Easily add content to all of your Pages, Posts, and Custom Post Types.
 * Version:           1.1.1
 * Author:            Byron Johnson
 * Author URI:        https://byronj.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-content-adder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EASY_CONTENT_ADDER_VERSION', '1.1.1' );


/****************************
* global variables
****************************/

// retrieve our plugin settings from options table
$beca_options = get_option('beca_settings');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-content-adder-activator.php
 */
function activate_easy_content_adder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-content-adder-activator.php';
	Easy_Content_Adder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-content-adder-deactivator.php
 */
function deactivate_easy_content_adder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-content-adder-deactivator.php';
	Easy_Content_Adder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_content_adder' );
register_deactivation_hook( __FILE__, 'deactivate_easy_content_adder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-content-adder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easy_content_adder() {

	$plugin = new Easy_Content_Adder();
	$plugin->run();

}
run_easy_content_adder();