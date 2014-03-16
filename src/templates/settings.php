<?php
	// Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = new Brafton_Options(); 
 ?>

<div class="wrap">
    <div class="brafton-options">
    <h2> <?php echo $brafton_options->get_product(); ?>  Importer</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('WP_Brafton_Article_Importer_group'); ?>
        <?php @do_settings_fields('WP_Brafton_Article_Importer_group'); ?>

        <?php do_settings_sections('WP_Brafton_Article_Importer'); ?>

        <?php @submit_button(); ?>
    </form>
    </div><!--- .brafton-options -->
</div><!-- .wrap -->