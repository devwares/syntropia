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

	
	/******************************************************************************
	 * Constructeur : attend une chaine et un numero de pin pour le gpio
	 * todo : ctrl presence package wiringpi ici ou dans gpioaccess ?
	 ******************************************************************************/
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

	/******************************************************************************
	 * toString : utilise pour afficher l'etat de l'objet
	 ******************************************************************************/
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
	
	
	/******************************************************************************
	 * Modifie l'etat d'un gpio pendant n secondes (n = 0 pour infini)
	 ******************************************************************************/
	public function setState($state, $delay) // attend un booleen, et un délai (0 = infini)

	{
		
		try
		{
			$this->_gpioaccess->Out();
			// initialise le parametre a passer a la commande shell selon l'etat
			if ($state) {$this->_gpioaccess->High();} else {$this->_gpioaccess->Low();}
		}
		
		catch (\GpioException $e)
		{
			throw new \GpioException('Classe Gpio => setState => probleme gpio => ' . $e);
		}
		
		catch (\ShellException $e)
		{
			throw new \ShellException('Classe Gpio => setState => probleme shell => ' . $e);
		}
		
	}
	
	/******************************************************************************
	 * Renvoie l'etat de la "value" du gpio : true pour 1, false pour 0
	 ******************************************************************************/
	public function getState()
	
	{
		
		try
		{
			$valeurGpio = $this->_gpioaccess->getValue();
			// si gpio valeur = 1, retourne true, si valeur = 0 retourne false, sinon -1
			if ($valeurGpio == 1) return true; else if ($valeurGpio=='0') return false;
			else throw new \GpioException('Classe Gpio => getState => probleme valeur gpio => ' . $e);
		}
		catch (\ShellException $e)
		{
			throw new \ShellException('Classe Gpio => getState => probleme shell => ' . $e);
		}
		
	}
	
	/******************************************************************************
	 * Renvoie le nombre total d'objets instancies
	 ******************************************************************************/
	public static function getNumber()
	
	{
		return self::$_number_of_gpios;
	}
	
}




?>
