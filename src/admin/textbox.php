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
 * TextBox object two dimensional array 
 * ex:
 * array('name' => 'foo', 
 * 		'options' => array (
 * 							'value' => ' bar', 
 *							'value2' => ' bum'), 
 *      'default' => 'value'
 * )
 */ 
 public $value; 

class TextBox extends Option
{

	
	function validate( TextBox $option ){
 
	}

	/**
	* @used to register admin setting without storing value in the database
	* example $option_group for plugin option page is brafton_plugin_options used to be WP_Brafton_Article_Importer
	* @reference http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	*/
	function set( $option_group,  $args )
	{ 
		$this->option = $args;
		register_setting( 
			$option_group,
			$this_option['name'], 
			array(&$this, 'validate')
			); 
	}

	public function set( $args )
	{
		$this->name = $args; 
		register_setting( 'option_group', 
							'name' => $this->name
			);
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