<?php



if( ! class_exists('Brafton_Taxonomy') )
{
	
	class Brafton_Taxonomy {
	    
		/**
		* global constant serialized category array
		*/
		if ! defined( 'CATEGORIES' ); 
			define('CATEGORIES'); 

		/**
		 *	Array $post_categories[int]
		 */	
		private $post_categories; 
		

		function __construct($args){

		}

	    /**
		 * Retrieves list of categories assigned to a given article from client's feed. Stores
		 * previously used category meta details in a serialized array constant CATEGORIES to minimize web requests. 
		 * Returns wp category ids
		 * @param String $post_categories
		 * @return Array post_category[]
		 */
		private function get_article_categories( $categories ){
			
			$categories_in_wp = unserialize( $this->CATEGORIES ); 

			if ( empty( $categories_in_wp ) ) {
				foreach( $categories as $category_id ) ){					
					$category_values = store_category( $category_id ); 
					$categories_in_wp = $category_values['categories_in_wp'];
					$post_categories = $category_values['post_categories'];
				}
  		  		$this->CATEGORIES = serialize( $categories_in_wp ) ); 
			}
  		  	else
  		  	{
  		  		$categories_in_wp = unserialize( 'CATEGORIES' ); 

  		  		foreach ( $categories as $category_id ){
  		  			$saved_category_names = $categories_in_wp[$category_id]; 
  		  			if ( ! in_array( $category_id, $saved_category_names )
  		  			{
		  		  		$category_values = store_category( $category_id ); 
						$categories_in_wp = $category_values['categories_in_wp'];
						$post_categories = $category_values['post_categories'];
  		  			}
  		  			else
  		  			{	
  		  				$wp_category_id = $saved_category_names['wp_id'];
  		  				$post_categories[] = $wp_category_id; 
  		  			}
  		  		}
  		  		$this->CATEGORIES = serialize( $categories_in_wp );
  		  	}
  		  	return $post_categories; 
		}

		private function get_custom_categories( $post_categories )
		{
			$custom_categories = explode(",", get_option("braftonxml_sched_cats_input"));

			foreach ( $custom_categories as $category_name ){
				$category_id = wp_create_category( $category_name );
				$post_categories[] = $category_id; 
			}

			return $post_categories; 
		}

		private function get_video_categories()
		{

		}

	    /**
		 * Returns article specific meta data from feed.
		 * @return array
		 */
		private function get_article_tags( $categories ){

		}		

		private function get_custom_tags()
		{

		}

		private function get_video_tags()
		{

		}

		private function category_exists()
		{

		}

		private function store_category( $category_id = NULL, $custom_category = NULL)
		{
			$post_categories = $this->post_categories; 
			if( isset( $custom_category ) ){
				$category_id = 'custom_category'; 		
			}
			else
			{
				$category_name = $category_id->getName();
			}
			$wp_category_id = wp_create_category( $category_name );
			$post_categories[] = $wp_category_id;  
			$categories_in_wp[$category_id] = array( 'name' => $category_name, 'wp_id' => $wp_category_id );	
			$category_values = array( 'categories_in_wp' => $categories_in_wp , 'post_categories' => $post_categories )
			return $categories_values;
		}

	  
		public function insert_categories()
		{

		}

		public function insert_tags()
		{
			
		}

	}
}
	



?>