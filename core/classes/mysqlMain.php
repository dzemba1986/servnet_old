<?php
class myMysql
{
  public $sql;
  public $result;
  public $num_rows;
  public function connect_pl($host, $user, $password, $database)
  {
    $sql = mysql_connect($host, $user, $password) or die(mysql_error());			
    if($this->sql != $sql)
    {
      mysql_select_db($database, $sql);
      //zamiast następujących 4 linijek dodałem odpowiadającą konfigurację do /etc/mysql/my.cnf
      $char = "set character_set_connection='utf8', character_set_client='utf8', character_set_results='utf8'";
      $enc = "set lc_time_names = 'pl_pl'";
      mysql_query($enc) or die(mysql_error());
      mysql_query($char) or die(mysql_error());
    }
    $this->sql = $sql;
    if(!$sql)
    {
      echo "Could not connect to the database!";
      return false;
    }
    return $this->sql;
  }
  //funkcja query zwraca:
  //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
  //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
  //wartość, jeżeli wynik powinien być tylko jeden
  //tablicę wartości...

  public function num_rows($zapytanie)
  {
    $sql = $this->connect();
    $result = mysql_query($zapytanie, $sql);
    $this->result = $result;
    if ($result === false)
    {
      echo "<br>zapytanie $zapytanie nie wykonało się poprawnie!<br>";
      echo "<br>".mysql_error($sql)."<br>";
      return false;
    }
    if ($wskaznik === true)
      return null;		//jezeli to bylo insert update delete drop
    return mysql_num_rows($wskaznik);
  }
  public function query($zapytanie)
  {
    $this->num_rows = 0;
    $sql = $this->connect();
    $wskaznik = mysql_query($zapytanie, $sql);
    $this->result = $wskaznik;
    if ($wskaznik === false)
    {
      echo "<br>zapytanie $zapytanie nie wykonało się poprawnie!<br>";
      echo "<br>".mysql_error($sql)."<br>";
      return false;
    }
    if ($wskaznik === true)
      return $wskaznik;		//jezeli to bylo insert update delete drop
    $wynik;
    if(mysql_affected_rows($sql) > 1)
    {
      $this->num_rows = mysql_num_rows($wskaznik);
      for($i=0; $i < mysql_affected_rows($sql); $i++)
      {
        $wynik[] = mysql_fetch_array($wskaznik);
      }
    }
    else
    {
      $this->num_rows = mysql_num_rows($wskaznik);
      $wynik = mysql_fetch_array($wskaznik);
    }
    return $wynik;
  }
  public function query_assoc($zapytanie)
  {
    $this->num_rows = 0;
    $sql = $this->connect();
    $wskaznik = mysql_query($zapytanie, $sql);
    $this->result = $wskaznik;
    if ($wskaznik === false)
    {
      echo "<br>zapytanie $zapytanie nie wykonało się poprawnie!<br>";
      echo "<br>".mysql_error($sql)."<br>";
      return false;
    }
    if ($wskaznik === true)
      return $wskaznik;		//jezeli to bylo insert update delete drop
    $wynik;
    if(mysql_affected_rows($sql) > 1)
    {
      $this->num_rows = mysql_num_rows($wskaznik);
      for($i=0; $i < mysql_affected_rows($sql); $i++)
      {
        $wynik[] = mysql_fetch_assoc($wskaznik);
      }
    }
    else
    {
      $this->num_rows = mysql_num_rows($wskaznik);
      $wynik = mysql_fetch_assoc($wskaznik);
    }
    return $wynik;
  }
  public function query_assoc_array($zapytanie)
  {
    $this->num_rows = 0;
    $sql = $this->connect();
    $wskaznik = mysql_query($zapytanie, $sql);
    $this->result = $wskaznik;
    if ($wskaznik === false)
    {
      echo "<br>zapytanie $zapytanie nie wykonało się poprawnie!<br>";
      echo "<br>".mysql_error($sql)."<br>";
      return false;
    }
    if ($wskaznik === true)
      return $wskaznik;		//jezeli to bylo insert update delete drop
    $wynik;
    $this->num_rows = mysql_num_rows($wskaznik);
    for($i=0; $i < mysql_affected_rows($sql); $i++)
    {
      $wynik[] = mysql_fetch_assoc($wskaznik);
    }
    return $wynik;
  }
  public function query_update($zapytanie, $id, $tabela)
  {
    $sql = $this->connect();
    $this->query_log2($zapytanie, $id, $tabela);
    $wskaznik = mysql_query($zapytanie, $sql);
    $this->result = $wskaznik;
    if ($wskaznik === false)
    {
      echo "<br>zapytanie $zapytanie nie wykonało się poprawnie!<br>";
      echo "<br>".mysql_error($sql)."<br>";
      return false;
    }
    if ($wskaznik === true)
      return $wskaznik;		//jezeli to bylo insert update delete drop
    $wynik;
    if(mysql_affected_rows($sql) > 1)
      for($i=0; $i < mysql_affected_rows($sql); $i++)
      {
        $wynik[] = mysql_fetch_array($wskaznik);
      }
    else
      $wynik = mysql_fetch_array($wskaznik);
    return $wynik;
  }
}
