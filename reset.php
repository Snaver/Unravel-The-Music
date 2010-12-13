<?php
$start_time = microtime();
mysql_connect('localhost', 'drewtown', 'aoeiii1900') or die(mysql_error());
mysql_select_db('unravel');
mysql_query("UPDATE songs SET comments_yesterday=comments_today");
mysql_query("UPDATE songs SET comments_today='0'");
$end_time = microtime();
$total = $end_time - $start_time;
echo('completed on ' . date('d - F - Y') . ' with total time of ' . $total);
?>