<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://guestplan.com
 * @since      1.0.0
 *
 * @package    Guestplan
 * @subpackage Guestplan/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Guestplan
 * @subpackage Guestplan/admin
 * @author     Guestplan <info@guestplan.com>
 */
class Guestplan_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

    }

    private function load_dependencies()
    {

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-guestplan-admin-settings.php';

    }

    public function check_onboarding_notice()
    {

        if (!current_user_can('administrator')) {return;}
        if (get_option('guestplan_api_key', null) != null) {return;}

        echo '<div class="notice notice-warning is-dismissible"><p>' . sprintf(__('Configure your <a href="%s">booking widget</a> to link with your Guestplan account.', 'guestplan'), esc_url(admin_url('options-general.php?page=' . $this->plugin_name))) . '</p></div>';

    }

}
