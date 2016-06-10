<?php
require('../path.php');
if(!defined('MY_MYSQL_LISTA_CLASS'))
{
  define('MY_MYSQL_LISTA_CLASS', true);
  class myMysql
  {
    public $sql;
    public $result;
    public function connect()
    {
      $sql = mysql_connect('10.111.233.9', 'internet', 'b@zAd@nych')or die(mysql_error());			
  //    $sql = mysql_connect('localhost', 'internet_dev', 'szczurek20P4')or die(mysql_error());			
      if($this->sql != $sql)
      {
        mysql_select_db('internet',$sql);
        //zamiast następujących 4 linijek dodałem odpowiadającą konfigurację do /etc/mysql/my.cnf
        //mysql_select_db('internet_dev',$sql);
        $char = "set character_set_connection='utf8', character_set_client='utf8', character_set_results='utf8'";
        $enc = "set lc_time_names = 'pl_pl'";
        mysql_query($enc) or die(mysql_error());
        mysql_query($char) or die(mysql_error());
      }
      $this->sql = $sql;
      if(!$sql)
      {
        echo "nie moge sie polaczyc z baza!";
        return false;
      }
      //		echo("<br>$sql<br>");
      return $this->sql;
    }
    //funkcja query zwraca:
    //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
    //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
    //wartość, jeżeli wynik powinien być tylko jeden
    //tablicę wartości...
    private function query_log($zapytanie)
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
    private function query_log2($zapytanie, $id=null, $table=null)
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
    public function num_rows($zapytanie)
    {
      $sql = $this->connect();
      //		echo ("<br>$zapytanie<br>");
      $wskaznik = mysql_query($zapytanie, $sql);
      $this->result = $wskaznik;
      //echo (mysql_info($sql));
      if ($wskaznik === false)
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
      $sql = $this->connect();
      //		echo ("<br>$zapytanie<br>");
      $this->query_log($zapytanie);
      $wskaznik = mysql_query($zapytanie, $sql);
      $this->result = $wskaznik;
      //echo (mysql_info($sql));
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
    public function query_assoc($zapytanie)
    {
      $sql = $this->connect();
      //		echo ("<br>$zapytanie<br>");
      $this->query_log($zapytanie);
      $wskaznik = mysql_query($zapytanie, $sql);
      //echo (mysql_info($sql));
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
          $wynik[] = mysql_fetch_assoc($wskaznik);
        }
      else
        $wynik = mysql_fetch_assoc($wskaznik);
      return $wynik;
    }
    public function query_assoc_array($zapytanie)
    {
      $sql = $this->connect();
      //		echo ("<br>$zapytanie<br>");
      $this->query_log($zapytanie);
      $wskaznik = mysql_query($zapytanie, $sql);
      //echo (mysql_info($sql));
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
      for($i=0; $i < mysql_affected_rows($sql); $i++)
      {
        $wynik[] = mysql_fetch_assoc($wskaznik);
      }
      return $wynik;
    }
    public function query_update($zapytanie, $id, $table)
    {
      $sql = $this->connect();
      //		echo ("<br>$zapytanie<br>");
      $this->query_log2($zapytanie, $id, $table);
      $wskaznik = mysql_query($zapytanie, $sql);
      $this->result = $wskaznik;
      //echo (mysql_info($sql));
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
    
    public function getList3($mode, $order, &$paging, $find_phrase=null, $search_field=null)
    {
      if ($order)
      {
        if($order =="adres")
        {
          $isnull = "";
          $order = " ORDER BY address ASC";
        }
        elseif($order =="modyf")
        {
          $isnull = "";
          $order = " ORDER BY net_modyf DESC";
        }
        else
        {
          $isnull = ", $order is null AS isnull ";
          $order = " ORDER BY isnull ASC, $order ASC";
        }
      }
      else 
      {
        $isnull = ", UNIX_TIMESTAMP(net_start)=0 AS isnull ";
        $order = " ORDER BY isnull ASC, net_start ASC";
      }
      $query;
      $search;
      if($find_phrase && $search_field)
      {
        if($search_field=='id')
          $search=" $search_field LIKE '$find_phrase' ";
        elseif($search_field=='last_modyfication')
          $search=" date($search_field) LIKE '$find_phrase' ";
        else
          $search=" $search_field LIKE '%$find_phrase%' ";
      }
      switch($mode)
      {
        case 'in_progress':
          $inner_query="
            SELECT	a.id as net_id,
			  a.ara_id net_ara_id,	
                          a.start_date as net_start, 
                          DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                          DATE_ADD(a.start_date,INTERVAL 21 DAY) AS net_end_date,
                          DATE_FORMAT(DATE_ADD(a.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _net_end_date,
                          a.address,
                          a.phone,
                          a.switch as net_switch,
                          a.switch_loc_str as net_switch_loc_str,
                          a.port as net_port,
                          c.wire_length as net_wire,
                          a.mac,
                          a.service as net_service, 
          								a.moved_phone as moved_phone,
                          DATE_FORMAT(a.moved_phone, '%d.%m.%y') as _moved_phone,
                          a.speed,
                          c.socket_installation_date as net_socket_date,
                          DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                          a.payment_activation as net_payment_activation,
                          DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                          a.service_activation as net_activation, 
                          DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                          a.service_configuration as net_configuration, 
                          DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration, 
                          a.informed as net_informed, 
                          DATE_FORMAT(a.informed, '%d.%m.%y') as _net_informed, 
                          a.installation_date,
                          DATE_FORMAT(a.installation_date, '<b>%W</b><br>%d.%m.%y %H:%i') as _installation_date,
                          IF(m.mod_s_datetime,DATE_FORMAT(m.mod_s_datetime, '<b>%W</b><br>%d.%m.%y %H:%i'),NULL) as _mod_s_datetime,
                          IF(m.mod_s_datetime,m.mod_s_datetime,NULL) as mod_s_datetime,
                          a.info as net_info,
                          a.info_boa as net_info_boa,
                          a.last_modyfication as net_modyf,
                          DATE_FORMAT(a.last_modyfication, '%d.%m.%y %H:%i') as _net_modyf,
                          (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(a.start_date)) AS net_awaiting_time,
                          b.id as phone_id,
			  b.ara_id as phone_ara_id,
                          DATE_FORMAT(b.start_date, '%d.%m.%y') as phone_start, 
                          DATE_FORMAT(DATE_ADD(b.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS phone_end_date,
                          b.switch as phone_switch, 
                          b.switch_loc_str as phone_switch_loc_str,
                          b.port as phone_port, 
                          d.wire_length as phone_wire,
                          b.service as phone_service,
          		            b.moved_phone as phone_moved_phone,
                          DATE_FORMAT(b.moved_phone, '%d.%m.%y') as _phone_moved_phone, 
                          d.socket_installation_date as phone_socket_date,
                          DATE_FORMAT(d.socket_installation_date, '%d.%m.%y') as _phone_socket_date, 
                          b.payment_activation as phone_payment_activation,
                          DATE_FORMAT(b.payment_activation, '%d.%m.%y') as _phone_payment_activation, 
                          b.service_activation as phone_activation, 
                          DATE_FORMAT(b.service_activation, '%d.%m.%y') as _phone_activation, 
                          b.service_configuration as phone_configuration, 
                          DATE_FORMAT(b.service_configuration, '%d.%m.%y') as _phone_configuration, 
                          b.informed as phone_informed, 
                          DATE_FORMAT(b.informed, '%d.%m.%y') as _phone_informed, 
                          b.info as phone_info, 
                          b.info_boa as phone_info_boa, 
                          b.last_modyfication as phone_modyf,
                          DATE_FORMAT(b.last_modyfication, '%d.%m.%y %H:%i') as _phone_modyf,
                          (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(b.start_date)) AS phone_awaiting_time
                            FROM internet.Connections a 
                            INNER JOIN internet.Connections b
                            ON b.service_activation is null and b.resignation_date is null AND a.localization=b.localization and a.service!=b.service 
                            LEFT JOIN internet.Installations c
                            ON a.service=c.type AND a.localization=c.localization 
                            LEFT JOIN internet.Installations d
                            ON  b.service=d.type AND b.localization=d.localization
                            LEFT JOIN Modyfications m
                            ON a.modyfication=m.mod_id
                            WHERE a.service='net' AND a.service_activation is null AND a.resignation_date is null and (a.ara_id not like 'a%' or a.modyfication <> 0 or b.resignation_date is null)
                            UNION
                            select	a.id,
			  a.ara_id as net_ara_id,	
                          a.start_date as net_start, 
                          DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                          DATE_ADD(a.start_date,INTERVAL 21 DAY) AS net_end_date,
                          DATE_FORMAT(DATE_ADD(a.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _net_end_date,
                          a.address,
                          a.phone,
                          a.switch as net_switch,
                          a.switch_loc_str as net_switch_loc_str,
                          a.port as net_port,
                          c.wire_length as net_wire,
                          a.mac,
                          a.service as net_service, 
          								a.moved_phone as moved_phone,
                          DATE_FORMAT(a.moved_phone, '%d.%m.%y') as _moved_phone,
                          a.speed,
                          c.socket_installation_date as net_socket_date,
                          DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                          a.payment_activation as net_payment_activation,
                          DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                          a.service_activation as net_activation, 
                          DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                          a.service_configuration as net_configuration, 
                          DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration, 
                          a.informed as net_informed, 
                          DATE_FORMAT(a.informed, '%d.%m.%y') as _net_informed, 
                          a.installation_date,
                          DATE_FORMAT(a.installation_date, '<b>%W</b><br>%d.%m.%y %H:%i') as _installation_date,
                          IF(m.mod_s_datetime,DATE_FORMAT(m.mod_s_datetime, '<b>%W</b><br>%d.%m.%y %H:%i'),NULL) as _mod_s_datetime,
                          IF(m.mod_s_datetime,m.mod_s_datetime,NULL) as mod_s_datetime,
                          a.info,
                          a.info_boa,
                          a.last_modyfication as net_modyf,
                          DATE_FORMAT(a.last_modyfication, '%d.%m.%y %H:%i') as _net_modyf,
                          (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(a.start_date)) AS net_awaiting_time,
                          null,
			  null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
          								null,
          								null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null,
                          null
                            FROM Connections a 
                            LEFT JOIN Installations c
                            ON a.service=c.type AND a.localization=c.localization 
                            LEFT JOIN Modyfications m
                            ON a.modyfication=m.mod_id
                            WHERE a.service_activation is null and (SELECT count(*) from Connections where localization=a.localization and service_activation is null and service!=a.service and resignation_date is null)='0' and a.resignation_date is null and ( a.ara_id not like 'a%' or a.modyfication <> 0)";
          break;
        case 'for_configuration':
          $inner_query="
            SELECT	a.id as net_id,
                          a.start_date as net_start, 
                          DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                          DATE_ADD(a.start_date,INTERVAL 21 DAY) AS net_end_date,
                          DATE_FORMAT(DATE_ADD(a.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _net_end_date,
                          a.address,
                          a.speed,
                          a.phone,
                          a.switch as net_switch,
                          a.switch_loc_str as net_switch_loc_str,
                          a.port as net_port,
                          c.wire_length as net_wire,
                          a.mac,
                          a.service as net_service, 
                          c.socket_installation_date as net_socket_date,
                          DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                          a.payment_activation as net_payment_activation,
                          DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                          a.service_activation as net_activation, 
                          DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                          a.service_configuration as net_configuration, 
                          DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration, 
                          a.informed as net_informed, 
                          DATE_FORMAT(a.informed, '%d.%m.%y') as _net_informed, 
                          a.installation_date,
                          DATE_FORMAT(a.installation_date, '<b>%W</b><br>%d.%m.%y %H:%i') as _installation_date,
                          a.info as net_info,
                          a.info_boa as net_info_boa,
                          a.last_modyfication as net_modyf,
                          DATE_FORMAT(a.last_modyfication, '%d.%m.%y %H:%i') as _net_modyf,
                          (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(a.start_date)) AS net_awaiting_time
                            FROM Connections a 
                            LEFT JOIN Installations c
                            ON a.localization=c.localization AND a.service=c.type
                            WHERE a.service='net' AND a.service_activation is null AND a.resignation_date is null AND a.mac!='' AND a.service_configuration is null AND c.socket_installation_date is not null";
          break;
        case 'done':
          $inner_query="
            select a.id,
                   a.start_date as net_start, 
                   DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                   a.address,
                   a.speed,
                   a.phone,
                   a.switch as net_switch,
                   a.switch_loc_str as net_switch_loc_str,
                   a.port as net_port,
                   c.wire_length as net_wire,
                   a.mac,
                   a.service as net_service, 
                   c.socket_installation_date as net_socket_date,
                   DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                   a.payment_activation as net_payment_activation,
                   DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                   a.service_activation as net_activation, 
                   DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                   a.service_configuration as net_configuration, 
                   DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration 
                     FROM Connections a 
                     LEFT JOIN Installations c
                     ON a.service=c.type AND a.localization=c.localization 
                     WHERE a.service_activation is not null AND a.resignation_date is null";
          break;
        case 'all':
          $no_resignations;
          if($search) 
            $no_resignations='';
          else
            $no_resignations='a.resignation_date is null';
            $inner_query = "
            SELECT	a.id as net_id,
                          a.start_date as net_start, 
                          DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                          DATE_ADD(a.start_date,INTERVAL 21 DAY) AS net_end_date,
                          DATE_FORMAT(DATE_ADD(a.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _net_end_date,
                          a.address as net_address,
                          a.address,
                          a.phone,
                          a.switch as net_switch,
                          a.switch_loc_str as net_switch_loc_str,
                          a.port as net_port,
                          c.wire_length as net_wire,
                          c.wire_installation_date,
                          c.socket_installation_date,
                          DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                          c.wire_installer,
                          c.socket_installer,
                          a.mac,
                          a.service as net_service, 
                          c.socket_installation_date as net_socket_date,
                          a.payment_activation as net_payment_activation,
                          a.service_activation as net_activation, 
                          a.service_configuration as net_configuration, 
                          a.resignation_date as net_resignation, 
                          DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                          DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration, 
                          DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                          DATE_FORMAT(a.informed, '%d.%m.%y') as _net_informed, 
                          DATE_FORMAT(a.last_modyfication, '%d.%m.%y') as _last_modyfication, 
                          DATE_FORMAT(a.resignation_date, '%d.%m.%y') as _net_resignation, 
                          a.last_modyfication as net_modyf,
                          DATE_FORMAT(a.last_modyfication, '%d.%m.%y %H:%i') as _net_modyf,
                          a.informed as net_informed 
                            FROM Connections a 
                            LEFT JOIN Installations c
                            ON a.service=c.type AND a.localization=c.localization
                            WHERE $no_resignations $search";
          break;
        case 'resignations':
          $inner_query="
            select a.id,
                   a.start_date as net_start, 
                   DATE_FORMAT(a.start_date, '%d.%m.%y') as _net_start, 
                   DATE_ADD(a.start_date,INTERVAL 21 DAY) AS net_end_date,
                   DATE_FORMAT(DATE_ADD(a.start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _net_end_date,
                   a.address,
                   a.phone,
                   a.switch as net_switch,
                   a.port as net_port,
                   c.wire_length as net_wire,
                   a.mac,
                   a.service as net_service, 
                   c.socket_installation_date as net_socket_date,
                   DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _net_socket_date, 
                   a.payment_activation as net_payment_activation,
                   DATE_FORMAT(a.payment_activation, '%d.%m.%y') as _net_payment_activation, 
                   a.service_activation as net_activation, 
                   DATE_FORMAT(a.service_activation, '%d.%m.%y') as _net_activation, 
                   a.service_configuration as net_configuration, 
                   DATE_FORMAT(a.service_configuration, '%d.%m.%y') as _net_configuration, 
                   a.resignation_date as net_resignation, 
                   DATE_FORMAT(a.resignation_date, '%d.%m.%y') as _net_resignation, 
                   (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(a.start_date)) AS net_awaiting_time
                     FROM Connections a 
                     LEFT JOIN Installations c
                     ON  a.service=c.type AND a.localization=c.localization
                     WHERE a.resignation_date is not null";
          break;
        case 'installations':
          break;
      }
      //echo $query;
      $this->connect();
      $num_rows = $this->num_rows($inner_query);
      $paging->setTotalRows($num_rows);
      $offset = $paging->getOffset();
      $pages = $paging->getPages();
      $rows_per_page = $paging->getRowsPerPage();
      if($rows_per_page)
      {
        $query="SELECT * $isnull FROM ( $inner_query) z $order  LIMIT $offset, $rows_per_page";
        $rows = $this->query_assoc_array($query);
      }
      else
      {
        $query="SELECT * $isnull FROM ( $inner_query) z $order";
        $rows = $this->query_assoc_array($query);
      }
      return $rows;
    }
    
    public function getInstallationsList($mode, $order, &$paging, $find_phrase=null, $search_field=null)
    {
      if ($order)
      {
        if($order =="adres")
        {
          $isnull = "";
          $order = " ORDER BY address ASC";
        }
        else
        {
          $isnull = ", $order is null AS isnull ";
          $order = " ORDER BY isnull ASC, $order ASC";
        }
      }
      else 
      {
        $isnull = ", UNIX_TIMESTAMP(wire_installation_date)=0 AS isnull ";
        $order = " ORDER BY isnull ASC, wire_installation_date ASC";
      }
      $query;
      $search;
      if($find_phrase && $search_field)
      {
        if($search_field=='installation_id')
          $search="AND ( $search_field LIKE '$find_phrase') ";
        else
          $search="AND ( $search_field LIKE '%$find_phrase%') ";
      }
      switch($mode)
      {
        case 'pending_installations':
          $inner_query="
            SELECT c.address,
                   c.wire_length,
                   c.wire_installation_date,
                   DATE_FORMAT(c.wire_installation_date, '%d.%m.%y') as _wire_installation_date, 
                   c.socket_installation_date,
                   DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _socket_installation_date, 
                   c.wire_installer,
                   c.socket_installer,
                   c.type
                   FROM Installations c
                   WHERE (c.wire_installation_date is null AND c.socket_installation_date is not null) 
                   OR (c.socket_installation_date is null AND c.wire_installation_date is not null)";
          break;
        case 'done_installations':
          $inner_query="
            SELECT c.address,
                   c.wire_length,
                   c.wire_installation_date,
                   DATE_FORMAT(c.wire_installation_date, '%d.%m.%y') as _wire_installation_date, 
                   c.socket_installation_date,
                   DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _socket_installation_date, 
                   c.wire_installer,
                   c.socket_installer,
                   c.type
				   FROM Installations c
				   WHERE c.wire_installation_date is not null AND c.socket_installation_date is not null";
          break;
        case 'all_installations':
          $inner_query="
            SELECT c.address,
                   c.wire_length,
                   c.wire_installation_date,
                   DATE_FORMAT(c.wire_installation_date, '%d.%m.%y') as _wire_installation_date, 
                   c.socket_installation_date,
                   DATE_FORMAT(c.socket_installation_date, '%d.%m.%y') as _socket_installation_date, 
                   c.wire_installer,
                   c.socket_installer,
                   c.type
                   FROM Installations c
                   WHERE (c.wire_installation_date is not null OR c.socket_installation_date is not null) $search";
          break;
      }
      //echo $query;
      $num_rows = $this->num_rows($inner_query);
      $paging->setTotalRows($num_rows);
      $offset = $paging->getOffset();
      $pages = $paging->getPages();
      $rows_per_page = $paging->getRowsPerPage();
      if($rows_per_page)
      {
        $query="SELECT * $isnull FROM ( $inner_query) z $order LIMIT $offset, $rows_per_page";
        $rows = $this->query_assoc_array($query);
      }
      else
        $query="SELECT * $isnull FROM ( $inner_query) z $order";
        $rows = $this->query_assoc_array($query);
      return $rows;
    }
    public function getConnection($id)
    {
      $this->connect();
      $id = mysql_real_escape_string($id);
      $query = "SELECT *, Connections.id as id,
        DATE_FORMAT(start_date, '%d.%m.%y') as _start_date, 
        DATE_FORMAT(payment_activation, '%d.%m.%y') as _payment_activation, 
        DATE_FORMAT(service_activation, '%d.%m.%y') as _service_activation, 
        DATE_FORMAT(service_configuration, '%d.%m.%y') as _service_configuration, 
        DATE_FORMAT(installation_date, '%d.%m.%y') as _installation_date_date,		
        DATE_FORMAT(installation_date, '%H:%i') as _installation_date_time,		
        DATE_FORMAT(resignation_date, '%d.%m.%y') as _resignation_date,		
        DATE_FORMAT(informed, '%d.%m.%y') as _informed,
        DATE_FORMAT(DATE_ADD(start_date,INTERVAL 21 DAY), '%d.%m.%y') AS _end_date,
        a.login as a_user,
        c.login as c_user,
        i.login as i_user
          FROM Connections
          LEFT JOIN User a ON Connections.add_user=a.id
          LEFT JOIN User i ON Connections.installation_user=i.id
          LEFT JOIN User c ON Connections.configuration_user=c.id
          WHERE Connections.id='$id'";
      return $this->query($query);
    }
    public function getInstallationAddress($id)
    {
      $this->connect();
      $id = mysql_real_escape_string($id);
      $query = "SELECT address FROM Installations WHERE installation_id='$id'";
      return $this->query($query);
    }
    public function getConnectionAddressAndService($id)
    {
      $this->connect();
      $id = mysql_real_escape_string($id);
      $query = "SELECT address, service, localization
        FROM Connections WHERE id='$id'";
      return $this->query($query);
    }
    public function getInstallation($address, $type)
    {
      $this->connect();
      $id = mysql_real_escape_string($id);
      $address = mysql_real_escape_string($address);
      $query = "SELECT *,
        DATE_FORMAT(wire_installation_date, '%d.%m.%y') as _wire_installation_date, 
        DATE_FORMAT(socket_installation_date, '%d.%m.%y') as _socket_installation_date
          FROM Installations WHERE address='$address' AND type='$type'";
      $result = $this->query_assoc_array($query);
      if (count($result)>1)
        die ("Za dużo instalacji na jednym adresie!");
      return $result[0];

    }
    public function getBoaList($tryb, $tryb2, &$paging, $od, $do, $order, $payment=null, $activation=null)
    {
      $od = mysql_real_escape_string($od);
      $do = mysql_real_escape_string($do);
      $order = mysql_real_escape_string($order);
      $payment = intval($payment);
      $activation = intval($activation);
      if($tryb=='search' && ($tryb2=='activation' || $tryb2=='contract'))
      {
        $today = date("Y-m-d");
        if(!$od)
          $od=$today;
        if(!$do)
          $do=$today;
      }
      if ($order)
      {
        if($order =="adres")
        {
          $isnull = "";
          $sql_order = " ORDER BY address ASC";
        }
        else
        {
          $isnull = ", UNIX_TIMESTAMP($order)=0 AS isnull ";
          $sql_order = " ORDER BY isnull ASC, $order ASC";
        }
      }
      else
      {
        $isnull = ", UNIX_TIMESTAMP(start_date)=0 AS isnull ";
        $sql_order = " ORDER BY isnull ASC, start_date ASC";
      }
      $query_part = " SELECT * FROM (SELECT Connections.id, start_date, service_activation, payment_activation,
        DATE_FORMAT(start_date, '%d.%m.%y') AS _start_date,
        address, 
        ara_id,
        ara_sync,
        speed, 
        switch, 
          service,
            mac,
            DATE_FORMAT(payment_activation, '%d.%m.%y') AS _payment_activation,
            DATE_FORMAT(Boa.ara_sync, '%d.%m.%y') AS ara,
            DATE_FORMAT(service_activation, '%d.%m.%y') AS _net_date,
            DATE_FORMAT(resignation_date, '%d.%m.%y') AS _resignation_date,
            phone,
            phone2,
            phone3,
            User_sync.login as sync_user,
            User_add.login as add_user";
      if($tryb=='search' && $tryb2=='activation' && $od && $do)
      {
        if(!$activation && $payment)
          $zapytanie = $query_part."
            $isnull
            FROM Connections LEFT JOIN Boa ON Boa.connection_id=Connections.id
            LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
            LEFT JOIN User User_add ON User_add.id = Connections.add_user
            ) a
            WHERE (payment_activation >= '$od' AND payment_activation <= '$do' )
            $sql_order";
        elseif($activation && $payment)
          $zapytanie = $query_part."
          $isnull
          FROM Connections LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          ) a
          WHERE ( (service_activation >= '$od' AND service_activation <= '$do' ) || (payment_activation >= '$od' AND payment_activation <= '$do' ))
          $sql_order";
        else
          $zapytanie = $query_part."
            $isnull
            FROM Connections LEFT JOIN Boa ON Boa.connection_id=Connections.id
            LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
            LEFT JOIN User User_add ON User_add.id = Connections.add_user
            ) a
            WHERE (service_activation >= '$od' AND service_activation <= '$do' )
            $sql_order";
      }
      elseif($tryb=='search' && $tryb2=='contract' && $od && $do)
      {
        $zapytanie = $query_part."
          $isnull
          FROM Connections LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          ) a
          WHERE (start_date >= '$od' AND start_date <= '$do' )
          $sql_order";
      }
      elseif($tryb=='search' && $tryb2=='address')
      {
        if(!$od)
          $od='NieMaTakiegoAdresu';
        $zapytanie = $query_part."
          $isnull
          FROM Connections
          LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          ) a
          WHERE address LIKE '%$od%'
          $sql_order";
      }
      elseif($tryb=='contract' && $tryb2=='sync')
      {
        $zapytanie = $query_part."
          $isnull
          FROM Connections
          LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          ) a
          WHERE ara_sync is not null 
          $sql_order";
      }
      elseif($tryb=='contract' && $tryb2=='')
      {
        if(!$od)
          $od='NieMaTakiegoAdresu';
        $zapytanie = $query_part."
          $isnull
          FROM Connections
          LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          ) a
          WHERE ara_sync is null 
          $sql_order";
      }
      elseif($tryb=='contract' && $tryb2=='all')
        $zapytanie = $query_part."
          $isnull 
          FROM Connections LEFT JOIN Boa ON Boa.connection_id=Connections.id
          LEFT JOIN User User_sync ON User_sync.id = Boa.user_id
          LEFT JOIN User User_add ON User_add.id = Connections.add_user
          )a $sql_order";
   //   echo $zapytanie;
    //  echo "<br>$tryb<br>$tryb2<br>$od<br>$do";
      $num_rows = $this->num_rows($zapytanie);
      $paging->setTotalRows($num_rows);
      $offset = $paging->getOffset();
      $pages = $paging->getPages();
      $rows_per_page = $paging->getRowsPerPage();
      if($rows_per_page)
      {
        $query="$zapytanie LIMIT $offset, $rows_per_page";
        $rows = $this->query_assoc_array($query);
      }
      else
        $rows = $this->query_assoc_array($zapytanie);
      return $rows;
    }
    public function araDeSync($id)
    {
      $permissions = $_SESSION['permissions'];
      if(($permissions & 128)!=128)
         die("Nie masz uprawnień!");
      $id=intval($id);
      $user = intval($_SESSION['user_id']);
      $query = "DELETE FROM Boa WHERE connection_id='$id'";
      $this->query_update($query, $id, 'Boa');
    }
    public function araSync($id)
    {
      $query = "SELECT * FROM Boa WHERE connection_id='$id'";
      if($this->query($query))
        return false;
      $permissions = $_SESSION['permissions'];
      if(($permissions & 128)!=128)
         die("Nie masz uprawnień!");
      $id=intval($id);
      $user = intval($_SESSION['user_id']);
      $query = "INSERT INTO Boa (connection_id, ara_sync, user_id) VALUES('$id', NOW(), $user)";
      $this->query($query);
    }
    public function getUlic()
    {
      $query = "SELECT * FROM Teryt";
      return $this->query_assoc_array($query);
    }
  }
}
