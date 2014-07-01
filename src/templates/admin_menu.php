<?php
    // Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = new Brafton_Options(); 
?>

<div class="wrap">
    <div class="brafton-settings">
    <h2> <?php echo $brafton_options->get_product(); ?>  Importer</h2>
    <form method="post" action="options.php"> 

        <div class="admin">
            <?php @settings_fields('brafton_admin_group'); ?>
            <?php @do_settings_fields('brafton_admin_group'); ?>
        

            <?php @settings_fields('brafton_admin_group'); ?>
            <?php @do_settings_fields('brafton_admin_group'); ?>

            <?php do_settings_sections('brafton_admin_options_section'); ?>


            <?php @submit_button(); ?>
        </div>
    </form>
    </div><!--- .brafton-options -->
</div><!-- .wrap -->