<?php

// TODO : ajouter gestion exception dans chaque fonction

namespace Syntropia;
include_once 'GpioException.php';
include_once 'ShellException.php';

class GpioAccess
{
	
	private $_pin;
	
	
	/******************************************************************************
	 * Constructeur, requiert un numero de pin
	 ******************************************************************************/
	public function __construct($pin) // $number = int
	
	{
	
		// note : ajouter des controles
		$this->_pin=$pin;
	
	}

	/******************************************************************************
	 * modifie un gpio en mode "out"
	 * renvoie un array qui contient la sortie de la commande
	 ******************************************************************************/
	public function Out()
	
	{
		
		$command = 'gpio -g mode ' . $this->_pin . ' out' ;
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			echo 'return_var = ' . $return_var . '<br>';
			echo 'commande = ' . $command . '<br>';
			print_r($sortie_script);
			throw new \ShellException('GpioAccess => Out() => erreur lors de l\'execution de la commande shell - code retour : ' . $return_var);
		}
		
		return $sortie_script;
		
	}
	
	/******************************************************************************
	 * modifie un gpio en mode "in"
	 * renvoie un array qui contient la sortie de la commande
	 ******************************************************************************/
	public function In()
	
	{
		
		$command = 'gpio -g mode ' . $this->_pin . ' in' ;
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => In() => exception shell - code retour : ' . $return_var);
		}
		
		return $sortie_script;
	
	}
	
	/******************************************************************************
	 * modifie un gpio en mode "high" (1)
	 * renvoie un array qui contient la sortie de la commande
	 ******************************************************************************/
	public function High()
	
	{

		$command = 'gpio -g write ' . $this->_pin . ' 1' ;
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => High() => exception shell - code retour : ' . $return_var);
		}
		
		return $sortie_script;
	
	}
	
	/******************************************************************************
	 * modifie un gpio en mode "low" (0)
	 * renvoie un array qui contient la sortie de la commande
	 ******************************************************************************/
	public function Low()
	
	{

		$command = 'gpio -g write ' . $this->_pin . ' 0' ;
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => Low() => exception shell - code retour : ' . $return_var);
		}
		
		return $sortie_script;
		
	}
	
	/******************************************************************************
	 * retourne la "value" de l'objet (1 ou 0, soit 'high' ou 'low')
	 ******************************************************************************/
	public function getValue()
	{
		
		$command = 'gpio -g read ' . $this->_pin ;
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => getValue() => exception shell - code retour : ' . $return_var);
		}
		
		// lit la sortie standard de la commande d'etat du GPIO
		foreach($sortie_script as $ligne)
		{
			if ($ligne == '1')
			{
				return 1;
			}
			else if ($ligne == '0')
			{
				return 0;
			}
			else return -1;
		}
	
	}

	/******************************************************************************
	 * retourne le numero de pin de l'objet (soit le numero de gpio)
	 ******************************************************************************/
	public function getPinNumber()
	{
		return $this->_pin;
	}
	
	
	/******************************************************************************
	 * execute une commande et retourne la sortie
	 * genere une exception adaptee si errorlevel
	 * PAS ENCORE IMPLEMENTE
	 ******************************************************************************/
	private function gpioExe ($commande)
	{
		
		exec($commande, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			// TODO : faire un Switch en fonction du code retour, et genere le message approprie
			throw new \ShellException('GpioAccess => gpioExe() => exception shell - code retour : ' . $return_var);
		}
		
		return $sortie_script;
	
	}
	

}

?>