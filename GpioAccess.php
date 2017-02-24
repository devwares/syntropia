<?php

// namespace Syntropia;

class GpioAccess
{

	private $_name;
	private $_number;

	public function __construct($name, $number) // $name = string, $number = int
	{

		// note : ajouter des controles
		$this->_name=$name;
		$this->_number=$number;

		// incremente le nombre de gpios initialises
		self::$_number_of_gpios = self::$_number_of_gpios + 1 ;

	}

}
	
	
?>