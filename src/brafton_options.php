<?php 
	require_once( sprintf( "%s/brafton_errors.php", dirname( __FILE__ ) ) );

	// Settings page options.
	define( 'BRAFTON_ERROR_LOG', 'brafton_error_log', true );
	define( 'BRAFTON_ENABLE_ERRORS', 'brafton_errors', true );
	define( 'BRAFTON_ENABLE_ARTICLES', 'brafton_import_articles', true ); 
	define( 'BRAFTON_ENABLE_VIDEO', 'braftonxml_video', true );  
	define( 'BRAFTON_ENABLE_IMAGES', 'brafton_photo', true ); 
	define( 'BRAFTON_AUTHOR', 'brafton_default_author', true ); 
	define( 'BRAFTON_FEED', 'braftonxml_sched_API_KEY', true ); 
	define( 'BRAFTON_DOMAIN', 'braftonxml_domain', true ); 
	define( 'BRAFTON_CUSTOM_POST_TAG', 'brafton_custom_post_tag', true ); 
	define( 'BRAFTON_SCHEDULED_STATUS', 'braftonxml_sched_status', true ); 
	define( 'BRAFTON_TAGS', 'brafton_tags_option', true ); 
	define( 'BRAFTON_CATEGORIES', 'brafton_categories', true ); 
	define( 'BRAFTON_OVERWRITE', 'braftonxml_overwrite', true ); 
	define( 'BRAFTON_POST_DATE', 'braftonxml_publishdate', true ); 
	define( 'BRAFTON_VIDEO', 'braftonxml_video', true ); 
	define( 'BRAFTON_VIDEO_SECRET', 'braftonxml_videosecret', true ); 
    define( 'BRAFTON_VIDEO_PUBLIC', 'braftonxml_videopublic', true );
	define( 'BRAFTON_VIDEO_FEED_NUM', 'braftonxml_video_feed_num', true ); 
    define( 'BRAFTON_VIDEO_PLAYER', 'brafton_video_player', true ); 
    define( 'BRAFTON_ENABLE_SCRIPT', 'brafton_enable_script', true );
    define( 'BRAFTON_PLAYER_CSS', 'brafton_player_css', true );
	define( 'BRAFTON_CUSTOM_POST', 'brafton_custom_post_type', true ); 
	define( 'BRAFTON_DISABLE', 'brafton_purge', true ); 
	define( 'BRAFTON_CUSTOM_CATEGORY', 'brafton_custom_category', true ); 
	define( 'BRAFTON_CUSTOM_TAXONOMY', 'brafton_custom_taxonomy', true ); 
	define( 'BRAFTON_IMPORT_COUNT', 'braftonxml_sched_triggercount', true );

    // Content logs
    define( 'BRAFTON_ARTICLE_LOG', 'brafton_article_log', true ); 
    define( 'BRAFTON_TAXONOMY_LOG', 'brafton_taxonomy_log', true ); 
    define( 'BRAFTON_VIDEO_LOG', 'brafton_video_log', true );
    define( 'BRAFTON_IMAGES_LOG', 'brafton_images_log', true ); 
	/**
	 * Singleton Class for retrieving options from the wordpress database.
	 */
	class Brafton_Options
	{	
		//Store brafton options
		public $brafton_options;
        //Array of plugin errors log
        public $errors; 
        public $archives; 
        //Brafton_Options Object 
        private static $instance = null;

        //Let's hinder direct instantiation by cloning.  
		private final function __construct( ){
			$options  = $this->get_defined_constants();
			$brafton_options = array();
			foreach( $options as $key => $option )
			{
				$option_value =  get_option( $option );

				if( $option == 'brafton_error_log' )
					brafton_initialize_log( 'brafton_error_log' );
	        	$brafton_options[$option] = $option_value;
			}
			$this->brafton_options = $brafton_options;  

		}

		private final function __clone() { }
    	public final function __sleep() {
       		throw new Exception('Serializing of Singletons is not allowed');
    	}

    	public function get_defined_constants() {
    		$constants = array();
    		$global_constants = get_defined_constants();
    		foreach( $global_constants as $name => $value )
    		{
    			if(strpos($name, 'BRAFTON_') !== false ) :
    				$constants[$name] = $value; 
    			endif;
    		}
    		return $constants;
    	}

    	/**
    	 * Access this object with this method.
    	 */
    	public static function get_instance() {
        	if (self::$instance === null) 
        		self::$instance = new self();
        	return self::$instance;
    	}
	 	/**
	 	 * Registers settings for plugin options page.
	 	 */
	 	public function register_options()
	 	{
	 		$options = $this->brafton_options;

	 		foreach( $options as $key => $value )
	 		{
	 			register_setting('WP_Brafton_Article_Importer_group', $key );
	 		}
	 	}
	 	/**
         * Checks which company client is partnered with. 
         * Castleford, ContentLEAD, or Brafton
         * @return string $product
         */		
        public function brafton_get_product()
        {
            $product = get_option('braftonxml_domain');

            switch( $product ){
                case 'api.brafton.com/':
                    return 'Brafton';
                    break;  
                case 'api.contentlead.com/':
                    return 'ContentLEAD';
                    break; 
                case 'api.castleford.com.au/':
                    return 'Castleford'; 
                    break; 
            }
        }

        /**
         *  
         *  Retrieves an array of author ids with user level greater than 0 from WordPress Database. 
         *  @uses http://codex.wordpress.org/Function_Reference/get_users
         *  @return array [int]
         */
        public function brafton_get_blog_authors()
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

        public function brafton_has_api_key(){
	        $option = get_option('braftonxml_sched_API_KEY');

	        if( $option == '' ) //better to check if api key is valid
	        	return false; 

	        return true; 
        }

        public function validate_api_key()
        {
        	$option = get_option('braftonxml_sched_API_KEY');
        	//what kind of hashing algorithm do we use for our API keys
        }

    	public function last_import_run()
    	{

    	}

    	public function custom_post()
    	{
			$custom = $get_option('brafton_custom_post_type', true );
			return $custom;
    	}
	    /**
         *  Checks if Brafton Post type option is enabled in Importer settings.
         * @return bool 
         */
        public  function custom_post_type_enabled()
        {
            $option = get_option('brafton_custom_post_type');

            if( $option == "on")
                return true;

            return false; 
        }

        /**
         * Purges Options
         */
        public function purge_options()
        {
        	#todo 
        }

    	public function link_to_product()
    	{
    		$product = $this->brafton_get_product(); 
    		switch( $product )
    		{
    			case 'Brafton' : 
    				$url = 'http://brafton.com'; 
    				break; 
    			case 'ContentLEAD': 
    				$url = 'http://contentlead.com';
    				break; 
    			case 'Castleford': 
    				$url = 'http://castleford.com.au';
    				break; 
    		}
    		$output = sprintf('<a href="%s">%s</a>', $url, $product ); 

    		return $output; 	
    	}
    	
    	/**
         * Renders an upload field
         */
        public function settings_xml_upload($args)
        {
            $name = $args['name'];
            $label = $args['label'];
            echo sprintf('<div class="archive-upload"><p>%s</p><input type="file" name="%s" /></div>', $label, $name);
        }

        public function get_article_link()
        {
        	$feed = get_option('braftonxml_sched_API_KEY');
        	$product = get_option('braftonxml_domain');
        	$post_id = get_the_ID();

        	$brafton_id = get_post_meta($post_id, 'brafton_id', true);
        	$feed_url = sprintf('http://%s%s/news/%s', $product, $feed, $brafton_id);

        	return $feed_url; 
        }

        public function get_sections()
        {
        	$sections = array(
        		'brafton-article-section' => 'Article Settings', 
        		'brafton-video-section' => 'Video Settings', 
        		'brafton-advanced-section' => 'Advanced Settings', 
        		'brafton-developer-section' => 'Developer Settings'
        		); 
	        return $sections;
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
            echo sprintf('<div class="%s"><input type="text" name="%s" id="%s" value="%s" /></div>', $args['name'], $field, $field, $value);
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


               $blog_authors = $this->brafton_get_blog_authors(); 

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
                    $output .= '<div class="radio-option ' . str_replace( '_', '-', $element['name'] ) . '"><label><input type="radio" name="'. esc_attr($element['name']) .'" value="'. esc_attr($key) . '"';

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
	}


?>