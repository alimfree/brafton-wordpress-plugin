<?php
/**
 * @author Ali
 * This class is used by Article, Video, and Image meta data 
 * in wordpress options table. Helps optimize plugin's resource usage.
 * Requires PHP 5>=5.3.0
 */

class Brafton_Feed
{
	public $option_name;
	function __construct( Brafton_Options $brafton_options ){
		$this->brafton_options = Brafton_Options::get_instance();	
		$this->option_name = $this->get_option_name;

		add_option( $this->option_name );
	}

	private function get_option_name()
	{
		$class = strtolower( get_called_class() );

		switch( $class )
		{
			case 'brafton_image_handler' :
				return 'brafton_image';
				break;
			case 'brafton_video_importer' :
				return 	'brafton_video';
				break;
			case 'brafton_article_importer' :
				return 'brafton_article';
				break;
		}
	}

	/**
	 * Accepts associative array of meta data to store in options table
	 * Arguments depend on the calling class and the data they need to store
	 * 
	 * ex article importer class might pass the following:
	 * $args = array( array('brafton_id' => '', 'post_id' => '' ));
	 * @param $args  
	 */
	public function update_option($args)
	{
		$article_array = get_option( $this->option_name  );

		$updated_article_array = array_unshift( $article_array, $args);

       	update_option( $this->option_name , serialize( $updated_article_array ) );
	}

}

?>