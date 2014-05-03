<?php 



if ( !class_exists( 'Article_Importer' ) )
{	
	include_once ( plugin_dir_path( __FILE__ ) . '../vendors/SampleAPIClientLibrary/ApiHandler.php');
	include_once 'brafton_article_helper.php';
	include_once 'brafton_taxonomy.php';
	include_once 'brafton_image_handler.php';
	include_once 'brafton_errors.php';
	/**
	 * @package WP Brafton Article Importer 
	 *
	 */
	class Brafton_Article_Importer {

		 public $brafton_articles;
		 public $brafton_images;
		//Initialize 
		function __construct ( Brafton_Image_Handler $brafton_image = Null, Brafton_Taxonomy $brafton_cats, Brafton_Taxonomy $brafton_tags, Brafton_Article_Helper $brafton_article, Brafton_Errors $brafton_errors ){
			//let's get feed data for previously imported articles
			$this->brafton_articles = get_option('brafton_articles');
			
			if( 'on' == $this->brafton_options->get_option('brafton_photo') )
			{	//grab image data for previously imported images
				$this->brafton_images = get_option('brafton_images');
				//and load the image class.
				$this->brafton_image_handler = $brafton_image;
			}
			$this->brafton_cats = $brafton_cats;
			$this->brafton_tags = $brafton_tags; 
			$this->brafton_article = $brafton_article; 
			$this->brafton_errors = $brafton_errors;

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

			$articles_array = $this->brafton_article->get_articles(); //look in article_helper for method definition. array of NewsItem objects

			$article_id_array = array();
			foreach( $article_array as $a ){
				//Get article meta data from feed
				$brafton_id = $a->getID(); 
				$date = get_publish_date( $a ); 
				$post_title = $a->getHeadline();
				$content = $a->getText(); 
				$photos = $a->getPhotos(); 
				$post_excerpt = $a->getExtract(); 
				$keywords = $a->getKeywords();
				$cats = $a->getCategories(); 
				$tags = $a->getTags();

				//Get more article meta data
				$post_author = $this->brafton_article->get_post_author(); 
				$post_status = $this->brafton_article->get_post_status();
				$post_content = $this->get_post_content($content); 
				

				//prepare article tag id array
				$input_tags = $this->brafton_tags->get_terms( $tags, 'tag' );

				//prepare article category id array
				$post_category = $this->brafton_cats->get_terms( $cats, 'category' );  

				//prepare single article meta data array
				$article = compact('post_author', 'post_date', 'post_content', 'post_title', 'post_status', 'post_excerpt', 'post_categories', 'tag_input'); 

				//insert article to WordPress database
				$post_id = $this->brafton_article->exists( $brafton_id );
				if( ! $post_id ){
					array_unshift( $this->article_id_array, array( 	'brafton_id' => $brafton_id, 
												'post_id' => $post_id ) );	
					
					$post_id = $this->brafton_article->insert_article($article);
				}
				else
					$this->brafton_article->update_post( $article, $post_id );
				
				//update post to include thumbnail image
				$this->brafton_image_handler->insert_image( $photos, $post_id, $has_video ); 
			}
			//update article_array
			$this->brafton_feed->update_options( $article_id_array );
		}

	}

}
	

?>