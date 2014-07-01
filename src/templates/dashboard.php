<<<<<<< HEAD
<?php 
/**
 * dashboard page View
 */


// Initialize Settings
require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
$brafton_options = Brafton_options::get_instance(); 

 $post_type = $brafton_options->brafton_get_post_type();

 echo $post_type;
 $args = array( 'post_type' => $post_type, 'posts_per_page' => -1 );

 $brafton_articles = new WP_Query( $args );
?>

<h1><?php echo $brafton_options->brafton_get_product(); ?> Dashboard </h1>

<?php echo $brafton_articles->found_posts; ?>
<?php if( $brafton_articles->have_posts() ) : while( $brafton_articles->have_posts() ) : $brafton_articles->the_post(); ?>
	<?php $post_id = get_the_ID(); ?>
	<ul class="brafton posts" >
		<li class="post-<?php echo $post_id; ?>">
			<?php $brafton_id = get_post_meta( $post_id, 'brafton_id', true ); ?>
			<?php $edit_link = get_edit_post_link( $post_id ); ?>

			<p><a href="<?php echo $edit_link; ?>"><?php echo the_title(); ?></a></p>

		</li>
	</ul>

<?php endwhile;
 endif; 

 wp_reset_postdata();
 ?>
=======
<?php
	// Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = new Brafton_Options(); 
 ?>

<div class="wrap">
    <div class="importer-dashboard">
        <h2> <?php echo $brafton_options->get_product(); ?>  Importer</h2>
        <p>Welcome to your content Dashboard</p>
    </div><!--- .brafton-options -->
</div><!-- .wrap -->
>>>>>>> master
