<?php
if(!class_exists('WP_Brafton_Article_Importer_Settings'))
{
    /*
     *Requires Wordpress Version 2.7
     *Handling HTTP Requests*
     */
    if( !class_exists( 'WP_Http' ) )
        include_once( ABSPATH . WPINC. '/class-http.php' );

	class WP_Brafton_Article_Importer_Settings
	{
        /**
         * @var Array 
         */
        private $blog_authors; 

		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
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
        	register_setting('WP_Brafton_Article_Importer-group', 'brafton_default_author');
        	register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_sched_API_KEY');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_domain');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_sched_tags');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_sched_status');
            register_setting('WP_Brafton_Article_Importer-group', 'brafton_tags_option');
            register_setting('WP_Brafton_Article_Importer-group', 'brafton_categories');
            register_setting('WP_Brafton_Article_Importer-group', 'brafton_categories_options');
            register_setting('WP_Brafton_Article_Importer-group', 'brafton_photo');
            register_setting('WP_Brafton_Article_Importer-group', 'brafton_importer_status');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_overwrite');
        	register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_publishdate');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_video');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_videoPublic');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_videoSecret');
            register_setting('WP_Brafton_Article_Importer-group', 'braftonxml_videoFeedNum');



            
            // add your settings section
        	add_settings_section(
        	    'WP_Brafton_Article_Importer-section', 
        	    'Importer Settings', 
        	    array(&$this, 'settings_section_WP_Brafton_Article_Importer'), 
        	    'WP_Brafton_Article_Importer'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_sched_API_KEY', 
                'API Key', 
                array(&$this, 'settings_field_input_text'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'field' => 'braftonxml_sched_API_KEY'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_domain', 
                'API Domain', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'name' => 'braftonxml_domain', 
                    'options' => array( 'api.brafton.com/' => ' Brafton', 
                                        'api.contentlead.com/'=> ' ContentLEAD', 
                                        'api.castleford.com.au/' => ' Castleford'
                        )
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-brafton_default_author', 
                'Post Author', 
                array(&$this, 'settings_field_dropdown'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'field' => 'brafton_default_author'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-brafton_categories', 
                'Categories', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'name' => 'brafton_categories', 
                    'options' => array('categories' => ' Brafton Categories',
                                       'no_categories' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_sched_tags', 
                'Tags', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'name' => 'braftonxml_sched_tags', 
                    'options' => array('tags' => ' Brafton Tags as Tags',
                                       'keywords' => ' Brafton Keywords as Tags',
                                       'categories' => ' Brafton Categories as Tags', 
                                       'none' => ' None')
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_sched_status', 
                'Default Post Status', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                 array(
                    'name' => 'braftonxml_sched_status', 
                    'options' => array('published' => ' Published',
                                       'draft' => ' Draft'),
                    'default' => 'published'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_publishdate', 
                'Set published date to: ', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
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
                'WP_Brafton_Article_Importer-braftonxml_overwrite', 
                'Overwrite Articles with Feed Updates', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'name' => 'braftonxml_overwrite', 
                    'options' => array('on' => ' On',
                                       'off' => ' Off'), 
                    'default' => 'off'
                )
            );
            add_settings_field(
                'WP_Brafton_Article_Importer-braftonxml_video', 
                'Videos', 
                array(&$this, 'render_radio'), 
                'WP_Brafton_Article_Importer', 
                'WP_Brafton_Article_Importer-section',
                array(
                    'name' => 'braftonxml_video',
                    'options' => array( 'off' => ' Off',
                                        'on' => ' On'), 
                    'default' => 'off'

                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_WP_Brafton_Article_Importer()
        {
            // Think of this as help text for the section.
            echo 'Please Enter Your Brafton Settings.';
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

        public function settings_field_dropdown( $element )
        {
            $field = $element['field'];
            $value = get_option( $element['field'] ); 
            $options = $this->get_blog_authors(); 

            $output = '<select name= "' . esc_attr( $field ) . '" >'; 
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

        private function get_blog_authors()
        {
            $users = array(); 
            $args = array(  'blog_id' => $GLOBALS['blog_id'], 
                            'orderby' => 'display_name',
                            'who' => 'authors',
                );

            $blogusers = get_users( $args );
            $user_attributes = array();
            foreach ($blogusers as $user) {
                $user_attributes['id'] = $user->ID;
                $user_attributes['name'] = $user->display_name;
                $users[] = $user_attributes; 
            }
            return $users; 
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
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP Brafton Article Importer Settings', 
        	    'WP Brafton Article Importer', 
        	    'manage_options', 
        	    'WP_Brafton_Article_Importer', 
        	    array(&$this, 'plugin_settings_page')
        	);
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
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Brafton_Article_Importer_Settings
} // END if(!class_exists('WP_Brafton_Article_Importer_Settings'))
