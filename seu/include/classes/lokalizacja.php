<?php 
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
require(SEU_ABSOLUTE.'/include/classes/daddy.php');
if(!defined('MYSQL_LOKALIZACJA_CLASS'))
{
  define('MYSQL_LOKALIZACJA_CLASS', true);
  class Lokalizacja extends Daddy
  {
    public $id;
    public function dodaj($_ulic, $_nr_bloku, $_klatka)
    {
      $_klatka = strtolower($_klatka);
      $_blok = strtolower($_blok);
      $errors = 0;
      $osiedle;
      if($_ulic != "00000")
      {
        if(! $this->sprawdz_nr_bloku($_nr_bloku))
        {
          Daddy::error("Nieprawidłowy nr bloku");
          $errors++;
        }
        if($_klatka && !$this->sprawdz_klatke($_klatka))
        {
          Daddy::error("Nieprawidłowa klatka");
          $errors++;
        }
        if(! $this->sprawdz_ulic($_ulic))
        {
          Daddy::error("Nieprawidłowe osiedle");
          $errors++;
        }
        $osiedle = $this->sprawdz_ulic($_ulic);
      }

      if ($errors > 0)
        exit(0);
      //wsio pasi tworzymy przełącznik
      $_nr_bloku = mysql_real_escape_string($_nr_bloku);
      $_klatka = mysql_real_escape_string($_klatka);
      $zapytanie = "SELECT id FROM Lokalizacja WHERE ulic='$_ulic' AND nr_bloku='$_nr_bloku' AND klatka='$_klatka'";
      $wynik = $this->query($zapytanie);
      if($wynik)
      {
        if(defined('DEBUG'))
          echo "Podana lokalizacja istnieje, nie tworze nowej".$wynik[0];
        $this->id = $wynik[0];
        return;
      }
      $zapytanie = "INSERT INTO `Lokalizacja` (`osiedle`, `ulic`, `nr_bloku`, `klatka`)  VALUES('$osiedle', '$_ulic',  '$_nr_bloku', '$_klatka');";
      $sql = $this->connect();
      $wykonaj = mysql_query($zapytanie, $sql) or die(mysql_error());
      if(defined('DEBUG'))
        echo $zapytanie."<br>";
      //echo (mysql_affected_rows($sql));
      //echo $wykonaj;

      $this->id = null;
      if($wykonaj)
        $this->id = mysql_insert_id($sql);

    }
    protected function sprawdz_nr_bloku($_nr_bloku)
    {
      $pattern = '/\b[0-9]{1,3}[a-zA-Z]?\b/u';
      if(preg_match($pattern, $_nr_bloku))
        return true;
      return false;
    }	
    protected function sprawdz_klatke($_klatka)
    {
      $pattern = '/[a-ząćęłńóśźż][0-9]?/u';
      if(preg_match($pattern, $_klatka))
        return true;
      return false;
    }	
    protected function sprawdz_ulic($_ulic)
    {
      $pattern = '/[0-9]{5}/i';
      if(!preg_match($pattern, $_ulic))
        return false;
      $query = "SELECT short_name FROM Teryt WHERE ulic='$_ulic'";
      $wynik = $this->query($query);
      $wynik = $wynik['short_name'];
      if ($wynik)
        return $wynik;
      return false;
    }
    public static function getUlic()
    {
      $sql = new MysqlSeu();
      $query = "SELECT * FROM Teryt ORDER BY short_name";
      return $sql->query_assoc_array($query);
    }
  }
}
