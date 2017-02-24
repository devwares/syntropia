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
 * TODO : Deleguer l'acces direct au GPIO a une classe modele "GpioAccess"
 * TODO : limite le nombre d'objets possibles en fonction du modele de raspberry
 * 
 */
namespace Syntropia;
include_once 'GpioAccess.php';
include_once 'GpioException.php';
include_once 'ShellException.php';

class Gpio
{

	private static $_number_of_gpios = 0;
	private $_name;
	private $_pin;

	public function __construct($name, $pin) // $name = string, $pin = int

	{		
		
		// note : ajouter des controles
		$this->_name=$name;
		$this->_pin=$pin;
		
		// instancie un GpioAccess
		$this->_gpioaccess=new GpioAccess($pin);
		
		// incremente le nombre de gpios initialises
		self::$_number_of_gpios = self::$_number_of_gpios + 1 ;
		
	}

	public function __toString()
	{
		// $retour='name='.$this->_name.', pin number='.$this->_pin.', state='.$this->getState();
		
		try
		{
			$retour='name='.$this->_name.', pin number='.$this->_pin.', state='.$this->_gpioaccess->getValue();
		}
		catch (GpioException $e)
		{
			throw new \GpioException('GPIO Exception => ' . $e);
		}
		catch (ShellException $e)
		{
			throw new \ShellException('Shell Exception => ' . $e);
		}
		
		return $retour;
	}
	
	public function setState($state) // attend un booleen

	{
		
		// initialise le parametre a passer a la commande shell selon l'etat
		
		try
		{
			
			if ($state)
			{
				//$state_parameter='1';
				$this->_gpioaccess->Out();
				$this->_gpioaccess->High();
			}
			else
			{
				//$state_parameter='0';
				$this->_gpioaccess->Out();
				$this->_gpioaccess->Low();
			}
		
		}
		catch (\GpioException $e)
		{
			throw new \GpioException('impossible de changer l\'etat du gpio => ' . $e);
		}
		catch (\ShellException $e)
		{
			throw new \ShellException('probleme shell => ' . $e);
		}
		
	}
	
	public function getState()
	
	{
		// recupere l'etat physique du gpio via une commande shell, plutot que de se fier à l'etat de l'objet
		$command = 'gpio -g read ' . $this->_pin;
		exec($command, $sortie_script, $return_var);

		// 
		if ($return_var != 0)
		{
			throw new \GpioException('erreur script code retour' . $return_var);
		}
		
		
		// lit la sortie standard de la commande d'etat du GPIO
		foreach($sortie_script as $ligne)
		{
			if ($ligne == '1')
			{
				return true;
			}
			else if ($ligne == '0')
			{
				return false;
			}
		}
		
		// si etat GPIO ni 0 ni 1, retourne -1
		return -1;
		
	}
	
	public static function getNumber()
	
	{
		return self::$_number_of_gpios;
	}
	
}




?>
