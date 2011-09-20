<?php 
class Device extends Daddy
{
	public $old_mac;
	public $mac;
	public $dev_id;
	public $lokalizacja;
	//w konstruktorze brakuje parametrow lokalizacja i parent port sn.
	public function sprawdzDevice($_device)
	{
		$errors = $this->sprawdzDeviceSkladnia($_device);
		if(($_device['device_type']!='Switch_bud' && $_device['device_type']!='Virtual' && !$this->sprawdz_mac($_device['mac'])) ||
		(($_device['device_type']=='Switch_bud' && $_device['device_type']=='Virtual') && $_device['mac'] && !$this->sprawdz_mac($_device['mac'])))
		{
			if($_device['device_type']=='Host')
			{
				echo'<script type="text/javascript">alert("Ten adres MAC jest już zajęty!\nPozostawienie go w takiej postaci jest wysoce niewskazane!");</script>';
			}
			else
			{
				Daddy::error("Zajęty Adres MAC");
				$errors++;
			}
		}
		if($errors > 0)
		{
			echo "<br>Bledne dane";
			return false;
		}
		return true;
	}
	private function sprawdzDeviceSkladnia($_device)
	{
		$errors = 0;
		if(((($_device['device_type']=='Host' ||$_device['device_type']=='Switch_bud') && $_device['mac']) 
				&& ! $this->sprawdz_mac_skladnia($_device['mac'])) || $_device['device_type']!='Virtual' &&
			($_device['device_type']!='Host' && $_device['device_type']!='Switch_bud') && !$this->sprawdz_mac_skladnia($_device['mac']))
		{
			Daddy::error("Nieprawidłowy Adres MAC");
			$errors++;
		}

                if(defined('DEBUG'))
		  print_r($_device['ip']);
		if($_device['device_type']=='Switch_bud' && $_device['ip'][1]['ip'] && !$this->sprawdzAdresy($_device['ip'])||
			($_device['device_type']=='Virtual' && $_device['ip'][1]['ip'] && !$this->sprawdzAdresy($_device['ip']))||
			($_device['device_type']!='Switch_bud' && $_device['device_type']!='Virtual' &&  !$this->sprawdzAdresy($_device['ip'])))
		{
			Daddy::error("Nieprawidłowy(we) adres(y) IP");
			$errors++;
		}
		if($_device['gateway'] && ! $this->sprawdz_ip($_device['gateway']))
		{
			Daddy::error("Nieprawidłowy Adres bramy");
			$errors++;
		}
		if(! Daddy::sprawdz_typ($_device['device_type']))
		{
			Daddy::error("Nieprawidłowy typ urządzenia");
			$errors++;
		}
		if(! $this->sprawdz_device($_device['parent_device']) && $_device['device_type'] != "Switch_centralny" && ($_device['device_type'] != 'Virtual' || ( $_device['device_type'] == 'Virtual' && !$_device['ip'][1]['ip'])))
		{
                  print_r($_device);
			Daddy::error("Podane urzadzenie nie istnieje");
			$errors++;
		}
		return $errors;
	}
	public function dodajDoMagazynu($_device)
	{
//		echo "do momentu zaktualizowania wyłaczam dodawanie elementów";
//		exit();
		$_mac = strtolower($_device['mac']);
		$_device_type = $_device['device_type'];	
		$this->lokalizacja =  $this->znajdz_lokalizacje('00000', null, null);
		if(!$this->lokalizacja)
		{
			echo "nie udalo sie dodac lokalizacji";
			$this->dev_id = -1;
			exit();
		}
		$sql = $this->connect();
		//sprawdz skladnie mac
		if($_mac && ! $this->sprawdz_mac_skladnia($_mac))
			die("Nieprawidłowy Adres MAC");
		if($_mac && ! $this->sprawdz_mac($_mac))
			die("Zajęty Adres MAC");
	//	Po sprawdzeniu parametrow laczymy sie z baza i dodajemy do niej rekord
		$zapytanie = "INSERT INTO `Device` (`mac`, `device_type`, `lokalizacja`) 
			 VALUES('$_mac', '$_device_type', '$this->lokalizacja')";
		$wynik = $this->query($zapytanie);
                if(defined('DEBUG'))
		  echo("<br>".$zapytanie."<br>");
		if ($wynik)
		{
			$dev_id = mysql_insert_id();
			$this->mac = $_mac;	//zakonczone powodzeniem
			$this->dev_id = $dev_id;	//zakonczone powodzeniem
                        if(defined('DEBUG'))
			  echo "<br>Dodano device<br>";
		}
		else
		{
			$this->mac = '-1';	//zakonczone powodzeniem
			$this->dev_id = '-1';	//zakonczone powodzeniem
		}
		$this->queryLogger($zapytanie);

	}
	public function modyfikujMagazyn($_dev_id, $_other_name, $_opis, $_opis_zdarzenia)
	{
		$dev_id = intval($_dev_id);
		$this->connect();
		$other_name = mysql_real_escape_string($_other_name);
		$opis = mysql_real_escape_string($_opis);
		$opis_zdarzenia = mysql_real_escape_string($_opis_zdarzenia);
		$user = intval($_SESSION['user_id']);
		$zapytanie = "UPDATE Device SET other_name='$other_name', opis='$opis' WHERE dev_id='$dev_id'";
		$this->query($zapytanie);
		$this->queryLogger($zapytanie);
		$this->loguj($dev_id, 111, $user, $opis_zdarzenia , 'modyfikuj');

	}
	public function dodaj($_device, $device_type_obj)
	{
//		echo "do momentu zaktualizowania wyłaczam dodawanie elementów";
//		exit();
		
		$this->lokalizacja =  $this->znajdz_lokalizacje($_device['osiedle'], $_device['blok'], $_device['klatka']);
		if(!$this->lokalizacja)
		{
			echo "nie udalo sie dodac lokalizacji";
			$this->dev_id = -1;
			exit();
		}
		$sql = $this->connect();
	//	echo("<br>$sql<br>");
		$this->sprawdzDeviceSkladnia($_device);
		$_exists;
		if($_device['exists'])
			$_exists = 1;
		else
			$_exists = 0;
		$_mac = strtolower(mysql_real_escape_string($_device['mac']));
		$_other_name = mysql_real_escape_string(htmlspecialchars($_device['other_name']));
		$_gateway = mysql_real_escape_string(htmlspecialchars($_device['gateway']));
		$_opis = mysql_real_escape_string(htmlspecialchars($_device['opis']));
		$_device_type = mysql_real_escape_string($_device['device_type']);
		$_parent_port = mysql_real_escape_string($_device['parent_port']);
		$_parent_device = mysql_real_escape_string($_device['parent_device']);
		$this->lokalizacja = mysql_real_escape_string($this->lokalizacja);
		$_uplink_parent_ports = $_device['uplink_parent_ports'];
		$_uplink_local_ports = $_device['uplink_local_ports'];
                $subnet_occur;
		foreach($_device['ip'] as &$adres)
		{
			$adres['ip'] = mysql_real_escape_string($adres['ip']);
			$adres['podsiec'] = mysql_real_escape_string($adres['podsiec']);
			$adres['main'] = mysql_real_escape_string($adres['main']);
                        if($adres['podsiec'])
                        {
                          if(!$subnet_occur[$adres['podsiec']])
                            $subnet_occur[$adres['podsiec']] = true;
                          else
                            die('Podano 2 adresy w jednej podsieci!!!');
                        }
			if(!$this->sprawdz_ip_czywolne($adres['ip'], $adres['podsiec']))
				if($_device_type!='Switch_bud' || ($_device_type=='Switch_bud' && $adres['ip']))
					die('Adres ip '.$adres['ip']." w podsieci ".$adres['podsiec']." jest zajęty!!!");
		}
		unset($adres);
                if(defined('DEBUG'))
		  echo ("count". count($_uplink_parent_ports));
		for($k=0; $k<count($_uplink_parent_ports);$k++)
		{
			$_uplink_parent_ports[$k] =  mysql_real_escape_string($_uplink_parent_ports[$k]);
			$_uplink_local_ports[$k] =  mysql_real_escape_string($_uplink_local_ports[$k]);
//			print_r($_uplink_parent_ports);
		}
	//	sprawdzamy dostępność uplinku

		if($this->findDuplicates($_uplink_parent_ports) || $this->findDuplicates($_uplink_local_ports))
			die("Porty uplinku nie moga się powtarzać!!!");
		if(!$this->portsAvaible($_parent_device, $_uplink_parent_ports))
			die("Podane porty sa zajęte!");

	//	Po sprawdzeniu parametrow laczymy sie z baza i dodajemy do niej rekord
		$zapytanie = "INSERT INTO `Device` (`mac`, `gateway`, `opis`,
			`device_type`, `lokalizacja`, `other_name`, `exists`) 
			 VALUES('$_mac', '$_gateway', 
			'$_opis', '$_device_type', 
			'$this->lokalizacja', '$_other_name', '$_exists')";
		$wynik = $this->query($zapytanie);
                
        if(defined('DEBUG'))
        {
          echo("<br>".$zapytanie."<br>");
        }
	//tu trzeba jeszcze dodać do lokalizacji mac urzadzenia
		if ($wynik)
		{
			$dev_id = mysql_insert_id();
			$this->queryLogger($zapytanie);
                        if(defined('DEBUG'))
			  echo "<br>Dodano device o id = $dev_id<br>";
			if($_device_type=='Switch_bud' && !$_device['ip'][1]['ip'])
			{
				echo "<br> Nie dodaje IP bo nie podano;)<br>";
				$wynik2 = 1;
			}
			else
			{
				$zapytanie2 = "INSERT INTO `Adres_ip` (`device`, `podsiec`, `ip`, `main`) VALUES ";
				foreach($_device['ip'] as $adres)
				{
					$zapytanie2 = $zapytanie2."('".$dev_id."', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."'),";
				}
                                if(defined('DEBUG'))
                                {
                                  echo "<br>dlugosc: ".strlen($zapytanie2)."<br>";
                                }
                                $zapytanie2 = substr($zapytanie2, 0, strlen($zapytanie2)-1);
				$wynik2 = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);
			}
//sprawdzamy czy jest więcej portów nadrzędnych jeżeli

			$zapytanie3 = "INSERT INTO `Agregacja` (`device`, `parent_port`, `local_port`, `parent_device`, `uplink`) VALUES";
			for($x=0; $x<count($_uplink_parent_ports);$x++)
				$zapytanie3 = $zapytanie3." ('".$dev_id."', '".$_uplink_parent_ports[$x]."', '".$_uplink_local_ports[$x]."', '$_parent_device', '1'),";
			$zapytanie3 = substr($zapytanie3, 0, strlen($zapytanie3)-1);
			$wynik3 = $this->query($zapytanie3);
			$this->queryLogger($zapytanie3);

			if($wynik2 && $wynik3)
			{
				$this->mac = $_mac;	//zakonczone powodzeniem
				$this->dev_id = $dev_id;	//zakonczone powodzeniem
                                if(defined('DEBUG'))
				  echo "<br>Dodano adresy IP<br>";
			}
			else
			{
				$this->mac = -1;	//zakonczone niepowodzeniem
				$this->dev_id = -1;	//zakonczone niepowodzeniem
				echo "<br>Dodawanie adresów zakonczone niepowodzeniem<br>";
				echo mysql_error();
			}
                        if(defined('DEBUG'))
			  echo "$zapytanie2<br>$zapytanie3<br>";
		}
		else
		{
			$this->dev_id = -1;	//zakonczone niepowodzeniem
			$this->mac = -1;	//zakonczone niepowodzeniem
			echo "<br>Dodawanie uzadzenia zakonczone niepowodzeniem<br>";
		}

	}
	public function dodaj_virtual($_device)
	{
//		echo "do momentu zaktualizowania wyłaczam dodawanie elementów";
//		exit();
	        if($_device['osiedle'])
                  //jeżeli jest podany adres virtualny
                {
                  $this->lokalizacja =  $this->znajdz_lokalizacje($_device['osiedle'], $_device['blok'], $_device['klatka']);
                  if(!$this->lokalizacja)
                  {
                          echo "nie udalo sie dodac lokalizacji";
                          $this->dev_id = -1;
                          exit();
                  }
                }
                else
                {
                  $this->lokalizacja =  2;
                }
		$sql = $this->connect();
	//	echo("<br>$sql<br>");
		$this->sprawdzDeviceSkladnia($_device);
		$_exists;
		if($_device['exists'])
			$_exists = 1;
		else
			$_exists = 0;
		$_mac = strtolower(mysql_real_escape_string($_device['mac']));
		$_other_name = mysql_real_escape_string(htmlspecialchars($_device['other_name']));
		$_opis = mysql_real_escape_string(htmlspecialchars($_device['opis']));
		$_device_type = "Virtual";
		$_parent_port = mysql_real_escape_string($_device['parent_port']);
		$_parent_device = mysql_real_escape_string($_device['parent_device']);
		$this->lokalizacja = mysql_real_escape_string($this->lokalizacja);
		$_uplink_parent_ports = $_device['uplink_parent_ports'];
		$_uplink_local_ports = $_device['uplink_local_ports'];
                $subnet_occur;
                if($_device['ip'][1]['ip'])
                {
                  if($this->lokalizacja==2 && !$_other_name)
                    die("Przy braku lokalizacji wymagane jest podanie nazwy urządzenia!");
                  foreach($_device['ip'] as &$adres)
                  {
                    $adres['ip'] = mysql_real_escape_string($adres['ip']);
                    $adres['podsiec'] = mysql_real_escape_string($adres['podsiec']);
                    $adres['main'] = mysql_real_escape_string($adres['main']);
                    if($adres['podsiec'])
                    {
                      if(! $subnet_occur[$adres['podsiec']])
                        $subnet_occur[$adres['podsiec']] = true;
                      else
                        die('Podano 2 adresy w jednej podsieci!!!');
                    }
                    if(!$this->sprawdz_ip_czywolne($adres['ip'], $adres['podsiec']))
                      if($_device_type!='Switch_bud' || ($_device_type=='Switch_bud' && $adres['ip']))
                        die('Adres ip '.$adres['ip']." w podsieci ".$adres['podsiec']." jest zajęty!!!");
                  }
                }
                else
                {
                  if($this->lokalizacja==2)
                    die("Przy braku lokalizacji Adres IP jest wymagany!");
                }
		unset($adres);
                if(defined('DEBUG'))
		  echo ("count". count($_uplink_parent_ports));
		for($k=0; $k<count($_uplink_parent_ports);$k++)
		{
			$_uplink_parent_ports[$k] =  mysql_real_escape_string($_uplink_parent_ports[$k]);
			$_uplink_local_ports[$k] =  mysql_real_escape_string($_uplink_local_ports[$k]);
//			print_r($_uplink_parent_ports);
		}
	//	sprawdzamy dostępność uplinku
                if($_parent_device)
                {
                  if($this->findDuplicates($_uplink_parent_ports) || $this->findDuplicates($_uplink_local_ports))
                          die("Porty uplinku nie moga się powtarzać!!!");
                  if(!$this->portsAvaible($_parent_device, $_uplink_parent_ports))
                          die("Podane porty sa zajęte!");
                }
	//	Po sprawdzeniu parametrow laczymy sie z baza i dodajemy do niej rekord
		$zapytanie = "INSERT INTO `Device` (`mac`, `gateway`, `opis`,
			`device_type`, `lokalizacja`, `other_name`, `exists`) 
			 VALUES('$_mac', '', 
			'$_opis', '$_device_type', 
			'$this->lokalizacja', '$_other_name', '$_exists')";
		$wynik = $this->query($zapytanie);
                if(defined('DEBUG'))
                {
                  echo("<br>".$zapytanie."<br>");
                }
                //tu trzeba jeszcze dodać do lokalizacji mac urzadzenia
		if ($wynik)
		{
			$dev_id = mysql_insert_id();
			$this->queryLogger($zapytanie);
                        if(defined('DEBUG'))
			  echo "<br>Dodano device o id = $dev_id<br>";
			if(!$_device['ip'][1]['ip'])
			{
				echo "<br> Nie dodaje IP bo nie podano;)<br>";
				$wynik2 = 1;
			}
			else
			{
				$zapytanie2 = "INSERT INTO `Adres_ip` (`device`, `podsiec`, `ip`, `main`) VALUES ";
				foreach($_device['ip'] as $adres)
				{
					$zapytanie2 = $zapytanie2."('".$dev_id."', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."'),";
				}
                                if(defined('DEBUG'))
                                {
                                  echo "<br>dlugosc: ".strlen($zapytanie2)."<br>";
                                }
                                $zapytanie2 = substr($zapytanie2, 0, strlen($zapytanie2)-1);
				$wynik2 = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);
			}
//sprawdzamy czy jest więcej portów nadrzędnych jeżeli
                        if($this->lokalizacja!=2)
                        {
                          $zapytanie3 = "INSERT INTO `Agregacja` (`device`, `parent_port`, `local_port`, `parent_device`, `uplink`) VALUES";
                          for($x=0; $x<count($_uplink_parent_ports);$x++)
                                  $zapytanie3 = $zapytanie3." ('".$dev_id."', '".$_uplink_parent_ports[$x]."', '".$_uplink_local_ports[$x]."', '$_parent_device', '1'),";
                          $zapytanie3 = substr($zapytanie3, 0, strlen($zapytanie3)-1);
                          $wynik3 = $this->query($zapytanie3);
                          $this->queryLogger($zapytanie3);
                        }
                        else
                          $wynik3=true;
			if($wynik2 && $wynik3)
			{
				$this->mac = TRUE;	//zakonczone powodzeniem
				$this->dev_id = $dev_id;	//zakonczone powodzeniem
                                $host = new Host();
			        $host->updateDhcp($this->dev_id, $this->mac, 'update');
                                if(defined('DEBUG'))
				  echo "<br>Dodano adresy IP<br>";
			}
			else
			{
				$this->mac = -1;	//zakonczone niepowodzeniem
				$this->dev_id = -1;	//zakonczone niepowodzeniem
				echo "<br>Dodawanie adresów zakonczone niepowodzeniem<br>";
				echo mysql_error();
			}
                        if(defined('DEBUG'))
			  echo "$zapytanie2<br>$zapytanie3<br>";
		}
		else
		{
			$this->dev_id = -1;	//zakonczone niepowodzeniem
			$this->mac = -1;	//zakonczone niepowodzeniem
			echo "<br>Dodawanie uzadzenia zakonczone niepowodzeniem<br>";
		}

	}
	public function modyfikuj($_device)
	{
		
		$this->lokalizacja =  $this->znajdz_lokalizacje($_device['osiedle'], $_device['blok'], $_device['klatka']);
		if(!$this->lokalizacja)
		{
			echo "nie udalo sie dodac lokalizacji";
			$this->dev_id = -1;	
			$this->mac = -1;
			exit();
		}
//Sprawdzamy poprawność składniową danych
		if($this->sprawdzDeviceSkladnia($_device) > 0 )
			exit(0);
//Zabezpieczenie przed sql injection		
		$sql = $this->connect();
		$_exists;
		if($_device['exists'])
			$_exists = 1;
		else
			$_exists = 0;
		$_mac = strtolower(mysql_real_escape_string($_device['mac']));
		$_dev_id = mysql_real_escape_string($_device['dev_id']);
		$_other_name = mysql_real_escape_string(htmlspecialchars($_device['other_name']));
		$_gateway = mysql_real_escape_string(htmlspecialchars($_device['gateway']));
		$_opis = mysql_real_escape_string(htmlspecialchars($_device['opis']));
		$_device_type = mysql_real_escape_string($_device['device_type']);
		$_parent_device = mysql_real_escape_string($_device['parent_device']);
		$_parent_port = mysql_real_escape_string($_device['parent_port']);
		$this->lokalizacja = mysql_real_escape_string($this->lokalizacja);
		$_uplink_parent_ports = $_device['uplink_parent_ports'];
		$_uplink_local_ports = $_device['uplink_local_ports'];
                $subnet_occur;
		foreach($_device['ip'] as &$adres)
		{
			$adres['ip'] = mysql_real_escape_string($adres['ip']);
			$adres['podsiec'] = mysql_real_escape_string($adres['podsiec']);
			$adres['main'] = mysql_real_escape_string($adres['main']);
                        if($adres['podsiec'])
                        {
                          if(! $subnet_occur[$adres['podsiec']])
                            $subnet_occur[$adres['podsiec']] = true;
                          else
                            die('Podano 2 adresy w jednej podsieci!!!');
                        }
		}
		unset($adres);
                if(defined('DEBUG'))
                {
		  echo ("count". count($_uplink_parent_ports));
                }
                for($k=0; $k<count($_uplink_parent_ports);$k++)
		{
			$_uplink_parent_ports[$k] =  mysql_real_escape_string($_uplink_parent_ports[$k]);
			$_uplink_local_ports[$k] =  mysql_real_escape_string($_uplink_local_ports[$k]);
                        if(defined('DEBUG'))
			  print_r($_uplink_parent_ports);
		}
	//	sprawdzamy dostępność uplinku

		if($this->findDuplicates($_uplink_parent_ports) || $this->findDuplicates($_uplink_local_ports))
			die("Porty uplinku nie moga się powtarzać!!!");
	//	Po sprawdzeniu parametrow laczymy sie z baza i dodajemy do niej rekord
		$zapytanie;
		if($_device_type=='Host')
			$zapytanie = "UPDATE `Device` SET `exists`='$_exists', `gateway`='$_gateway', `opis`='$_opis',
			`device_type`='$_device_type', `lokalizacja`='$this->lokalizacja',
			 `other_name`='$_other_name' WHERE dev_id='$_dev_id'"; 
		else	
			$zapytanie = "UPDATE `Device` SET `exists`='$_exists', `gateway`='$_gateway', `opis`='$_opis',
			`device_type`='$_device_type', `lokalizacja`='$this->lokalizacja',
			 `other_name`='$_other_name' WHERE dev_id='$_dev_id'"; 
		$wynik = $this->query($zapytanie);
		$this->queryLogger($zapytanie);
                if(defined('DEBUG'))
                {
                  echo("<br>".$zapytanie."<br>");
                }
                if ($wynik)
		{
                if(defined('DEBUG'))
		  echo "<br>Zaktualizowano device<br>";	
		//tu trzebaby sprawdzić czy ip istnieje.
		$wynik2 = $wynik3 = true;
		foreach($_device['ip'] as $adres)
		{
			$zapytanie = "SELECT * FROM Adres_ip WHERE ip='".$adres['ip']."' AND podsiec='".$adres['podsiec']."'";
                        if(defined('DEBUG'))
                        {
			  echo"<br>";
			  print_r($adres);
			  echo"<br>$zapytanie<br>";
                        }
                        $sql = $this->connect();
			$wynik = mysql_query($zapytanie);
			//exit();
			$zapytanie2="";
			if (mysql_num_rows($wynik)==1)
			{
//Jezeli istnieje
                                if(defined('DEBUG'))
				  echo "<br>adres istnieje";
				$wynik = mysql_fetch_array($wynik);
				if(!$wynik['device'])
				{
//Jeżeli istnieje ale jest nie przypisany to przypisujemy go do tego device
					$zapytanie2 = "UPDATE `Adres_ip` SET `device`='$_dev_id' 
						WHERE ip='".$adres['ip']."' AND podsiec='".$adres['podsiec']."'";
					$wynik = $this->query($zapytanie2);
					$this->queryLogger($zapytanie);
				}
				
				elseif($wynik['device']!=$_dev_id)
				{
		//Jeżeli istnieje i jest przypisane do innego to wywalamy error
					echo "Dev_id $_dev_id: Adres ".$adres['ip']." jest przypisany do innego urządzenia(id=".$wynik['device'].") w swojej podsieci!!!";
					exit();
				}
		//Jeżeli istnieje i jest przypisane do tego urzadzenia to nic nie robimy
			}
			elseif(mysql_num_rows($wynik)==0)
			{
		//Jeżeli nie istnieje to usuwamy dotychczasowe ip z bazy o ile takie było w tym vlanie i dodajemy nowe
				$zapytanie_x = "DELETE FROM Adres_ip WHERE device='$_dev_id' AND podsiec='".$adres['podsiec']."'";
				$wynik_x = $this->query($zapytanie);
				$this->queryLogger($zapytanie_x);
				$zapytanie2 = "INSERT INTO Adres_ip (`device`, `podsiec`, `ip`, `main`) 
					VALUES('$_dev_id', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."')";
				$wynik2 = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);

			
			}
			else
			{
				echo "Zduplikowane adresy w bazie!!!";
				exit();
			}

			//trzeba sprawdzić czy są adresy które są w bazie a których nie ma w formularzu
			//najprościej wykasowac wszystkie adresy od tego device i dodawac je na nowo.
		}
		$zapytanie = "SELECT * FROM Adres_ip WHERE device='$_dev_id'";
		$adresy_w_bazie = $this->query_assoc_array($zapytanie);
		for($j=0; $j<count($adresy_w_bazie); $j++)
		{
			$present = false;
			foreach($_device['ip'] as $adres_z_form)
			{
				if($adresy_w_bazie[$j]['ip']==$adres_z_form['ip'] && $adresy_w_bazie[$j]['podsiec']==$adres_z_form['podsiec'])
					$present=true;
			}
			if(!$present)
			{
				$zapytanie2 = "DELETE FROM Adres_ip WHERE device='$_dev_id' AND ip='".$adresy_w_bazie[$j]['ip']."' AND podsiec='".$adresy_w_bazie[$j]['podsiec']."'";
                                if(defined('DEBUG'))
				  echo"<br>$zapytanie2";
				$wykonaj = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);
			}
		}




		/*	
		$zapytanie2 = "INSERT INTO `Adres_ip` (`device`, `podsiec`, `ip`, `main`) VALUES ";
			foreach($_device['ip'] as $adres)
			{
				$zapytanie2 = $zapytanie2."('".$_dev_id."', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."'),";
			}
			echo "<br>dlugosc: ".strlen($zapytanie2)."<br>";
			$zapytanie2 = substr($zapytanie2, 0, strlen($zapytanie2)-1);
			$wynik2 = $this->query($zapytanie2);
*/
			$parent_ports = $_uplink_parent_ports;
//sprawdzamy czy jest więcej portów nadrzędnych jeżeli
			$zapytanie = "SELECT parent_device FROM Agregacja WHERE device='$_dev_id' AND uplink='1' LIMIT 1";
			$base_parent = $this->query($zapytanie);
			$base_parent = $base_parent['parent_device'];
                        if(defined('DEBUG'))
			  echo "z bazy $base_parent";
			$zapytanie = "SELECT * FROM Agregacja WHERE device='$_dev_id' AND parent_device='$base_parent'";
                        $z_porty = $this->query_assoc_array($zapytanie);
			$z_porty_liczba = mysql_num_rows($this->result);
                        if(defined('DEBUG'))
                        {
			  echo "<br>Porty: ";
			  print_r($parent_ports);
			  print_r($z_porty);
                        }
			$do_usuniecia = array();
			if($base_parent == $_parent_device)
				$this->updateUplinks($_dev_id, $_parent_device, $z_porty, $_uplink_local_ports, $parent_ports, $base_parent);			
			else
			{
				$this->updateUplinks($_dev_id, $base_parent, $z_porty, Array(), Array(),'');			
				$this->updateUplinks($_dev_id, $_parent_device, Array(), $_uplink_local_ports, $parent_ports, $base_parent);			
			}
/*				
			foreach($parent_ports as $key1=>$port)
			{
				$juz_jest = false;
				foreach($z_porty as $key2=>$zapisany_port)
				{
					if($port == $zapisany_port['parent_port'])
					{
						$juz_jest = true;
						$do_usuniecia[$key2] = -10;//nie do usuniecia
						echo"<br>port o numerze: $key2 nie zostanie usuniety";
					}
				}
				if(!$juz_jest)
				{
					//jeżeli port nadrzędny nie był wczesniej podłaczony do naszego device
					$zapytanie = "SELECT dev_id FROM Agregacja WHERE Agregacja.parent_device='$_parent_device' 
						AND Agregacja.parent_port='$port'";
					$wynik = mysql_query($zapytanie);
					if(mysql_num_rows($wynik)>0)
					{
						//ten port jest jużokupowany
						echo "Ten port jest już zajęty!!!";
						exit();
					}
					else
					{
						$zapytanie4 = "SELECT * FROM `Agregacja` WHERE device='$_dev_id' AND parent_device='$_parent_device' AND parent_port='$port'";
						$wynik4 = $this->query($zapytanie4);
		//przeciwdziałanie dodawaniu duplikatów
						if(mysql_num_rows($this->result)==0){
							$zapytanie3 = "INSERT INTO `Agregacja` (`device`, `parent_port`, `parent_device`, `local_port`, `uplink`) 
								VALUES('$_dev_id', '$port', '$_parent_device', '".$_uplink_local_ports[$key1]."','1')";
							$wynik3 = $this->query($zapytanie3);
						}
					}
				}

			}
			//teraz usuwamy porty nadrzedne ktore zostaly odlaczone
			$wynik4 = true;
			echo"<br>Porty do usuniecia:";
			print_r($do_usuniecia);
			for($key=0; $key<$z_porty_liczba; $key++)
			{
				if($do_usuniecia[$key]!=-10)
				{
					$zapytanie = "DELETE FROM Agregacja WHERE device='$_dev_id' AND parent_port='".$z_porty[$key]['parent_port']."'";
					$wynik = mysql_query($zapytanie);
					if(mysql_affected_rows($this->sql)>0)
						echo "<br>key: $key - Usunieto port ".$z_porty[$key]['parent_port']."!";
					else
					{
						echo "<br>key: $key - Nie moglem usunac portu: ".$z_port[$key]['parent_port']."!!";
						$wynik4 = false;
					}
				}

			}
*/

			if($wynik2)
			{
				$this->mac = $_mac;	//zakonczone powodzeniem
				$this->dev_id = $_dev_id;	//zakonczone powodzeniem
                                if(defined('DEBUG'))
				  echo "<br>Edycja adresów IP i portów zakończona powodzeniem<br>";
			}
			else
			{
				$this->mac = -1;	//zakonczone niepowodzeniem
				$this->dev_id = -1;	//zakonczone niepowodzeniem
				echo "<br>Edycja adresów zakonczone niepowodzeniem<br>";
				echo mysql_error();
			}
                        if(defined('DEBUG'))
			  echo "$zapytanie2<br>$zapytanie3<br>";
		}
		else
		{
			$this->mac = -1;	//zakonczone niepowodzeniem
			$this->dev_id = -1;	//zakonczone niepowodzeniem
			echo "<br>Edycja urzadzenia zakonczone niepowodzeniem<br>";
		}

	}
	public function modyfikuj_virtual($_device)
	{
		
          if($_device['osiedle'])
          {
		$this->lokalizacja =  $this->znajdz_lokalizacje($_device['osiedle'], $_device['blok'], $_device['klatka']);
		if(!$this->lokalizacja)
		{
			echo "nie udalo sie dodac lokalizacji";
			$this->dev_id = -1;	
			$this->mac = -1;
			exit();
		}
          }
          else
            $this->lokalizacja = 2;
//Sprawdzamy poprawność składniową danych
		if($this->sprawdzDeviceSkladnia($_device) > 0 )
			exit(0);
//Zabezpieczenie przed sql injection		
		$sql = $this->connect();
		$_exists;
		if($_device['exists'])
			$_exists = 1;
		else
			$_exists = 0;
		$_mac = strtolower(mysql_real_escape_string($_device['mac']));
		$_dev_id = mysql_real_escape_string($_device['dev_id']);
		$_other_name = mysql_real_escape_string($_device['other_name']);
		$_opis = mysql_real_escape_string($_device['opis']);
		$_device_type = "Virtual";
		$_parent_device = mysql_real_escape_string($_device['parent_device']);
		$_parent_port = mysql_real_escape_string($_device['parent_port']);
		$this->lokalizacja = mysql_real_escape_string($this->lokalizacja);
		$_uplink_parent_ports = $_device['uplink_parent_ports'];
		$_uplink_local_ports = $_device['uplink_local_ports'];
                $subnet_occur;
		foreach($_device['ip'] as &$adres)
		{
			$adres['ip'] = mysql_real_escape_string($adres['ip']);
			$adres['podsiec'] = mysql_real_escape_string($adres['podsiec']);
			$adres['main'] = mysql_real_escape_string($adres['main']);
                        if($adres['podsiec'])
                        {
                          if(! $subnet_occur[$adres['podsiec']])
                            $subnet_occur[$adres['podsiec']] = true;
                          else
                            die('Podano 2 adresy w jednej podsieci!!!');
                        }
		}
                print_r($subnet_occur);
		unset($adres);
		echo ("count". count($_uplink_parent_ports));
		for($k=0; $k<count($_uplink_parent_ports);$k++)
		{
			$_uplink_parent_ports[$k] =  mysql_real_escape_string($_uplink_parent_ports[$k]);
			$_uplink_local_ports[$k] =  mysql_real_escape_string($_uplink_local_ports[$k]);
                        if(defined('DEBUG'))
			  print_r($_uplink_parent_ports);
		}
	//	sprawdzamy dostępność uplinku

		if($this->findDuplicates($_uplink_parent_ports) || $this->findDuplicates($_uplink_local_ports))
			die("Porty uplinku nie moga się powtarzać!!!");
	//	Po sprawdzeniu parametrow laczymy sie z baza i dodajemy do niej rekord
			$zapytanie = "UPDATE `Device` SET `exists`='$_exists', `virtual`='1', `mac`='$_mac', `gateway`='$_gateway', `opis`='$_opis',
			`device_type`='$_device_type', `lokalizacja`='$this->lokalizacja',
			 `other_name`='$_other_name' WHERE dev_id='$_dev_id'"; 
		$wynik = $this->query($zapytanie);
		
                $this->queryLogger($zapytanie);
                if(defined('DEBUG'))
                  echo("<br>".$zapytanie."<br>");
		if ($wynik)
		{
		echo "<br>Zaktualizowano device<br>";	
		//tu trzebaby sprawdzić czy ip istnieje.
		$wynik2 = $wynik3 = true;
		foreach($_device['ip'] as $adres)
		{
		    $zapytanie = "SELECT * FROM Adres_ip WHERE ip='".$adres['ip']."' AND podsiec='".$adres['podsiec']."'";
                    echo $zapytanie;
                    if(defined('DEBUG'))
                    {
			echo"<br>";
			print_r($adres);
			echo"<br>$zapytanie<br>";
                    }
                    $sql = $this->connect();
			$wynik = mysql_query($zapytanie);
			//exit();
			$zapytanie2="";
			if (mysql_num_rows($wynik)==1)
			{
//Jezeli istnieje
                                if(defined('DEBUG'))      
				  echo "<br>adres istnieje";
				$wynik = mysql_fetch_array($wynik);
				if(!$wynik['device'])
				{
//Jeżeli istnieje ale jest nie przypisany to przypisujemy go do tego device
					$zapytanie2 = "UPDATE `Adres_ip` SET `device`='$_dev_id' 
						WHERE ip='".$adres['ip']."' AND podsiec='".$adres['podsiec']."'";
					$wynik = $this->query($zapytanie2);
					$this->queryLogger($zapytanie);
				}
				
				elseif($wynik['device']!=$_dev_id)
				{
		//Jeżeli istnieje i jest przypisane do innego to wywalamy error
					echo "Dev_id $_dev_id: Adres ".$adres['ip']." jest przypisany do innego urządzenia(id=".$wynik['device'].") w swojej podsieci!!!";
					exit();
				}
		//Jeżeli istnieje i jest przypisane do tego urzadzenia to nic nie robimy
			}
			elseif(mysql_num_rows($wynik)==0)
			{
		//Jenżeli nie istnieje to usuwamy dotychczasowe ip z bazy o ile takie było w tym vlanie i dodajemy nowe
				$zapytanie_x = "DELETE FROM Adres_ip WHERE device='$_dev_id' AND podsiec='".$adres['podsiec']."'";
				$wynik_x = $this->query($zapytanie);
				$this->queryLogger($zapytanie_x);
				$zapytanie2 = "INSERT INTO Adres_ip (`device`, `podsiec`, `ip`, `main`) 
					VALUES('$_dev_id', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."')";
				$wynik2 = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);

			
			}
			else
			{
				echo "Zduplikowane adresy w bazie!!!";
				exit();
			}

			//trzeba sprawdzić czy są adresy które są w bazie a których nie ma w formularzu
			//najprościej wykasowac wszystkie adresy od tego device i dodawac je na nowo.
		}
		$zapytanie = "SELECT * FROM Adres_ip WHERE device='$_dev_id'";
		$adresy_w_bazie = $this->query_assoc_array($zapytanie);
		for($j=0; $j<count($adresy_w_bazie); $j++)
		{
			$present = false;
			foreach($_device['ip'] as $adres_z_form)
			{
				if($adresy_w_bazie[$j]['ip']==$adres_z_form['ip'] && $adresy_w_bazie[$j]['podsiec']==$adres_z_form['podsiec'])
					$present=true;
			}
			if(!$present)
			{
				$zapytanie2 = "DELETE FROM Adres_ip WHERE device='$_dev_id' AND ip='".$adresy_w_bazie[$j]['ip']."' AND podsiec='".$adresy_w_bazie[$j]['podsiec']."'";
                                if(defined('DEBUG'))
				  echo"<br>$zapytanie2";
				$wykonaj = $this->query($zapytanie2);
				$this->queryLogger($zapytanie2);
			}
		}




		/*	
		$zapytanie2 = "INSERT INTO `Adres_ip` (`device`, `podsiec`, `ip`, `main`) VALUES ";
			foreach($_device['ip'] as $adres)
			{
				$zapytanie2 = $zapytanie2."('".$_dev_id."', '".$adres['podsiec']."', '".$adres['ip']."', '".$adres['main']."'),";
			}
			echo "<br>dlugosc: ".strlen($zapytanie2)."<br>";
			$zapytanie2 = substr($zapytanie2, 0, strlen($zapytanie2)-1);
			$wynik2 = $this->query($zapytanie2);
*/
			$parent_ports = $_uplink_parent_ports;
//sprawdzamy czy jest więcej portów nadrzędnych jeżeli
			$zapytanie = "SELECT parent_device FROM Agregacja WHERE device='$_dev_id' AND uplink='1' LIMIT 1";
			$base_parent = $this->query($zapytanie);
			$base_parent = $base_parent['parent_device'];
			$zapytanie = "SELECT * FROM Agregacja WHERE device='$_dev_id' AND parent_device='$base_parent'";
			$z_porty = $this->query_assoc_array($zapytanie);
			$z_porty_liczba = mysql_num_rows($this->result);
                        if(defined('DEBUG'))
                        {
                          echo "z bazy $base_parent";
                          echo "<br>Porty: ";
                          print_r($parent_ports);
                          print_r($z_porty);
                        } 
			$do_usuniecia = array();
			if($base_parent == $_parent_device)
				$this->updateUplinks($_dev_id, $_parent_device, $z_porty, $_uplink_local_ports, $parent_ports, $base_parent);			
			else
			{
				$this->updateUplinks($_dev_id, $base_parent, $z_porty, Array(), Array(),'');			
				$this->updateUplinks($_dev_id, $_parent_device, Array(), $_uplink_local_ports, $parent_ports, $base_parent);			
			}
/*				
			foreach($parent_ports as $key1=>$port)
			{
				$juz_jest = false;
				foreach($z_porty as $key2=>$zapisany_port)
				{
					if($port == $zapisany_port['parent_port'])
					{
						$juz_jest = true;
						$do_usuniecia[$key2] = -10;//nie do usuniecia
						echo"<br>port o numerze: $key2 nie zostanie usuniety";
					}
				}
				if(!$juz_jest)
				{
					//jeżeli port nadrzędny nie był wczesniej podłaczony do naszego device
					$zapytanie = "SELECT dev_id FROM Agregacja WHERE Agregacja.parent_device='$_parent_device' 
						AND Agregacja.parent_port='$port'";
					$wynik = mysql_query($zapytanie);
					if(mysql_num_rows($wynik)>0)
					{
						//ten port jest jużokupowany
						echo "Ten port jest już zajęty!!!";
						exit();
					}
					else
					{
						$zapytanie4 = "SELECT * FROM `Agregacja` WHERE device='$_dev_id' AND parent_device='$_parent_device' AND parent_port='$port'";
						$wynik4 = $this->query($zapytanie4);
		//przeciwdziałanie dodawaniu duplikatów
						if(mysql_num_rows($this->result)==0){
							$zapytanie3 = "INSERT INTO `Agregacja` (`device`, `parent_port`, `parent_device`, `local_port`, `uplink`) 
								VALUES('$_dev_id', '$port', '$_parent_device', '".$_uplink_local_ports[$key1]."','1')";
							$wynik3 = $this->query($zapytanie3);
						}
					}
				}

			}
			//teraz usuwamy porty nadrzedne ktore zostaly odlaczone
			$wynik4 = true;
			echo"<br>Porty do usuniecia:";
			print_r($do_usuniecia);
			for($key=0; $key<$z_porty_liczba; $key++)
			{
				if($do_usuniecia[$key]!=-10)
				{
					$zapytanie = "DELETE FROM Agregacja WHERE device='$_dev_id' AND parent_port='".$z_porty[$key]['parent_port']."'";
					$wynik = mysql_query($zapytanie);
					if(mysql_affected_rows($this->sql)>0)
						echo "<br>key: $key - Usunieto port ".$z_porty[$key]['parent_port']."!";
					else
					{
						echo "<br>key: $key - Nie moglem usunac portu: ".$z_port[$key]['parent_port']."!!";
						$wynik4 = false;
					}
				}

			}
*/

			if($wynik2)
			{
				$this->mac = TRUE;	//zakonczone powodzeniem
				$this->dev_id = $_dev_id;	//zakonczone powodzeniem
                                $host = new Host();
			        $host->updateDhcp($this->dev_id, $_mac, 'update');
				echo "<br>Edycja adresów IP i portów zakończona powodzeniem<br>";
			}
			else
			{
				$this->mac = FALSE;	//zakonczone niepowodzeniem
				$this->dev_id = -1;	//zakonczone niepowodzeniem
				echo "<br>Edycja adresów zakonczone niepowodzeniem<br>";
				echo mysql_error();
			}
                        if(defined('DEBUG'))
			  echo "$zapytanie2<br>$zapytanie3<br>";
		}
		else
		{
			$this->mac = -1;	//zakonczone niepowodzeniem
			$this->dev_id = -1;	//zakonczone niepowodzeniem
			echo "<br>Edycja uzadzenia zakonczone niepowodzeniem<br>";
		}

	}
	private function znajdz_lokalizacje($_osiedle, $_blok, $_klatka)
	{
		$lokalizacja = new Lokalizacja();
		$lokalizacja->dodaj($_osiedle, $_blok, $_klatka);
		return $lokalizacja->id;
	}
	public  function wymien($_porty_nowe, $_dev_id_1, $_dev_id_2)
	{

		//			     $porty_nowe['port1.2.3'] = 'port5.6.7'
		//			        port urządzenia1 ^          ^port urządzenia 2

		$this->connect();
		$_dev_id_1 = mysql_real_escape_string($_dev_id_1);
		$_dev_id_2 = mysql_real_escape_string($_dev_id_2);

		//najpierw pobieramy info o dotychczasowym przypisaniu portów
		$zapytanie = "SELECT Agregacja.parent_port , Agregacja.device , 0 as uplink FROM Agregacja  WHERE Agregacja.parent_device='$_dev_id_1' UNION
			SELECT Agregacja.local_port , Agregacja.parent_device , 1 as uplink FROM Agregacja  WHERE Agregacja.device='$_dev_id_1'";
		$porty_stare_1 = $this->query_assoc_array($zapytanie);
		$zapytanie = "SELECT Agregacja.parent_port , Agregacja.device , 0 as uplink FROM Agregacja  WHERE Agregacja.parent_device='$_dev_id_2' UNION
			SELECT Agregacja.local_port , Agregacja.parent_device , 1 as uplink FROM Agregacja  WHERE Agregacja.device='$_dev_id_2'";
		$porty_stare_2 = $this->query_assoc_array($zapytanie);

		//teraz tworzymy listę wszystkich portów portów urządzeń
		$model_1 = $this->getDeviceModel($_dev_id_1);
		$model_2 = $this->getDeviceModel($_dev_id_2);
		$port_list_1 = $this->getModelPortList($model_1['id']);
		$tmp = array();
		foreach($port_list_1 as $port)
			$tmp[$port]="";
                unset($port);
		$port_list_1 = $tmp;
		$port_list_2 = $this->getModelPortList($model_2['id']);
		$tmp = array();
		foreach($port_list_2 as $port)
			$tmp[$port]="";
                unset($port);
		$port_list_2 = $tmp;
		if($porty_stare_1)
		  foreach($port_list_1 as $port)
				foreach($porty_stare_1 as $key=>$element)
					$port_list_1[$element['parent_port']]=$element['device'];
		
		if($porty_stare_2)
		  foreach($port_list_2 as $port)
				foreach($porty_stare_2 as $key=>$element)
					$port_list_2[$element['parent_port']]=$element['device'];


//		echo"<br>$_dev_id_1<br>$_dev_id_2<br>";
                define('DEBUG', true);
                if(defined('DEBUG'))
                {
                  print_r($porty_stare_1);
                  print_r($port_list_1);
		  foreach($port_list_1 as $key=>$port)
			echo"<br>$key => $port";
	  	  foreach($port_list_2 as $key=>$port)
			echo"<br>$key => $port";
		  foreach($_porty_nowe as $key=>$port)
			echo"<br>$key => $port";

                }
		//tworzymy tablice do dalszych weryfikacji

		$uzyty2 = array();
		if($_porty_nowe)
		foreach($_porty_nowe as $key1=>$port1)
		{
			$port1 = mysql_real_escape_string($port1);
			$przypisany = false;
			foreach($_porty_nowe as $key2=>$port2)
			{
				$port2 = mysql_real_escape_string($port2);
				if($port1 && $port2 && $key1!=$key2 && $port1 == $port2)
				{
					die("zdublowany port!!!");
				}
			}
			if($port1 == "" && $port_list_1[$key1] != "")
				die("nie przypisano wszystkich portów z urzadzenia 1!!!");
			if($port1)
				$uzyty2[$port1] = true;
		}
		foreach($port_list_2 as $_port=>$_dev_id)
			if($_dev_id && !$uzyty2[$_port])
				die("nie przypisano wszystkich portów z urządzenia 2!!!");
		//Warunki sprawdzone teraz czas na podmianę
		//ropoczynamy transakcję
		$this->query("SET AUTOCOMMIT=0", $this->sql);
		$this->query("BEGIN", $this->sql);
		foreach($port_list_1 as $_port=>$_dev_id)
		{
			if($_dev_id)
			{
				$zapytanie = "UPDATE Agregacja, Device SET Agregacja.parent_device='1', Agregacja.parent_port='".$_porty_nowe[$_port]."'  WHERE Agregacja.parent_device='$_dev_id_1' AND Agregacja.parent_port='".$_port."'";
//				echo"<br>$zapytanie<br>";
				$this->query($zapytanie) or die(mysql_error());
				$this->queryLogger($zapytanie);
				$zapytanie = "UPDATE Agregacja, Device SET Agregacja.device='1', Agregacja.local_port='".$_porty_nowe[$_port]."'  WHERE Agregacja.device='$_dev_id_1' AND Agregacja.local_port='".$_port."'";
//				echo"<br>$zapytanie<br>";
				$this->query($zapytanie) or die(mysql_error());
				$this->queryLogger($zapytanie);

			}
		}
		foreach($port_list_2 as $_port=>$_dev_id)
		{
			if($_dev_id)
			{
				foreach($_porty_nowe as $key2=>$nowy)
					if($nowy == $_port)
					{
						$zapytanie = "UPDATE Agregacja, Device SET Agregacja.parent_device='$_dev_id_1', Agregacja.parent_port='".$key2."'  WHERE Agregacja.parent_device='$_dev_id_2' AND Agregacja.parent_port='".$_port."'";
						$this->query($zapytanie) or die(mysql_error());
						$this->queryLogger($zapytanie);
						$zapytanie = "UPDATE Agregacja, Device SET Agregacja.device='$_dev_id_1', Agregacja.local_port='".$key2."'  WHERE Agregacja.device='$_dev_id_2' AND Agregacja.local_port='".$_port."'";
						$this->query($zapytanie) or die(mysql_error());
						$this->queryLogger($zapytanie);
						break;

					}
			}
		}
		$zapytanie = "UPDATE Agregacja, Device SET Agregacja.parent_device='$_dev_id_2'  WHERE Agregacja.parent_device='1'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
		$zapytanie = "UPDATE Agregacja, Device SET Agregacja.device='$_dev_id_2'  WHERE Agregacja.device='1'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
		$zapytanie = "SELECT Device.lokalizacja, Device.other_name FROM Device WHERE Device.dev_id='$_dev_id_1'";
		$dev1 = $this->query_assoc($zapytanie) or die(mysql_error());	
		$zapytanie = "SELECT Device.lokalizacja, Device.other_name FROM Device WHERE Device.dev_id='$_dev_id_2'";
		$dev2 = $this->query_assoc($zapytanie) or die(mysql_error());	
		if(!$dev1 || !$dev2)
			die("nie udało się pobrać info o jednym z adresów dev_id: $_dev_id_1     $_dev_id_2");


// zamiana lokalizacji i parent_device zamienianych przełączników ***************************
		$zapytanie = "UPDATE Device SET Device.lokalizacja='".$dev2['lokalizacja']."', Device.other_name='".$dev2['other_name']."' WHERE Device.dev_id='$_dev_id_1'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
//		echo $zapytanie."<br>";
		$zapytanie = "UPDATE Device SET Device.lokalizacja='".$dev1['lokalizacja']."', Device.other_name='".$dev1['other_name']."' WHERE Device.dev_id='$_dev_id_2'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
//		echo $zapytanie."<br>";
//*******************************************************************************************

//zamiana adresów ip ************************************************************************
		$zapytanie = "UPDATE Adres_ip SET Adres_ip.device='1' WHERE Adres_ip.device='$_dev_id_1'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
//	i	echo $zapytanie."<br>";
		$zapytanie = "UPDATE Adres_ip SET Adres_ip.device='$_dev_id_1' WHERE Adres_ip.device='$_dev_id_2'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
//		echo $zapytanie."<br>";
		$this->swap('1', $_dev_id_2);
//		echo $zapytanie."<br>";
		echo"zamieniono";
//*******************************************************************************************

		$lokalizacja1 = $this->query("SELECT CONCAT(osiedle, ' ', nr_bloku, klatka) as lok FROM Lokalizacja WHERE id='".$dev1['lokalizacja']."'");
		$lokalizacja2 = $this->query("SELECT CONCAT(osiedle, ' ', nr_bloku, klatka) as lok FROM Lokalizacja WHERE id='".$dev2['lokalizacja']."'");
		$this->loguj($_dev_id_1, $dev1['lokalizacja'], $user, 'Porzeniesiono na lokalizację '.$lokalizacja2['lok'], 'wymien');
		$this->loguj($_dev_id_2, $dev2['lokalizacja'], $user, 'Porzeniesiono na lokalizację '.$lokalizacja1['lok'], 'wymien');
		$this->loguj($_dev_id_1, $dev2['lokalizacja'], $user, 'Zamontowano '.$dev_id_2, 'wymien');
		$this->loguj($_dev_id_2, $dev1['lokalizacja'], $user, 'Zamontowano '.$dev_id_1, 'wymien');
		$this->query("COMMIT");
	        echo"<center><a href=\"index.php?device=$_dev_id_2\">Powrót</a></center>";
			
	}
	function swap($current_dev, $new_dev)
	{
		$zapytanie = "UPDATE Adres_ip SET Adres_ip.device='$new_dev' WHERE Adres_ip.device='$current_dev'";
		$this->query($zapytanie) or die(mysql_error());
		$this->queryLogger($zapytanie);
	}
	function updateUplinks($l_dev, $p_dev, $base_uplink, $form_local_port, $form_parent_port, $b_p_dev)
	{
                if(defined('DEBUG'))
                {
                  echo"<br>base ";
                  print_r($base_uplink);
                  echo"<br>local ";
                  print_r($form_local_port);
                  echo"<br> parent ";
                  print_r($form_parent_port);
                  echo"<br>";
                  echo"<br>";
                }
                //funkcja ta dziala przy zalozeniu ze nie istnieje polaczenie o zamienionych wartosciach device i parent_device

		//sprawdzamy prawidłowość danych danych
		
		//pobieramy listę wszystkich portów obu urządzeń
		$zapytanie = "SELECT device, parent_device, local_port, parent_port FROM Agregacja WHERE device='$l_dev' UNION
			SELECT parent_device, device, parent_port, local_port FROM Agregacja WHERE parent_device='$l_dev'";
		$l_dev_all_ports = $this->query_assoc_array($zapytanie);
		$zapytanie = "SELECT device, parent_device, local_port, parent_port FROM Agregacja WHERE parent_device='$p_dev' UNION
			SELECT parent_device, device, parent_port, local_port FROM Agregacja WHERE device='$p_dev'";
		$p_dev_all_ports = $this->query_assoc_array($zapytanie);
		if(count($form_local_port)>0)
		foreach($form_local_port as $key_l=>$form_local_one)
			{
		//sprawdzamy czy podany port lokalny nie jest wykorzystywany przez inne urzadzenie niz nadrzedne
                              if(is_array($l_dev_all_ports))
				 foreach($l_dev_all_ports as $l_dev_one)
					if($l_dev_one['local_port'] == $form_local_one  && $l_dev_one['parent_device']!=$b_p_dev && $l_dev_one['parent_device']!=$p_dev)
						die("port ".$form_local_one." zajęty");
		
		//sprawdzamy czy podany nadrzedny port nie jest wykorzystywany przez inne urzadzenie niz lokalne
				 foreach($p_dev_all_ports as $p_dev_one)
					if($p_dev_one['parent_port'] == $form_parent_port[$key_l]  && $p_dev_one['device']!=$l_dev)
						die("port ".$form_parent_port[$key_l]." zajęty");
			}
		//
		$zapytanie = "SELECT * FROM Agregacja WHERE device='$l_dev' AND parent_device='$p_dev'";
		$base_uplink_array = $this->query_assoc_array($zapytanie);
		$present = array();
                if(is_array($base_uplink))
		foreach($base_uplink as $b_uplink)
		{
			$record_occurs = false;
			foreach($form_local_port as $key=>$form_local_one)
			{
                          if(defined('DEBUG'))
                          {
				echo"<br> Lokal ".$b_uplink['local_port']." $form_local_one<br>";
				echo"Parent ".$b_uplink['parent_port']." ".$form_parent_port[$key]."<br>";
                          }
				if($b_uplink['local_port']==$form_local_one)
				{
					if($b_uplink['parent_port']==$form_parent_port[$key])
					{
						$record_occurs = true;
						$present[$key] = true;
					}
					else
					{
						$zapytanie = "UPDATE Agregacja SET parent_port='".$form_parent_port[$key]."', parent_device='$p_dev' WHERE
							device='$l_dev' AND parent_device='$b_p_dev' AND local_port='".$b_uplink['local_port']."'";
						$this->query($zapytanie) or die(mysql_error());
						$this->queryLogger($zapytanie);
                                                if(defined('DEBUG'))
						  echo"<br>$zapytanie<br>";
						$present[$key] = true;
						$record_occurs = true;
					}
				}
				else
				{
					if($b_uplink['parent_port']==$form_parent_port[$key])
					{
						$zapytanie = "UPDATE Agregacja SET local_port='".$form_local_one."', parent_device='$p_dev' WHERE
							device='$l_dev' AND parent_device='$b_p_dev' AND parent_port='".$b_uplink['parent_port']."'";
                                                if(defined('DEBUG'))
						  echo"<br>$zapytanie<br>";
						$this->query($zapytanie) or die(mysql_error());
						$this->queryLogger($zapytanie);
						$present[$key] = true;
						$record_occurs = true; 
					}
				}
			}
			if(!$record_occurs)
			{
				$zapytanie = "DELETE FROM Agregacja WHERE device='$l_dev' AND parent_device='$p_dev' AND 
					local_port='".$b_uplink['local_port']."' AND parent_port='".$b_uplink['parent_port']."'";
                                if(defined('DEBUG'))
				  echo"<br>$zapytanie<br>";
				$this->query($zapytanie) or die(mysql_error()); 
				$this->queryLogger($zapytanie);
			}
		}
                if(defined('DEBUG'))
                {
		  print_r($form_parent_port);
		  print_r($present);
                }
                //jeżeli uplink nie istniał wcześniej należy go dodać
		if(count($form_parent_port)>0)
		foreach($form_parent_port as $key=>$form_parent_one)
		{
			if(!$present[$key])
			{
				$zapytanie = "INSERT INTO Agregacja SET device='$l_dev', parent_device='$p_dev', local_port='".$form_local_port[$key]."',
					uplink='1', parent_port='".$form_parent_one."'";
                                if(defined('DEBUG'))
				  echo"<br>$zapytanie<br>";
				$this->query($zapytanie) or die(mysql_error());	
				$this->queryLogger($zapytanie);
			}
		}
	}
	public function usunDoMagazynu($dev_id)
	{
		$this->connect();
		$dev_id = mysql_real_escape_string($dev_id);
		$powod = mysql_real_escape_string($powod);
		$uszkodzony = mysql_real_escape_string($uszkodzony);
		$zapytanie = "SELECT * FROM Device WHERE dev_id='$dev_id'";
		$device = $this->query($zapytanie);
		if($device['device_type']=='Host')
			die("Hostów nie przenosimy");
		//Sprawdzenie czy nie ma podrzędnych urządzeń
		$zapytanie = "SELECT device FROM Agregacja WHERE parent_device='$dev_id' AND uplink='1'";
		$children = $this->query_assoc_array($zapytanie);
		if(count($children))
		{
			die("urządzenie o ID=$dev_id ma urządzenia podrzędne");
		}
		//Usunięcie powiązanych IP
		$this->query("SET AUTOCOMMIT=0");
		$this->query("BEGIN");
		$zapytanie = "DELETE FROM Adres_ip WHERE device='$dev_id'";
		$adresy = $this->query($zapytanie);
		$this->queryLogger($zapytanie);

		//Usunięcie powiązanych łaczy w agregacji
		$zapytanie = "DELETE FROM Agregacja WHERE device='$dev_id' OR parent_device='$dev_id'";
		$lacza = $this->query($zapytanie);
		$this->queryLogger($zapytanie);

		//Zmiana lokalizacji na magazyn
		$magazyn = $this->znajdz_lokalizacje('00000','','');
		$zapytanie = "UPDATE Device SET lokalizacja='$magazyn' WHERE dev_id='$dev_id'";
		$lokalizacja = $this->query($zapytanie);
		$this->queryLogger($zapytanie);
		$this->loguj($dev_id, $device['lokalizacja'], $user, 'Przeniesiono do magazynu' , 'modyfikuj');
		$this->loguj($dev_id, $magazyn, $user, 'Przeniesiono do magazynu' , 'modyfikuj');
		if($adresy && $lacza && $lokalizacja)
		{
			$this->query("COMMIT");
			echo "Przeniesiono do magazynu";
		}
		else	
		{
			$this->query("ROLLBACK");
			echo "Nie udało się przenieść do magazynu";
		}
	echo"<center><a href=\"index.php\">Powrót</a></center>";
}

public function usunVirtual($dev_id)
{
	$dev_id = intval($dev_id);
	if($this->getType($dev_id) == 'Virtual')
	{
		$query = "DELETE FROM Device WHERE dev_id='$dev_id'";
		$this->query($query);
		$this->queryLogger($query);
		return 0;
	}
	else
		echo "<center>To nie jest urządzenie virtualne!!!</center><center><a href=\"index.php\">Powrót</a></center>";
	return 1;
}
public function changeMac($dev_id, $mac)
{
  $mac = strtolower($mac);
  $con_id = intval($con_id);
  $old_mac = $this->getDeviceMac($dev_id);
  if(!$this->sprawdz_mac_skladnia($mac))
  {
    echo  "Niewłaściwy format addresu mac!";
    return false;
  }
  elseif(!$this->sprawdz_mac($mac))
  {
    echo "zajety adres mac!";
    return false;
  }
  elseif($dev_id)
  {
    $query = "UPDATE Device SET mac='$mac' WHERE dev_id='$dev_id'";
    $result = $this->query_update($query, $dev_id, 'Device', 'dev_id');
    if($this->getDeviceType($dev_id)=="Host")
    {
      $host = new Host();
      $host->updateDhcp($dev_id, $mac, 'update');
    }
    $this->loguj($dev_id, $this->getDeviceLoc($dev_id), $user, "zmiana adresu mac ($old_mac)", 'modyfikuj');
    if($result)
    {
      return true;
    }
    return false;
  }
}



private function getOldMac()
{
//	$query = "SELECT * FROM Device WHERE dev_id='$this->dev_id'";
//	$
}
}
