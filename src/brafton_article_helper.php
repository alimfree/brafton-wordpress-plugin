<?php
	include_once ( plugin_dir_path( __FILE__ ) . '../vendors/SampleAPIClientLibrary/ApiHandler.php');
	class Brafton_Article_Helper {
		public $post_type;
		// Require Client Libraries 
		function __construct( Brafton_Options $brafton_options ){
			if( get_option('brafton_custom_post_type', true ) == 'on')
				$this->post_type = 'brafton_article'; 
			else
				$this->post_type = 'post';
		}

		/**
		 * Checks if article already exists in WordPress database. Returns post_id or false if 
		 * no posts are found.
		 * @return Mixed $post_id
		 * @param int brafton_id       
		 */
		public function exists( $brafton_id ) //should be private
		{

			$args = array(
					'post_type' => $this->post_type, 
					'meta_query' => array( 
						array( 
							'key' => 'brafton_id', 
							'value' => $brafton_id 
						) 
					) 
			);

			$find = new WP_Query( $args );

			$post_id = false; 
			if( $find->have_posts() ) {
				while( $find->have_posts() ) {
				    $find->the_post();
				    $post_id = get_the_ID();
				} // end while
			} // end if
			wp_reset_postdata();

			return $post_id; 
		}
		/**
		 * Updates existing articles to reflect changes made to articles in client's feed 
		 * Reference: http://codex.wordpress.org/Function_Reference/wp_update_post
		 * @param Array $post_exists['post_id', 'post_status']
		 * @param Array $article_array 
		 * @return int $post_id 
		 */
		private function update_post( $article_array,  $post_exists )
		{
			$article_array['ID'] = $post_exists;
			//Makes sure to update articles still in drafts
			if ( $article_array['post_status']  == 'draft' ) //make sure publish status is a string
			{
				$article_array['edit_date']  = true; 
			}

			$post_id = wp_update_post( $article_array ); 
			return $post_id;

		}

		/**
		 * Grab Articles either from a specified Archive file or from Client Feed
		 * @return Array $article_array['post_author', 'post_date', 'post_content', 'post_title', 'post_status', 'post_excerpt', 'post_categories', 'tag_input']
		 */
		public function get_articles( )
		{
			$feed_settings = $this->get_feed_settings(); 
			//Archive upload check 
			if (isset($_FILES['brafton-archive']['tmp_name'])) //todo add archive file upload settings
			{
				echo "Archive Option Selected<br/>";
				$articles = NewsItem::getNewsList( $_FILES['archive']['tmp_name'], "html" );
			} 
			else 
			{
				if ( preg_match( "/\.xml$/", $feed_settings['api_key'] ) ){
					$articles = NewsItem::getNewsList( $feed_settings['api_key'], 'news' );
				}
				else
				{
					$url = 'http://' . $feed_settings['api_url'];
					$ApiHandler = new ApiHandler( $feed_settings['api_key'], $url );
					$articles = $ApiHandler->getNewsHTML(); 	
				}
			}

			return $articles; 
		}

		/**
		 * Retrieve post author from brafton settings
		 * @return String $post_author
		 */
		public function get_post_author()
		{
			$post_author = apply_filters('braftonxml_author', get_option("braftonxml_default_author", 1));
			return $post_author; 
		}

		/**
		 * Retrieve default post status from brafton setttings
		 * @return String $post_status
		 */
		public function get_post_status()
		{	
			$post_status = get_option("braftonxml_sched_status");
			return $post_status; 
		}

		/**
		 * //Article publish date
		 * @return String $post_date
		 */
		public function get_publish_date($article_array) {
			
			switch ( get_option( BRAFTON_POST_DATE ) )
			{
				case 'modified':
					$date = $article_array->getLastModifiedDate();
					break;

				case 'created':
					$date = $article_array->getCreatedDate();
					break;

				default:
					$date = $article_array->getPublishDate();
					break;
			}

			//format post date
			$post_date_gmt = strtotime($date);
			$post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
			$post_date = get_date_from_gmt($post_date_gmt);
			
			return $post_date;
		}

		/**
		 * Retrieves client feed uri and brafton API key from brafton settings
		 * @return Array $feed_settings['url', 'API_key']
		 */
		public function get_feed_settings( $has_video = NULL ){
			if( ! isset( $has_video) ) {
				$feed_settings = array(
					"api_url" => get_option(BRAFTON_DOMAIN),
					"api_key" => get_option(BRAFTON_FEED),
				);	
			}
			else
			{
				$feed_settings = array(

					); 
			}
			
			return $feed_settings; 
		}

		/**
		 * Insert article into database
		 * @return int post_id
		 * @param Array $article_array = array (
		 * 								'post_author', 
		 * 								'post_date', 
		 * 								'post_content', 
		 * 								'post_title', 
		 * 								'post_status', 
		 * 								'post_excerpt', 
		 * 								'post_categories', 
		 * 								'tag_input', 
		 * 								'brafton_id'
		 * 							);
		 */
		public function insert_article($article_array){
			
			$article_array['post_type'] = $this->post_type; 
			$article_array['post_content'] = sanitize_text_field( $article_array['post_content'] );

			// //Checks if post exists
			$post_exists = $this->exists( $article_array['brafton_id'] );
			$brafton_id = $article_array['brafton_id']; 
			unset( $article_array['brafton_id'] );
			//if article does not exist
			if ( $post_exists  == false )
			{	//add the article to WordPress
				$post_id = wp_insert_post( $article_array ); 
				if( is_wp_error( $post_id) )

				brafton_log( 
					array(
						'option' => 'brafton_article_log',
						'priority' => 1, 
						'message' => array( 
										'brafton_id' => $brafton_id, 
										'post_id' => $post_id 
									)
					)
				);
				//add custom meta field so we can find the article again later.
				update_post_meta($post_id, 'brafton_id', $brafton_id );
				return $post_id;

			}
			else
			{
				//check if overwrite is set to on
				if ( get_option('braftonxml_overwrite') == 'on' ){
					$post_id = $this->update_post( $article_array, $post_exists ); 

				return $post_id;
				}

			}
			//not returning post_id here because if post already exists and overwrite 
			//isn't enabled, post_id will be undefined.
		}

		/**
		 * Generates an array of all sucessfully imported articles. Maintains order
		 * articles are found in the client's feed.
		 * 
		 * @return article_log
		 * 
		 */
		public function imported_articles(){

		}

		/**
		 * @usedby WP_Brafton_Article_Importer
		 * Completely removes all instances of Brafton Articles from WP. 
		 */
        public function purge_articles()
        {
        	
        }
	}
?>