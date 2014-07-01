<?php

public string $name
public string $type

class Option
{
	function __construct()
	{
		$this->type = $this->gettype();  
	}


	public function validate( Option $option ){
 
	}
	/**
	 * @uses Brafton_Options to return $option argument from serialized options group in the database
	 * @param String $option 
	 */
	public function get( Brafton_Options $brafton_options, Option $option)
	{
	
	}

	public function set( $args )
	{
	
	}

	public function get_name()
	{
		$return $this->name;
	}

	function get_type()
	{
		return $this->type; 
	}

}

?>