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
            require_once(sprintf("%s/src/brafton_options.php", dirname(__FILE__)));
            $brafton_options = new Brafton_options(); 
            require_once(sprintf("%s/wp_brafton_article_importer_settings.php", dirname(__FILE__)));
            $brafton_importer_settings = new WP_Brafton_Article_Importer_Settings( $brafton_options );
            
            // Register custom post types
            require_once(sprintf("%s/src/brafton_article_template.php", dirname(__FILE__)));
            if( $brafton_options->custom_post_type_enabled() )
                $Brafton_Article_Template = new Brafton_Article_Template( $brafton_options );
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

            if( get_option('brafton_purge') == 'options' )
                $this->brafton_options->purge_options(); 

            if( get_option('brafton_purge_articles') )
                $this->brafton_options->purge_articles(); 
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

    /* This is the scheduling hook for our plugin that is triggered by cron */
    #add_action('braftonxml_sched_hook', 'run_import', 10, 2);
    
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
    /**
     * Run the importer
     */
    // function run_import(){
    //     $brafton_cats = new Brafton_Taxonomy();
    //     $brafton_tags = new Brafton_Taxonomy();
    //     $brafton_image = new Brafton_Image_Handler();
    //     $brafton_article = new Brafton_Article_Helper();

    //     $brafton_article_importer = new Brafton_Article_Importer(
    //         $brafton_image, 
    //         $brafton_cats, 
    //         $brafton_tags, 
    //         $brafton_article
    //         );

    //     $brafton_article_importer->import_articles();
    //     update_option("braftonxml_sched_triggercount", get_option("braftonxml_sched_triggercount") + 1, 0);
    //     $wp_version = get_bloginfo('version');

    //     #run duplicate killer if version is not appropriate
    // }
  //Load the admin page Stylesheet. 
    function wp_brafton_article_importer_settings_style() {
        $siteurl = get_option('siteurl');
        $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/settings.css';
        echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
    }
    add_action('admin_head', 'wp_brafton_article_importer_settings_style');

}