<?php
class Reaport
{
  private $fields;
  private $title;
  public function getL3SwitchReaport()
  {
    $query = "SELECT m.name, l.osiedle, l.nr_bloku as blok, l.klatka, d.other_name, 
      GROUP_CONCAT(DISTINCT a.parent_port ORDER BY a.parent_port ASC SEPARATOR '<br>')as parent_port, 
      GROUP_CONCAT(DISTINCT CONCAT_WS(' / ',i10.ip, CAST(n10.netmask AS CHAR)) ORDER BY i10.ip ASC SEPARATOR '<br>') as v10_ip, 
      GROUP_CONCAT(DISTINCT CONCAT_WS(' / ',i12.ip, CAST(n12.netmask AS CHAR))ORDER BY i12.ip ASC SEPARATOR '<br>') as v12_ip,  
      GROUP_CONCAT(DISTINCT CONCAT_WS(' / ',i2.ip, CAST(n2.netmask AS CHAR))ORDER BY i2.ip ASC SEPARATOR '<br>') as v2_ip, 
      GROUP_CONCAT(DISTINCT CONCAT_WS(' / ',i3.ip, CAST(n3.netmask AS CHAR))ORDER BY i3.ip ASC SEPARATOR '<br>') as v3_ip, d.mac, s.sn
    FROM Device d
    LEFT JOIN Agregacja a ON (a.device=d.dev_id AND a.uplink='1')
    LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
    LEFT JOIN Switch_rejon s ON d.dev_id=s.device
    LEFT JOIN Model m ON s.model=m.id
    LEFT JOIN Adres_ip i10 ON (i10.device=d.dev_id AND i10.main='1')
    LEFT JOIN Podsiec n10 ON i10.podsiec=n10.id
    LEFT JOIN (Adres_ip i12, Podsiec n12) ON (i12.device=d.dev_id AND i12.podsiec=n12.id AND n12.vlan='12')
    LEFT JOIN (Adres_ip i2, Podsiec n2) ON (i2.device=d.dev_id AND i2.podsiec=n2.id AND n2.vlan='2')
    LEFT JOIN (Adres_ip i3, Podsiec n3) ON (i3.device=d.dev_id AND i3.podsiec=n3.id AND n3.vlan='3')
    WHERE d.device_type='Switch_rejon' AND l.id!='111' GROUP BY d.dev_id ORDER BY l.osiedle, l.nr_bloku";
    $daddy = new Daddy();
    $result = $daddy->query_assoc_array($query);
    return $result;
  }
  public function getL2SwitchReaport()
  {
    $query = "SELECT l.osiedle, l.nr_bloku as blok, l.klatka, d.other_name, CONCAT(p_l.osiedle, p_l.nr_bloku, p_l.klatka) as parent_device,
      p_i.ip as parent_address,  
      GROUP_CONCAT(DISTINCT a.parent_port ORDER BY a.parent_port ASC SEPARATOR '<br>')as parent_port, 
      GROUP_CONCAT(DISTINCT CONCAT_WS(' / ',i10.ip, CAST(n10.netmask AS CHAR)) ORDER BY i10.ip ASC SEPARATOR '<br>') as v10_ip, m.name, d.mac, s.sn
    FROM Device d
    LEFT JOIN Agregacja a ON (a.device=d.dev_id AND a.uplink='1')
    LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
    LEFT JOIN Switch_bud s ON d.dev_id=s.device
    LEFT JOIN Model m ON s.model=m.id
    LEFT JOIN Adres_ip p_i ON p_i.device=a.parent_device
    LEFT JOIN (Lokalizacja p_l, Device p_d) ON (p_d.dev_id=a.parent_device AND p_d.lokalizacja=p_l.id)
    LEFT JOIN Adres_ip i10 ON (i10.device=d.dev_id AND i10.main='1')
    LEFT JOIN Podsiec n10 ON i10.podsiec=n10.id
    WHERE d.device_type='Switch_bud' AND l.id!='111' GROUP BY d.dev_id ORDER BY l.osiedle, l.nr_bloku, l.klatka";
    $daddy = new Daddy();
    $result = $daddy->query_assoc_array($query);
    return $result;
  }
  public function getSwitchPorts($dev_id)
  {
    $dev_id = intval($dev_id);
    $query = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
    $daddy = new Daddy();
    $device_type = $daddy->query($query);
    $device_type = $device_type['device_type'];
    if($device_type=="Switch_centralny")
      $device_type='Switch_rejon';
    $query = "SELECT model FROM $device_type WHERE device='$dev_id'";
    $model = $daddy->query($query);
    $model = $model['model'];
    $query = "SELECT * FROM ((SELECT a.device as device_id, a.parent_port, l.osiedle, l.nr_bloku as blok, l.klatka, d.other_name,   
      GROUP_CONCAT(DISTINCT concat_ws(' / ', i10.ip, CAST(n10.netmask AS CHAR)) ORDER BY i10.ip ASC SEPARATOR '<br>') as ip, d.device_type, d.mac
    FROM Agregacja a
    LEFT JOIN Device d ON (a.device=d.dev_id)
    LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
    LEFT JOIN Adres_ip i10 ON (i10.device=d.dev_id AND i10.main='1')
    LEFT JOIN Podsiec n10 ON i10.podsiec=n10.id
    WHERE a.parent_device='$dev_id' GROUP BY parent_port )
    UNION (SELECT a.parent_device as device_id, a.local_port, l.osiedle, l.nr_bloku as blok, l.klatka, d.other_name,  
      GROUP_CONCAT(DISTINCT concat_ws(' / ', i10.ip, CAST(n10.netmask AS CHAR)) ORDER BY i10.ip ASC SEPARATOR '<br>') as ip, d.device_type, d.mac
    FROM Agregacja a
    LEFT JOIN Device d ON (a.parent_device=d.dev_id)
    LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
    LEFT JOIN Adres_ip i10 ON (i10.device=d.dev_id AND i10.main='1')
    LEFT JOIN Podsiec n10 ON i10.podsiec=n10.id
    WHERE a.device='$dev_id' GROUP BY parent_port ))k
    ORDER BY parent_port";
    $result = $daddy->query_assoc_array($query);
    foreach($result as &$device)
    { 
      unset($query);
      switch($device['device_type'])
      {
        case 'Host':
          $query = "SELECT h.nr_mieszkania, p.nazwa_pakietu as pakiet 
                    FROM Host h
                    LEFT JOIN Pakiet p ON p.id=h.pakiet
                    WHERE h.device='".$device['device_id']."'";
          break;
        case 'Switch_bud':
          $query = "SELECT s.sn, m.name as model 
                    FROM Switch_bud s
                    LEFT JOIN Model m ON m.id=s.model
                    WHERE s.device='".$device['device_id']."'";
          break;
        case 'Switch_rejon':
          $query = "SELECT s.sn, m.name as model 
                    FROM Switch_rejon s
                    LEFT JOIN Model m ON m.id=s.model
                    WHERE s.device='".$device['device_id']."'";
          break;
        case 'Bramka_voip':
          $query = "SELECT s.sn, m.name as model 
                    FROM Bramka_voip s
                    LEFT JOIN Model m ON m.id=s.model
                    WHERE s.device='".$device['device_id']."'";
          break;
        case 'Kamera':
          $query = "SELECT s.sn, m.name as model 
                    FROM Kamera s
                    LEFT JOIN Model m ON m.id=s.model
                    WHERE s.device='".$device['device_id']."'";
          break;
        case 'Router':
          $query = "SELECT s.sn, m.name as model 
                    FROM Router s
                    LEFT JOIN Model m ON m.id=s.model
                    WHERE s.device='".$device['device_id']."'";
          break;

      }
      if($query)
      {
        $extra_fields = $daddy->query_assoc_array($query);
        $index = count($device);
        foreach($extra_fields[0] as $key=>$field)
          $device[$key] = $field;
        unset($extra_fields);
      }
    }
    return $daddy->sortByPort($result, $model);
  }
  public function getVoipGatewayReaport()
  {
    $query = "SELECT m.name, l.osiedle, l.nr_bloku as blok, l.klatka,  a.parent_port, a.parent_device, 
      CONCAT_WS(' / ',i3.ip, CAST(n3.netmask AS CHAR)) as v3_ip, d.mac, s.sn, CONCAT(lp.osiedle, lp.nr_bloku, lp.klatka, ' (', ap.ip, ')') as parent_dev_lok
    FROM Device d
    LEFT JOIN Agregacja a ON (a.device=d.dev_id AND a.uplink='1')
    LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
    LEFT JOIN Bramka_voip s ON d.dev_id=s.device
    LEFT JOIN Model m ON s.model=m.id
    LEFT JOIN Adres_ip i10 ON (i10.device=d.dev_id AND i10.main='1')
    LEFT JOIN Podsiec n10 ON i10.podsiec=n10.id
    LEFT JOIN (Adres_ip i3, Podsiec n3) ON (i3.device=d.dev_id AND i3.podsiec=n3.id AND n3.vlan='3')
    LEFT JOIN Device dp ON dp.dev_id=a.parent_device
    LEFT JOIN Lokalizacja lp ON lp.id=dp.lokalizacja
    LEFT JOIN Adres_ip ap ON (a.parent_device=ap.device AND ap.main=1)
    WHERE d.device_type='Bramka_voip' AND l.id!='111' GROUP BY d.dev_id ORDER BY l.osiedle, l.nr_bloku";
    $daddy = new Daddy();
    $result = $daddy->query_assoc_array($query);
    return $result;
  }
    public function getDevIps($dev_id)
    {
      $dev_id = intval($dev_id);
      $query = "SELECT i.ip, n.address as subnet, n.id as subnet_id, n.netmask, n.vlan 
                FROM Adres_ip i 
                INNER JOIN Podsiec n ON i.podsiec=n.id
                WHERE i.device='$dev_id'";
      $daddy = new Daddy();
      return $daddy->query_assoc_array($query);
    }
    public function getL2Switches()
    {
      $query = "SELECT d.dev_id, l.osiedle, l.nr_bloku, l.klatka, d.other_name, m.name, d.mac, s.sn
                FROM Device d
                LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
                LEFT JOIN Switch_bud s ON s.device=d.dev_id
                LEFT JOIN Model m ON s.model=m.id
                WHERE d.device_type='Switch_bud' AND l.id!='111' ORDER BY l.osiedle, l.nr_bloku, l.klatka";
      $daddy = new Daddy();
      return $daddy->query_assoc_array($query);
    }
    public function getL3Switches()
    {
      $query = "SELECT d.dev_id, l.osiedle, l.nr_bloku, l.klatka, d.other_name, m.name, d.mac, s.sn
                FROM Device d
                LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
                LEFT JOIN Switch_rejon s ON s.device=d.dev_id
                LEFT JOIN Model m ON s.model=m.id
                WHERE (d.device_type='Switch_rejon' OR d.device_type='Switch_centralny') AND l.id!='111' ORDER BY l.osiedle, l.nr_bloku, l.klatka";
      $daddy = new Daddy();
      return $daddy->query_assoc_array($query);
    }
    public function getRipeStats($date, $order, $direction, $dc)
    {
      $daddy = new Daddy();
      if(!$daddy->validLongDate($date))
        die("Wrong date format! Should be YYYY-MM-DD");
      $query = "SELECT *, (POW(2, (32 - netmask))-used - 2) as unused FROM Ripe_stats WHERE date='$date' ORDER BY subnet";
      $result = $daddy->query_assoc_array($query);
      if($result)
      {
        $result = $this->tuneRipe($result, $dc);
        if($direction!="asc" && $direction!="desc")
          $direction="asc";
        if(!$order)
          $order='subnet';
        if($order==="subnet" || $order=="netmask" || $order=="unused" || $order=="used") 
          $this->keySort(&$result, $order, $direction);
      }
      return $result;
    }
    private function keySort($array, $key, $direction)
    {
      for($i=0; $i< count($array); $i++)
        for($j=$i+1; $j< count($array); $j++)
        {
          if($direction == "asc")
          {
            if($array[$i][$key] > $array[$j][$key])
            {
              $tmp = $array[$j];
              $array[$j] = $array[$i];
              $array[$i] = $tmp;
            }
          }
          elseif($direction == "desc")
          {
            if($array[$i][$key] < $array[$j][$key])
            {
              $tmp = $array[$j];
              $array[$j] = $array[$i];
              $array[$i] = $tmp;
            }
          }
        }
      return $array;
    }
    private function tuneRipe($in, $dc)
    {
      $out = array();
      $wtvk = 0;
      $serwis = 0;
      foreach($in as &$row)
      {
        if($row['subnet']=='46.175.41.0')
          $row['netmask'] = 24;
        elseif($row['subnet']=='46.175.44.128')
        {
          $row['subnet'] = '46.175.44.0';
          $row['netmask'] = 24;
        }
        elseif(substr($row['subnet'], 0, 10)=='213.5.208.')
        {
          $last8 = substr($row['subnet'], 10);
          if($last8 >= 128)
            $serwis += $row['used'];
          else
            $wtvk += $row['used'];
        }
        $row['used'] += $dc;
        if($row['used'] > (pow(2, 32-$row['netmask'])-2))
            $row['used'] = pow(2, 32-$row['netmask'])-2;
      }
      unset($row);
      foreach($in as &$row)
      {
        if(substr($row['subnet'], 0, 3)=='10.')
          continue;
        elseif($row['subnet']=='213.5.208.0')
        {
          $row['netmask'] = 25;
          $row['used'] = $wtvk;
        }
        elseif($row['subnet']=='213.5.208.128')
        {
          $row['netmask'] = 25;
          $row['used'] = $serwis;
        }
        elseif(substr($row['subnet'], 0, 10)=='213.5.208.')
          continue;
        $out[] = $row;
      }

      return $out;
    }

    public function getVlanIpUtilization($vid, $sort='ip')
    {
      $vid = intval($vid);
      $output = array();
      $daddy = new Daddy();
      $subnets = $daddy->getSubnetsArray($vid); 
      $counter = 0;
      foreach($subnets as $subnet)
      {
            $subnet_size = pow(2, (32 - $subnet['netmask']))-2;
            $query = "SELECT count(*) FROM Adres_ip WHERE podsiec='".$subnet['id']."'";
            $result = $daddy->query($query);
            $subnet_used = $result[0];
            $output[$counter]['ip'] = $subnet['address'];
            $output[$counter]['name'] = $subnet['opis'];
            $output[$counter]['netmask'] = $subnet['netmask'];
            $output[$counter]['size'] = $subnet_size;
            $output[$counter]['free'] = $subnet_size - $subnet_used;
            $counter++;
      }
      if($sort && ($sort=='ip' || $sort=='name' || $sort=='size' || $sort=='free'))
      {
        foreach($output as &$row1)
        {
          foreach($output as &$row2)
          {
            if($row1==$row2)
              continue;
            if($row1[$sort] < $row2[$sort])
            {
              $tmp = $row1;
              $row1 = $row2;
              $row2 = $tmp;
            }
          }
        }
      }
      return $output;
    }


  }
