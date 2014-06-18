<?php
if(!class_exists('Brafton_Article_Template'))
{
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class Brafton_Article_Template
	{

		const POST_TYPE	= "brafton-article";
		private $_meta	= array(
			'brafton_id',
			'photo_id',
		);

        public $brafton_options;
		
    	/**
    	 * The Constructor
    	 */
    	public function __construct( Brafton_Options $brafton_options )
    	{
                $this->brafton_options = $brafton_options;
    		// register actions
    		add_action('init', array(&$this, 'init'));
    		add_action('admin_init', array(&$this, 'admin_init'));
    	} // END public function __construct()

    	/**
    	 * hook into WP's init action hook
    	 */
    	public function init()
    	{
    		// Initialize Post Type
    		$this->create_brafton_post_type();
    		add_action('save_post', array(&$this, 'save_post'));
    	} // END public function init()

    	/**
    	 * Create the post type
    	 */
    	public function create_brafton_post_type()
    	{         
            $post_slug = $this->brafton_options->options['brafton_custom_post_slug'];
            if( !$post_slug )
                $post_slug = 'blog'; 

     		register_post_type(self::POST_TYPE,
    			array(
    				'labels' => array(
    					'name' => $this->brafton_options->brafton_get_product() . ' Articles',
    					'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE)))
    				),
    				'public' => true,
    				'has_archive' => true,
                    'taxonomies' => array('category'),
                    'rewrite'            => array( 'slug' => $post_slug ),
    				'description' => __("This is a sample post type meant only to illustrate a preferred structure of plugin development"),
    				'supports' => array(
    					   'title', 'author' , 'editor', 'excerpt', 'thumbnail', 'revisions', 'post_formats',
    				),
    			)
    		);

            add_action( 'brafton_rewrite_hook', 'brafton_flush_rewrites' );
            
    	}
	
    	/**
    	 * Save the metaboxes for this custom post type
    	 */
    	public function save_post($post_id)
    	{
            // verify if this is an auto save routine. 
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }
            
    		if( isset($_POST['post_type']) == self::POST_TYPE && current_user_can('edit_post', $post_id))
    		{
    			foreach($this->_meta as $field_name)
    			{
    				// Update the post's meta field
    				update_post_meta($post_id, $field_name, $_POST[$field_name]);
    			}
    		}
    		else
    		{
    			return;
    		} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    	} // END public function save_post($post_id)

    	/**
    	 * hook into WP's admin_init action hook
    	 */
    	public function admin_init()
    	{			
    		// Add metaboxes
    		add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
    	} // END public function admin_init()
			
    	/**
    	 * hook into WP's add_meta_boxes action hook
    	 */
    	public function add_meta_boxes()
    	{
    		// Add this metabox to every selected post
    		add_meta_box( 
    			sprintf('WP_Brafton_Article_Importer_%s_section', self::POST_TYPE),
    			sprintf('%s Article Information', ucwords(str_replace("_", " ", $this->brafton_options->brafton_get_product() ))),
    			array(&$this, 'add_inner_meta_boxes'),
    			self::POST_TYPE, 
                'side'
    	    );					
    	} // END public function add_meta_boxes()

        public function brafton_rewrite_hook(){
            do_action('brafton_rewrite_hook');
        }
        /**
         *  Flush Rewrites urls when custom post slug is specified. 
         */
        public function brafton_flush_rewrites(){
            if( $this->brafton_options->options['brafton_custom_post_slug'] ){ 
                echo "custom post slug is entered.";
                flush_rewrite_rules();
            }
        }

		/**
		 * called off of the add meta box
		 */		
		public function add_inner_meta_boxes($post)
		{		
			// Render the job order metabox
			include(sprintf("%s/templates/brafton_article_template_metabox.php", dirname(__FILE__), self::POST_TYPE));			
		} // END public function add_inner_meta_boxes($post)

	} // END class Brafton_Article
} // END if(!class_exists('Brafton_Article'))