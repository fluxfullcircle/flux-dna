<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       fluxfullcircle.com
 * @since      1.0.0
 *
 * @package    Flux_Dna
 * @subpackage Flux_Dna/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Flux_Dna
 * @subpackage Flux_Dna/includes
 * @author     Flux <taahir@fluxfullcircle.com>
 */
class Flux_Dna_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'flux-dna',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
