<?php
  class MysqlPstmt
  {
    public $mysqli;
    public $result;
    public $num_rows;
    public function connect_pl($host, $user, $password, $database)
    {
      if(!$this->mysqli)
      {
        $this->mysqli  = new mysqli($host, $user, $password, $database);
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
        }
      }
      if($this->sql != $sql)
      {
        if (!$mysqli->set_charset("utf8")) {
          printf("Error loading character set utf8: %s\n", $mysqli->error);
        }
        //$char = "set character_set_connection='utf8', character_set_client='utf8', character_set_results='utf8'";
        $enc = "set lc_time_names = 'pl_pl'";
        $this->mysqli->query($enc) or die(mysql_error());
        // mysql_query($char) or die(mysql_error());
      }
      return $this->mysqli;
    }
    //funkcja query zwraca:
    //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
    //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
    //wartość, jeżeli wynik powinien być tylko jeden
    //tablicę wartości...
    public function query($query, $param)
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
    public function query_update($zapytanie, $id, $tabela, $id_field)
    {
      $sql = $this->connect();
      $this->query_log2($zapytanie, $id, $tabela, $id_field);
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
    protected function query_log($zapytanie)
    {
      $zapytanie2 = strtolower($zapytanie);
      if(strpos($zapytanie2, 'insert')!==false ||strpos($zapytanie2, 'update')!==false || strpos($zapytanie2, 'delete')!==false)
      {
        $this->connect();
        $user = intval($_SESSION['user_id']);
        $ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
        $safe_zapytanie = mysql_real_escape_string($zapytanie);
        $query = "INSERT INTO History (query_time, user_name, user_ip, query_text) VALUES(NOW(), '$user', '$ip', '$safe_zapytanie')";
        mysql_query($query) or die(mysql_error());
      }
    }
    protected function query_log2($zapytanie, $id=null, $table=null, $id_field=null)
    {
      $id_collumn;
      $this->connect();
      $user = intval($_SESSION['user_id']);
      $ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
      $safe_zapytanie = mysql_real_escape_string($zapytanie);
      $zapytanie2 = strtolower($zapytanie);
      $query;
      if($id && $table)
      {
        switch($table)
        {
          case 'Boa':
            $id_collumn = 'connection_id';
            break;
          case 'Installations':
            $id_collumn = 'installation_id';
            break;
          case 'Teryt':
            $id_collumn = 'ULIC';
          default:
            $id_collumn = 'id';
            break;
        }
        if($id_field)
          $id_collumn = $id_field;
        $query = "SELECT * FROM $table WHERE $id_collumn='$id'";
        $result = $this->query_assoc_array($query);
        $old = print_r($result[0], true);
        $old = mysql_real_escape_string($old);
        $query = "INSERT INTO History (query_time, user_name, user_ip, query_text, old_value, object_id, table_name) VALUES(NOW(), '$user', '$ip', '$safe_zapytanie', '$old', '$id', '$table')";
        //      echo $query;
      }
      else
      {
        $query = "INSERT INTO History (query_time, user_name, user_ip, query_text) VALUES(NOW(), '$user', '$ip', '$safe_zapytanie')";
      }
      mysql_query($query) or die(mysql_error());
    }
  }
