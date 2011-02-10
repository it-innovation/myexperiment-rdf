<?php
	if (isset($myexp_db['password']) and strlen($myexp_db['password'])>0) mysql_connect($myexp_db['server'],$myexp_db['user'],$myexp_db['password']) or die("Couldn't connect");
	else  mysql_connect($myexp_db['server'],$myexp_db['user']) or die("Couldn't connect");
	mysql_select_db($myexp_db['database']) or die ("No Database called $myexp_db[database]");
?>
