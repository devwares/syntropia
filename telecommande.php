<?php

	namespace syntropia;
	
	include_once 'interrupteur.php';
	include_once 'telerupteur.php';
	include_once 'gpio.php';
	include_once 'gpioaccess.php';
	include_once 'gpioexception.php';
	include_once 'shellexception.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
	
		<meta charset="ISO-8859-1">
		<title>Tests jQuery Gpio</title>
		
		<link rel="stylesheet" href="css/telecommande.css" />
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
		<!-- <script type="text/javascript" src="js/telecommande.js"></script>  -->
	
	</head>

	<body>

		<?php 
		
		/******************************************************************************
		 * Instancie les GPIO � utiliser
		 ******************************************************************************/
		$neonGarage = new Interrupteur('neonGarage', 17, 'Neon Garage');
		$lumiereExt = new Telerupteur('lumiereExt', 27, 'Lumiere Exterieure');
		
		/******************************************************************************
		 * Incorpore les interrupteurs, en fonction de l'�tat des GPIOs
		 ******************************************************************************/
		?><div id="bouton-neon-garage">
			<table>
				<tr><?php echo $neonGarage;?></tr>
				<tr>Neon Garage</tr>
				<tr><br><br><br><br><br><br><br><br><br><br></tr>
				<tr><?php echo $lumiereExt;?></tr>
				<tr>Lumiere Exterieure</tr>
			</table>
		</div>

	</body>
	
</html>
