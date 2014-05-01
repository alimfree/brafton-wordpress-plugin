<?php

if(!class_exists('WP_Brafton_Article_Importer_Settings'))
{
    /*
     *Requires Wordpress Version 2.7
     *Handling HTTP Requests*
     */
    if( !class_exists( 'WP_Http' ) )
        include_once( ABSPATH . WPINC. '/class-http.php' );
    /**
     * Contains logic used to render Brafton Options Menu
     * @uses Brafton_Options to dynamically display options stored in database
     */
	class WP_Brafton_Article_Importer_Settings
	{
		/**
         * Construct the Plugin object
         * @param $brafton_options 
		 */
		public function __construct( Brafton_Options $brafton_options )
		{
            if( isset( $brafton_options ) )
                $this->brafton_options = $brafton_options; 

			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('WP_Brafton_Article_Importer_group', 'brafton_default_author');
        	register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_API_KEY');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_domain');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_tags');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_status');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_tags_option');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_categories');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_categories_options');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_photo');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_importer_status');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_overwrite');
        	register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_publishdate');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_video');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoPublic');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoSecret');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoFeedNum');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_custom_post_type');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_purge');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_parent_categories');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_custom_taxonomy');
            register_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_triggercount');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_import_articles');
            register_setting('WP_Brafton_Article_Importer_group', 'brafton_errors');

            // add your settings section
        	add_settings_section(
        	    'brafton_article_section', 
        	    'Article Settings', 
        	    array(&$this, 'settings_section_brafton_article'), 
        	    'WP_Brafton_Article_Importer'
        	);
            add_settings_section(
                'brafton_video_section', 
                'Video Settings', 
                array(&$this, 'settings_section_brafton_video'), 
                'WP_Brafton_Article_Importer'
            );
            add_settings_section(
                'brafton_advanced_section', 
                'Advanced Settings', 
                array(&$this, 'settings_section_brafton_advanced'), 
                'WP_Brafton_Article_Importer'
            );
            
            add_settings_section(
                'brafton_developer_section', 
                'Developer Settings', 
                array(&$this, 'settings_section_brafton_developer'), 
                'WP_Brafton_Article_Importer'
            );

            // Possibly do additional admin_init tasks
        } // END public static function activate

        public function settings_section_brafton_article()
        {
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_article', 
                'Articles', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'name' => 'brafton_import_articles',
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'on'

                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_API_KEY', 
                'API Key', 
                array(&$this->brafton_options, 'settings_field_input_text'),  
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'name' => 'api-key',
                    'field' => 'braftonxml_sched_API_KEY'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_domain', 
                'Product', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'name' => 'braftonxml_domain', 
                    'options' => array( 'api.brafton.com/' => ' Brafton', 
                                        'api.contentlead.com/'=> ' ContentLEAD', 
                                        'api.castleford.com.au/' => ' Castleford'
                        ),
                    'default' => 'api.brafton.com/'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_default_author', 
                'Post Author', 
                array(&$this->brafton_options, 'settings_author_dropdown'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'name' => 'brafton_default_author'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_status', 
                'Default Post Status', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                 array(
                    'name' => 'braftonxml_sched_status', 
                    'options' => array('published' => ' Published',
                                       'draft' => ' Draft'),
                    'default' => 'published'
                )
            );
        }

        public function settings_section_brafton_video()
        {
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_video', 
                'Videos', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_video_section',
                array(
                    'name' => 'braftonxml_video',
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'off'
                )
            );
        }

        public function settings_section_brafton_developer()
        {
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_parent_categories', 
                'Hierarchical Categories ', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section',
                array(
                    'name' => 'brafton_parent_categories', 
                    'options' => array( 'on' => ' On', 
                                        'off'=> ' Off' 
                        ),
                    'default' => 'off'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_custom_taxonomy', 
                'Custom Taxonomy ', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section',
                array(
                    'name' => 'brafton_custom_taxonomy', 
                    'options' => array( 'on' => ' On', 
                                        'off'=> ' Off' 
                        ),
                    'default' => 'off'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_overwrite', 
                'Overwrite', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section',
                array(
                    'name' => 'braftonxml_overwrite', 
                    'options' => array('on' => ' On',
                                       'off' => ' Off'), 
                    'default' => 'off'
                )
            ); 
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_purge', 
                'Deactivation', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section',
                array(
                    'name' => 'brafton_purge',
                    'options' => array( 'none' => ' Stop Importing Content', 
                                        'posts' => ' Delete All ' . $this->brafton_options->brafton_get_product() . ' Articles', 
                                        'all' => ' Purge this plugin entirely!'
                                        ), 
                    'default' => 'none'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_errors', 
                'Errors', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section',
                array(
                    'name' => 'brafton_errors', 
                    'options' => array('on' => ' On',
                                       'off' => ' Off'), 
                    'default' => 'off'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_archives',
                'Archives',
                array(&$this->brafton_options, 'settings_xml_upload'),
                'WP_Brafton_Article_Importer',
                'brafton_developer_section',
                array('label' => 'Upload a specific xml Archive file', 
                    'name' => 'achives' 
                    )
            ); 
        }

        public function settings_section_brafton_advanced()
        {
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_custom_post_type', 
                'Custom Post Type', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section', 
                array(
                    'name' => 'brafton_custom_post_type', 
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'on'
                    )
                );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_categories', 
                'Categories', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                array(
                    'name' => 'brafton_categories', 
                    'options' => array('categories' => ' Brafton Categories',
                                       'no_categories' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_tags', 
                'Tags', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                array(
                    'name' => 'braftonxml_sched_tags', 
                    'options' => array('tags' => ' Brafton Tags as Tags',
                                       'keywords' => ' Brafton Keywords as Tags',
                                       'categories' => ' Brafton Categories as Tags', 
                                       'none' => ' None')
                )
            );
           
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_publishdate', 
                'Post Date: ', 
                array(&$this->brafton_options, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                 array(
                    'name' => 'braftonxml_publishdate', 
                    'options' => array('published' => ' Published Date',
                                       'modified' => ' Last Modified Date',
                                       'created' => ' Created Date'
                                       ),
                    'default' => 'published'
                )
            );
        }

        /* jQuery Tabs */
        public function scripts() {
            wp_print_scripts( 'jquery-ui-tabs' );
        }
       
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	$admin_page = add_menu_page(
        	    'WP Brafton Article Importer Settings', 
        	     $this->brafton_options->brafton_get_product() . ' Settings', 
        	    'manage_options', 
        	    'WP_Brafton_Article_Importer', 
        	    array(&$this, 'plugin_settings_page')
        	);

            add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );

        } // END public function add_menu()

        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s../src/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Brafton_Article_Importer_Settings
} // END if(!class_exists('WP_Brafton_Article_Importer_Settings'))
