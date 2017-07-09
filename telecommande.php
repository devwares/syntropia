<?php

namespace Syntropia;

include_once 'Lumiere.php';
include_once 'Gpio.php';
include_once 'GpioAccess.php';
include_once 'GpioException.php';
include_once 'ShellException.php';

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

/* M�mo pour l'utilisation de la classe Gpio :
 * 	
 * mod_gpio($gpio, true, $tempo) // ouvre le circuit (g�n�ralement, �teind)
 * mod_gpio($gpio, false, $tempo); // ferme le circuit (g�n�ralement, allume)
 *
 * o� $tempo = dur�e en secondes (0 pour infini)
 * 
 * get_state()
 * 
 * retourne TRUE pour circuit ouvert (g�n�ralement, �teind)
 * retourne FALSE pour circuit ferm� (g�n�ralement allum�)
 *
 */

/******************************************************************************
 * Instancie les GPIO � utiliser
 ******************************************************************************/
//$neonGarage = new Gpio('gpio17', 17);
//$priseGarage = new Gpio('gpio18', 18);
//$lumiereExt = new Gpio('gpio27', 27);
$neonGarage = new Lumiere('neonGarage', 17);

//$testLumiere = new Lumiere();

/******************************************************************************
 * Incorpore les interrupteurs, en fonction de l'�tat des GPIOs
 ******************************************************************************/

/* ICI, instructions pour tester si bouton garage allum� o� �teind : changer id et nom du bouton
 * en fonction de l'�tat en cours
 * 
 * Au d�marrage de la page, faire un simple include d'un 'fichier-qui-teste-et-affiche-l-interrupteur-de-tel-endroit.php'
 * 
 * A l'appel par jquery (ecouteur dans l'interrupteur), retester l'�tat et mettre � jour la partie interrupteur dans le .php 
 * 
 * Voir testpost.html/js/php : $('#zonetest').load('fichier-qui-teste-et-afiche-l-interrupteur.php',{ id:50, nom: 'durand'});
 * 
 * 
 * 30/06/17
 * L'id�e : cr�er une classe style "gpio-jquery" qui herite de Gpio.php, et red�finir la m�thode tostring pour qu'elle renvoie du html + jquery
 * diff�rent selon que l'interrupteur soit allum� ou �teind. OU, modifier directement le tostring de Gpio.php pour qu'il recoive un parametre
 * type "html"/"jquery"/"txt"etc.. 
 * 
 * IMPORTANT : proscrire les underscore et SURTOUT les tirets dans les param�tres pass�s via le javascript
 * 
 * todo : inclure jquery, ne plus r�f�rencer
 * 
 */


/* 30/06/17 : ICI, envoi TEST de la commande GPIO pour voir si tout va bien en bout de chaine */
// allumer
//$neonGarage->setState(false, 0);
// eteindre
//$neonGarage->setState(true, 0);

echo $neonGarage;

?>

</body>
</html>
