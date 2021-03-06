<?php

namespace syntropia;
include_once 'gpioexception.php';
include_once 'shellexception.php';

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
	public function High($tempo) // attend une temporisation en secondes
	
	{

		// 1ere commande : passer en etat 1

		$command = 'gpio -g write ' . $this->_pin . ' 1' ;
		
		exec($command, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => High() => exception shell "' . $command . '" - code retour : ' . $return_var);
		}


		// 2e commande (si $tempo > 0) : respecter une pause et remettre en etat 0
		
		if ($tempo > 0)
		{

			// todo : ajouter contr�le dur�e
			// todo : tester en background pour lib�rer la page ("&")
			$command = 'sleep ' . $tempo . ' && gpio -g write ' . $this->_pin . ' 0' ;

			exec($command, $sortie_script, $return_var);
			
			if ($return_var != 0)
			{
				// si la commande retourne une erreur, genere une exception
				throw new \ShellException('GpioAccess => High() => exception shell "' . $command . '" - code retour : ' . $return_var);
			}

		}
		
		return $sortie_script;
	
	}
	
	/******************************************************************************
	 * modifie un gpio en mode "low" (0)
	 * renvoie un array qui contient la sortie de la commande
	 ******************************************************************************/
	public function Low($tempo)
	
	{

		// 1ere commande : passer en etat 0
		
		$command = 'gpio -g write ' . $this->_pin . ' 0';
		exec($command, $sortie_script, $return_var);
		
		// fonctionne : $command2 = 'sleep 1 && gpio -g write ' . $this->_pin . ' 1' ;
		//	exec($command2, $sortie_script, $return_var);
		
		if ($return_var != 0)
		{
			// si la commande retourne une erreur, genere une exception
			throw new \ShellException('GpioAccess => Low() => exception shell "' . $command . '" - code retour : ' . $return_var);
		}
		
		// 2e commande (si $tempo > 0) : respecter une pause et remettre en etat 1
		
		if (floatval($tempo) > 0)
		{
		
			// todo : ajouter contr�le dur�e
			$command = 'nohup sleep ' . $tempo . ' && gpio -g write ' . $this->_pin . ' 1 &' ;
		
			exec($command, $sortie_script, $return_var);
				
			if ($return_var != 0)
			{
				// si la commande retourne une erreur, genere une exception
				throw new \ShellException('GpioAccess => Low() => exception shell "' . $command . '" - code retour : ' . $return_var);
			}
		
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
