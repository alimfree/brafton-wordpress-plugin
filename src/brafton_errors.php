<?php
/**
 * 
 * Plugin error handling and import failure debugging methods. 
 * Developer option Errors must be enabled to use these.
 * @author Ali <techsupport@brafton.com>
 * @package Brafton Importer
 * 
 */

/**
 * Use of this method assumes the existance of a log object stored in a wp options field 
 * Initialize a new log object with brafton_initialize_log()
 * 
 * Adds a new report object to a pre-existing log object serialized in wp database. 
 * 
 * @param Array $report 
 */

define("BRAFTON_ERROR_LOG", "brafton_error_log");
function brafton_log( $report ) {
    //Store all reports in brafton_error_log by default
    $brafton_default_report = array(
                        'option' => BRAFTON_ERROR_LOG, //option name errors are stored in wp
                        'notice' => '',  //admin notice message
                        'priority' => 0, //0 - log only when brafton errors enabled; 1- log regardless if errors are enabled
                        'message' => '',  //log entry message.
                    );


    // Parse and merge given $report with $defaults
    $report = wp_parse_args($report, $brafton_default_report);
	//retrieve log from wp options table.
	$log =  get_option( BRAFTON_ERROR_LOG, $report );
    $brafton_options = Brafton_Options::get_instance();
	// $report expected to be an array or an object. 
	if ( is_array( $report ) || is_object( $report ) ) {
        switch( $report['priority'] ){
            // store messages indefinately or until log limit is reached if brafton error reporting is enabled
            case 0: 
                //if brafton error reporting is enabled or priority is 1
                if ( $brafton_options->options['brafton_enable_errors'] == 'on' )
                    add_brafton_log_entry( $log, $report );
                break;
            //store messages indefinately or until log option limit is reached regardless of brafton error reporting.
            case 1: 
                add_brafton_log_entry( $log, $report );
                break;
        }
    }    
	else 
        //Blame web server. Send the error report to appropriate error handling routine defined by web server.
    	error_log( $report ); 
}

/**
 * Not intended to be used directly. Exists to avoid repetitive code and 
 * excessive nested if statements in brafton_log function.
 * @param Array $log
 * @param Array $report 
 */
function add_brafton_log_entry($log, $report) {
    $report['message'] = date("m/d/Y h:i:s A") . " - " . $report['message'] . "\n";
    if( $log['limit'] == NULL || $log['limit'] < $log['count'] )
    {
        //push new message to front of old log array.
        array_unshift( $log['entries'], $report );
        $log['count']++;
        //update the log option in wp database  
        update_option( $report['option'], $log ); 
    }
     //log report capacity has been reached
    else{
        //overwrite old previous log entries with empty array
        $log['entries'] = array();
        //reset counter
        $log['count'] = 0;
        //push new message to front of old log array.
        array_unshift( $log['entries'], $report );
        $log['count']++;
        //update the log option in wp database  
        update_option( $report['option'], $log ); 
    }
}

/**
 * Initializes a new log object. Can also be used to overwrite an existing log. 
 * 
 * @param String $option
 * @param Array $log
 */
function brafton_initialize_log($option, $log = NULL ){
    //retrieve old log if one exists.
    $log = get_option( $option , $log );

    $brafton_default_log = array(
                            'count' => 0, //number of reports stored. Empty initially.
                            'limit' => 2000, //ingeger -limit log entries capacity
                            'priority' => 0, //0 - log entries only when brafton errors enabled; 1 -log entries always
                            'entries' => array() //array of report objects
                        );
    //parse $log and merge into default log array.
    $log = wp_parse_args( $log, $brafton_default_log );
    //initialize log field. 
    $option_value =  update_option( $option, $log );
}

/**
 * Display admin notices for all log entries with notice key set.
 */
 function brafton_admin_notice() {
     #todo
}

?>