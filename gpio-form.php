<?php 

// echo 'debut script gpio-form';

if ($_POST['gpio17'] == 'on') {$gpio17_state='1';} elseif ($_POST['gpio17'] == '') {$gpio17_state='0';}
if ($_POST['gpio18'] == 'on') {$gpio18_state='1';} elseif ($_POST['gpio18'] == '') {$gpio18_state='0';}
if ($_POST['gpio22'] == 'on') {$gpio22_state='1';} elseif ($_POST['gpio22'] == '') {$gpio22_state='0';}

$command = 'gpio -g mode 17 out && gpio -g write 17 ' . $gpio17_state ;
exec($command, $sortie_script, $return_var);

$command = 'gpio -g mode 18 out && gpio -g write 18 ' . $gpio18_state ;
exec($command, $sortie_script, $return_var);

$command = 'gpio -g mode 22 out && gpio -g write 22 ' . $gpio22_state ;
exec($command, $sortie_script, $return_var);
	
?>
