<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              fluxfullcircle.com
 * @since             1.0.0
 * @package           Flux_Dna
 *
 * @wordpress-plugin
 * Plugin Name:       Flux DNA
 * Plugin URI:        fluxfullcircle.com
 * Description:       Flux DNA is what sets us apart from the rest.
 * Version:           1.0.0
 * Author:            Flux
 * Author URI:        fluxfullcircle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flux-dna
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
define( 'FLUX_DNA_VERSION', '1.0.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flux-dna-activator.php
 */
function activate_flux_dna() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flux-dna-activator.php';
	Flux_Dna_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flux-dna-deactivator.php
 */
function deactivate_flux_dna() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flux-dna-deactivator.php';
	Flux_Dna_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flux_dna' );
register_deactivation_hook( __FILE__, 'deactivate_flux_dna' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flux-dna.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flux_dna() {

	$plugin = new Flux_Dna();
	$plugin->run();

}
run_flux_dna();
