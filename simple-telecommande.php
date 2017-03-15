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
 * TODO : commencer à faire une page test2/test3 en ne prenant que les 8 gpio du relai
 * 
 * TODO : 	1) faire classe Interrupteur qui herite de Gpio ?
 * 			réécrire méthodes qui allume et éteigne
 * 			exemple pour bouton lumiere ext : off -> sleep 1 -> on
 * 
 * 			ou 2) ajouter méthode "clignoter" et "appui court" ?
 * 
 * TODO :	Ajouter Out et In avec radiobuttons
 */

/*	$command = 'gpio -g mode ' . $data['gpio_number'] . ' out && gpio -g write ' . $data['gpio_number'] . ' ' . $data['gpio_state'] ;
	exec($command, $sortie_script, $return_var); */

namespace Syntropia;

include_once 'Gpio.php';
include_once 'GpioAccess.php';
include_once 'GpioException.php';
include_once 'ShellException.php';

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

<?php 

/******************************************************************************
 * Instancie systematiquement tous les gpios à utiliser
 ******************************************************************************/
$tab_gpio[] = new Gpio('neon-garage', 17);
$tab_gpio[] = new Gpio('prise-garage', 18);
$tab_gpio[] = new Gpio('lumiere-exterieure', 27);


foreach ( $tab_gpio as $gpio )
{
	echo $gpio;
}

echo '<br><br>';

/******************************************************************************
* Si requete de type POST validee
******************************************************************************/
if (isset($_POST['gpio_post_request']) and $_POST['gpio_post_request']=='true')
{
	// boucle sur les gpio instancies
	foreach ( $tab_gpio as $gpio )
	{
		
		$name = $gpio->getName();
		
		// verifie si le gpio est inclu dans la requete post
		// exemple : "gpio17_state=low"
		if (isset($_POST[$name .'_state']))
		{
	
			// stocker le nb de secondes de temporisation dans $tempo, sinon $tempo = 0
			// exemple : "gpio18_tempo=60"
			if(isset($_POST[$name . '_tempo'])) $tempo = $_POST[$name .'_tempo']; else $tempo = 0;
			
			// appelle fonction mod_gpio avec true ou false selon que requete contienne 'high' ou 'low'
			if ($_POST[$name . '_state'] == 'high') mod_gpio($gpio, true, $tempo); else if ($_POST[$name . '_state'] == 'low') mod_gpio($gpio, false, $tempo);
			
		}
	
	}
}




/******************************************************************************
 * modifie l'etat d'un gpio specifié en parametre
 ******************************************************************************/
function mod_gpio(Gpio $gpio, $gpio_state, $tempo)
{
	
	try
	{
		$gpio->setState($gpio_state, $tempo);
	}
	catch (\GpioException $e)
	{
		echo 'fonction mod_gpio : ';
		echo $e ;
		die();
	}
	catch (\ShellException $e)
	{
		echo 'fonction mod_gpio : ';
		echo $e ;
		echo '<br>' . $gpio;;
		die();
	}
	
}

?>

<form action="simple-telecommande.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="true" name="gpio_post_request" />
	<input type="hidden" value="low" name="neon-garage_state" />
	<input type="submit" value="ALLUMER NEON GARAGE" />
</form>

<br>

<form action="simple-telecommande.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="true" name="gpio_post_request" />
	<input type="hidden" value="high" name="neon-garage_state" />
	<input type="submit" value="ETEINDRE NEON GARAGE" />
</form>

<br><br>

<form action="simple-telecommande.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="true" name="gpio_post_request" />
	<input type="hidden" value="low" name="lumiere-exterieure_state" />
	<input type="hidden" value=".3" name="lumiere-exterieure_tempo" />
	<input type="submit" value="SWITCHER LUMIERE EXTERIEURE" />
</form>

<br><br>

<form action="simple-telecommande.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="true" name="gpio_post_request" />
	<input type="hidden" value="low" name="prise-garage_state" />
	<input type="submit" value="ALLUMER PRISE GARAGE" />
	duree (0 = infini) : <input type="text" name="prise-garage_tempo" value=0>
</form>

<br>

<form action="simple-telecommande.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="true" name="gpio_post_request" />
	<input type="hidden" value="high" name="prise-garage_state" />
	<input type="submit" value="COUPER PRISE GARAGE" />
</form>

</body>

</html>
