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
            // echo '<div class="tabs">';
            // echo '<div class ="tab">';
        
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_API_KEY', 
                'API Key', 
                array(&$this, 'settings_field_input_text'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'field' => 'braftonxml_sched_API_KEY'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_domain', 
                'Product', 
                array(&$this, 'render_radio'), 
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
                array(&$this, 'settings_author_dropdown'), 
                'WP_Brafton_Article_Importer', 
                'brafton_article_section',
                array(
                    'name' => 'brafton_default_author'
                )
            );


             

            // echo '</div> <!--.tab end -->';
        }

        public function settings_section_brafton_video()
        {
            // echo '<div class ="tab">';
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_video', 
                'Videos', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_video_section',
                array(
                    'name' => 'braftonxml_video',
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'off'

                )
            );

            
            // echo '</div> <!--.tab end -->';
        }

        public function settings_section_brafton_developer()
        {
            // echo '<div class ="tab">';
            
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_custom_post_type', 
                $this->brafton_options->get_product() . ' Custom Post Type', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_developer_section', 
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
                'brafton_developer_section',
                array(
                    'name' => 'brafton_purge',
                    'options' => array( 'none' => ' Stop Importing Content', 
                                        'posts' => ' Delete All ' . $this->brafton_options->get_product() . ' Articles', 
                                        'all' => ' Purge this plugin entirely!'
                                        ), 
                    'default' => 'none'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_custom_taxonomy', 
                'Custom Taxonomy ', 
                array(&$this, 'render_radio'), 
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

            // echo '</div><!-- .tab -->';
            // echo '</div><!-- .tabs-->';

        }

        public function settings_section_brafton_advanced()
        {
            // echo '<div class ="tab">';

            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_categories', 
                'Categories', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                array(
                    'name' => 'brafton_categories', 
                    'options' => array('categories' => ' Brafton Categories',
                                       'no_categories' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_brafton_parent_categories', 
                'Hierarchical Categories ', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                array(
                    'name' => 'brafton_parent_categories', 
                    'options' => array( 'on' => ' On', 
                                        'off'=> ' Off' 
                        ),
                    'default' => 'off'
                )
            ); 
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_sched_tags', 
                'Tags', 
                array(&$this, 'render_radio'), 
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
                'WP_Brafton_Article_Importer_braftonxml_sched_status', 
                'Default Post Status', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                 array(
                    'name' => 'braftonxml_sched_status', 
                    'options' => array('published' => ' Published',
                                       'draft' => ' Draft'),
                    'default' => 'published'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_publishdate', 
                'Post Date: ', 
                array(&$this, 'render_radio'), 
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
            add_settings_field(
                'WP_Brafton_Article_Importer_braftonxml_overwrite', 
                'Overwrite', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'brafton_advanced_section',
                array(
                    'name' => 'braftonxml_overwrite', 
                    'options' => array('on' => ' On',
                                       'off' => ' Off'), 
                    'default' => 'off'
                )
            );

            // echo '</div><!-- .tab -->'; 
        }

        /**
         * Renders an upload field
         */
        public function settings_xml_upload()
        {

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
                    $output .= '<div class="radio-option ' . str_replace( '_', '-', $element['name'] ) . '>"><label><input type="radio" name="'. esc_attr($element['name']) .'" value="'. esc_attr($key) . '"';

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
        	$admin_page = add_options_page(
        	    'WP Brafton Article Importer Settings', 
        	     $this->brafton_options->get_product() . ' Importer', 
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
