<?php

function callendar()
{
	$date = time();
	$day = date('d', $date);
	$month = date('m', $date);
	$year = date('y', $date);
	$first_day = mktime(0,0,0, $month, 1, $year);

	$month_name = date('n', $day);
	$month_days = cal_days_in_month(0, $month, $year);
	$day_of_week = date('w', $first_day);
	if ($day_of_week == 1)
	$empty_cells = 6;
	else
	$empty_cells = $day_of_week - 1;
	$months = array (
		1 => 'Styczeń',
		2 => 'Luty',
		3 => 'Marzec',
		4 => 'Kwiecień',
		5 => 'Maj',
		6 => 'Czerwiec',
		7 => 'Lipiec',
		8 => 'Sierpień',
		9 => 'Wrzesień',
		10 => 'Październik',
		11 => 'Listopad',
		12 => 'Grudzień');
	echo $months[$month_name];
	echo "<table class=\"callendar\" border=\"1\" bordercolor=\"black\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
	<td>P</td>
	<td>W</td>
	<td>Ś</td>
	<td>C</td>
	<td>P</td>
	<td>S</td>
	<td class=\"sunday\">N</td>
	</tr>
	<tr>";
	for ($i=0; $i<$empty_cells; $i++)
	echo "<td>&nbsp;</td>";
	$week_day_counter = $empty_cells;
	for ($current = 1; $current <= $month_days; $current++)
	{
	$week_day_counter++;
	if ($week_day_counter == 7)
	{
		echo "<td class=\"sunday\">$current</td>";
		echo "</tr>\n<tr>";
		$week_day_counter = 0;
	}
	else
		echo "<td>$current</td>";
	}
	while ($week_day_counter < 7)
	{
	echo "<td>&nbsp;</td>";
	$week_day_counter++;
	}
	echo "</tr>\n</table>";
}
callendar();
