<?php

require('../../../security.php');
require('../../../include/definitions.php');

//echo 'test'; exit();

$x210s = Switch_bud::getx210Winogrady();
$files_path = '/usr/share/nginx/html/servnet/.dhcp_files';

$sysout = system("rm $files_path/gen/*");

$data = "#!/bin/bash" . "\n" .
		"user=ra-daniel" . "\n" .
		"pass=$(openssl enc -base64 -d <<< TXVzdGFuZzE5ODYuCg==)" . "\n";
foreach ($x210s as $x210){
	
	//$hosty = Switch_bud::get_all_hosts($x210['dev_id']);
	
	
		
// 	foreach ($hosty as $host){
// 		if($host['device_type'] == 'Host'){
			
			$data .= "sshpass -p \$pass ssh -T -p22222 -o StrictHostKeyChecking=no \$user@" . $x210['ip'] . " << DUPA" . "\n" .
				"en" . "\n" .	
				"conf t". "\n" .	
				"policy-map 501M" . "\n" .
				"class default" . "\n" .
				"police single-rate 509952 512000 512000 action drop-red" . "\n" .
				"exit" . "\n" .
				"exit" . "\n" .
				"exit" . "\n" .
				"wr" . "\n" .
				"DUPA" . "\n";
// 		}
// 	}
}
$filename = $files_path."/gen/add_policy.sh";
$file = fopen($filename, "w");
fwrite($file, $data);
fclose($file);


$data = "#!/bin/bash" . "\n" .
		"user=ra-daniel" . "\n" .
		"pass=$(openssl enc -base64 -d <<< TXVzdGFuZzE5ODYuCg==)" . "\n";
foreach ($x210s as $x210){

	$hosty = Switch_bud::get_all_hosts($x210['dev_id']);
	$data .= "sshpass -p \$pass ssh -T -p22222 -o StrictHostKeyChecking=no \$user@" . $x210['ip'] . " << DUPA" . "\n" .
		"en" . "\n" .
		"conf t" . "\n";


	foreach ($hosty as $host){
		if($host['device_type'] == 'Host'){
		
		$data .= "interface " . $host['parent_port'] . "\n" .
			"no service-policy input 500M" . "\n" .
			"service-policy input 501M" . "\n" .
			"exit" . "\n";
// 			"wr" . "\n" .
// 			"DUPA" . "\n";
			}
	}
	
	$data .= "exit" . "\n" . 
		"wr" . "\n" .
		"DUPA" . "\n";
}



$filename = $files_path."/gen/change_policy.sh";
$file = fopen($filename, "w");
fwrite($file, $data);
fclose($file);



$data = "#!/bin/bash" . "\n" .
		"user=ra-daniel" . "\n" .
		"pass=$(openssl enc -base64 -d <<< TXVzdGFuZzE5ODYuCg==)" . "\n";
foreach ($x210s as $x210){

	//$hosty = Switch_bud::get_all_hosts($x210['dev_id']);



	// 	foreach ($hosty as $host){
	// 		if($host['device_type'] == 'Host'){
		
	$data .= "sshpass -p \$pass ssh -T -p22222 -o StrictHostKeyChecking=no \$user@" . $x210['ip'] . " << DUPA" . "\n" .
			"en" . "\n" .
			"conf t". "\n" .
			"no policy-map 300Mbps" . "\n" .
			"no policy-map 30Mbps" . "\n" .
			"no policy-map 500M" . "\n" .
			"exit" . "\n" .
			"wr" . "\n" .
			"DUPA" . "\n";
	// 		}
	// 	}
}
$filename = $files_path."/gen/delete_policy.sh";
$file = fopen($filename, "w");
fwrite($file, $data);
fclose($file);




echo 'OK';