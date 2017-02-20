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

class Gpio
{

	public static $_number_of_gpios = 0;
	private $_name;
	private $_number;
	private $_state;

	public function __construct($name, $number, $state) // $name = string, $number = int, $state = boolean

	{		
		
		// note : ajouter des controles
		$this->_name=$name;
		$this->_number=$number;
		$this->_state=$state;
		
		// incremente le nombre de gpios initialises
		self::$_number_of_gpios = self::$_number_of_gpios + 1 ;
		
		/*******************************************************************************************
		 * modifie l'etat du gpio via une commande shell
		 *******************************************************************************************/ 
		if ($state)
		{
			$state_parameter='1';
		}
		else
		{
			$state_parameter='0';
		}
		
		$command = 'gpio -g mode ' . $number . ' out && gpio -g write ' . $number . ' ' . $state_parameter;
		
		exec($command, $sortie_script, $return_var);
		
		// exception en cas d'errorlevel different de 0
		if ($return_var != 0)
		{
			throw new GpioException('GPIO access error : ' . $sortie_script);
		}
		
	}

	public function set_state($state) // attend un booleen

	{
		// initialise le booleen d'instance $_state
		$this->_state=$state;
		
		// initialise le parametre a passer a la commande shell selon l'etat
		if ($state)
		{
			$state_parameter='1';
		}
		else
		{
			$state_parameter='0';
		}
		
		// execute la commande shell pour modifier l'etat du gpio
		
		$command = 'gpio -g mode ' . $this->_number . ' out && gpio -g write ' . $this->_number . ' ' . $state_parameter ;
		exec($command, $sortie_script, $return_var);
		
		// exception en cas d'errorlevel different de 0
		if ($return_var != 0)
		{
			throw new GpioException('GPIO access error : ' . $sortie_script);
		}
		
	}
	
	public function get_state()
	
	{
		// recupere l'etat physique du gpio via une commande shell, plutot que de se fier à l'etat de l'objet
		$command = 'gpio -g read ' . $this->_number;
		exec($command, $sortie_script, $return_var);
		
		// 
		if ($return_var != 0)
		{
			throw new GpioException('GPIO access error : ' . $sortie_script);
		}
		
		
		// lit la sortie standard de la commande d'etat du GPIO
		foreach($sortie_script as $ligne)
		{
			if ($ligne == '1')
			{
				$this->_state=true;
				return true;
			}
			else if ($ligne == '0')
			{
				$this->_state=false;
				return false;
			}
		}
		
		// si etat GPIO ni 0 ni 1, retourne -1
		return -1;
		
	}
	
}

?>
