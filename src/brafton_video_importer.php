<?php

// create a parent importer class that avoids reusing article class code. 
	if ( class_exists( 'Video_Importer' ) )
	{
		include_once('../vendors/RCClientLibrary/AdferoArticlesVideoExtensions/AdferoVideoClient.php');
		include_once('../vendors/RCClientLibrary/AdferoArticles/AdferoClient.php');
		include_once('../vendors/RCClientLibrary/AdferoPhotos/AdferoPhotoClient.php');

		include_once('brafton_video_helper.php')

		class Brafton_Video_Importer 
		{
			

		}

	}



?>