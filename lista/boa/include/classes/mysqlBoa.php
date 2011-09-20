<?php
class MysqlBoa extends MysqlMain
{
  public function connect()
  {
    return $this->connect_pl('localhost', 'internet', 'szczurek20P4', 'internet');
  }
  //funkcja query zwraca:
  //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
  //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
  //wartość, jeżeli wynik powinien być tylko jeden
  //tablicę wartości...
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
    $query_part = " SELECT * FROM (SELECT c.id, c.start_date, c.service_activation, c.payment_activation,
      DATE_FORMAT(c.start_date, '%d.%m.%y') AS _start_date,
      c.address, 
      c.ara_id,
      b.ara_sync,
      c.speed, 
      c.switch, 
      c.service,
      c.mac,
      c.info_boa,
      DATE_FORMAT(c.payment_activation, '%d.%m.%y') AS _payment_activation,
      DATE_FORMAT(b.ara_sync, '%d.%m.%y') AS ara,
      DATE_FORMAT(c.service_activation, '%d.%m.%y') AS _net_date,
      DATE_FORMAT(c.resignation_date, '%d.%m.%y') AS _resignation_date,
      DATE_FORMAT(i.wire_installation_date, '%d.%m.%y') AS _wire_date,
      DATE_FORMAT(i.socket_installation_date, '%d.%m.%y') AS _socket_date,
      DATE_FORMAT(c.installation_date, '%d.%m.%y %H:%m') AS _installation_date,
      i.socket_installation_date,
      c.installation_date,
      c.phone,
      c.phone2,
      c.phone3,
      User_sync.login as sync_user,
      User_add.login as add_user";
    if($tryb=='search' && $tryb2=='activation' && $od && $do)
    {
      if(!$activation && $payment)
        $zapytanie = $query_part."
          $isnull
          FROM Connections c LEFT JOIN Boa b ON b.connection_id=c.id
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
          LEFT JOIN User User_sync ON User_sync.id = b.user_id
          LEFT JOIN User User_add ON User_add.id = c.add_user
          ) a
          WHERE (payment_activation >= '$od' AND payment_activation <= '$do' )
          $sql_order";
      elseif($activation && $payment)
        $zapytanie = $query_part."
        $isnull
        FROM Connections c LEFT JOIN Boa b ON b.connection_id=c.id
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
        ) a
        WHERE ( (service_activation >= '$od' AND service_activation <= '$do' ) || (payment_activation >= '$od' AND payment_activation <= '$do' ))
        $sql_order";
      else
        $zapytanie = $query_part."
          $isnull
          FROM Connections c LEFT JOIN Boa b ON b.connection_id=c.id
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
          LEFT JOIN User User_sync ON User_sync.id = b.user_id
          LEFT JOIN User User_add ON User_add.id = c.add_user
          ) a
          WHERE (service_activation >= '$od' AND service_activation <= '$do' )
          $sql_order";
    }
    elseif($tryb=='search' && $tryb2=='contract' && $od && $do)
    {
      $zapytanie = $query_part."
        $isnull
        FROM Connections c LEFT JOIN Boa b ON b.connection_id=c.id
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
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
        FROM Connections c
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN Boa b ON b.connection_id=c.id
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
        ) a
        WHERE address LIKE '%$od%'
        $sql_order";
    }
    elseif($tryb=='contract' && $tryb2=='sync')
    {
      $zapytanie = $query_part."
        $isnull
        FROM Connections c
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN Boa b ON b.connection_id=c.id
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
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
        FROM Connections c
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN Boa b ON b.connection_id=c.id
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
        ) a
        WHERE ara_sync is null 
        $sql_order";
    }
    elseif($tryb=='contract' && $tryb2=='all')
      $zapytanie = $query_part."
        $isnull 
        FROM Connections c LEFT JOIN Boa b ON b.connection_id=c.id
          LEFT JOIN Installations i ON (c.localization = i.localization AND c.service = i.type)
        LEFT JOIN User User_sync ON User_sync.id = b.user_id
        LEFT JOIN User User_add ON User_add.id = c.add_user
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
    $this->query_update($query, $id, 'Boa', null);
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
