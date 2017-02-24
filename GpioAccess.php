<?php

class GpioAccess

{
	
	private $_pin;
	
	public function __construct($pin) // $number = int
	
	{
	
		// note : ajouter des controles
		$this->_pin=$pin;
		
		// ajouter un controle pour voir si shell repond et gpio repond
	
	}

	public function Out()
	
	{
		// commande shell pour mettre mode out
	}
	
	public function In()
	
	{
		// commande shell pour mettre mode in
	}
	
	
	public function High()
	
	{
		// commande shell pour mettre value high
	}
	
	
	public function Low()
	
	{
		// commande shell pour mettre value low
	}
	
	public function getMode()
	{
		
	}
	
	public function getValue()
	{
		
	}
	
	
	
	
}

?>