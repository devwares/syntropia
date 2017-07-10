<?php
namespace syntropia;
include_once 'gpio.php';

//$commande = 'echo $state='.$state.' >> /tmp/toto.txt';
//exec($commande);

class Lumiere extends Gpio
{

	/******************************************************************************
	 * toString : redéfini pour afficher un bouton en jQuery
	 ******************************************************************************/
	public function __toString()
	{
		
		try
		{
			
			$nomdiv = 'div' . $this->_name;
			$idimg = 'img' . $this->_name;
			$pin = $this->_pin;
			$label = $this->_label;
			
			// Gpio High = Lumiere actuellement eteinte
			if ($this->_gpioaccess->getValue()==1)
			{
				$image = 'img/lumiere-off.png';
				$nextstate = '0';
			}
			// Gpio Low = Lumiere actuellement allumee
			elseif($this->_gpioaccess->getValue()==0)
			{
				$image = 'img/lumiere-on.png';
				$nextstate = '1';
			}
			// Gpio -1 = erreur ?
			elseif($this->_gpioaccess->getValue()==-1)
			{
				$retour='Erreur : etat anormal du GPIO';
				return $retour;
			}
				
			$retour='
					<div id="' . $nomdiv . '">
						<div id="zonetoto">Cette ligne disparaitra si le javascript fonctionne</div>
					
						<table>
							<tr>
						  		<th>Neon Garage</th>
						 	</tr>
						 	<tr>
						  		<th><img id="' . $idimg . '" src="' . $image . '"></img></th>
						  	</tr>
						</table>
						  				
						<script type="text/javascript">
							$(document).ready(function()
							{
					
								/* Instructions Témoin */
								 $("#zonetoto").hide(1500);
					
								/* Instructions Réelles */
								$("#' . $idimg . '").on(\'click\', function(even) {
									
									/* $.post(\'lumiere.php\',{ pin:' . $pin . ', state:0}); */
									$("#' . $nomdiv . '").load(\'lumiere.php\',{ pin:' . $pin . ', state:' . $nextstate . '});
							
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
if (isset($_POST['pin']) and isset($_POST['state']))
{

	try
	{

		// Récupère les paramètres
		$pin = $_POST['pin'];
		$state = $_POST['state'];

		// Instancie un Gpio
		$lumiere = new Lumiere('gpioLumiere', $pin);

		// Modifie l'état du Gpio, avec comme délai 0 puisqu'il s'agit d'un objet de type Lumiere
		$lumiere->setState($state, 0);

		// Affiche le nouvel interrupteur
		echo $lumiere;

	}
	catch (Exception $e)
	{
		throw new \GpioException('Lumiere Exception => ' . $e);
	}

}

?>
