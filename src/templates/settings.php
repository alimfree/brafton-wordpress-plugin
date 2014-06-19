<?php
<<<<<<< HEAD
    // Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = Brafton_options::get_instance(); 
 ?>

<div class="wrap">
    <div class="brafton-options">
    <h2> <?php echo $brafton_options->brafton_get_product(); ?>  Importer</h2>

    <?php 

    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
            echo '<div class="updated fade"><p>' . __( sprintf('%s options updated.', $brafton_options->brafton_get_product() ) ) . '</p></div>'; ?>

    <form method="post" action="options.php" enctype="multipart/form-data"> 
        <?php @settings_fields('WP_Brafton_Article_Importer_group'); ?>
        <div class="ui-tabs"> 
            <ul class="ui-tabs-nav">
                <?php 
                

               $sections = $brafton_options->get_sections(); 
                foreach ( $sections as $section_slug => $section )
                   echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>'; ?>

            </ul><!-- end .ul-tabs-nav -->
                <?php do_settings_sections( $_GET['page'] ); ?>

        </div><!-- end .ul-tabs-->
        <?php   @submit_button(); ?>
        <?php
        if( $brafton_options->brafton_has_api_key() )
            echo '<div class="footer">Thank you for Partnering with ' . $brafton_options->link_to_product() .' </div>';
        ?>
    </form>

    <form method="post" action="" enctype="multipart/form-data">
        
            <div class="ui-tabs-panel" id="brafton-developer-section">
                <?php $args = array( 'name' => 'brafton-archive', 'label' => 'Upload a specific xml Archive file' ); ?>
               <?php  $brafton_options->settings_xml_upload($args); ?>
            </div>
    </form>

    </div><!--- .brafton-options -->
</div><!-- .wrap -->
 
<?php echo '<script type="text/javascript">
        jQuery(document).ready(function($) {
            var sections = [];';
            
            foreach ( $sections as $section_slug => $section )
                echo "sections['$section'] = '$section_slug';";
            
            echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
            wrapped.each(function() {
                $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
            });
            $(".ui-tabs-panel").each(function(index) {
                $(this).attr("id", sections[$(this).children("h3").text()]);
                if (index > 0)
                    $(this).addClass("ui-tabs-hide");
            });
            $(".ui-tabs").tabs({
                fx: { opacity: "toggle", duration: "fast" }
            });
            
            $("input[type=text], textarea").each(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
                    $(this).css("color", "#999");
            });
            
            $("input[type=text], textarea").focus(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
                    $(this).val("");
                    $(this).css("color", "#000");
                }
            }).blur(function() {
                if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
                    $(this).val($(this).attr("placeholder"));
                    $(this).css("color", "#999");
                }
            });
            
            $(".wrap h3, .wrap table").show();
            
            // This will make the "warning" checkbox class really stand out when checked.
            // I use it here for the Reset checkbox.
            $(".warning").change(function() {
                if ($(this).is(":checked"))
                    $(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
                else
                    $(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
            });
            
            // Browser compatibility
            if ($.browser.mozilla) 
                     $("form").attr("autocomplete", "off");
        });
    </script>';
<?php
	// Initialize Settings
    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));
    $brafton_options = Brafton_options::get_instance(); 
 ?>

<div class="wrap">
    <div class="brafton-options">
    <h2> <?php echo $brafton_options->brafton_get_product(); ?>  Importer</h2>

    <?php 

    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
            echo '<div class="updated fade"><p>' . __( sprintf('%s options updated.', $brafton_options->brafton_get_product() ) ) . '</p></div>'; ?>

    <form method="post" action="options.php" enctype="multipart/form-data"> 
        <?php @settings_fields('WP_Brafton_Article_Importer_group'); ?>
        <div class="ui-tabs"> 
            <ul class="ui-tabs-nav">
                <?php 
                

               $sections = $brafton_options->get_sections(); 
                foreach ( $sections as $section_slug => $section )
                   echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>'; ?>

            </ul><!-- end .ul-tabs-nav -->
                <?php do_settings_sections( $_GET['page'] ); ?>

        </div><!-- end .ul-tabs-->
        <?php   @submit_button(); ?>
        <?php
        if( $brafton_options->brafton_has_api_key() )
            echo '<div class="footer">Thank you for Partnering with ' . $brafton_options->link_to_product() .' </div>';
        ?>
    </form>

    <form method="post" action="" enctype="multipart/form-data">
        
            <div class="ui-tabs-panel" id="brafton-developer-section">
                <?php $args = array( 'name' => 'brafton-archive', 'label' => 'Upload a specific xml Archive file' ); ?>
               <?php  $brafton_options->settings_xml_upload($args); ?>
            </div>
    </form>

    </div><!--- .brafton-options -->
</div><!-- .wrap -->
 
<?php echo '<script type="text/javascript">
        jQuery(document).ready(function($) {
            var sections = [];';
            
            foreach ( $sections as $section_slug => $section )
                echo "sections['$section'] = '$section_slug';";
            
            echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
            wrapped.each(function() {
                $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
            });
            $(".ui-tabs-panel").each(function(index) {
                $(this).attr("id", sections[$(this).children("h3").text()]);
                if (index > 0)
                    $(this).addClass("ui-tabs-hide");
            });
            $(".ui-tabs").tabs({
                fx: { opacity: "toggle", duration: "fast" }
            });
            
            $("input[type=text], textarea").each(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
                    $(this).css("color", "#999");
            });
            
            $("input[type=text], textarea").focus(function() {
                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
                    $(this).val("");
                    $(this).css("color", "#000");
                }
            }).blur(function() {
                if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
                    $(this).val($(this).attr("placeholder"));
                    $(this).css("color", "#999");
                }
            });
            
            $(".wrap h3, .wrap table").show();
            
            // This will make the "warning" checkbox class really stand out when checked.
            // I use it here for the Reset checkbox.
            $(".warning").change(function() {
                if ($(this).is(":checked"))
                    $(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
                else
                    $(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
            });
            
            // Browser compatibility
            if ($.browser.mozilla) 
                     $("form").attr("autocomplete", "off");
        });
    </script>';
?>
=======

	// Initialize Settings

    require_once( sprintf(realpath(dirname(__FILE__) . '/..') .'/brafton_options.php'));

    $brafton_options = Brafton_options::get_instance(); 
 ?>



<div class="wrap">

    <div class="brafton-options">

    <h2> <?php echo $brafton_options->brafton_get_product(); ?>  Importer</h2>



    <?php 



    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )

            echo '<div class="updated fade"><p>' . __( sprintf('%s options updated.', $brafton_options->brafton_get_product() ) ) . '</p></div>'; ?>



    <form method="post" action="options.php" enctype="multipart/form-data"> 

        <?php @settings_fields('WP_Brafton_Article_Importer_group'); ?>

        <div class="ui-tabs"> 

            <ul class="ui-tabs-nav">

                <?php 

                



               $sections = $brafton_options->get_sections(); 

                foreach ( $sections as $section_slug => $section )

                   echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>'; ?>



            </ul><!-- end .ul-tabs-nav -->

                <?php do_settings_sections( $_GET['page'] ); ?>



        </div><!-- end .ul-tabs-->

        <?php   @submit_button(); ?>

        <?php

        if( $brafton_options->brafton_has_api_key() )

            echo '<div class="footer">Thank you for Partnering with ' . $brafton_options->link_to_product() .' </div>';

        ?>

    </form>



    <form method="post" action="" enctype="multipart/form-data">

        

            <div class="ui-tabs-panel" id="brafton-developer-section">

                <?php $args = array( 'name' => 'brafton-archive', 'label' => 'Upload a specific xml Archive file' ); ?>

               <?php  $brafton_options->settings_xml_upload($args); ?>

            </div>

    </form>



    </div><!--- .brafton-options -->

</div><!-- .wrap -->

 

<?php echo '<script type="text/javascript">

        jQuery(document).ready(function($) {

            var sections = [];';

            

            foreach ( $sections as $section_slug => $section )

                echo "sections['$section'] = '$section_slug';";

            

            echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");

            wrapped.each(function() {

                $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));

            });

            $(".ui-tabs-panel").each(function(index) {

                $(this).attr("id", sections[$(this).children("h3").text()]);

                if (index > 0)

                    $(this).addClass("ui-tabs-hide");

            });

            $(".ui-tabs").tabs({

                fx: { opacity: "toggle", duration: "fast" }

            });

            

            $("input[type=text], textarea").each(function() {

                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")

                    $(this).css("color", "#999");

            });

            

            $("input[type=text], textarea").focus(function() {

                if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {

                    $(this).val("");

                    $(this).css("color", "#000");

                }

            }).blur(function() {

                if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {

                    $(this).val($(this).attr("placeholder"));

                    $(this).css("color", "#999");

                }

            });

            

            $(".wrap h3, .wrap table").show();

            

            // This will make the "warning" checkbox class really stand out when checked.

            // I use it here for the Reset checkbox.

            $(".warning").change(function() {

                if ($(this).is(":checked"))

                    $(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");

                else

                    $(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");

            });

            

            // Browser compatibility

            if ($.browser.mozilla) 

                     $("form").attr("autocomplete", "off");

        });

    </script>';

?>   
>>>>>>> 1.3.5
