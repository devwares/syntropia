<?php
namespace syntropia;
include_once 'gpio.php';

//$commande = 'echo $state='.$state.' >> /tmp/toto.txt';
//exec($commande);

class Interrupteur extends Gpio
{

	/******************************************************************************
	 * toString : redéfini pour afficher un bouton en jQuery
	 ******************************************************************************/
	public function __toString()
	{
		
		try
		{
			$name = $this->_name;
			$iddiv = 'div' . $name;
			$idimg = 'img' . $name;
			$pin = $this->_pin;
			$label = $this->_label;
			$state = $this->_gpioaccess->getValue();
			
			// Gpio High = Interrupteur actuellement eteint
			if ($state==1)
			{
				$image = 'img/interrupteur-off.png';
				$nextstate = '0';
			}
			// Gpio Low = Interrupteur actuellement allume
			elseif($state==0)
			{
				$image = 'img/interrupteur-on.png';
				$nextstate = '1';
			}
			// Gpio -1 = erreur ?
			elseif($this->_gpioaccess->getValue()==-1)
			{
				$retour='Erreur : etat anormal du GPIO';
				return $retour;
			}
				
			$retour='
				<div id="' . $iddiv . '">					
					<img style="width:100%;" id="' . $idimg . '" src="' . $image . '"></img>
						
					<script type="text/javascript">
					$(document).ready(function()
						{
				
							$("#' . $idimg . '").on(\'click\', function(even) {
								
								$("#' . $iddiv . '").load(\'interrupteur.php\',{ name:"' . $name . '", pin:' . $pin . ', label:"' . $label . '", state:' . $nextstate . '});
							});			
						});
					</script>
				</div>
				';
				
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


// Détecte si requete post et bons arguments, auquel cas déclenche l'acces GPIO :
if (isset($_POST['name']) and isset($_POST['pin']) and isset($_POST['state']) and isset($_POST['label']))
{

	try
	{

		// Récupère les paramètres
		$name = $_POST['name'];
		$pin = $_POST['pin'];
		$state = $_POST['state'];
		$label = $_POST['label'];

		// Instancie un Gpio
		$interrupteur = new Interrupteur($name, $pin, $label);

		// Modifie l'état du Gpio, avec comme délai 0 puisqu'il s'agit d'un objet de type Interrupteur
		$interrupteur->setState($state, 0);

		// Affiche le nouvel interrupteur
		echo $interrupteur;

	}
	catch (Exception $e)
	{
		throw new \GpioException('Interrupteur Exception => ' . $e);
	}

}

?>
