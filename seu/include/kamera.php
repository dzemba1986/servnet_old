<?php 
class Kamera extends Daddy
{
	public $device;
	public $sn;
	public $nazwa;
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
			echo("<br>".$this->device->dev_id."<br>");
			$sql = $this->connect();
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			echo("<br>".$this->device->dev_id."<br>");
			$_sn = mysql_real_escape_string($_sn);
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
	
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Kamera (device, sn, model, producent) 
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
//			echo("<br>".$this->device->dev_id."<br>");
			$sql = $this->connect();
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
                        if(defined('DEBUG'))
			  echo("<br>".$this->device->dev_id."<br>");
			$_sn = mysql_real_escape_string($_sn);
			$_model = mysql_real_escape_string($_model);
			$_producent = mysql_real_escape_string($_producent);
			$_opis_historii = mysql_real_escape_string($_opis_historii);
	
			//wsio pasi tworzymy przełącznik
			$zapytanie = "INSERT INTO Kamera (device, sn, model, producent) 
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
	public function modyfikuj($_device, $_sn, $_opis_historii)
	{
		$this->device = new Device();
		$this->device->sql = &$this->sql;
		$this->device->modyfikuj($_device);
		if($this->device->dev_id != -1)
		{
			$this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
			$_opis_historii = mysql_real_escape_string($_opis_historii);
			$_sn = mysql_real_escape_string($_sn);
			$zapytanie = "UPDATE Kamera SET sn='".$_sn."' WHERE device='".$this->device->dev_id."'";
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

