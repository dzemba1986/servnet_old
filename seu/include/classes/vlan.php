<?php 
class Vlan extends Daddy
{
	public function dodajVlan($vid, $opis)
	{
		if (($vid==='0' ||  ($vid >= 1 && $vid < 4097)) && $opis && strlen($opis)<15)
		{
			$sql = $this->connect();
			$opis = mysql_real_escape_string($opis);
			$zapytanie = "SELECT vid FROM Vlan WHERE vid='$vid'";
			$wynik = mysql_query($zapytanie);
			if (mysql_affected_rows($sql)>0)
			{
				echo("Vlan $vid już istnieje!");
				return;
			}
			else
			{
				$zapytanie = "INSERT INTO Vlan (`vid`, `opis`) VALUES('$vid', '$opis')";
				$wynik = mysql_query($zapytanie);
				if (mysql_affected_rows($sql)>0)
				{
					echo("Dodano Vlan $vid.");
				        return;
				}

			}
			echo("Nie dodano Vlanu $vid!!!");
		}
		else
			echo("Błędne dane wejściowe $vid $opis");
	}
	public function usunVlan($vid)
	{
		$odpowiedz;
		if($vid==='0' || ($vid > 1 && $vid < 4097))
		{
			if (Vlan::vlanIstnieje($vid))
			{
				if (!Vlan::vlanJestPusty($vid))
					$odpowiedz = "W tym Vlanie istnieją jeszcze podsieci! Aby móc usunąć Vlan $vid należy usuną wszystkie podsieci do niego należące!";
				else
				{
					$sql = $this->connect();
					$zapytanie = "DELETE FROM Vlan WHERE vid='$vid'";
					$wynik = mysql_query($zapytanie);
					if (mysql_affected_rows($sql)==1)
						$odpowiedz = "Usunieto Vlan $vid.";
					else
						$odpowiedz = "nie usunieto Vlanu $vid.";
				}
			}
			else
				$odpowiedz = "Vlan $vid nie istnieje!";
		}
		echo $odpowiedz;
	}
	public function vlanIstnieje($vid)
	{
		$sql = $this->connect();
		$vid = mysql_real_escape_string($vid);
		$zapytanie = "SELECT vid FROM Vlan WHERE vid='$vid'";
		$wynik = mysql_query($zapytanie);
		if (mysql_affected_rows($sql)>0)
			return true;
		return false;
	}
	public function vlanJestPusty($vid)
	{
		$sql = $this->connect();
		$vid = mysql_real_escape_string($vid);
		$zapytanie = "SELECT id FROM Podsiec WHERE vlan='$vid'";
		$wynik = mysql_query($zapytanie);
		if (mysql_affected_rows($sql)>0)
			return false;
		return true;
			
	}
	public function pobierzVlany()
	{
		$sql = $this->connect();
		$zapytanie = "SELECT * FROM Vlan";
		$wykonaj = mysql_query($zapytanie);
		$odpowiedz = array();
		for($i=0; $i<mysql_affected_rows($sql); $i++)
			$odpowiedz[] = mysql_fetch_assoc($wynik);
		return $odpowiedz;
	}
}

