
$(document).ready(function()

		{

			/* Ecouteur bouton neon garage ON */
			$("#NeonGarageOn").on('click', function(even) {

					$.post( 'simple-telecommande.php', {gpio_post_request: 'true', neon_garage_state: 'low' } );

			});

			/* Ecouteur bouton neon garage OFF */
			$("#NeonGarageOff").on('click', function(even) {

					$.post( 'simple-telecommande.php', {gpio_post_request: 'true', neon_garage_state: 'high' } );

			});			

		});
