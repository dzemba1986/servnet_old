<?php

require('path.php');
require(LISTA_ABSOLUTE.'/include/classes/connections.php');
require(LISTA_ABSOLUTE.'/include/classes/mysql.php');
require(LISTA_ABSOLUTE.'/include/classes/localization.php');

$arPost = json_decode(file_get_contents('php://input'), true);
// var_dump($arPost);
// exit();
if ($arPost['haslo'] == '3129'){
	
	$arStreetMap = [
			'Bóżnicza' => '01957',
			'Czarna Rola' => '03269',
			'Kosmonautów' => '09439',
			'Marcelińska' => '12272' ,
			'Na Murawie' => '13631',
			'Naramowicka' => '13989',
			'Pasterska' => '15776',
			'Pod Lipami' => '16636',
			'Przyjaźni' => '17923',
			'Kondratija Rylejewa' => '19232',
			'Towarowa' => '22907',
			'Wichrowe Wzgórze' => '23990',
			'Wilczak' => '24263',
			'Zwycięstwa' => '26323',
	];
	
	$start_date = date('d.m.y');
	$ara_id = 'a1234';
	$address = [
		'ulic' => $arStreetMap[$arPost['ulica']],
		'blok' => $arPost['dom_szczegol']	?	$arPost['dom'].'/'.$arPost['dom_szczegol']	:	$arPost['dom'],
		'mieszkanie' => $arPost['lokal'],
		'other_name' => $arPost['lokal_szczegol']
	];		
	$phone = $arPost['tel'] ? $arPost['tel'] : $arPost['kom'];
	$phone2 = $arPost['tel']		?		$arPost['kom'] ? $arPost['kom'] : ''		:		'';
	$service = 'net';
	$speed = 300;
	$info = $arPost['email']	?	'ANKIETA'.'		'.$arPost['email'].' '.$arPost['uwagi']	:	'ANKIETA'.'		'.$arPost['uwagi'];
	
	$_SESSION['user_id'] = 2;
	
	$connection = new Connections();
	//var_dump($start_date);
	//exit();
	
	// function insertOne($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $speed, $ara_id)
	$res = $connection->insertOne($start_date, $address, null, 'net', $info, $phone, $phone2, null, 300, $ara_id);
	if($res['code']){
		
		$rtn = array("lokalizacja_id" => $res['error_desc'], "opis_bledu" => "");
		http_response_code(200);
		print json_encode($rtn);
	}
	else{
		$rtn = array("lokalizacja_id" => -1, "opis_bledu" => $res['error_desc']);
		http_response_code(500);
		print json_encode($rtn);
	}
	
}
else echo 'dupa';


?>