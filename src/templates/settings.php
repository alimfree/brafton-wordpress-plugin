<?php
	// Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = new Brafton_Options(); 
 ?>

<div class="wrap">
    <div class="importer-dashboard">
        <h2> <?php echo $brafton_options->get_product(); ?>  Importer</h2>
        <p>Welcome to your content Dashboard</p>


                    <?php do_settings_sections('WP_Brafton_Article_Importer'); ?>
        
        
        <?php @submit_button(); ?>
    </div><!--- .brafton-options -->
</div><!-- .wrap -->