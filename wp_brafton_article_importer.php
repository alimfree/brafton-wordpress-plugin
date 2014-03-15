<?php
/*
Plugin Name: WP Brafton Article Importer
Plugin URI: http://www.brafton.com/support/wordpress
version: 1.0
Author: Ali
Author URL: http://www.brafton.com
*/

if(!class_exists('WP_Brafton_Article_Importer'))
{
    class WP_Brafton_Article_Importer
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Initialize Settings
            require_once(sprintf("%s/wp_brafton_article_importer_settings.php", dirname(__FILE__)));
            $WP_Brafton_Article_Importer_Settings = new WP_Brafton_Article_Importer_Settings();
            
            // Register custom post types
            require_once(sprintf("%s/src/brafton_article_template.php", dirname(__FILE__)));
            $Brafton_Article_Template = new Brafton_Article_Template();
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {

            //add actions and filters here: 
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {

            //remove scheduling hooks and any actions 
            // Do nothing
        } // END public static function deactivate
    } // END class WP_Brafton_Article_Importer
} // END if(!class_exists('WP_Brafton_Article_Importer'))

if(class_exists('WP_Brafton_Article_Importer'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Brafton_Article_Importer', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Brafton_Article_Importer', 'deactivate'));

    // instantiate the plugin class
    $WP_Brafton_Article_Importer = new WP_Brafton_Article_Importer();

    // Add a link to the settings page onto the plugin page
    if(isset($WP_Brafton_Article_Importer))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        { 
            $settings_link = '<a href="options-general.php?page=WP_Brafton_Article_Importer">Settings</a>'; 
            array_unshift($links, $settings_link); 
            return $links; 
        }

        $plugin = plugin_basename(__FILE__); 
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }
}