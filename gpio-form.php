<?php 

echo 'valeur post_gpio17 : ' . $_POST['gpio17'];

if ($_POST['gpio17'] == 'on')
{
	try
	{
		echo '---1';
		// $gpio17 = new Gpio('diode_bleue', '17', true);
		
		$command = 'gpio -g mode 17 out && gpio -g write 17 1' ;
		exec($command, $sortie_script, $return_var);

	}
	catch (GpioException $e)
	{
		echo $e ;
	}
}
else if ($_POST['gpio17'] == 'off')
{
	try
	{
		echo '2';
		$gpio17 = new Gpio('diode_bleue', '17', false);
	}
	catch (GpioException $e)
	{
		echo $e ;
	}
}

echo 'fin script';

echo 'Test etat diode sur GPIO 17 : ' . $gpio17->get_state();

?>
