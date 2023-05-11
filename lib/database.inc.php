<?php
	include_once (dirname(__FILE__).'/../config.inc.php');
	$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
?>
