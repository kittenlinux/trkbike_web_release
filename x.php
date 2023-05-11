<?php
$date = new DateTime();
$date = $date->sub(DateInterval::createFromDateString('1 days'));
echo $date->format('Y-m-d');

?>