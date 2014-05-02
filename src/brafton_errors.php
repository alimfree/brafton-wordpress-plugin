<?php

class Brafton_Errors {

	public $brafton_errors_option;

	function __construct( Brafton_Options $brafton_options )
	{
		$this-
		$this->brafton_errors_option = get_option( 'brafton_errors' );	
	}

	function log($message ) {
 		if (get_option('brafton_errors') == 'on' ) {
			// $message expected to be an array including error type and msg
        	if (is_array($message) || is_object($message)) {
        		$this->brafton_errors_option[$this->type] = $message;
        	   	update_option( 'brafton_errors', serialize( $this->brafton_errors_option ) );
       		} 
       		else {
       			//send the error message to web server defined error handling routine
            	error_log($message);
        	}
    	}
	}

	function display() {

	}
}
?>