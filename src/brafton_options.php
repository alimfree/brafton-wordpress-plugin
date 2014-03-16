<?php 

	class Brafton_Options
	{

		 /**
	         * Checks which company client is partnered with. 
	         * Castleford, ContentLEAD, or Brafton
	         * @return string $product
	         */
	        public function get_product()
	        {
	            $option = get_option('braftonxml_domain');

	            switch( $option ){
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
	        public function get_blog_authors()
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

	        public function has_api_key(){
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
		        $purge = get_option('brafton_purge');

		        if ( $purge = 'options')
		        {
		   	       	unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_default_author');
		        	unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_API_KEY');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_domain');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_tags');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_sched_status');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_tags_option');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_categories');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_categories_options');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_photo');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'brafton_importer_status');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_overwrite');
		        	unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_publishdate');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_video');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoPublic');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoSecret');
		            unregister_setting('WP_Brafton_Article_Importer_group', 'braftonxml_videoFeedNum');
		            unregister_setting('WP_Brafton_article_Importer_group', 'brafton_custom_post_type');
		            unregister_setting('WP_Brafton_article_Importer_group', 'brafton_purge');
		        } 
	        }

	    	public function link_to_product()
	    	{
	    		$product = $this->get_product(); 
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
	    		$output = '<a href="' . $url . '">' . $product . '</a>'; 

	    		return $output; 	
	    	}
	}


?>