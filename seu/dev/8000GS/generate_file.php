<?php

require('../../security.php');
require('../../include/definitions.php');

$AT8000GSs = Switch_bud::getAT8000GS();
$files_path = '/usr/share/nginx/html/servnet/.dhcp_files';

$sysout = system("rm $files_path/gen/*");

foreach ($AT8000GSs as $AT8000GS){
	
	$hosty = Switch_bud::get_all_hosts($AT8000GS['dev_id']);
	
	$data = '';
	foreach ($hosty as $host){
		if($host['device_type'] == 'Host'){
			
			$data .= "interface ethernet " . $host['parent_port'] . "\n" .
				"traffic-shape 520000 5200000" . "\n" .
				"rate-limit 800000" . "\n" .
				"exit" . "\n";
		}
	}
	
	$filename = $files_path."/gen/" . $AT8000GS['ip'] . ".txt";
	$file = fopen($filename, "w");
	fwrite($file, $data);
	fclose($file);
}

echo 'OK';