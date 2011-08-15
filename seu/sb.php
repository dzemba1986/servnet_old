<?php 
class Switch_bud extends Daddy
{
        public $device;
	public function dodaj($_device, $_sn, $_model, $_producent, $_typ, $_port_count, $_vlan, $_opis_historii)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent, $_port_count);
		if ($errors > 0)
			exit(0);
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->dodaj($_device);
		if($this->device->dev_id != -1)
		{
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_sn = mysql_real_escape_string($_sn);
			$_opis_historii = mysql_real_escape_string($_opis_historii);
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			$_typ = mysql_real_escape_string($_typ);
			$_port_count = mysql_real_escape_string($_port_count);
			$_vlan = mysql_real_escape_string($_vlan);
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Switch_bud (device, sn, model, producent, typ, port_count, vlan) 
				 VALUES('".$this->device->dev_id."', '$_sn', '$_model', '$_producent', '$_typ', '$_port_count', '$_vlan')";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'dodaj');
			}
		}

	}
	public function dodajDoMagazynu($mac, $sn, $model, $producent)
	{
		
	public function modyfikuj($_device, $_sn, $_model, $_producent, $_typ, $_port_count, $_vlan, $_opis_historii)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent, $_port_count);
		if ($errors > 0)
			exit(0);
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->modyfikuj($_device);
		if($this->device->dev_id != -1)
		{
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_sn = mysql_real_escape_string($_sn);
			$_opis_historii = mysql_real_escape_string($_opis_historii);
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			$_typ = mysql_real_escape_string($_typ);
			$_port_count = mysql_real_escape_string($_port_count);
			$_vlan = mysql_real_escape_string($_vlan);
			//wsio pasi tworzymy przełącznik
			$zapytanie = "UPDATE Switch_bud SET device='".$this->device->dev_id."', sn='$_sn', model='$_model',
				 producent='$_producent', port_count='$_port_count', typ='$_typ', vlan='$_vlan' WHERE device='".$this->device->dev_id."'";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'modyfikuj');
			}
		}

	}
	public function sprawdz_vlan()
	{
		if($vlan > 0 && $vlan < 4097)
			return true;
		return false;
	}
	private function sprawdzDane($_sn, $_model, $_producent, $_port_count)
	{
		$errors = 0;
		if(! $this->sprawdz_sn($_sn))
		{
			Daddy::error("Nieprawidłowy numer seryjny");
			$errors++;
		}
		if(! $this->sprawdz_l_portow($_port_count))
		{
			Daddy::error("Nieprawidłowa liczba portów");
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
}

