<?php
/*
 * neongarage.php
 * ATTENTION : pas encore fonctionnel
 * 
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>sans titre</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.30.1" />
</head>

<body>
	
<?php echo $neonGarage;
	
	if ($neonGarage->getState() == true)
	{
		// circuit ouvert (g�n�ralement �teind)
		$retour = '<button id="boutonNeonGarage">Allumer</button>

			$("#NeonGarageOn").on(\'click\', function(even) {
				
				$(\'#zonetest\').load(\'testload.htm\', function() {
					alert ("Mise � jour de la zone par bouton Low");
					
				});
				
			});

		';
	}
	else if ($neonGarage->getState() == false)
	{
		// circuit ferm� (g�n�ralement allum�)
		$retour = '<button id="boutonNeonGarage">Eteindre</button>';
	}
	else
	{
		$retour = '<button id="boutonNeonGarage">Erreur</button>';
	}
	
?>
	
</body>

</html>
