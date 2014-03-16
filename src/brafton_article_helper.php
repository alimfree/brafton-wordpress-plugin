<?php
	class Brafton_Article_Helper {

		// Require Client Libraries 
		include_once '../vendors/SampleAPIClientLibrary/ApiHandler.php';

		/**
		 * Formats post content 
		 * @param String $content
		 * @return String $post_content
		 */
		private function format_post_content($content)
		{
			$post_content = preg_replace('|<(/?[A-Z]+)|e', "'<' . strtolower('$1')", $this->content);
			$post_content = str_replace('<br>', '<br />', $post_content);
			$post_content = str_replace('<hr>', '<hr />', $post_content);

			return $post_content;
		}

		/**
		 * Checks if article already exists in WordPress database. Returns array including
		 * $post_id and $post_status if article with given $brafton_id is found. Returns
		 * false if nothing is found.
		 * @return mixed false or $post_exists['post_id', 'post_status']
		 * @param int brafton_id       
		 */
		private function article_exists( $brafton_id, WP_Query $article_query = NULL )
		{
			$args = array( 
							'meta_key' => 'brafton_id', 
							'meta_value' => $brafton_id, 
							'post_type' => 'Brafton-Article',
				);			
			$article_query = $article_query ? : new WP_Query( $args );

			if ( $article_query->have_posts() ){
				the_post();
				$post_id = the_ID();

				$post_status = get_post_status( $post_id );
				$post_exists = compact('post_id', 'post_status'); 
				return $post_exists; 
			}
			
			return false;  
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
			$args =  array(
							'post_type' => 'Brafton-Article',
							'post_content' => $article_array['post_content'],
							'post_date' => $artilce_array['post_date'],
							'post_title' => $artilce_array['post_title'],
							'post_excerpt' => $artilce_array['post_excerpt'],
							'post_status' => $artilce_array['post_status'],
							'post_category' => $article_array['post_categories'],
				);
			//Makes sure to update articles still in drafts
			if ( $article_array['publish_status']  == 'draft' ) //make sure publish status is a string
			{
				$args['edit_date']  => true; 
			}

			$post_id = wp_update_post( $args ); 
			return $post_id;

		}

		/**
		 * Grab Articles either from a specified Archive file or from Client Feed
		 * @return Array $article_array['post_author', 'post_date', 'post_content', 'post_title', 'post_status', 'post_excerpt', 'post_categories', 'tag_input']
		 */
		public function get_articles( ApiHandler $ApiHandler = NULL )
		{
			$feed_settings = $this->get_feed_settings(); 
			//Archive upload check 
			if ($_FILES['archive']['tmp_name']) //todo add archive file upload settings
			{
				echo "Archive Option Selected<br/>";
				$articles = NewsItem::getNewsList( $_FILES['archive']['tmp_name'], "html" );
			} 
			else 
			{
				if ( preg_match( "/\.xml$/", $feed_settings['api_key'] ) )
					$articles = NewsItem::getNewsList( $feed_settings['api_key'], 'news' );
				else
				{
					$ApiHandler = $ApiHandler ? : new ApiHandler( $feed_settings['api_key'], $feed_settings['api_url'] );
					$articles = $ApiHandler->getNewsHTML(); 		
				}
			}

			return $articles_array; 
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
			$post_status = get_option("braftonxml_sched_status", "publish");
			return $post_status; 
		}

		/**
		 * //Article publish date
		 * @return String $post_date
		 */
		public function get_publish_date($article_array) {
			
			switch ( get_option( 'braftonxml_publishdate' ) )
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
				$feedSettings = array(
					"url" => get_option("braftonxml_sched_url"),
					"API_Key" => get_option("braftonxml_sched_API_KEY"),
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
		 * @param Array $article_array['post_author', 'post_date', 'post_content', 'post_title', 'post_status', 'post_excerpt', 'post_categories', 'tag_input']
		 */
		public function insert_article($article_array){
			
			$article_array['post_type'] = 'Brafton-Article'; 
			//Checks if post exists
			$post_exists = $this->article_exists( $article_array['brafton_id'] ); 
			$post_id = $post_exists['post_id']; 

			if ( ! $posts_id ] )
				$post_id = wp_insert_post( $article_array ); 
			else
			{
				//check if overwrite is set to on
				if ( /*overwrite is on */ )
					$this->update_post( $article_array, $post_exists ); 
			}

			if ( ! is_wp_error( $post_id ) )
				return $post_id;
			if( ! $post_id )
				return; 
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