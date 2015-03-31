<html>
  <head>
    <title>Formularz HTML</title>
  </head>
    <body>
    
    <a href="index_switch.php"><button>Powrót</button></a><br><br>

<?php

  header('Content-Type: text/html; charset=utf-8');
  
 
    if(count($_POST) == 6)
    {
    	$cam_vlan = 0;
      $mac_rej = 'brak';
    	switch($_POST['area']) {
    		case 'rejOK13ł':
    		  $cam_vlan = 26;
    		  $mac_rej = '00:00:cd:29:9c:03';
    		  break;
    		case 'rejOK22g':
    		  $cam_vlan = 26;
    		  $mac_rej = '00:00:cd:28:c0:31';
    		  break;
    		case 'rejOK5g':
    		  $cam_vlan = 26;
    		  $mac_rej = '00:00:cd:29:9c:11';
    		  break;
    		case 'rejOP11a':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:28:c0:25';
    		  break;
    		case 'rejOP19e':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:28:c0:11';
    		  break;
    		case 'rejOP22m':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:28:c0:57';
    		  break;
    		case 'rejOP3g':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:28:c0:09';
    		  break;
    		case 'rejOP4j':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:28:c0:39';
    		  break;
    		case 'rejOP4jv2':
    		  $cam_vlan = 20;
    		  $mac_rej = '00:00:cd:29:9d:73';
    		  break;
    		case 'rejOPL13d':
    		  $cam_vlan = 28;
    		  $mac_rej = '00:00:cd:28:c0:1d';
    		  break;
    		case 'rejOPL5k':
    		  $cam_vlan = 28;
    		  $mac_rej = '00:00:cd:28:c0:1b';
    		  break;
    		case 'rejOPL7g':
    		  $cam_vlan = 28;
    		  $mac_rej = '00:00:cd:29:9d:89';
    		  break;
    		case 'rejOWW11a':
    		  $cam_vlan = 24;
    		  $mac_rej = '00:00:cd:28:c0:1f';
    		  break;
    		case 'rejOWW23g':
    		  $cam_vlan = 24;
    		  $mac_rej = '00:00:cd:28:c0:13';
    		  break;
    		case 'rejOWW26f':
    		  $cam_vlan = 24;
    		  $mac_rej = '00:00:cd:28:c0:2f';
    		  break;
    		case 'rejOWW34g':
    		  $cam_vlan = 24;
    		  $mac_rej = '00:00:cd:28:c0:2b';
    		  break;
    		case 'rejOWW6i':
    		  $cam_vlan = 24;
    		  $mac_rej = '00:00:cd:29:9d:7f';
    		  break;
    		case 'rejOZ12a':
    		  $cam_vlan = 22;
    		  $mac_rej = '00:00:cd:28:c0:4f';
    		  break;
    		case 'rejOZ19':
    		  $cam_vlan = 22;
    		  $mac_rej = '00:00:cd:28:c0:37';
    		  break;
    		case 'rejOZ22ł':
    		  $cam_vlan = 22;
    		  $mac_rej = '00:00:cd:28:c0:0d';
    		  break;
    		case 'rejOZ4d':
    		  $cam_vlan = 22;
    		  $mac_rej = '00:00:cd:29:9d:5d';
    		  break;
    	}
    	
    	if($_POST['switch_choice']==='24port'){
    		$max_port=24;
    	}
    	else{
    		$max_port=48;
    	}
           
				echo 'crypto key generate rsa<br>';
				echo 'y<br>';
				echo 'interface range ethernet g(1-'.($max_port-1).')<br>';
				echo 'spanning-tree portfast<br>';
				echo 'spanning-tree bpduguard<br>';
				echo 'exit<br>';
				echo 'interface ethernet g'.$max_port.'<br>';
				// Wpisać odpowiedni rejon – lista na dole
				echo 'description '.$_POST['uplink_switch_name'].'<br>';
				echo 'vlan database<br>';
				echo 'default-vlan disable<br>';
				echo 'exit<br>';
				echo 'interface range ethernet g(1-'.($max_port-1).')<br>';
				echo 'switchport protected ethernet g'.$max_port.'<br>';
				echo 'exit<br>';
				echo 'interface ethernet g'.$max_port.'<br>';
				echo 'switchport mode trunk<br>';
				echo 'exit<br>';
				echo 'vlan database<br>';
		
				echo 'vlan 2,3,4,10,555,'.$cam_vlan.'<br>';
				echo 'exit<br>';
				echo 'interface range ethernet g(1-'.($max_port-1).')<br>';
				echo 'switchport access vlan 555<br>';
				echo 'exit<br>';
				echo 'interface ethernet g'.$max_port.'<br>';
				echo 'switchport trunk allowed vlan add 2<br>';
				echo 'switchport trunk allowed vlan add 3<br>';
				echo 'switchport trunk allowed vlan add 4<br>';
				echo 'switchport trunk allowed vlan add 10<br>';
				
				echo 'switchport trunk allowed vlan add '.$cam_vlan.'<br>';
				echo 'exit<br>';
				echo 'interface range ethernet g(1-'.($max_port-1).')<br>';
				echo 'no lldp enable<br>';
				echo 'exit<br>';
				echo 'interface vlan 10<br>';
				echo '! Podac adres IP wg projektu sieci<br>';
				echo 'ip address '.$_POST['ip'].' 255.255.252.0<br>';
				echo 'exit<br>';
		
				echo 'mac access-list arppoisoning<br>';
				echo 'permit '.$mac_rej.' 00:00:00:00:00:00 any vlan 2<br>';
				echo 'deny any any vlan 2<br>';
		
				echo 'permit '.$mac_rej.' 00:00:00:00:00:00 any vlan 3<br>';
				echo 'deny any any vlan 3<br>';
		
				echo 'permit '.$mac_rej.' 00:00:00:00:00:00 any vlan 4<br>';
				echo 'deny any any vlan 4<br>';
				echo 'permit any any<br>';
				echo 'exit<br>';
				echo 'interface ethernet g'.$max_port.'<br>';
				echo 'service-acl input arppoisoning<br>';
				echo 'exit<br>';
		
				echo 'Hostname '.$_POST['switch_name'].'<br>';
				echo 'radius-server host 172.20.4.17 key 5z@5zlyk<br>';
				echo 'logging 172.20.4.17<br>';
				echo 'aaa authentication enable default radius<br>';
				echo 'aaa authentication login default radius local<br>';
				echo 'aaa authentication login notelnet enable<br>';
				echo 'line telnet<br>';
				echo 'login authentication notelnet<br>';
				echo 'exit<br>';
				echo 'username lo-admin1 password 25481333079d41b8d1fd3d539057bb34 level 15 encrypted<br>';
				echo 'username lo-admin2 password 69b1f396a65c75e53ddf8bf00c0080f3 level 15 encrypted<br>';
				echo 'username lo-guest1 password d21d09a1708ae39eff71818d1d68e9f2  encrypted<br>';
				echo 'username lo-guest2 password 1c14cf90aff3e035ae13dd9aabfa36ab  encrypted<br>';
				echo 'username salivan password 295a997457362b854f2adb54c9b148e0 level 15 encrypted<br>';
				echo 'ip ssh port 22222<br>';
				echo 'ip ssh server<br>';
		
				echo 'snmp-server location '.$_POST['switch_name'].'<br>';
		
				echo 'snmp-server contact '.$_POST['mail'].'<br>';
				echo 'snmp-server view czytaj internet included<br>';
				echo 'snmp-server community wymyslj@k12spr0st3 ro 172.20.4.4 view czytaj<br>';
				echo 'snmp-server community wymyslj@k12spr0st3 ro 172.20.4.17 view czytaj<br>';
				echo 'no ip http server<br>';
				echo 'no ip https server<br>';
				echo 'clock timezone +1 zone POL<br>';
				echo 'clock summer-time recurring 4 Sun Mar 02:00 4 Sun Oct 03:00 zone POL<br>';
				echo 'clock source sntp<br>';
				echo 'sntp client poll timer 60<br>';
				echo 'sntp unicast client enable<br>';
				echo 'sntp unicast client poll<br>';
				echo 'sntp server 172.20.4.17 poll<br>';
				echo 'no ip domain-lookup<br>';
				echo 'end<br>';
				echo 'login_banner WARNING`You`are`connected`to`Winogrady`Network.`Unauthorized`access`and`use`of`this`network`will`be`vigorously`prosecuted.<br>';
				echo 'end<br>';
				echo 'copy r s<br>';
				echo 'y<br>';
			   
    }  
    else
    {
       echo 'Nieprawidłowa liczba parametrów!';
    }
?>

    </body>
</html>


