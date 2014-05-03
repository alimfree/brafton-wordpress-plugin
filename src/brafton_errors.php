<?php
/**
 * This class handles brafton wordpress plugin error handling. Any reported errors 
 * are stored as a serialized array in wp database in. 
 */
class Brafton_Errors {

	/**
	 * Array of errors 
	 */ 
	public $brafton_errors_option;
	/**
	 * Array Plugin Settings Options
	 */
	public $brafton_options;
	/**
	 * Brafton_Errors Object used to declare class once
	 */ 
	private static $instance = null;

	private final function __construct()
	{
		$this->brafton_options = Brafton_Options::get_instance();	
		$brafton_errors = array( 'type' =>'', 'message' => '', 'priority' => '' );
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

		$this->brafton_errors_option =  $this->brafton_options->get_option(ERRORS_OPTION);

 		if ( $this->brafton_options->get_option(ENABLE_ERRORS) == 'on' ) {
			// $message expected to be an array including error type and msg
        	if (is_array($message) || is_object($message)) {
        		$brafton_errors_option[$this->type] = $message;
        	   	update_option( ERRORS_OPTION, serialize( $this->brafton_errors_option ) );
       		} 
       		else {
       			//send the error message to web server defined error handling routine
            	error_log($message);
        	}
    	}
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
}
?>