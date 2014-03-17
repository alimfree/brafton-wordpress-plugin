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
        	register_setting('brafton_admin_group', 'brafton_default_author');
        	register_setting('brafton_admin_group', 'braftonxml_sched_API_KEY');
            register_setting('brafton_admin_group', 'braftonxml_domain');
            register_setting('brafton_admin_group', 'braftonxml_sched_tags');
            register_setting('brafton_admin_group', 'braftonxml_sched_status');
            register_setting('brafton_admin_group', 'brafton_tags_option');
            register_setting('brafton_admin_group', 'brafton_categories');
            register_setting('brafton_admin_group', 'brafton_categories_options');
            register_setting('brafton_admin_group', 'brafton_photo');
            register_setting('brafton_admin_group', 'brafton_importer_status');
            register_setting('brafton_admin_group', 'braftonxml_publishdate');
            register_setting('brafton_developer_group', 'braftonxml_overwrite');
            register_setting('brafton_video_group', 'braftonxml_video');
            register_setting('brafton_video_group', 'braftonxml_videoPublic');
            register_setting('brafton_video_group', 'braftonxml_videoSecret');
            register_setting('brafton_video_group', 'braftonxml_videoFeedNum');
            register_setting('brafton_developer_group', 'brafton_custom_post_type');
            register_setting('brafton_developer_group', 'brafton_purge');


            
            // add your settings section
        	add_settings_section(
        	    'WP_Brafton_Article_Importer_section', 
        	    'Importer Settings', 
        	    array(&$this, 'settings_page_description'), 
        	    'brafton_admin'
        	);
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_domain', 
                'Product', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
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
                'WP_Brafton_Article_Importer_braftonxml_sched_API_KEY', 
                'API Key', 
                array(&$this, 'settings_field_input_text'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'field' => 'braftonxml_sched_API_KEY'
                )
            );
           
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_default_author', 
                'Post Author', 
                array(&$this, 'settings_author_dropdown'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'brafton_default_author'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_categories', 
                'Categories', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'brafton_categories', 
                    'options' => array('categories' => ' Brafton Categories',
                                       'no_categories' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_tags', 
                'Tags', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'braftonxml_sched_tags', 
                    'options' => array('tags' => ' Brafton Tags as Tags',
                                       'keywords' => ' Brafton Keywords as Tags',
                                       'categories' => ' Brafton Categories as Tags', 
                                       'none' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_status', 
                'Default Post Status', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                 array(
                    'name' => 'braftonxml_sched_status', 
                    'options' => array('published' => ' Published',
                                       'draft' => ' Draft'),
                    'default' => 'published'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_publishdate', 
                'Set published date to: ', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                 array(
                    'name' => 'braftonxml_publishdate', 
                    'options' => array('published' => ' Published Date',
                                       'modified' => ' Last Modified Date',
                                       'created' => ' Created Date'
                                       ),
                    'default' => 'published'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_video', 
                'Videos', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'braftonxml_video',
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'off'

                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_overwrite', 
                'Overwrite Articles with Feed Updates', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'braftonxml_overwrite', 
                    'options' => array('on' => ' On',
                                       'off' => ' Off'), 
                    'default' => 'off'
                )
            ); 
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_custom_post_type', 
                $this->brafton_options->get_product() . ' Custom Post Type', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section', 
                array(
                    'name' => 'brafton_custom_post_type', 
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'on'
                    )
            );
             add_settings_field(
                'WP_Brafton_Article_Importer_brafton_purge', 
                'When Deactivating Plugin', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer_section',
                array(
                    'name' => 'brafton_purge',
                    'options' => array( 'none' => ' Stop Importing Content', 
                                        'posts' => ' Delete All ' . $this->brafton_options->get_product() . ' Articles', 
                                        'all' => ' Purge this plugin entirely!'
                                        ), 
                    'default' => 'none'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_page_description()
        {
            // Think of this as help text for the section.
            if( $this->brafton_options->has_api_key() )
                echo 'Thank you for Partnering with ' . $this->brafton_options->link_to_product() .' ';
            else
                echo 'Please Enter Your Importer Settings. ';


        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)

        public function settings_author_dropdown( $element )
        {
            $field = $element['name'];
            $value = get_option( $element['name'] ); 
            
            $output = '<select name= "' . esc_attr( $field ) . '" >'; 
  
                $options = $this->author_options(); 
            
               
                foreach ( $options as $o )
                {
                   
                    $output .= '<option value="' .  esc_attr( $o['id'] ) . '"'; 
                    if( $value == $o['id'] )
                        $output .=  ' selected >'; 
                    else
                        $output .= '>';

                    $output .=  esc_attr( $o['name'] ) . '</option>';
                    
                }
                $output .=  '</select>';

            echo sprintf( $output );
        }

        /**
         * @uses Brafton_Options to retrieve users with authorship privileges 
         */
        private function author_options(){


               $blog_authors = $this->brafton_options->get_blog_authors(); 

               return $blog_authors; 
        }

        public function render_radio($element)
        {
            $output = '';
            $value = get_option( $element['name'] );
            //echo $value;

            if ( $value == '' && isset( $element['default'] ) ){
                $value = $element['default'];
                update_option( $element['name'], $element['default'] );
            }
            
                foreach ($element['options'] as $key => $option)
                {
                    $output .= '<div class="radio-option"><label><input type="radio" name="'. esc_attr($element['name']) .'" value="'. esc_attr($key) . '"';

                    if ( $value == $option ){
                      $output .=   checked($key, $value, true) . ' checked' . ' /><span>' . esc_html($option) . '</span></label></div>';
                    }
                    $output .=   checked($key, $value, false) . ' /><span>' . esc_html($option) . '</span></label></div>';
                }   
            
                    
            echo sprintf( $output );
        }

        public function render_select($element)
        {
            $element = array_merge(array('value' => null), $element);
            
            $output = '<select name="'. esc_attr($element['name']) .'"' . (isset($element['class']) ? ' class="'. esc_attr($element['class']) .'"' : '') . '>';
            
            foreach ( (array) $element['options'] as $key => $option) 
            {
                if (is_array($option)) {
                    $output .= '<optgroup label="' . esc_attr($key) . '">' . $this->_render_options($option) . '</optgroup>';
                }
                else {
                    $output .= $this->_render_options(array($key => $option), $element['value']);
                }
                
            }
            
            return $output . '</select>';
        }

        // helper for: render_select()
        private function _render_options($options, $selected = '') 
        {   
            $output = '';
            
            foreach ($options as $key => $option) {
                $output .= '<option value="'. esc_attr($key) .'"'. selected((string) $selected, $key, false) .'>' . esc_html($option) . '</option>';
            }
            
            return $output;
        }
        
        /**
         * @reference http://codex.wordpress.org/Function_Reference/add_submenu_page
         * @reference http://codex.wordpress.org/Function_Reference/add_options_page
         * Add our menus
         */		
        public function add_menu()
        {
            //Create Parent Menue 
            add_menu_page(
                'Brafton', 
                $this->brafton_options->get_product(),  
                'manage_options', 
                'brafton_parent_menu' 
                array(&$this, 'display_brafton_dashboard')
                ); 
            // Add statistics to manage this plugin's settings
        	add_options_page(
        	    'Brafton Article Importer Settings', 
        	   ' Dashboard', 
        	    'manage_options', 
        	    'brafton_parent_menu', 
        	    array(&$this, 'display_brafton_dashboard')
        	);
            //add sub menu's to plugin's settings page
            add_submenu_page(
                'brafton_parent_menu', 
                'Settings', 
                'Content settings', 
                'manage_options',  
                'brafton_admin_menu',  
                array(&$this, 'display_admin_menu')
            );
            add_submenu_page(
                'brafton_parent_menu', 
                'Settings', 
                'Video Settings', 
                'manage_options',  
                'brafton_video_menu',  
                array(&$this, 'display_video_menu')
            );
            add_submenu_page(
                'brafton_parent_menu', 
                'Settings', 
                'Advanced Settings', 
                'manage_options',  
                'brafton_dev_menu',  
                array(&$this, 'display_dev_menu')
            );    
            add_submenu_page(
                'brafton_parent_menu', 
                'Settings', 
                'Import Archives', 
                'manage_options',  
                'brafton_archive_menu',  
                array(&$this, 'display_archive_menu')
            );    
            // //add dashbard page to display content stats
            // add_dashboard_page(
            //     'Brafton Dashboard', 
            //     'Dashboard', 
            //     'manage_options', 
            //     'brafton_importer_dashboard', 
            //     array( &$this, 'display_brafton_dashboard')
            // );
        } // END public function add_menu()
        

        public function render_parent_menu()
        {
            echo $this->brafton_options->get_product(); 
        }

        public function display_brafton_dashboard()
        {
              if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the  dev menu template
            include(sprintf("%s../src/templates/dashboard.php", dirname(__FILE__)));
        }
       
        public function display_dev_menu()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the  dev menu template
            include(sprintf("%s../src/templates/dev_menu.php", dirname(__FILE__)));
        }

        public function display_archive_menu()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the  dev menu template
            include(sprintf("%s../src/templates/archive_menu.php", dirname(__FILE__)));
        }

        public function display_video_menu()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the  dev menu template
            include(sprintf("%s../src/templates/video_menu.php", dirname(__FILE__)));
        }

        public function display_admin_menu()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the  dev menu template
            include(sprintf("%s../src/templates/admin_menu.php", dirname(__FILE__)));
        }

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
