<?php
$ip_str_tot = "1:234::we:3424:3";
$ip_str_seg = preg_split('/:/', $ip_str_tot);
$seg_num = count($ip_str_seg);

//removing empty beginning and ending
if(strlen($ip_str_seg[0]) == 0) 
{
  $ip_str_seg = array_slice($ip_str_seg, 1);
  $seg_num--;
}
if(strlen($ip_str_seg[$seg_num-1]) == 0)
{
  $ip_str_seg = array_slice($ip_str_seg, 0, -1);
  $seg_num--;
}
var_dump($ip_str_seg);

if($seg_num == 8)
{
  $ip_res = $ip_str_seg;
  //po prostu przepisujemy
}
else
{
  
  $ip_res;
  $x = 0;
  $seg = 7;
  for($i=0; $i<$seg_num; $i++)
  {
    if(strlen($ip_str_seg[$i])==0)
      break;
    else
    {
      $ip_res[$seg--] = $ip_str_seg[$i];
      $x++;
    }
  }
  $seg=0;
  for($i=($seg_num-1); $i>$x; $i--)
      $ip_res[$seg++] = $ip_str_seg[$i];

}
for($i=0; $i<8; $i++)
{
  if($ip_res[$i])
  {
        $mask = '/^[0-9a-fA-F]{0,4}$/i';
        return preg_match($mask, $value);

     $ip_res[$i] = intval($ip_res[$i]);
  }
  else
    $ip_res[$i] = 0;
}

var_dump($ip_str_tot);
var_dump($ip_res);
