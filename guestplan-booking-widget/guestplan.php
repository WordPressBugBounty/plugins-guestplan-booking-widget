<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://guestplan.com
 * @since             1.0.0
 * @package           Guestplan
 *
 * @wordpress-plugin
 * Plugin Name:       Guestplan Booking Widget
 * Plugin URI:        https://guestplan.com/
 * Description:       Turn website visitors into guests with the Guestplan Booking Widget.
 * Version:           1.0.11
 * Author:            Guestplan
 * Author URI:        https://guestplan.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       guestplan
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('GUESTPLAN_VERSION', '1.0.11');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-guestplan-activator.php
 */
function activate_guestplan()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-guestplan-activator.php';
    Guestplan_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-guestplan-deactivator.php
 */
function deactivate_guestplan()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-guestplan-deactivator.php';
    Guestplan_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_guestplan');
register_deactivation_hook(__FILE__, 'deactivate_guestplan');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-guestplan.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_guestplan()
{

    $plugin = new Guestplan();
    $plugin->run();

}
run_guestplan();
