<?php
namespace syntropia;
include_once 'gpio.php';


// D�tecte si requete post et bons arguments, auquel cas d�clenche l'acces GPIO :
if (isset($_POST['pin']) and isset($_POST['state']))
{

	try
	{
		
		// R�cup�re les param�tres
		$pin = $_POST['pin'];
		$state = $_POST['state'];
		
		// Instancie un Gpio
		$postGpio = new Gpio($pin, $state);
		
		// Modifie l'�tat du Gpio, avec comme d�lai 0 puisqu'il s'agit d'un objet de type Lumiere
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
	 * toString : red�fini pour afficher un bouton en jQuery
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
  		<th><img id="NeonGarage" src="img/Lumiere-off.png"></img></th>
  	</tr>
</table>
				
					<script type="text/javascript">
						$(document).ready(function()
						{
				
							/* Instructions T�moin */
							 $("#zonetoto").hide(1500);
				
							/* Instructions R�elles */
							$("#NeonGarage").on(\'click\', function(even) {
				
								$("#zonetoto").load(\'Lumiere.php\',{ pin:17, state:\'low\'});
				
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
  		<th><img id="NeonGarage" src="img/Lumiere-on.png"></img></th>
  	</tr>
</table>

					<script type="text/javascript">
						$(document).ready(function()
						{
				
							/* Instructions T�moin */
							 $("#zonetoto").hide(1500);
				
							/* Instructions R�elles */
							$("#NeonGarage").on(\'click\', function(even) {
								
								$("#zonetoto").load(\'Lumiere.php\',{ pin:17, state:\'high\'});
								
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
