<?php

namespace Syntropia;

class GpioAccess

{
	
	private $_pin;
	
	public function __construct($pin) // $number = int
	
	{
	
		// note : ajouter des controles
		$this->_pin=$pin;
		
		// ajouter un controle pour voir si shell et gpio repondent
	
	}

	public function Out()
	
	{
		// commande shell pour mettre mode out
		echo 'gpio -g mode <pin> out';
	}
	
	public function In()
	
	{
		// commande shell pour mettre mode in
		echo 'gpio -g mode <pin> in';
	}
	
	
	public function High()
	
	{
		// commande shell pour mettre value high
		echo 'gpio -g write <pin> 1';
	}
	
	public function Low()
	
	{
		// commande shell pour mettre value low
		echo 'gpio -g write <pin> 0';
	}
	
	public function getValue()
	{
		echo 'gpio -g read <pin>';
	}
	
}

?>