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

class Guestplan_Admin_Settings
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

    }

    public function setup_plugin_options_menu()
    {

        add_options_page(
            __('Guestplan Widget', 'guestplan-plugin'),
            __('Guestplan Widget', 'guestplan-plugin'),
            'manage_options',
            $this->plugin_name,
            array($this, 'render_settings_page_content')
        );

    }

    public function add_action_links($links)
    {

        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', 'guestplan') . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    public function default_general_options()
    {

        $defaults = array(
            'api_key' => '',
            'guestplan_auto_open' => false,
            'guestplan_hide_btn' => false,
            'guestplan_show_specific_pages' => false,
        );
        return $defaults;

    }

    public function render_settings_page_content($active_tab = '')
    {

        ?>
    <div class="wrap">
        <h2><?php _e('Guestplan Booking Widget Options', 'guestplan');?></h2>
        <form method="post" action="options.php">
            <?php

        settings_fields('guestplan_general');
        do_settings_sections('guestplan_general');
        submit_button();

        ?>
        </form>
    </div><!-- /.wrap -->
<?php

    }

    public function general_options_callback()
    {

        $options = get_option('guestplan_api_key');
        echo '<p>' . __('Link your Guestplan Booking Widget with your Guestplan Account', 'guestplan') . '</p>';

    } // end general_options_callback

    public function initialize_general_options()
    {

        add_settings_section(
            'general_settings_section',
            __('General Options', 'guestplan-plugin'),
            array($this, 'general_options_callback'),
            'guestplan_general'
        );

        add_settings_field(
            'guestplan_api_key',
            __('API Key', 'guestplan-plugin'),
            array($this, 'api_key_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        add_settings_field(
            'guestplan_widget_language',
            __('Widget Language', 'guestplan-plugin'),
            array($this, 'widget_language_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        add_settings_field(
            'guestplan_auto_open',
            __('Open Automatically', 'guestplan-plugin'),
            array($this, 'widget_auto_open_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        add_settings_field(
            'guestplan_hide_btn',
            __('Hide Button', 'guestplan-plugin'),
            array($this, 'widget_hide_btn_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        //  NEWSLETTER
        
        add_settings_field(
            'guestplan_newsletter_hide',
            __('Hide Newsletter Subscription', 'guestplan-plugin'),
            array($this, 'widget_newsletter_hide_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        register_setting(
            'guestplan_general',
            'guestplan_newsletter_hide',
            array(
                'type' => 'boolean',
            )
        );

        add_settings_field(
            'guestplan_newsletter_default_optin',
            __('Newsletter Subscription Opt-In', 'guestplan-plugin'),
            array($this, 'widget_newsletter_default_optin_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        register_setting(
            'guestplan_general',
            'guestplan_newsletter_default_optin',
            array(
                'type' => 'boolean',
            )
        );


        // ###

        add_settings_field(
            'guestplan_show_specific_pages',
            __('Display Only On Specific Pages', 'guestplan-plugin'),
            array($this, 'widget_show_specific_pages_callback'),
            'guestplan_general',
            'general_settings_section'
        );

        register_setting(
            'guestplan_general',
            'guestplan_api_key',
            array(
                'type' => 'string',
                'sanitize_callback' => array($this, 'sanitize_api_key'),
            )
        );

        register_setting(
            'guestplan_general',
            'guestplan_widget_language',
            array(
                'type' => 'string',
            )
        );

        register_setting(
            'guestplan_general',
            'guestplan_auto_open',
            array(
                'type' => 'boolean',
            )
        );
        register_setting(
            'guestplan_general',
            'guestplan_hide_btn',
            array(
                'type' => 'boolean',
            )
        );

        register_setting(
            'guestplan_general',
            'guestplan_show_specific_pages',
            array(
                'type' => 'boolean',
            )
        );

        register_setting(
            'guestplan_general',
            'guestplan_specific_pages_list',
            array(
                'type' => 'array',
            )
        );

    } // end initialize_general_options
    
    
    public function widget_show_specific_pages_callback()
    {
        $specific_pages = get_option('guestplan_show_specific_pages');
        echo '<input type="checkbox" id="guestplan_show_specific_pages" name="guestplan_show_specific_pages" value="1"' . checked(1, $specific_pages, false) . '/>';
        echo '<p class="description">' . __('Display the widget ONLY on specified pages: (select below, use ctrl/cmd+click to select multiple)', 'guestplan') . '</p>';
		
		$option = get_option('guestplan_specific_pages_list', array());
		if (!is_array($option)) {
			$option = array();
		}
		$pages = get_pages();
		echo '<select multiple="multiple" name="guestplan_specific_pages_list[]" style="width: 100%; height: 500px">';
		foreach ($pages as $page) {
			$selected = in_array( $page->ID, $option ) ? ' selected="selected" ' : '';
			echo '<option value="' . esc_attr( $page->ID ) . '"' . $selected . '>';
			echo esc_html( $page->post_title );
			echo '</option>';
		}
		echo '</select>';
    }
    
    public function api_key_callback()
    {

        $api_key = get_option('guestplan_api_key');
        echo '<input class="regular-text" type="text" id="guestplan_api_key" name="guestplan_api_key" value="' . esc_attr( $api_key ) . '" />';
        echo '<p class="description">' . __('Your API Key can be found under \'Online Reservations\' option in the Guestplan app.', 'guestplan'); 

    } // end api_key_callback

   public function widget_language_callback()
    {
        $selected_language = get_option('guestplan_widget_language'); // Get the currently saved language setting

        echo '<select id="guestplan_widget_language" name="guestplan_widget_language">';

        // Default option
        echo '<option value="locale"' . selected( $selected_language, 'locale', false ) . '>' . __( 'Locale', 'guestplan' ) . '</option>';

        // Special options
        echo '<option value="account"' . selected( $selected_language, 'account', false ) . '>' . __( 'Account Language', 'guestplan' ) . '</option>';
        echo '<option value="html"' . selected( $selected_language, 'html', false ) . '>' . __( 'Detect from HTML', 'guestplan' ) . '</option>';


        // Language options
        $languages = array(
            'ca'    =>  'Catalan',
            'cs'    =>  'Czech',
            'da'    =>  'Danish',
            'de'    =>  'German',
            'en'    =>  'English',
            'en-gb' =>  'English (UK)',
            'es'    =>  'Spanish',
            'fi'    =>  'Finnish',
            'fr'    =>  'French',
            'hr'    =>  'Croatian',
            'it'    =>  'Italian',
            'ja'    =>  'Japanese',
            'nl'    =>  'Dutch',
            'no'    =>  'Norwegian',
            'pt'    =>  'Portuguese',
            'pl'    =>  'Polish',
            'sk'    =>  'Slovak',
            'sr'    =>  'Serbian',
            'sv'    =>  'Swedish',
            'tr'    =>  'Turkish',
            'zh'    =>  'Chinese',
        );

        foreach ( $languages as $value => $text ) {
            echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected_language, $value, false ) . '>' . esc_html( $text ) . '</option>';
        }

        echo '</select>';
        echo '<p class="description">' . __('Select the language for the Guestplan widget. "Locale" uses your WordPress site\'s get_locale() setting. "Account Language" uses the language configured in your Guestplan account. "Detect from HTML" attempts to detect the language from the surrounding HTML.', 'guestplan') . '</p>';

    }

    public function widget_auto_open_callback()
    {

        $auto_open = get_option('guestplan_auto_open');
        echo '<input type="checkbox" id="guestplan_auto_open" name="guestplan_auto_open" value="1"' . checked(1, $auto_open, false) . '/>';
        echo '<p class="description">' . __('Open the widget automatically on desktops', 'guestplan') . '</p>';

    } // end widget_auto_open_callback

    public function widget_hide_btn_callback()
    {

        $hide_btn = get_option('guestplan_hide_btn');
        echo '<input type="checkbox" id="guestplan_hide_btn" name="guestplan_hide_btn" value="1"' . checked(1, $hide_btn, false) . '/>';
        echo '<p class="description">' . __('Hide the default floating button. Useful if you provide your own button to open widget. Call "_gstpln.openWidget()" to open widget programatically.', 'guestplan') . '</p>';

    } // end widget_auto_open_callback

    public function widget_newsletter_hide_callback()
    {

        $newsletter_hide_btn = get_option('guestplan_newsletter_hide');
        echo '<input type="checkbox" id="guestplan_newsletter_hide" name="guestplan_newsletter_hide" value="1"' . checked(1, $newsletter_hide_btn, false) . '/>';
        echo '<p class="description">' . __('Do not ask guests for newsletter consent.', 'guestplan') . '</p>';

    } // end widget_newsletter_hide_callback

    public function widget_newsletter_default_optin_callback()
    {

        $newsletter_optin = get_option('guestplan_newsletter_default_optin');
        echo '<input type="checkbox" id="guestplan_newsletter_default_optin" name="guestplan_newsletter_default_optin" value="1"' . checked(1, $newsletter_optin, false) . '/>';
        echo '<p class="description">' . __('When selected, the Newsletter subscription checkbox in the widget will be on by default.', 'guestplan') . '</p>';

    } // end widget_newsletter_default_optin_callback

    public function sanitize_api_key($input)
    {

        $val = sanitize_text_field($input);
        if (strlen($val) == 0) {
            add_settings_error('guestplan', 'invalid-api-key', __('API Key is required.', 'guestplan'));
        }
        return $val;

    }

}