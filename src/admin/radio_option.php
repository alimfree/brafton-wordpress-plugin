<?php
/**
* Removing redundancy from ghost
* @author Ali
* 
*/


/**
* @var String 
*/
 public string $name; 

 /**
 * Radio object is a two dimensional array 
 * ex:
 * array('name' => 'foo', 
 * 		'options' => array (
 * 							'value' => ' bar', 
 *							'value2' => ' bum'), 
 *      'default' => 'value'
 * )
 */ 
 public $value; 

class Radio extends Option
{

	
	public function validate( Radio $option ){
 
	}
	/**
	 * @uses Brafton_Options to return $option argument from serialized options group in the database
	 * @param String $option 
	 */
	public function get( Brafton_Options $brafton_options, Radio $name )
	{
		$value = $brafton_options->get_option( $option['name'], $this->type );

	}

	/**
	* @usedby to register admin setting without storing value in the database
	*/
	public function set( $option_group,  $args )
	{ 
		$this->option = $args;
		register_setting( 
			$option_group,
			$this_option['name'], 
			array(&$this, 'validate')
			); 
	}
}

?>