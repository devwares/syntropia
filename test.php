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

CONTROLE DES GPIO<br></br>

<?php 

/******************************************************************************
 * Instancie systematiquement tous les gpios à utiliser
 ******************************************************************************/
$tab_gpio=init_gpios(32);

/******************************************************************************
 * Si requete de type POST validee, fait un "set" sur les objets selon params
 * TODO : faire du javascript / ajax à la place
 ******************************************************************************/
if (isset($_POST['gpio_post_request']) and $_POST['gpio_post_request']=='true')
{
	
	try 
	{
		
		// boucle sur chaque gpio instancie, et change son etat si contenu dans la requete
		$max=Gpio::getNumber();
		for ($cpt=1; $cpt < $max; $cpt++)

		{
			if (isset($_POST['gpio'. $cpt]))
			{
				// temporisation : si gpio??tempo existe (?? = numero pin), stocker dans $tempo à passer en argument, sinon $tempo = 0
				if(isset($_POST['gpio'. $cpt .'tempo'])) $tempo = $_POST['gpio'. $cpt .'tempo']; else $tempo = 0;
				// appelle fonction mod_gpio avec true ou false selon que gpio?? (?? = numero pin) contienne 'high' ou 'low'
				if ($_POST['gpio' .$cpt] == 'high') mod_gpio($tab_gpio[$cpt], true, $tempo); else if ($_POST['gpio' .$cpt] == 'low') mod_gpio($tab_gpio[$cpt], false, $tempo);
			}
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
 * Retourne un array qui contient la liste des gpio
 ******************************************************************************/
function init_gpios($max_gpio)
{

	for ($cpt_gpio=0; $cpt_gpio<$max_gpio ; $cpt_gpio++)
	{
			$tab_gpio[$cpt_gpio] = new Gpio('gpio_' . $cpt_gpio, $cpt_gpio);
	}
	
	return $tab_gpio;
	
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


/******************************************************************************
 * genere une alerte javascript (fonction de debuggage)
 ******************************************************************************/
function alerte($message)
{
	$sortie = '<script type="text/javascript" language="javascript">alert("'. $message . '");</script>';
	echo $sortie;
	return 0;
}


?>

	<form  action="test.php" method="post" enctype="multipart/form-data">

	<TABLE BORDER="1">
	<TR>
	<TH>Etat</TH>
	<TH>Nom</TH>
	<TH>Statut</TH>
	</TR>

	<?php	
	
		// boucle sur tous les gpio instancies, et cree un label et deux radiobuttons pour chaque
		$max=Gpio::getNumber();
		
		for ($cpt=1; $cpt < $max; $cpt++)

		{
			
			// verifie l'etat du gpio, coche la case si le resultat du getState est positif
			try
			{
				$tmpvar_radio_gpio_high = $tab_gpio[$cpt]->getState(); // remplace "echo 'checked';"
			}
			catch (\GpioException $e)
			{
				// si probleme d'acces au gpio
				$tmpvar_radio_gpio_disabled = true;
			}
			catch (\ShellException $e)
			{
				// si probleme d'execution du programme shell
				$tmpvar_radio_gpio_disabled = true;
			}

			// Debut ligne de tableau du gpio en cours
			echo '<TR>';
			
			// affiche le boutton radio 'High'
			echo '<TH>';
			echo '<input type="radio" value="high" name="gpio'. $cpt . '" ';
			if ($tmpvar_radio_gpio_high) echo 'checked';
			echo '/>High';
 			
			// affiche le boutton radio 'Low'
 			echo '<input type="radio" value="low" name="gpio'. $cpt . '" ';
 			if (!$tmpvar_radio_gpio_high) echo 'checked';
 			echo '/>Low';
 			echo '</TH>';
 			
 			// affiche le nom du gpio
			echo '<TH>';
 			echo 'GPIO ' . $cpt;
			echo '</TH>';
 			
 			// affiche le statut (tostring) du gpio
			echo '<TH>';
			echo $tab_gpio[$cpt];
			echo '</TH>';

			// fin ligne de tableau du gpio en cours
			echo '</TR>';
		}
		
	
	?>

		</TABLE>
	
		<!-- input cache pour savoir si un post a ete effectue depuis ce fichier -->
		<input type="hidden" value="true" name="gpio_post_request" />
		<br>
		<input type="submit" value="Modifier l'etat des GPIO" />

    </form>

</body>

</html>
