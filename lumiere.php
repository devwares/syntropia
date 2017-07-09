<?php
namespace syntropia;
include_once 'gpio.php';

//		$commande = 'echo postgpio = ' + $postGpio + ' > /tmp/testphp.txt';
//		exec($commande);		


// Détecte si requete post et bons arguments, auquel cas déclenche l'acces GPIO :
if (isset($_POST['pin']) and isset($_POST['state']))
{

	try
	{
		

		// Récupère les paramètres
		$pin = $_POST['pin'];
		$state = $_POST['state'];
		
		// Instancie un Gpio
		$postGpio = new Gpio('postgpio', $pin);
		
		// Modifie l'état du Gpio, avec comme délai 0 puisqu'il s'agit d'un objet de type Lumiere
		$postGpio->setState($state, 0);

		$commande = 'echo $state='.$state.' >> /tmp/toto.txt';
		exec($commande);

		
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
			
			// Gpio High = Lumiere actuellement eteinte
			if ($this->_gpioaccess->getValue()==1)
			{
				$retour='
					<div id="zonetoto">Cette ligne disparaitra si le javascript fonctionne</div>
						
						
<table>
	<tr>
  		<th>Neon Garage</th>
 	</tr>
 	<tr>
  		<th><img id="NeonGarage" src="img/lumiere-off.png"></img></th>
  	</tr>
</table>

					<script type="text/javascript">
						$(document).ready(function()
						{
				
							/* Instructions Témoin */
							 $("#zonetoto").hide(1500);
				
							/* Instructions Réelles */
							$("#NeonGarage").on(\'click\', function(even) {
								
								$("#zonetoto").load(\'lumiere.php\',{ pin:18, state:0});
								
							});
				
				
						});
					</script>
						';
			}
			
			// Gpio Low = Lumiere actuellement allumee
			elseif($this->_gpioaccess->getValue()==0)
			{
				$retour='
					<div id="zonetoto">Cette ligne disparaitra si le javascript fonctionne</div>
						
						
<table>
	<tr>
  		<th>Neon Garage</th>
 	</tr>
 	<tr>
  		<th><img id="NeonGarage" src="img/lumiere-on.png"></img></th>
  	</tr>
</table>

					<script type="text/javascript">
						$(document).ready(function()
						{
				
							/* Instructions Témoin */
							 $("#zonetoto").hide(1500);
				
							/* Instructions Réelles */
							$("#NeonGarage").on(\'click\', function(even) {
								
								$("#zonetoto").load(\'lumiere.php\',{ pin:18, state:1});
								
							});
				
				
						});
					</script>
						';
			}
			
			// Gpio -1 = erreur ?
			elseif($this->_gpioaccess->getValue()==-1)
			{
				$retour='Erreur : etat anormal du GPIO';
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
