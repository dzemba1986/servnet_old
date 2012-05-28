<?php 
class Switch_bud extends Daddy
{
        public $device;
	public $all_hosts = array();
	public function dodaj_do_magazynu($_device, $_sn, $_model, $_producent)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent);
		if ($errors > 0)
			exit(0);
                if(!$_device['mac'] && !$_sn)
                  die("Musisz podać mac lub sn!");
                
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->dodajDoMagazynu($_device);
		if($this->device->dev_id != -1)
		{
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_sn = mysql_real_escape_string($_sn);
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Switch_bud (device, sn, model, producent) 
				 VALUES('".$this->device->dev_id."', '$_sn', '$_model', '$_producent')";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->queryLogger($zapytanie);
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, 'Dodano do magazynu' , 'dodaj');
			}
		}

	}
	public function dodaj($_device, $_sn, $_model, $_producent, $_typ, $_opis_historii)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent);
		if ($errors > 0)
			exit(0);
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->dodaj($_device, $this);
		if($this->device->dev_id != -1)
		{
			$_sn = mysql_real_escape_string($_sn);
			$_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			$_typ = mysql_real_escape_string($_typ);
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Switch_bud (device, sn, model, producent, typ) 
				 VALUES('".$this->device->dev_id."', '$_sn', '$_model', '$_producent', '$_typ')";
			if(defined('DEBUG'))
                          echo"<br>$zapytanie<br>";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->queryLogger($zapytanie);
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'dodaj');
			}
		}
		else
			echo"<BR>nie udało sie utworzyć device!<BR>";

	}
	public function modyfikuj($_device, $_typ, $_sn, $_opis_historii)
	{
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->modyfikuj($_device);
		if($this->device->dev_id != -1)
		{
			$_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));
			$_typ = mysql_real_escape_string($_typ);
			$_vlan = mysql_real_escape_string($_vlan);
			$_sn = mysql_real_escape_string($_sn);
			//wsio pasi tworzymy przełącznik
			$zapytanie = "UPDATE Switch_bud SET typ='$_typ', sn='$_sn'  WHERE device='".$this->device->dev_id."'";
			$wynik = $this->query($zapytanie);
			if(defined('DEBUG'))
			  echo"<BR>$zapytanie<BR>";
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->queryLogger($zapytanie);
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'modyfikuj');
			}
		}

	}
	private function sprawdzDane($_sn, $_model, $_producent)
	{
		$errors = 0;
		if(! $this->sprawdz_sn($_sn))
		{
			Daddy::error("Nieprawidłowy numer seryjny");
			$errors++;
		}
		if(! $_producent)
		{
			Daddy::error("Nie podano producenta!");
			$errors++;
		}
		if(! $_model)
		{
			Daddy::error("Nie podano modelu!");
			$errors++;
		}
		return $errors;
	}
	public function sprawdzIp($ip)
	
	{
		$sql = $this->connect();
		$main_count;
		if(defined('DEBUG'))
	  	  print_r($ip);
		foreach($ip as $adres)
		{
			if (!$adres['ip'])
				continue;
			if(!Daddy::sprawdz_ip($adres['ip']))
			{
				Daddy::error("dupaaaa Nieprawidłowy Adres IP: ".$adres['ip']);
				return false;
			}
			if(!$this->sprawdz_podsiec($adres['podsiec']))
			{
				Daddy::error("Nieprawidłowa podsiec");
				return false;
			}
			if(! $this->sprawdz_vlan($adres['vlan']))
			{
				Daddy::error("Nieprawidłowy vlan");
				return false;
			}
			$main_count+=$adres['main'];
		}
		if($main_count > 1)
			return false;
		return true; 
	}

	public static function get_all_hosts($dev_id)
	
	{
	$zapytanie = "Select a.ip as adres_ip, p.vlan, ag.parent_device, ag.parent_port,
       		ag.local_port ,concat(l.osiedle, l.nr_bloku,'/', h.nr_mieszkania) as adres,
		concat(l.osiedle, l.nr_bloku) as adres_voip,
       		h.pakiet, d.* from Device d
        	JOIN Agregacja ag ON d.dev_id=ag.device AND ag.parent_device=:dev_id AND
        	ag.uplink=1
        	JOIN Adres_ip a ON d.dev_id=a.device AND a.main=1
        	LEFT JOIN Lokalizacja l ON l.id=d.lokalizacja
        	LEFT JOIN Host h ON h.device=d.dev_id
        	LEFT JOIN Bramka_voip b ON b.device=d.dev_id
        	JOIN Podsiec p ON a.podsiec=p.id
        	where d.device_type='Host' OR d.device_type='Bramka_voip'";
        $sql = new MysqlSeuPdo();
	$wynik = $sql->query($zapytanie, array('dev_id' => $dev_id));
        if (!$wynik)
        	die("Nie można pobrać parametrów hostów!");     //zakonczone niepowodzeniem
        else
   	     {
		return $wynik;
             }
	}
}

