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
 * SI REQUETE POST, FAIT UN SET SUR L'OBJET AVEC LE STATUT SELON PARAMETRES RECUS
 * TODO : faire fonction qui prend le numero de gpio en parametre
 * TODO : faire du javascript / ajax à la place
 ******************************************************************************/
if ($_POST['gpio_post_request']=='true')
{
	
	if ($_POST['gpio17'] == 'on')
	{
		try
		{
			$gpio17 = new Gpio('diode_bleue', '17', true);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}
	else
	{
		try
		{
			$gpio17 = new Gpio('diode_bleue', '17', false);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}

	if ($_POST['gpio18'] == 'on')
	{
		try
		{
			$gpio18 = new Gpio('diode_bleue', '18', true);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}
	else
	{
		try
		{
			$gpio18 = new Gpio('diode_bleue', '18', false);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}

	if ($_POST['gpio22'] == 'on')
	{
		try
		{
			$gpio22 = new Gpio('diode_bleue', '22', true);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}
	else
	{
		try
		{
			$gpio22 = new Gpio('diode_bleue', '22', false);
		}
		catch (GpioException $e)
		{
			echo $e ;
		}
	}
	
	
}
		
?>


	<form  action="test.php" method="post" enctype="multipart/form-data">

		<input type="checkbox" name="gpio17" id="idcase1" /> <label for="idcase1">Led Bleue</label>
		<input type="checkbox" name="gpio18" id="idcase2" /> <label for="idcase2">Led Verte</label>
		<input type="checkbox" name="gpio22" id="idcase3" /> <label for="idcase3">Led Rouge</label>
		<input type="hidden" value="true" name="gpio_post_request" />
		
		<br><br>
		
		<input type="submit" value="Modifier l'etat des GPIO" />

    </form>


</body>

</html>