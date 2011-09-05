<?php 
class Router extends Daddy
{
	public $sn;
	public $device;
	public $producent;
	public $model;
 	public function dodaj_do_magazynu($_device, $_sn, $_model, $_producent)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent);
		if ($errors > 0)
			exit(0);
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->dodajDoMagazynu($_device);
		if($this->device->dev_id != -1)
		{
			$sql = $this->connect();
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_sn = mysql_real_escape_string(htmlspecialchars($_sn));
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
	
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Router (device, sn, model, producent) 
				 VALUES('".$this->device->dev_id."', '$_sn', '$_model', '$_producent')";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->queryLogger($zapytanie);
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, 'Dodano do magazynu', 'dodaj');
			}
		}
	}
 	public function dodaj($_device, $_sn, $_model, $_producent, $_opis_historii)
	{
		$errors = $this->sprawdzDane($_sn, $_model, $_producent);
		if ($errors > 0)
			exit(0);
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->dodaj($_device, $this);
		if($this->device->dev_id != -1)
		{
			$sql = $this->connect();
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_sn = mysql_real_escape_string(htmlspecialchars($_sn));
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			$_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));
	
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Router (device, sn, model, producent) 
				 VALUES('".$this->device->dev_id."', '$_sn', '$_model', '$_producent')";
			$wynik = $this->query($zapytanie);
			if (!$wynik)
				$this->device = -1;	//zakonczone niepowodzeniem
			else
			{
				$this->queryLogger($zapytanie);
				$this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'dodaj');
			}
		}
	}
	public function modyfikuj($_device, $_opis_historii)
	{
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->modyfikuj($_device);
		if($this->device->dev_id != -1)
		{
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));
			$zapytanie = "UPDATE Router SET device='".$this->device->dev_id."'
                           WHERE device='".$this->device->dev_id."'";
			$wynik = $this->query($zapytanie);
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
}

