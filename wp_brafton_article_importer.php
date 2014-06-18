<?php
/*
Plugin Name: WP Brafton Article Importer
Plugin URI: http://www.brafton.com/support/wordpress
version: 1.0
Author: Ali
Author URL: http://www.brafton.com
*/

if( !class_exists( 'WP_Brafton_Article_Importer' ) )
{
    if ( !defined( 'BRAFTON_PLUGIN_VERSION_KEY' ) )
                define( 'BRAFTON_PLUGIN_VERSION_KEY', 'brafton_importer_version' );

    if ( !defined( 'MYPLUGIN_VERSION_NUM' ) )
                define( 'BRAFTON_PLUGIN_VERSION_NUM', '1.0.0' );


    include_once 'src/brafton_article_helper.php';
    include_once 'src/brafton_taxonomy.php';
    include_once 'src/brafton_image_handler.php';
    include_once 'src/brafton_article_importer.php';
    include_once 'src/brafton_errors.php';
    include_once 'src/brafton_video_helper.php';
    include_once 'src/brafton_video_importer.php';

    class WP_Brafton_Article_Importer
    {   
        public $brafton_options; 
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Initialize Settings

            require_once( sprintf( "%s/src/brafton_errors.php", dirname( __FILE__ ) ) );
            require_once( sprintf( "%s/src/brafton_options.php", dirname( __FILE__ ) ) );
            $brafton_options = Brafton_options::get_instance();
            require_once( sprintf( "%s/wp_brafton_article_importer_settings.php", dirname( __FILE__ ) ) );
            $brafton_importer_settings = new WP_Brafton_Article_Importer_Settings( $brafton_options );
            
            // Register custom post types
            require_once( sprintf( "%s/src/brafton_article_template.php", dirname( __FILE__ ) ) );
            if( $brafton_options->options['brafton_custom_post_type'] === "on" )
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
            $brafton_options = Brafton_options::get_instance();
            //remove scheduled hook.
            braftonxml_clear_all_crons( 'brafton_import_trigger_hook' );

            if( $brafton_options->options['brafton_purge'] == 'posts' )
            {
                brafton_log( array( 'message' => "attempting to delete articles" ) );
                $brafton_options->purge_articles(); 
            }

            
            if( $brafton_options->options['brafton_purge'] == 'all' )
            {
                $brafton_options->purge_articles(); 
                delete_option( 'brafton_options' );
                delete_option( 'brafton_error_log' );
            }


            // Do nothing
        } // END public static function deactivate
    } // END class WP_Brafton_Article_Importer
} // END if(!class_exists('WP_Brafton_Article_Importer'))

if( class_exists( 'WP_Brafton_Article_Importer' ) )
{
    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'WP_Brafton_Article_Importer', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WP_Brafton_Article_Importer', 'deactivate' ) );

    // instantiate the plugin class
    $WP_Brafton_Article_Importer = new WP_Brafton_Article_Importer();

    /* This is the scheduling hook for our plugin that is triggered by cron */
    #add_action('brafton_import_trigger_hook', 'run_import', 10, 2);
    
    // Add a link to the settings page onto the plugin page
    if( isset( $WP_Brafton_Article_Importer ) )
    {
        // Add the settings link to the plugins page
        function plugin_settings_link( $links )
        { 
            $settings_link = '<a href="options-general.php?page=WP_Brafton_Article_Importer">Settings</a>'; 
            array_unshift( $links, $settings_link ); 
            return $links; 
        }

        $plugin = plugin_basename( __FILE__ ); 
        add_filter( "plugin_action_links_$plugin", 'plugin_settings_link' );
        
        //Manually run importer when settings are saved.
        add_action( 'load-toplevel_page_WP_Brafton_Article_Importer', 'run_article_import' );

        add_action( 'load-toplevel_page_WP_Brafton_Article_Importer', 'run_video_import' );
        //Run video and article importers when archives form is saved
        add_action( 'load-brafton_page_brafton_archives', 'run_article_import' );
        //add_action( 'load-brafton_page_brafton_archives', 'run_video_import' );

        function braftonxml_clear_all_crons($hook)
        {
            $crons = _get_cron_array();
            if ( empty( $crons ) )
                return;

            foreach ( $crons as $timestamp => $cron )
                if ( !empty( $cron[$hook] ) )
                    unset($crons[$timestamp][$hook]);
            _set_cron_array( $crons );
        }

        /**
         * Run the article importer
         */
        function run_article_import(){
            //Wait until settings are saved before attempting to import articles
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true || isset( $_POST['option_page'] ) && $_POST['option_page'] == 'brafton_archives' )
            {
                //Grab saved options.
                $brafton_options = Brafton_options::get_instance();
                //If article importing is disabled - do nothing
                if( $brafton_options->options['brafton_import_articles'] === 'off' ) {
                        brafton_log( array( 'message' => "Article importing is disabled." ) );
                    return;
                    } 
                //If api key isn't set - do nothing
                if( !$brafton_options->options['brafton_api_key'] ) {
                    brafton_log( array( 'message' => " Brafton Api key is not set." ) );
                    return;
                }
                //if brafton error reporting is enabled - log importing.
                brafton_log( array( 'message' => 'Starting to import articles.' ) );
                //We need curl to upload via archives.
                if ( !function_exists( 'curl_init' ) && $_POST['option_page'] == 'brafton_archives' )
                    echo "<li>WARNING: <b>cURL</b> is disabled or not installed on your server. cURL is required to upload article archive.</li>";
                
                //We need DOMDocument to parse XML feed.
                if ( !class_exists( 'DOMDocument' ) )
                    echo "<li>WARNING: DOM XML is disabled or not installed on your server.  It is required for this plugin's operation.</li>";

                                
                $brafton_cats = new Brafton_Taxonomy( $brafton_options );
                $brafton_tags = new Brafton_Taxonomy( $brafton_options );
                $brafton_image = new Brafton_Image_Handler( $brafton_options );
                $brafton_article = new Brafton_Article_Helper( $brafton_options );
                $brafton_article_importer = new Brafton_Article_Importer(
                    $brafton_image, 
                    $brafton_cats, 
                    $brafton_tags, 
                    $brafton_article, 
                    $brafton_options
                    );
                $brafton_article_importer->import_articles();
                $brafton_options->update_option( "brafton_options", "brafton_import_trigger_count", $brafton_options->get_option( "brafton_options", "braftonxml_sched_triggercount") + 1, 0);
            }
        }
        

         /**
         * Run importer for video articles
         */
        function run_video_import()
        {
            //Wait until settings are saved before attempting to import articles
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) 
            {
                $brafton_options = Brafton_options::get_instance();

                if( $brafton_options->options['brafton_enable_video'] === 'off' ) {
                    brafton_log( array( 'message' => 'Video importing is disabled') );
                    return;
                }
                    brafton_log( array( 'message' => 'Starting to import videos.' ) );
                    $brafton_cats = new Brafton_Taxonomy( $brafton_options );
                    $brafton_tags = new Brafton_Taxonomy( $brafton_options );
                    $brafton_image = new Brafton_Image_Handler( $brafton_options );
                    $brafton_video = new Brafton_Video_Helper( $brafton_options );
                    $brafton_video_importer = new Brafton_Video_Importer(
                        $brafton_image, 
                        $brafton_cats, 
                        $brafton_video, 
                        $brafton_options 
                        );
                    $brafton_video_importer->import_videos();
                    $brafton_options->update_option( "brafton_options", "brafton_import_trigger_count", $brafton_options->get_option( "brafton_options", "braftonxml_sched_triggercount") + 1, 0);
                    
                    //Schedule event.
                    braftonxml_clear_all_crons( 'brafton_import_trigger_hook' );

                    wp_schedule_event(time() + 3600, "hourly", "brafton_import_trigger_hook" );
                    //braftonxml_sched_trigger_schedule( );
            }
        }
        
        #run duplicate killer if version is not appropriate
    }

    function brafton_update_plugin(){
        #todo;
    }

    //Add video player scripts and css to site <head>
    function brafton_enqueue_video_scripts() {

        $brafton_options = Brafton_options::get_instance(); 
        //support atlantisjs embed codes
        $player = $brafton_options->options['brafton_video_player'];
        switch( $player ) {
            case $player = "atlantis":
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'atlantisjs', 'http://p.ninjacdn.co.uk/atlantisjs/v0.11.7/atlantis.js', array( 'jquery' ) );
                wp_enqueu_script( 'videojs', '//vjs.zencdn.net/4.3/video.js', array( 'jquery' ) );
                if( $brafton_options->options['brafton_player_css'] == 'on' )
                    wp_enqueue_style( 'atlantis', 'http://p.ninjacdn.co.uk/atlantisjs/v0.11.7/atlantisjs.css' );
                    wp_enqueue_style( 'videocss', '//vjs.zencdn.net/4.3/video-js.css' );
                break;
        }

    }
    add_action( 'wp_enqueue_scripts', 'brafton_enqueue_video_scripts' );
    /**
     * Unschedule automated hourly imports. 
     */
    add_action("init", "clear_crons_left");
    function clear_crons_left()
    {
        wp_clear_scheduled_hook( "brafton_import_trigger_hook" );
    }

    /**  
     * This is the scheduling hook for our plugin that is triggered by cron
     */
    add_action('brafton_import_trigger_hook', 'braftonxml_sched_trigger_schedule', 10, 2);
    function braftonxml_sched_trigger_schedule()
    {
        run_article_import();
        run_video_import();
        //update_option("braftonxml_sched_triggercount", get_option("braftonxml_sched_triggercount") + 1);

        // HACK: posts are duplicated due to a lack of cron lock resolution (see http://core.trac.wordpress.org/ticket/19700)
        // this is fixed in wp versions >= 3.4.
        $wpVersion = get_bloginfo( 'version' );

        brafton_log( array( 'message' => "Import successfully triggered by wp cron." ) );
        if ( version_compare( $wpVersion, '3.4', '<') )
            duplicateKiller();
    }

  //Load the admin page Stylesheet. 
    function wp_brafton_article_importer_settings_style() {
        $siteurl = get_option( 'siteurl' );
        $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/settings.css';
        echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
    }
    add_action( 'admin_head', 'wp_brafton_article_importer_settings_style' );

}