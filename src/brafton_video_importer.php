<?php
/**
 * Video Import loop
 */

include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoArticlesVideoExtensions/AdferoVideoClient.php');
include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoArticles/AdferoClient.php');
include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoPhotos/AdferoPhotoClient.php');

include_once 'brafton_video_helper.php';
include_once 'brafton_taxonomy.php';
include_once 'brafton_image_handler.php';
include_once 'brafton_errors.php';


class Brafton_Video_Importer 
{
	public $brafton_video;
	public $brafton_images;
	//Initialize 
	function __construct ( 
					Brafton_Image_Handler $brafton_image = Null, 
					Brafton_Taxonomy $brafton_cats, 
					Brafton_Taxonomy $brafton_tags, 
					Brafton_Video_Helper $brafton_video)
	{
		if( 'on' == get_option(BRAFTON_ENABLE_IMAGES) )
		{	//grab image data for previously imported images
			$this->brafton_images = get_option('brafton_images');
			//and load the image class.
			$this->brafton_image_handler = $brafton_image;
		}
		$this->brafton_cats = $brafton_cats;
		$this->brafton_tags = $brafton_tags; 
		$this->brafton_video = $brafton_video; 
		$this->brafton_image = $brafton_image;
	}

	public function import_videos()
	{
		$video_articles = $this->brafton_video->get_video_articles();

		


		foreach( $video_articles as $video )
		{

			$brafton_id = $article->id;
			$video_format = $this->brafton_video->get_video_output( $brafton_id );
			var_dump( $video_format );

		}
			
	}

}

?>