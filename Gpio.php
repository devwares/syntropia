<?php
/*
 * Gpio.php
 * 
 * Copyright 2017  <pi@gaia>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
 
 
 // gpio -g read <number>
 

class Gpio
{

	public static $_number_of_gpios = 0;
	private $_name;
	private $_number;
	private $_state;

	public function __construct($name, $number, $state) // $name = string, $number = int, $state = boolean

	{

		$this->_nom=$name;
		$this->_number=$number;
		$this->_state=$state;

		// incremente le nombre de gpios initialises
		self::$_number_of_gpios = self::$_number_of_gpios + 1 ;
		
	}

	public function set_state($state)

	{
		// initialise le booleen d'instance $_state
		$this->_state=$state;
		
		// execute la commande shell pour modifier l'etat du gpio
		$command = 'gpio -g mode ' . $this->_number . ' out && gpio -g write ' . $data['gpio_number'] . ' ' . $data['gpio_state'] ;
	exec($command, $sortie_script, $return_var);
		
	}
	
	public function get_state()
	
	{
		// recupere l'etat physique du gpio via une commande shell, plutot que de se fier à l'etat de l'objet
		$command = 'gpio -g read ' . $this->_number;
		exec($command, $sortie_script, $return_var);
		
		// 
		if ($return_var != 0)
		{
			throw new Exception('GPIO access error');
		}
		
		return($this->_state);
		
	}
	
}

?>
