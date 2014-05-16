<?php 
	include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoArticlesVideoExtensions/AdferoVideoClient.php');
	include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoArticles/AdferoClient.php');
	include_once ( plugin_dir_path( __FILE__ ) . '../vendors/RCClientLibrary/AdferoPhotos/AdferoPhotoClient.php');

	class Brafton_Video_Helper
	{
		private $video_out_client; 
		private $video_client; 
		public $client; 
		private $base_url = 'http://api.video.brafton.com/v2/';

		function __construct()
		{
			$video_settings = $this->get_video_settings();

			$this->video_client = new AdferoVideoClient( $this->base_url, $video_settings['video_public'], $video_settings['video_secret'] );
			$this->client = new AdferoClient( $this->base_url, $video_settings['video_public'], $video_settings['video_secret'] );

			$this->video_out_client = $this->video_client->videoOutputs();
		}
		


		/**
		 * Retrieves video settings from options table. 
		 * 
		 * @return Array $video_settings
		 */
		private function get_video_settings()
		{
			$video_settings = array(
					'video_secret' => get_option( 'braftonxml_videosecret' ),
					'video_public' => get_option( 'braftonxml_videopublic' ), 
					'feed_num' => get_option( 'braftonxml_video_feed_num' )
				);

			return $video_settings; 
		}
		/**
		 * Grabs all video articles from client's feed. 
		 * @return Array $video_articles. 
		 */
		public function get_video_articles()
		{
			$feeds = $this->client->Feeds();
			$feedList = $feeds->ListFeeds(0, 10);

			$video_articles = $this->client->Articles();
			$feed_num = get_option( 'braftonxml_video_feed_num' ); 
			$articleList = $video_articles->ListForFeed($feedList->items[ $feed_num ]->id, 'live', 0, 100);		
			return $video_articles;
		}

		/**
		 * Returns video 
		 */
		public function get_video_output( $brafton_id )
		{

			$videoList = $this->video_out_client->ListForArticle($brafton_id,0,10);
			$list = $videoList->items;

			foreach( $list as $list_item ){
				$output = $this->video_out_client->Get($list_item->id);
				$type = $output->type; 
				$format = $this->format_video_output($output, $type);
			}

			return $format; 

		}	

		public function format_video_output( $output, $type )
		{

				$video_format = $output->path; 

				if( $type =  "custom" ) 
				{
					$path = $output->path;
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					if($ext){
						$video_format = $path; break;
					}
				}
				else  {
					$width=$output->width; 
					$height=$output->height;
					$output_array = compact( 'video_format' , 'width', 'height'); 
				 } 
					
			return $output_array; 
		}
	}

?>