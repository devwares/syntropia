<?php
namespace syntropia;
include_once 'gpio.php';

//$commande = 'echo $state='.$state.' >> /tmp/toto.txt';
//exec($commande);

class Telerupteur extends Gpio
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
			$image = 'img/telerupteur.png';

			$retour='
				<div id="' . $iddiv . '">
					<img style="width:100%;" id="' . $idimg . '" src="' . $image . '"></img>

					<script type="text/javascript">
					$(document).ready(function()
						{

							$("#' . $idimg . '").on(\'click\', function(even) {

								$("#' . $iddiv . '").load(\'telerupteur.php\',{ name:"' . $name . '", pin:' . $pin . ', label:"' . $label . '"});
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
if (isset($_POST['name']) and isset($_POST['pin']) and isset($_POST['label']))
{

	try
	{

		// Récupère les paramètres
		$name = $_POST['name'];
		$pin = $_POST['pin'];
		$label = $_POST['label'];

		// Instancie un Gpio
		$telerupteur = new Telerupteur($name, $pin, $label);

		// Modifie l'état du Gpio, avec comme délai 0.25 puisqu'il s'agit d'un objet de type Telerupteur
		$telerupteur->setState(0, .25);

		// Affiche le nouveau telerupteur
		echo $telerupteur;

	}
	catch (Exception $e)
	{
		throw new \GpioException('Telerupteur Exception => ' . $e);
	}

}

?>
