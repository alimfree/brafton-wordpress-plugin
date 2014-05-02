<?php

class Brafton_Errors {

	public $brafton_errors_option;
	private static $instance = null;

	private final function __construct( Brafton_Options $brafton_options )
	{
		$this->brafton_options = Brafton_Options::get_instance();	
		$brafton_errors = array( 'Type' => 'Message');
		add_option('brafton_errors_log', $brafton_errors );
	}

	//prevent direct instantiation by cloning. 
	private final function __clone(){}
	private final function __sleep() {
		throw new Exception('Serializing of Singletons is not allowed');
	}

	/**
	 * You must access instantiate this class using this method.
	 */
	public static function get_instance() {
		if( self::$instance === null) 
			self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Store log message in options field 'brafton_errors'
	 */
	public function log($message ) {

		$this->brafton_errors_option =  $this->brafton_options->get_option('brafton_error_log');

 		if ( $this->brafton_options->get_option('brafton_errors') == 'on' ) {
			// $message expected to be an array including error type and msg
        	if (is_array($message) || is_object($message)) {
        		$brafton_errors_option[$this->type] = $message;
        	   	update_option( 'brafton_error_log', serialize( $this->brafton_errors_option ) );
       		} 
       		else {
       			//send the error message to web server defined error handling routine
            	error_log($message);
        	}
    	}
	}

	public function display() {
		$this->brafton_errors_option = get_option('brafton_errors');
		foreach( $this->brafton_errors_option as $error => $value)
		{
			#todo
		}
	}
}
?>