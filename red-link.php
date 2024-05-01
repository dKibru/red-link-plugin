<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://kibru.me
 * @since             1.0.0
 * @package           Red_Link
 *
 * @wordpress-plugin
 * Plugin Name:       Red Link
 * Plugin URI:        https://kibru.me/by-products/red-link
 * Description:       brings the Wikipedia 'red link' functionality for WordPress
 * Version:           1.0.0
 * Author:            Kibru Demeke
 * Author URI:        https://kibru.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       red-link
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RED_LINK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-red-link-activator.php
 */
function activate_red_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-red-link-activator.php';
	Red_Link_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-red-link-deactivator.php
 */
function deactivate_red_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-red-link-deactivator.php';
	Red_Link_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_red_link' );
register_deactivation_hook( __FILE__, 'deactivate_red_link' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-red-link.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_red_link() {

	$plugin = new Red_Link();
	$plugin->run();

}
run_red_link();
