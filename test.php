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

DEBUT DU CODE HTML<br></br>

<?php 

/******************************************************************************
 * Instancie systematiquement tous les gpios à utiliser
 ******************************************************************************/
$tab_gpio=array();
$tab_gpio=init_gpios(54);


/******************************************************************************
 * Si requete de type POST validee, fait un "set" sur les objets selon params
 * TODO : faire du javascript / ajax à la place
 ******************************************************************************/
if ($_POST['gpio_post_request']=='true')
{
	
	try 
	{
		if ($_POST['gpio17'] == 'on') mod_gpio(17, true); else mod_gpio(17, false);
		if ($_POST['gpio18'] == 'on') mod_gpio(18, true); else mod_gpio(18, false);
		if ($_POST['gpio22'] == 'on') mod_gpio(22, true); else mod_gpio(22, false);
	}
	catch (GpioException $e)
	{
		echo $e;
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
			echo $tab_gpio[$cpt_gpio].'<br>';
		}
		catch (GpioException $e)
		{
			echo $e ;
		}

		echo 'toto';
		echo $tab_gpio[18];
		echo 'tata';
		
		return $tab_gpio;
		
	}
}


/******************************************************************************
 * modifie l'etat d'un gpio specifié en parametre
 ******************************************************************************/
function mod_gpio(Gpio $gpio, $gpio_state)
{
	
	try
	{
		$gpio->set_state($gpio_state);
	}
	catch (GpioException $e)
	{
		echo $e ;
	}
	
}


?>

	<form  action="test.php" method="post" enctype="multipart/form-data">

<?php // if ($tab_gpio[17]->get_state()) echo ' checked';?>

		<input type="checkbox" name="gpio17" id="idcase1"  /> <label for="idcase1">Led Bleue</label>
		<input type="checkbox" name="gpio18" id="idcase2" checked /> <label for="idcase2">Led Verte</label>
		<input type="checkbox" name="gpio22" id="idcase3"  /> <label for="idcase3">Led Rouge</label>
		<input type="hidden" value="true" name="gpio_post_request" />
		<?php echo $tab_gpio[18]; ?>
		<?php echo $tab_gpio[22]; ?>
		
		<br><br>
		
		<input type="submit" value="Modifier l'etat des GPIO" />

    </form>


</body>

</html>