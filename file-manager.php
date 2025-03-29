<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/Jerl92
 * @since             1.0.0
 * @package           File_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       File Manager
 * Plugin URI:        https://https://github.com/Jerl92/File-Manager
 * Description:       File Manager plugin for wordpress
 * Version:           1.0.0
 * Author:            Jeremie Langevin
 * Author URI:        https://https://github.com/Jerl92/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       file-manager
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
define( 'FILE_MANAGER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-file-manager-activator.php
 */
function activate_file_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-file-manager-activator.php';
	File_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-file-manager-deactivator.php
 */
function deactivate_file_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-file-manager-deactivator.php';
	File_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_file_manager' );
register_deactivation_hook( __FILE__, 'deactivate_file_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-file-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_file_manager() {

	$plugin = new File_Manager();
	$plugin->run();

}
run_file_manager();
