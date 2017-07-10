<?php

	namespace syntropia;
	
	include_once 'lumiere.php';
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
		
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
		<!-- <script type="text/javascript" src="js/telecommande.js"></script>  -->
	
	</head>

	<body>

		<?php 
		
		/******************************************************************************
		 * Instancie les GPIO à utiliser
		 ******************************************************************************/
		$neonGarage = new Lumiere('neonGarage', 17, 'Neon Garage');
		
		/******************************************************************************
		 * Incorpore les interrupteurs, en fonction de l'état des GPIOs
		 ******************************************************************************/
		echo $neonGarage;
		
		?>

	</body>
	
</html>
