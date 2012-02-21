<?php require('include/html/header.php'); ?>
<?php
$c_width = 150; //collumn width;
$c_height = 40; //collumn height;
$start_h = 9*60; //starting hour in minutes;
$end_h = 17*60; // last hour;
$cell_border = 1;
$days = 1 + 7; // day 1 is hour col
$hours_width = 50;


$px_per_min = $c_height/60;
$rows = ($end_h - $start_h)/60 + 1; //extra row is for day name and date
$table_height = $rows * $c_height;
$table_width = ($days-1) * $c_width + $hours_width;


$mods = array(array('day'=> 2, 's_time'=>(9*60+10), 'e_time'=>(10*60)+20));

?>
<div class="week" style="margin: 50px 10px 10px 50px; position: relative; display: box; background: white; <?php echo 'width:'.$table_width.'px; height: '.$table_height.'px;'; ?>">
<?php
for($i=0; $i<$rows; $i++)
{
  for($j=0; $j<$days; $j++)
  {
    $x_pos = ($j-1)*$c_width + $hours_width;
    $y_pos = $i*$c_height;
    if($i==0)
    {
      //week days
      if($j==0)
      {
        //empty cell
      }
      else
      {
        echo '<div style="border: '.$cell_border.'px solid black; background: gray; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width.'px; height:'.$c_height.'px;"></div>'."\n"; 
      }
    }
    elseif($j==0)
    {
      $x_pos = 0;
      $hour = $i + ($start_h / 60) - 1 .':00';
      //hours col
      echo '<div style="border: '.$cell_border.'px solid black; background: yellow; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$hours_width.'px; height:'.$c_height.'px;">'.$hour.'</div>'."\n"; 
    }
    else
    {
    $x_pos = ($j-1)*$c_width + $hours_width;
    $y_pos = $i*$c_height;
      echo '<div style="border: '.$cell_border.'px solid blue; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width.'px; height:'.$c_height.'px;"></div>'."\n"; 
    }
  }
}
foreach($mods as $mod)
{
    $x_pos = ($mod['day']-1)*$c_width + $hours_width;
    $y_pos = ($mod['s_time'] -$start_h + 60) * $px_per_min;
    $height = ($mod['e_time']-$mod['s_time'])*$px_per_min;
      echo '<div style="border: '.$cell_border.'px solid black; background: green; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width.'px; height:'.$height.'px;"></div>'."\n"; 
}
?>
</div>
</body>
</html>
