<?php 	

    $options =	array( 
              	     array( 
                        'ENABLE_ARTICLES_OPTION' => 'brafton_import_articles', 
                        'label' => '', 
                        'section' => '', 
                        'options' => ''
                    )

                array( 'ENABLE_VIDEO_OPTION' => 'braftonxml_video', 
                array( 'ENABLE_IMAGES_OPTION' => 'brafton_photo',
                array( 'AUTHOR_OPTION' => 'brafton_default_author',
                array( 'FEED_OPTION' => 'braftonxml_sched_API_KEY',
                array( 'DOMAIN_OPTION' => 'braftonxml_domain',
                array( 
                    'ID' => 'CUSTOM_TAGS_OPTION',
                    'section' => 'brafton_advanced_section', 
                    'settings' => array(
                                    'name' => 'braftonxml_sched_tags', 
                                    'options' => array(
                                                    'tags' => ' Brafton Tags as Tags',
                                                    'keywords' => ' Brafton Keywords as Tags',
                                                    'categories' => ' Brafton Categories as Tags', 
                                                    'none' => ' None'
                                                    )
                                    )
                ),

                    ),
                array( 'SCHEDULED_STATUS_OPTION' => 'braftonxml_sched_status',
                array( 'TAGS_OPTION' => 'brafton_tags_option',
                array( 
                        'CATEGORIES_OPTION' => 'brafton_categories',
                        'section' => 'brafton_advanced_section', 
                        'settings' => array(
                                        'name' => 'brafton_categories', 
                                        'options' => array(
                                                        'categories' => ' Brafton Categories',
                                                        'no_categories' => ' None'
                                                        )
                                        )
                ),
                array( 'OVERWRITE_OPTION' => 'braftonxml_overwrite',
                array( 
                    'POST_DATE_OPTION' => 'braftonxml_publishdate', 
                    'section' => 'brafton_advanced_section'                
                    'settings' => array(
                                    'name' => 'braftonxml_publishdate', 
                                    'options' => array(
                                                    'published' => ' Published Date',
                                                    'modified' => ' Last Modified Date',
                                                     'created' => ' Created Date'
                                       ),
                                    'default' => 'published'
                    ),
                array( 'VIDEO_PUBLIC_OPTION' => 'braftonxml_videoPublic',
                array( 'VIDEO_FEED_OPTION' => 'braftonxml_videoSecret',
                array( 'VIDEO_FEED_NUM_OPTION' => 'braftonxml_videoFeedNum',
                array( 'CUSTOM_POST_OPTION' => 'brafton_custom_post_type',
                array( 'DISABLE_OPTION' => 'brafton_purge',
                array( 'PARENT_CATEGORIES_OPTION' => 'brafton_parent_categories',
                array( 'CUSTOM_TAXONOMY_OPTION' => 'brafton_custom_taxonomy',
                array( 'IMPORT_COUNT_OPTION' => 'braftonxml_sched_triggercount',
                array( 'ENABLE_ERRORS_OPTION' => 'brafton_errors', 
                array( 'ERRORS_OPTION' => "brafton_error_log"
                array( 'ARCHIVES_OPTION' => '', 'section' => '',  'options' => array('label' => 'Upload a specific xml Archive file', 
                    'name' => 'achives' 
                    ))
        );
?>

        array(
                    'name' => 'brafton_categories', 
                    'options' => array('categories' => ' Brafton Categories',
                                       'no_categories' => ' None')
                )