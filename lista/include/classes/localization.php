<?php 
if(!defined('LOKALIZACJA_LISTA_CLASS'))
{
  define('LOKALIZACJA_LISTA_CLASS', true);
  class Lokalizacja 
  {
    public $id;
    public function add($_ulic, $_nr_bloku, $_klatka, $_mieszkanie, $_other_name)
    {

      $_klatka = strtolower($_klatka);
      $_blok = strtolower($_blok);
      $errors = 0;

      $sql = new myMysql();
      $sql->connect();
      //wsio pasi tworzymy przełącznik
      $_ulic = mysql_real_escape_string($_ulic);
      $_mieszkanie = mysql_real_escape_string($_mieszkanie);
      $_other_name = mysql_real_escape_string($_other_name);
      $_nr_bloku = mysql_real_escape_string($_nr_bloku);
      $_klatka = mysql_real_escape_string($_klatka);
      $query = "SELECT id FROM Lokalizacja WHERE ulic='$_ulic' AND blok='$_nr_bloku' AND klatka='$_klatka' AND mieszkanie='$_mieszkanie' AND nazwa_inna='$_other_name'";
      $wynik = $sql->query($query);
      if($wynik)
      {
//        echo "Podana lokalizacja istnieje, nie tworze nowej";
        $this->id = $wynik[0];
        return $this->id;
      }
      $query = "INSERT INTO Lokalizacja SET  ulic='".$_ulic."',  blok='".$_nr_bloku."', mieszkanie='".$_mieszkanie."', klatka='".$_klatka."', nazwa_inna='".$_other_name."'";
      $sql->query($query) or die('Nie dodało się');
      //echo $query."<br>";
      //echo (mysql_affected_rows($sql));
      $this->id = mysql_insert_id();
      return $this->id;
    }
    public static function getAddressStr($id)
    {
      $sql = new myMysql();
      $id = intval($id);
      $query = "SELECT Concat(Teryt.short_name, Lokalizacja.blok, '/', Lokalizacja.mieszkanie, Lokalizacja.klatka,' ', Lokalizacja.nazwa_inna) as address_string FROM Lokalizacja INNER JOIN Teryt ON Lokalizacja.ulic=Teryt.ULIC WHERE Lokalizacja.id='$id'";
      $string = $sql->query($query);
      $string = $string['address_string'];
      return $string;
    }
    public function getLoc($id)
    {
      $sql = new myMysql();
      $id = intval($id);
      $query = "SELECT * FROM Lokalizacja WHERE Lokalizacja.id='$id'";
      $lok = $sql->query($query);
      return $lok;
    }
    protected function sprawdz_nr_bloku($_nr_bloku)
    {
      $pattern = '/\b[0-9]{1,3}[a-z]?\b/u';
      if(preg_match($pattern, '/'.$_nr_bloku.'/u'))
        return true;
      return false;
    }	
    protected function sprawdz_klatke($_klatka)
    {
      $pattern = '/\b[a-z][0-9]?\b/u';
      if(preg_match($pattern, '/'.$_klatka.'/u'))
        return true;
      return false;
    }	
    protected function sprawdz_osiedle($_osiedle)
    {
      if ($_osiedle)
        return true;
      return false;
    }
  }
  define('LOKALIZACJA', true);
}
