<div class="wrap">
    <h2>Brafton Article Importer</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('WP_Brafton_Article_Importer-group'); ?>
        <?php @do_settings_fields('WP_Brafton_Article_Importer-group'); ?>

        <?php do_settings_sections('WP_Brafton_Article_Importer'); ?>

        <?php @submit_button(); ?>
    </form>
</div>