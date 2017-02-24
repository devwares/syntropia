<?php
/*
 * test.php
 * 
 * Copyright 2017  <pi@gaia>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

/*	$command = 'gpio -g mode ' . $data['gpio_number'] . ' out && gpio -g write ' . $data['gpio_number'] . ' ' . $data['gpio_state'] ;
	exec($command, $sortie_script, $return_var); */

namespace Syntropia;

require 'Gpio.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>sans titre</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.24.1" />
</head>

<body>

CONTROLE DES GPIO<br></br>

<?php 

/******************************************************************************
 * Instancie systematiquement tous les gpios à utiliser
 ******************************************************************************/
$tab_gpio=init_gpios(26);

/******************************************************************************
 * Si requete de type POST validee, fait un "set" sur les objets selon params
 * TODO : faire du javascript / ajax à la place
 ******************************************************************************/
if (isset($_POST['gpio_post_request']) and $_POST['gpio_post_request']=='true')
{
	
	try 
	{
		
		// boucle sur chaque gpio instancie, et change son etat si la requete POST l'exige
		$max=Gpio::getNumber();
		for ($cpt=1; $cpt < $max; $cpt++)

		{
			if (isset($_POST['gpio'. $cpt]) AND $_POST['gpio' .$cpt] == 'on') mod_gpio($tab_gpio[$cpt], true); else mod_gpio($tab_gpio[$cpt], false);
		}

	}
	catch (GpioException $e)
	{
		echo 'fonction main : ';
		echo $e;
		die();
	}
	
}


/******************************************************************************
 * Instancie les gpio
 ******************************************************************************/
function init_gpios($max_gpio)
{

	for ($cpt_gpio=0; $cpt_gpio<$max_gpio ; $cpt_gpio++)
	{

		try
		{
			$tab_gpio[$cpt_gpio] = new Gpio('gpio_' . $cpt_gpio, $cpt_gpio);
		}
		catch (GpioException $e)
		{
			echo 'fonction init_gpios : ';
			echo $e ;
			die();
		}

	}
	
	return $tab_gpio;
	
}


/******************************************************************************
 * modifie l'etat d'un gpio specifié en parametre
 ******************************************************************************/
function mod_gpio(Gpio $gpio, $gpio_state)
{
	
	try
	{
		$gpio->setState($gpio_state);
	}
	catch (GpioException $e)
	{
		echo 'fonction mod_gpio : ';
		echo $e ;
		die();
	}
	
}

?>

echo 'tota';

	<form  action="test.php" method="post" enctype="multipart/form-data">

	<?php	
	
		// boucle sur tous les gpio instancies, et cree un label et une checkbox pour chaque
		$max=Gpio::getNumber();
		for ($cpt=1; $cpt < $max; $cpt++)

		{
			echo '<input type="checkbox" name="gpio'. $cpt . '" id="idcase' . $cpt . '" ';
			
			// verifie l'etat du gpio, coche la case si le resultat du getState est positif
			try
			{
				if ($tab_gpio[$cpt]->getState()) echo ' checked';
			}
			catch (GpioException $e)
			{
				echo 'fonction main (formulaire) : ';
				echo $e;
				die();
			}
			
			echo ' /> <label for="idcase' . $cpt . '">GPIO ' . $cpt . '</label><br>';
		}
		
	?>
	
		<!-- input cache pour savoir si un post a ete effectue depuis ce fichier -->
		<input type="hidden" value="true" name="gpio_post_request" />
		
		<br><br>
		
		<input type="submit" value="Modifier l'etat des GPIO" />

    </form>

</body>

</html>
