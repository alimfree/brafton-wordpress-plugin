<?php 



if ( !class_exists( 'Article_Importer' ) )
{	
	include_once '../vendors/SampleAPIClientLibrary/ApiHandler.php';
	include_once 'brafton_article_helper.php';
	/**
	 * @package WP Brafton Article Importer 
	 *
	 */
	class Brafton_Article_Importer {

		//Initialize 
		function __construct ( Brafton_Image_Handler $brafton_image = Null, Brafton_Taxonomy $brafton_taxonomy ){
			$this->brafton_image_handler = $brafton_image;
			$this->brafton_taxonomy = $brafton_taxonomy; 
		}

		/**
		 * @uses Brafton_Article_Helper to retrieve an articles array containing NewsItem objects.
		 * @uses ApiHandler indirectly through Brafton_Article_Helper to grab article specific metadata from client's xml uri.
		 * @uses NewsItem indirectly through ApiHandler to grab article specific metadata from client's xml uri.
		 * @uses NewsCategory indirectly through NewsItem to grab category id's from client's xml uri.
		 * @uses XMLHandler indirectly through NewsItem to make http requests to client's xml url. 
		 * @uses Brafton_Taxonomy to assign category and tags to Articles
		 * @uses Brafton_Image_handler to attach post thumbnails to Articles
		 * 
		 * 
		 * Imports content from client's xml feed's uri into WordPress. 
		 */
		public function import_articles(){

			$articles_array = $this->get_articles(); //look in article_helper for method definition. array of NewsItem objects


			foreach( $article_array as $a ){
				//Get article meta data from feed
				$brafton_id = $->getID(); 
				$date = get_publish_date( $a ); 
				$post_title = $a->getHeadline();
				$content = $a->getText(); 
				$photos = $a->getPhotos(); 
				$post_excerpt = $a->getExtract(); 
				$keywords = $a->getKeywords();
				$categories = $a->getCategories(); 
				$tags = $a->getTags();

				//Get more article meta data
				$post_author = $this->get_post_author(); 
				$post_status = $this->get_post_status();
				$post_content = $this->get_post_content($content); 
				

				//prepare article tag id array
				$tag_input = $this->brafton_taxonomy->get_post_tags( $tags, $post_id );

				//prepare article category id array
				$post_category = $this->brafton_taxonomy->get_post_categories( $categories, $post_id );  

				//prepare single article meta data array
				$article = compact('post_author', 'post_date', 'post_content', 'post_title', 'post_status', 'post_excerpt', 'post_categories', 'tag_input'); 

				//insert article to WordPress database
				$post_id = $this->insert_article($article);

				//update post to include thumbnail image
				$this->brafton_image_handler->insert_image( $photos, $post_id, $has_video ); 
			}
		}

	}

}
	

?>