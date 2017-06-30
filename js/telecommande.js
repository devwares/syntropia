$(document).ready(function()
		{
	
			/* Code à implémenter */
			$("#implement").html("echo '$.post(\'testpost.php\', {gpio_post_request: \'true\', neon-garage_state: \'high\'});'");
			
			/* Instructions Témoin */
			$("#zonetest").html("Cette ligne apparait si le javascript fonctionne");
			$("#zonetoto").hide(1500);
			
			/* Ecouteurs */
			
			$("#NeonGarageOn").on('click', function(even) {
				
				$('#zonetest').load('testload.htm', function() {
					alert ("Mise à jour de la zone par bouton Low");
					
				});
				
			});
			
			$("#NeonGarageOff").on('click', function(even) {
				
				$.post('simple-telecommande.php',{ gpio_post_request:true, neonGarage_state: 'high'});
				/* $.post('testpost.php',{ id:50, nom: 'durand'}); */
				
			});


		});