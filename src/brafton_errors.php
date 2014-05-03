<?php
/**
 * 
 * Plugin error handling and import failure debugging methods. 
 * Developer option Errors must be enabled to use these.
 * @author Ali
 * @package Brafton Importer
 * @subpackage Errors
 * 
 */

/**
 * Stores an object or array in options field 'brafton_errors'
 */
function brafton_log( $message ) {
	//retrieve log from wp options table.
	$log =  get_option(BRAFTON_ERROR_LOG);
	#var_dump($log);
		// $message expected to be an array or an object. 
    	if (is_array( $message ) || is_object( $message )) {

    		if( ! empty($log) )
    			array_unshift($log, $message);
    		else
    			$log = $message;
    			#var_dump($log);
    	   	update_option( BRAFTON_ERROR_LOG , $log );
   		} 
   		else 
        	error_log( $message ); //send the error message to appropriate error handling routine defined by web server.
}
/**
 * Displays admin notices for important errors
 */
// public function admin_notice_errors() {
// 	$this->brafton_errors_option = get_option(ENABLE_ERRORS);
// 	foreach( $this->brafton_errors_option as $error => $value)
// 	{
// 		#todo
// 	}
// }
?>