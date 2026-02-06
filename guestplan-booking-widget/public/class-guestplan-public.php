<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://guestplan.com
 * @since      1.0.0
 *
 * @package    Guestplan
 * @subpackage Guestplan/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Guestplan
 * @subpackage Guestplan/public
 * @author     Guestplan <info@guestplan.com>
 */
class Guestplan_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

    }

    public function add_header_widget()
    {
        global $wp_query;
        
        $open_on_specific = get_option('guestplan_show_specific_pages');
        if ($open_on_specific)
        {
            $allowed_pages = get_option('guestplan_specific_pages_list');
            $page_id = get_queried_object_id();
            if (!$page_id || !in_array( $page_id, $allowed_pages))
            {
                return;
            }
        }
    
        $api_key = get_option('guestplan_api_key', null);
        if ($api_key == null) 
        { 
            return; 
        }

        $auto_open = get_option('guestplan_auto_open', 0);

        $hide_btn = get_option('guestplan_hide_btn', 0);

        $newsletter_hide_btn = get_option('guestplan_newsletter_hide');
        $newsletter_optin = get_option('guestplan_newsletter_default_optin');

        $widget_language = get_option('guestplan_widget_language', 'locale');


        echo '<!-- Guestplan Booking Widget -->';
        echo '<script>
		(function(g,s,t,p,l,n){
		g["_gstpln"]={};
		(l=s.createElement(t)),(n=s.getElementsByTagName(t)[0]);
		l.async=1;l.src=p;n.parentNode.insertBefore(l,n);
		})(window,document,"script","https://cdn.guestplan.com/widget.js");';
        echo '_gstpln.accessKey = "' . esc_js( $api_key ) . '";';
        echo '_gstpln.open = ' . ($auto_open == 1 ? 'true' : 'false') . ';';

        if ($widget_language == 'account') {
            // useAccountLanguage
        } else  if ($widget_language == 'locale') {
            $locale = explode('_', get_locale())[0];
            echo '_gstpln.locale = "' . esc_js( $locale ) . '";';
        } else if ($widget_language == 'html') {
            echo '_gstpln.useHtmlLanguage = true;';
        } else {
            echo '_gstpln.locale = "' . esc_js( $widget_language ) . '";';
        }
        
        if ($hide_btn == 1) {
            echo '_gstpln.showFab = false;';
        }

        if ($newsletter_hide_btn == 1) {
            echo '_gstpln.useNewsletterSubscription = false;';
        }

        if ($newsletter_optin == 1) {
            echo '_gstpln.useNewsletterSubscriptionOptIn = true;';
        }

        echo '</script>';
        echo '<!-- // Guestplan Booking Widget -->';
    }

}
