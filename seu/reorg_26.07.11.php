<?php
$filename = ".reorg2.lock";
if (file_exists($filename))
   die("Skrypt mozna uruchomic tylko raz!");
   require("security.php");
   require("include/definitions.php");

   $daddy = new Daddy();

   // 1 zamiana podsieci 213.5.208.192/26 (id = 120) na 46.175.41.0/25
   // roznica miedzy podsieciami to 2790696896


   $diff = 2790696896;
   $s_id = 120;
   $query = "SELECT * FROM Adres_ip WHERE podsiec='$s_id'";
   $ips = $daddy->query($query);
   foreach($ips as $ip)
{
    $old_dec = IpAddress::hrToDec($ip['ip']);
      $new_dec = $old_dec - $diff;
        $new_hr = IpAddress::decToHr($new_dec);

          $query = "UPDATE Adres_ip SET ip='".$new_hr."' WHERE ip='".$ip['ip']."' AND podsiec='$s_id'";
          echo $query;
            $daddy->query($query);
}

//  2  - Zmienić adres podsieci i wartość maski z 213.5.208.192/26 na 46.175.41.0/25

$query = "UPDATE Podsiec SET address='46.175.41.0', netmask='25' WHERE id='$s_id'";
$daddy->query($query);



// 3 zamiana podsieci 46.175.46.128/25 (id = 111) na 46.175.47.0/25
// roznica miedzy podsieciami to 

$diff = -128;
$s_id = 111;
$query = "SELECT * FROM Adres_ip WHERE podsiec='$s_id'";
$ips = $daddy->query($query);
foreach($ips as $ip)
{
    $old_dec = IpAddress::hrToDec($ip['ip']);
      $new_dec = $old_dec - $diff;
        $new_hr = IpAddress::decToHr($new_dec);

          $query = "UPDATE Adres_ip SET ip='".$new_hr."' WHERE ip='".$ip['ip']."' AND podsiec='$s_id'";
            $daddy->query($query);
}

//  4  - Zmienić adres podsieci i wartość maski z  46.175.46.128/25 na 46.175.47.0/25

$query = "UPDATE Podsiec SET address='46.175.47.0', netmask='25' WHERE id='$s_id'";
$daddy->query($query);


// 5 zamiana podsieci 46.175.45.128/25 (id = 107) na 46.175.46.0/24
// roznica miedzy podsieciami to 

$diff = -128;
$s_id = 107;
$query = "SELECT * FROM Adres_ip WHERE podsiec='$s_id'";
$ips = $daddy->query($query);
foreach($ips as $ip)
{
    $old_dec = IpAddress::hrToDec($ip['ip']);
      $new_dec = $old_dec - $diff;
        $new_hr = IpAddress::decToHr($new_dec);

          $query = "UPDATE Adres_ip SET ip='".$new_hr."' WHERE ip='".$ip['ip']."' AND podsiec='$s_id'";
            $daddy->query($query);
}

//  6  - Zmienić adres podsieci i wartość maski z  46.175.45.128/25 na 46.175.46.0/24

$query = "UPDATE Podsiec SET address='46.175.46.0', netmask='24' WHERE id='$s_id'";
$daddy->query($query);


//  7  - Zmienić adres podsieci i wartość maski z  46.175.45.0/25 na 46.175.45.0/24

$s_id = 109;
$query = "UPDATE Podsiec SET address='46.175.45.0', netmask='24' WHERE id='$s_id'";
$daddy->query($query);



$file = fopen($filename, "w+");
fwrite($file, "REORGLOCK2");
fclose($file);
echo " Reorganizacja zakonczona pomyslnie.";

