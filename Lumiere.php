<?php
namespace Syntropia;
include_once 'Gpio.php';


// Détecte si requete post, et bons arguments, auquel cas déclenche l'acces GPIO :
if (isset($_POST['pin']) and isset($_POST['state']))
{

	try
	{
		
		// Récupère les paramètres
		$pin = $_POST['pin'];
		$state = $_POST['state'];
		
		// Instancie un Gpio
		$postGpio = new Gpio($pin, $state);
		
		// Modifie l'état du Gpio, avec comme délai 0 puisqu'il s'agit d'un objet de type Lumiere
		$postGpio->setState($state, 0);
		
		echo "c bon";
		
	}
	catch (Exception $e)
	{
		throw new \GpioException('Lumiere Exception => ' . $e);
	}
	
}

// Partie objet pour instantiation par autre classe/page
class Lumiere extends Gpio
{

	/******************************************************************************
	 * toString : redéfini pour afficher un bouton en jQuery
	 ******************************************************************************/
	public function __toString()
	{
		
		try
		{
			//$retour='name='.$this->_name.', pin number='.$this->_pin. ', label='.$this->_label.', state='.$this->_gpioaccess->getValue();
			
			$retour='toto';
			if ($this->_gpioaccess->getValue()==1)
			{
				$retour='High';
			}
			elseif($this->_gpioaccess->getValue()==0)
			{
				$retour='Low';
			}
			elseif($this->_gpioaccess->getValue()==-1)
			{
				$retour='-1';
			}
			
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

}

?>